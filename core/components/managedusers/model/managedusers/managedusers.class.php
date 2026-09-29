<?php
/**
 * ManagedUsers Classfile
 *
 * Copyright 2026 by Jan Dähne <jan.daehne@quadro.digital>
 *
 * @package managedusers
 * @subpackage classfile
 */

/**
 * class ManagedUsers
 */
class ManagedUsers
{
    /**
     * The permission required to use the CMP
     */
    const PERMISSION = 'managedusers';

    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'managedusers';

    /**
     * The class options
     * @var array $config
     */
    public $config = array();

    /**
     * ManagedUsers constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $config An array of options. Optional.
     */
    public function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('managedusers.core_path', $config, $this->modx->getOption('core_path') . 'components/managedusers/');
        $assetsUrl = $this->modx->getOption('managedusers.assets_url', $config, $this->modx->getOption('assets_url') . 'components/managedusers/');

        $this->config = array_merge(array(
            'namespace' => $this->namespace,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php',
        ), $config);

        $this->modx->lexicon->load('managedusers:default');
    }

    /**
     * Get an option from the class config or the system settings
     *
     * @param string $key
     * @param array $options
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $options = array(), $default = null)
    {
        if (is_array($options) && array_key_exists($key, $options)) {
            return $options[$key];
        }
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return $this->modx->getOption($this->namespace . '.' . $key, $options, $default);
    }

    /**
     * Check if the current user may use ManagedUsers
     *
     * @return bool
     */
    public function hasAccess()
    {
        return $this->modx->hasPermission(self::PERMISSION);
    }

    /**
     * Get the managed user group from the system setting "managedusers.usergroup"
     * (ID or name). The Administrator group can never be managed.
     *
     * @return modUserGroup|null
     */
    public function getUserGroup()
    {
        $value = trim((string) $this->modx->getOption('managedusers.usergroup', null, ''));
        if ($value === '') {
            return null;
        }

        /** @var modUserGroup $group */
        $group = is_numeric($value)
            ? $this->modx->getObject('modUserGroup', (int) $value)
            : $this->modx->getObject('modUserGroup', array('name' => $value));

        if (!$group || $this->isProtectedGroup($group)) {
            return null;
        }
        return $group;
    }

    /**
     * Groups which must never be managed by this CMP
     *
     * @param modUserGroup $group
     * @return bool
     */
    public function isProtectedGroup(modUserGroup $group)
    {
        return (int) $group->get('id') === 1 || $group->get('name') === 'Administrator';
    }

    /**
     * Get the role ID assigned to new users from "managedusers.role".
     * Fallback: the role with the least authority (highest number).
     *
     * @return int
     */
    public function getRoleId()
    {
        $value = (int) $this->modx->getOption('managedusers.role', null, 0);
        if ($value > 0 && $this->modx->getCount('modUserGroupRole', $value) > 0) {
            return $value;
        }

        $c = $this->modx->newQuery('modUserGroupRole');
        $c->sortby('authority', 'DESC');
        /** @var modUserGroupRole $role */
        $role = $this->modx->getObject('modUserGroupRole', $c);
        return $role ? (int) $role->get('id') : 1;
    }

    /**
     * SQL condition matching only users which are members of the managed group
     * and of no other group.
     *
     * @param modUserGroup $group
     * @param string $alias Alias of the modUser table
     * @return string
     */
    public function getExclusiveMemberCondition(modUserGroup $group, $alias = 'modUser')
    {
        $table = $this->modx->getTableName('modUserGroupMember');
        $groupId = (int) $group->get('id');

        return "{$alias}.id IN (SELECT `member` FROM {$table} WHERE `user_group` = {$groupId})"
            . " AND {$alias}.id NOT IN (SELECT `member` FROM {$table} WHERE `user_group` != {$groupId})";
    }

    /**
     * Check if a user may be edited via this CMP: not sudo, member of the managed
     * group and member of no other group.
     *
     * @param modUser $user
     * @param modUserGroup $group
     * @return bool
     */
    public function isManagedUser(modUser $user, modUserGroup $group)
    {
        if ($user->get('sudo')) {
            return false;
        }
        $userId = (int) $user->get('id');
        $groupId = (int) $group->get('id');

        $inGroup = $this->modx->getCount('modUserGroupMember', array(
            'member' => $userId,
            'user_group' => $groupId,
        ));
        $inOtherGroups = $this->modx->getCount('modUserGroupMember', array(
            'member' => $userId,
            'user_group:!=' => $groupId,
        ));

        return $inGroup > 0 && $inOtherGroups === 0;
    }

    /**
     * Validate username, fullname, email, active and password. Sets the values on the
     * user and profile objects.
     *
     * @param modUser $user
     * @param modUserProfile $profile
     * @param array $data
     * @return array ['errors' => [field => message], 'password' => string|null, 'generated' => bool]
     */
    public function validateUserData(modUser $user, modUserProfile $profile, array $data)
    {
        $this->modx->lexicon->load('core:user');

        $errors = array();
        $isNew = $user->isNew();
        $userId = $isNew ? 0 : (int) $user->get('id');

        /* username */
        $username = trim((string) ($data['username'] ?? ''));
        if ($username === '') {
            $errors['username'] = $this->modx->lexicon('user_err_not_specified_username');
        } elseif (!preg_match('/^[^\'\\x3c\\x3e\\(\\);\\x22]+$/', $username)) {
            $errors['username'] = $this->modx->lexicon('user_err_username_invalid');
        } elseif ($this->modx->getCount('modUser', array('username' => $username, 'id:!=' => $userId)) > 0) {
            $errors['username'] = $this->modx->lexicon('user_err_already_exists');
        } else {
            $user->set('username', $username);
        }

        /* fullname */
        $fullname = trim(strip_tags((string) ($data['fullname'] ?? '')));
        $profile->set('fullname', $fullname);

        /* email */
        $email = trim((string) ($data['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = $this->modx->lexicon('user_err_not_specified_email');
        } elseif (!$this->modx->getOption('allow_multiple_emails', null, true)
            && $this->modx->getCount('modUserProfile', array('email' => $email, 'internalKey:!=' => $userId)) > 0
        ) {
            $errors['email'] = $this->modx->lexicon('user_err_already_exists_email');
        } else {
            $profile->set('email', $email);
        }

        /* active */
        if (array_key_exists('active', $data)) {
            $active = !empty($data['active']) && $data['active'] !== 'false';
            if (!$active && !$isNew && $userId === (int) $this->modx->user->get('id')) {
                $errors['active'] = $this->modx->lexicon('managedusers.err_deactivate_self');
            } else {
                $user->set('active', $active);
            }
        } elseif ($isNew) {
            $user->set('active', true);
        }

        /* password */
        $password = null;
        $generated = !empty($data['generate_password']) && $data['generate_password'] !== 'false';
        if ($generated) {
            $password = $user->generatePassword();
        } else {
            $specified = (string) ($data['password'] ?? '');
            $confirm = (string) ($data['password_confirm'] ?? '');
            if ($specified === '' && $confirm === '') {
                if ($isNew) {
                    $errors['password'] = $this->modx->lexicon('user_err_not_specified_password');
                }
            } elseif ($specified !== $confirm) {
                $errors['password_confirm'] = $this->modx->lexicon('user_err_password_no_match');
            } elseif (strlen($specified) < (int) $this->modx->getOption('password_min_length', null, 8, true)) {
                $errors['password'] = $this->modx->lexicon('user_err_password_too_short');
            } elseif (!preg_match('/^[^\'\x3c\x3e\(\);\x22\x7b\x7d\x2f\x5c]+$/', $specified)) {
                $errors['password'] = $this->modx->lexicon('user_err_password_invalid');
            } else {
                $password = $specified;
            }
        }
        if ($password !== null && empty($errors)) {
            $user->set('password', $password);
        }

        return array(
            'errors' => $errors,
            'password' => $password,
            'generated' => $generated,
        );
    }
}
