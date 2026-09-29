<?php
/**
 * ManagedUsers home controller
 *
 * @package managedusers
 * @subpackage controllers
 */
class ManagedusersHomeManagerController extends modExtraManagerController
{
    /** @var ManagedUsers $managedusers */
    public $managedusers;

    public function initialize()
    {
        $corePath = $this->modx->getOption('managedusers.core_path', null, $this->modx->getOption('core_path') . 'components/managedusers/');
        $this->managedusers = $this->modx->getService('managedusers', 'ManagedUsers', $corePath . 'model/managedusers/');
        return parent::initialize();
    }

    public function checkPermissions()
    {
        return $this->modx->hasPermission('managedusers');
    }

    public function getLanguageTopics()
    {
        return array('core:user', 'managedusers:default');
    }

    public function process(array $scriptProperties = array())
    {
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('managedusers');
    }

    public function loadCustomCssJs()
    {
        $jsUrl = $this->managedusers->getOption('jsUrl') . 'mgr/';

        $this->addJavascript($jsUrl . 'managedusers.js');
        $this->addLastJavascript($jsUrl . 'widgets/user.windows.js');
        $this->addLastJavascript($jsUrl . 'widgets/users.grid.js');
        $this->addLastJavascript($jsUrl . 'widgets/home.panel.js');
        $this->addLastJavascript($jsUrl . 'sections/home.js');

        $group = $this->managedusers->getUserGroup();
        $config = array(
            'connectorUrl' => $this->managedusers->getOption('connectorUrl'),
            'usergroup' => $group ? $group->get('name') : '',
            'passwordMinLength' => (int) $this->modx->getOption('password_min_length', null, 8, true),
        );

        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            ManagedUsers.config = ' . json_encode($config) . ';
            MODx.load({ xtype: "managedusers-page-home" });
        });
        </script>');
    }

    public function getTemplateFile()
    {
        return $this->managedusers->getOption('templatesPath') . 'home.tpl';
    }
}
