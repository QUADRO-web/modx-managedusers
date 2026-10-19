<?php
/**
 * Setting Lexicon Entries for ManagedUsers
 *
 * @package managedusers
 * @subpackage lexicon
 */
$_lang['setting_managedusers.usergroup'] = 'Benutzergruppe';
$_lang['setting_managedusers.usergroup_desc'] = 'Die Benutzergruppe, deren Benutzer über die Benutzerverwaltung angelegt und bearbeitet werden können. Neue Benutzer werden automatisch dieser Gruppe zugewiesen. Die Gruppe „Administrator“ ist nicht erlaubt.';
$_lang['setting_managedusers.role'] = 'Rolle';
$_lang['setting_managedusers.role_desc'] = 'Die Rolle, mit der neue Benutzer der Benutzergruppe zugewiesen werden (Standard: Member).';
$_lang['setting_managedusers.profile_fields'] = 'Zusätzliche Profilfelder';
$_lang['setting_managedusers.profile_fields_desc'] = 'Weitere Profilfelder im Fenster zum Anlegen und Bearbeiten als JSON, z. B. [{"name": "comment", "label": "comment", "xtype": "textarea"}, {"name": "photo", "label": "user_photo", "xtype": "modx-combo-browser"}]. „name“: comment, photo, website, phone, mobilephone, fax, address, city, zip, state, country oder extended.feldname für ein erweitertes Feld. „label“ und „description“: Lexikon-Schlüssel oder Text. „xtype“: textfield (Standard), textarea oder modx-combo-browser (mit optionaler Medienquelle „source“).';
