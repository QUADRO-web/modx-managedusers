<?php
/**
 * Common initialization and permission check for all ManagedUsers processors
 *
 * @package managedusers
 * @subpackage processors
 */
trait ManagedUsersProcessorTrait
{
    /** @var ManagedUsers $managedusers */
    public $managedusers;

    /** @var modUserGroup $group */
    public $group;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('managedusers');
    }

    public function getLanguageTopics()
    {
        return array('core:user', 'managedusers:default');
    }

    /**
     * @return bool|string
     */
    protected function initializeManagedUsers()
    {
        $corePath = $this->modx->getOption('managedusers.core_path', null, $this->modx->getOption('core_path') . 'components/managedusers/');
        $this->managedusers = $this->modx->getService('managedusers', 'ManagedUsers', $corePath . 'model/managedusers/');
        if (!$this->managedusers) {
            return 'Could not load ManagedUsers service.';
        }

        $this->group = $this->managedusers->getUserGroup();
        if (!$this->group) {
            return $this->modx->lexicon('managedusers.err_no_usergroup');
        }
        return true;
    }
}
