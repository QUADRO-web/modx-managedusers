<?php
/**
 * Default Lexicon Entries for ManagedUsers
 *
 * @package managedusers
 * @subpackage lexicon
 */
$_lang['managedusers'] = 'Benutzerverwaltung';
$_lang['managedusers.menu'] = 'Benutzerverwaltung';
$_lang['managedusers.menu_desc'] = 'Benutzer der freigegebenen Benutzergruppe verwalten.';
$_lang['managedusers.desc'] = 'Hier können Sie die Benutzer der Benutzergruppe <strong>[[+usergroup]]</strong> anlegen und bearbeiten. Neue Benutzer werden automatisch dieser Benutzergruppe zugewiesen.';

$_lang['managedusers.user_create'] = 'Neuer Benutzer';
$_lang['managedusers.user_update'] = 'Benutzer bearbeiten';

$_lang['managedusers.active_desc'] = 'Deaktivierte Benutzer können sich nicht mehr anmelden.';

$_lang['managedusers.password_new'] = 'Neues Passwort';
$_lang['managedusers.password_confirm'] = 'Passwort bestätigen';
$_lang['managedusers.password_generate'] = 'Passwort automatisch generieren';
$_lang['managedusers.password_desc'] = 'Mindestens [[+min]] Zeichen.';
$_lang['managedusers.password_update_desc'] = 'Leer lassen, um das bisherige Passwort beizubehalten. Mindestens [[+min]] Zeichen.';
$_lang['managedusers.password_generated'] = 'Passwort generiert';
$_lang['managedusers.password_generated_msg'] = 'Das neue Passwort für <strong>[[+username]]</strong> lautet:<br><br><code>[[+password]]</code><br><br>Bitte notieren Sie es jetzt – es wird nicht erneut angezeigt.';

$_lang['managedusers.err_no_usergroup'] = 'Es ist keine gültige Benutzergruppe konfiguriert. Bitte hinterlegen Sie in der Systemeinstellung <strong>managedusers.usergroup</strong> die zu verwaltende Benutzergruppe (die Gruppe „Administrator“ ist nicht erlaubt).';
$_lang['managedusers.err_user_nf'] = 'Der Benutzer wurde nicht gefunden oder darf hier nicht bearbeitet werden.';
$_lang['managedusers.err_save'] = 'Beim Speichern des Benutzers ist ein Fehler aufgetreten.';
$_lang['managedusers.err_deactivate_self'] = 'Sie können Ihr eigenes Benutzerkonto nicht deaktivieren.';
