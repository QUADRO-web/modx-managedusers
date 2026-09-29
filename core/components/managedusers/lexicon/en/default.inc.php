<?php
/**
 * Default Lexicon Entries for ManagedUsers
 *
 * @package managedusers
 * @subpackage lexicon
 */
$_lang['managedusers'] = 'User Management';
$_lang['managedusers.menu'] = 'User Management';
$_lang['managedusers.menu_desc'] = 'Manage the users of the released user group.';
$_lang['managedusers.desc'] = 'Here you can create and edit the users of the user group <strong>[[+usergroup]]</strong>. New users are automatically assigned to this user group.';

$_lang['managedusers.user_create'] = 'New User';
$_lang['managedusers.user_update'] = 'Edit User';

$_lang['managedusers.active_desc'] = 'Inactive users can no longer log in.';

$_lang['managedusers.password_new'] = 'New Password';
$_lang['managedusers.password_confirm'] = 'Confirm Password';
$_lang['managedusers.password_generate'] = 'Generate password automatically';
$_lang['managedusers.password_desc'] = 'At least [[+min]] characters.';
$_lang['managedusers.password_update_desc'] = 'Leave empty to keep the current password. At least [[+min]] characters.';
$_lang['managedusers.password_generated'] = 'Password generated';
$_lang['managedusers.password_generated_msg'] = 'The new password for <strong>[[+username]]</strong> is:<br><br><code>[[+password]]</code><br><br>Please write it down now – it will not be shown again.';

$_lang['managedusers.err_no_usergroup'] = 'No valid user group is configured. Please set the user group to manage in the system setting <strong>managedusers.usergroup</strong> (the "Administrator" group is not allowed).';
$_lang['managedusers.err_user_nf'] = 'The user was not found or may not be edited here.';
$_lang['managedusers.err_save'] = 'An error occurred while saving the user.';
$_lang['managedusers.err_deactivate_self'] = 'You cannot deactivate your own user account.';
