<?php
/**
 * Update username, fullname, email and password of a managed user
 *
 * Only users which belong exclusively to the managed user group (and are not
 * sudo users) can be updated. All other properties of the request are ignored.
 *
 * @package managedusers
 * @subpackage processors
 */
require_once __DIR__ . '/_base.php';

class ManagedUsersUserUpdateProcessor extends modProcessor
{
    use ManagedUsersProcessorTrait;

    /** @var modUser $user */
    public $user;

    public function initialize()
    {
        $initialized = $this->initializeManagedUsers();
        if ($initialized !== true) {
            return $initialized;
        }

        $id = (int) $this->getProperty('id');
        $this->user = $id > 0 ? $this->modx->getObject('modUser', $id) : null;
        if (!$this->user || !$this->managedusers->isManagedUser($this->user, $this->group)) {
            return $this->modx->lexicon('managedusers.err_user_nf');
        }
        return true;
    }

    public function process()
    {
        try {
            return $this->saveUser();
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    protected function saveUser()
    {
        /** @var modUserProfile $profile */
        $profile = $this->user->getOne('Profile');
        if (!$profile) {
            $profile = $this->modx->newObject('modUserProfile');
            $this->user->addOne($profile, 'Profile');
        }

        $validation = $this->managedusers->validateUserData($this->user, $profile, $this->getProperties());
        foreach ($validation['errors'] as $field => $message) {
            $this->addFieldError($field, $message);
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }

        $beforeSave = $this->modx->invokeEvent('OnBeforeUserFormSave', array(
            'mode' => 'upd',
            'user' => &$this->user,
            'id' => $this->user->get('id'),
        ));
        if (is_array($beforeSave) && !empty(array_filter($beforeSave))) {
            return $this->failure(implode("\n", $beforeSave));
        }

        if (!$this->user->save()) {
            return $this->failure($this->modx->lexicon('managedusers.err_save'));
        }

        $this->modx->invokeEvent('OnUserFormSave', array(
            'mode' => 'upd',
            'user' => &$this->user,
            'id' => $this->user->get('id'),
        ));
        $this->modx->logManagerAction('managedusers.user_update', 'modUser', $this->user->get('id'));

        return $this->success('', array(
            'id' => $this->user->get('id'),
            'username' => $this->user->get('username'),
            'password' => $validation['generated'] ? $validation['password'] : '',
        ));
    }
}

return 'ManagedUsersUserUpdateProcessor';
