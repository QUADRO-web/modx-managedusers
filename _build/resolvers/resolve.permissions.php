<?php
/**
 * ManagedUsers permissions resolver
 *
 * Creates the access policy template "ManagedUsersTemplate" with the
 * permission "managedusers" and the access policy "ManagedUsers".
 *
 * @package managedusers
 * @subpackage build
 *
 * @var xPDOObject $object
 * @var array $options
 */

if (!$object->xpdo) {
    return true;
}

/** @var modX $modx */
$modx =& $object->xpdo;

$templateName = 'ManagedUsersTemplate';
$policyName = 'ManagedUsers';
$permissions = array(
    'managedusers' => 'managedusers.permission_desc',
);

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        /* policy template */
        $template = $modx->getObject('modAccessPolicyTemplate', array('name' => $templateName));
        if (!$template) {
            $template = $modx->newObject('modAccessPolicyTemplate');
            $template->fromArray(array(
                'name' => $templateName,
                'template_group' => 1, // Admin
                'description' => 'managedusers.policy_template_desc',
                'lexicon' => 'managedusers:permissions',
            ));
            if (!$template->save()) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[ManagedUsers] Could not create policy template ' . $templateName);
                return false;
            }
        }

        /* permissions */
        $policyData = array();
        foreach ($permissions as $name => $description) {
            $permission = $modx->getObject('modAccessPermission', array(
                'template' => $template->get('id'),
                'name' => $name,
            ));
            if (!$permission) {
                $permission = $modx->newObject('modAccessPermission');
                $permission->fromArray(array(
                    'template' => $template->get('id'),
                    'name' => $name,
                    'description' => $description,
                    'value' => true,
                ));
                $permission->save();
            }
            $policyData[$name] = true;
        }

        /* policy */
        $policy = $modx->getObject('modAccessPolicy', array('name' => $policyName));
        if (!$policy) {
            $policy = $modx->newObject('modAccessPolicy');
            $policy->fromArray(array(
                'name' => $policyName,
                'description' => 'managedusers.policy_desc',
                'parent' => 0,
                'class' => '',
                'lexicon' => 'managedusers:permissions',
            ));
        }
        $policy->set('template', $template->get('id'));
        $policy->set('data', array_merge((array) $policy->get('data'), $policyData));
        $policy->save();

        $modx->cacheManager->flushPermissions();
        $modx->log(modX::LOG_LEVEL_INFO, '[ManagedUsers] Access policy "' . $policyName . '" is ready.');
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $policy = $modx->getObject('modAccessPolicy', array('name' => $policyName));
        if ($policy) {
            $modx->removeCollection('modAccessContext', array('policy' => $policy->get('id')));
            $policy->remove();
        }
        $template = $modx->getObject('modAccessPolicyTemplate', array('name' => $templateName));
        if ($template) {
            $modx->removeCollection('modAccessPermission', array('template' => $template->get('id')));
            $template->remove();
        }
        $modx->cacheManager->flushPermissions();
        break;
}

return true;
