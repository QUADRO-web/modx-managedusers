<?php
/**
 * Setting Lexicon Entries for ManagedUsers
 *
 * @package managedusers
 * @subpackage lexicon
 */
$_lang['setting_managedusers.usergroup'] = 'User Group';
$_lang['setting_managedusers.usergroup_desc'] = 'The user group whose users can be created and edited via the user management. New users are automatically assigned to this group. The "Administrator" group is not allowed.';
$_lang['setting_managedusers.role'] = 'Role';
$_lang['setting_managedusers.role_desc'] = 'The role with which new users are assigned to the user group (default: Member).';
$_lang['setting_managedusers.profile_fields'] = 'Additional Profile Fields';
$_lang['setting_managedusers.profile_fields_desc'] = 'Additional profile fields in the create and update window as JSON, e.g. [{"name": "comment", "label": "comment", "xtype": "textarea"}, {"name": "photo", "label": "user_photo", "xtype": "modx-combo-browser"}]. "name": comment, photo, website, phone, mobilephone, fax, address, city, zip, state, country or extended.fieldname for an extended field. "label" and "description": lexicon key or text. "xtype": textfield (default), textarea or modx-combo-browser (with an optional media source "source").';
