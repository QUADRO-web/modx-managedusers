<?php
/**
 * ManagedUsers connector
 *
 * @package managedusers
 * @subpackage connector
 *
 * @var modX $modx
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('managedusers.core_path', null, $modx->getOption('core_path') . 'components/managedusers/');
/** @var ManagedUsers $managedusers */
$managedusers = $modx->getService('managedusers', 'ManagedUsers', $corePath . 'model/managedusers/');

// Handle request
$modx->request->handleRequest(array(
    'processors_path' => $managedusers->getOption('processorsPath'),
    'location' => '',
));
