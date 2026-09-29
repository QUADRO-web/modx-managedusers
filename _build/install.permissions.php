<?php
/**
 * Runs the permissions resolver in development (GPM does not execute
 * resolvers when installing a package from the repository).
 *
 * Usage: php _build/install.permissions.php [install|uninstall]
 *
 * @package managedusers
 * @subpackage build
 */

if (PHP_SAPI !== 'cli') {
    exit('CLI only');
}

require_once dirname(__DIR__) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setDebug(false);
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
$modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);

$object = new stdClass();
$object->xpdo = $modx;
$options = array(
    xPDOTransport::PACKAGE_ACTION => (isset($argv[1]) && $argv[1] === 'uninstall')
        ? xPDOTransport::ACTION_UNINSTALL
        : xPDOTransport::ACTION_INSTALL,
);

$result = include __DIR__ . '/resolvers/resolve.permissions.php';
echo ($result ? 'Done.' : 'Failed.') . PHP_EOL;
