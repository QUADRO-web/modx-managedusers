<?php
/**
 * Create a new user and assign the managed user group
 *
 * Only username, fullname, email and password are accepted. All other
 * properties of the request are ignored.
 *
 * @package managedusers
 * @subpackage processors
 */
require_once __DIR__ . '/_base.php';

class ManagedUsersUserCreateProcessor extends modProcessor
{
    use ManagedUsersProcessorTrait;

    public function initialize()
    {
        return $this->initializeManagedUsers();
    }

    public function process()
    {
        /** @var modUser $user */
        $user = $this->modx->newObject('modUser');
        /** @var modUserProfile $profile */
        $profile = $this->modx->newObject('modUserProfile');

        $validation = $this->managedusers->validateUserData($user, $profile, $this->getProperties());
        foreach ($validation['errors'] as $field => $message) {
            $this->addFieldError($field, $message);
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }

        $user->set('primary_group', $this->group->get('id'));
        $user->addOne($profile, 'Profile');

        /** @var modUserGroupMember $membership */
        $membership = $this->modx->newObject('modUserGroupMember');
        $membership->fromArray(array(
            'user_group' => $this->group->get('id'),
            'role' => $this->managedusers->getRoleId(),
            'rank' => 0,
        ));
        $memberships = array($membership);
        $user->addMany($memberships, 'UserGroupMembers');

        $beforeSave = $this->modx->invokeEvent('OnBeforeUserFormSave', array(
            'mode' => modSystemEvent::MODE_NEW,
            'user' => &$user,
            'id' => 0,
        ));
        if (is_array($beforeSave) && !empty(array_filter($beforeSave))) {
            return $this->failure(implode("\n", $beforeSave));
        }

        if (!$user->save()) {
            return $this->failure($this->modx->lexicon('managedusers.err_save'));
        }

        $this->modx->invokeEvent('OnUserFormSave', array(
            'mode' => modSystemEvent::MODE_NEW,
            'user' => &$user,
            'id' => $user->get('id'),
        ));
        $this->modx->logManagerAction('managedusers.user_create', 'modUser', $user->get('id'));

        return $this->success('', array(
            'id' => $user->get('id'),
            'username' => $user->get('username'),
            'password' => $validation['generated'] ? $validation['password'] : '',
        ));
    }
}

return 'ManagedUsersUserCreateProcessor';
