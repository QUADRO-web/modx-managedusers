# ManagedUsers

Custom Manager Page, über die Redakteure die Benutzer **einer bestimmten Benutzergruppe** anlegen und bearbeiten können – ohne Zugriff auf die übrigen Benutzer des CMS zu erhalten.

## Funktionen

- Auflistung aller Benutzer der konfigurierten Benutzergruppe (mit Suche, Sortierung, Paging)
- Bearbeiten von **Benutzername, Name, E-Mail, Passwort und Aktiv-Status** – andere Felder können nicht verändert werden
- Benutzer aktivieren/deaktivieren (Status wird in der Übersicht angezeigt; das eigene Konto kann nicht deaktiviert werden)
- Anlegen neuer Benutzer; diese werden automatisch der Benutzergruppe (mit der konfigurierten Rolle) zugewiesen
- Passwort manuell setzen oder automatisch generieren lassen

## Systemeinstellungen

| Schlüssel | Beschreibung |
|---|---|
| `managedusers.usergroup` | Die zu verwaltende Benutzergruppe (ID oder Name). Die Gruppe „Administrator“ ist nicht erlaubt. |
| `managedusers.role` | Rolle, mit der neue Benutzer der Gruppe zugewiesen werden (Standard: `1` = Member). |

## Berechtigung

Bei der Installation werden die Richtlinien-Vorlage **ManagedUsersTemplate** mit der Berechtigung `managedusers` und die Zugriffsrichtlinie **ManagedUsers** angelegt.

Um einer Benutzergruppe (z. B. „Redakteure“) den Zugriff zu erlauben:

1. *Benutzer → Zugriffsrechte → Benutzergruppe bearbeiten → Kontextzugriff*
2. Kontext `mgr`, Rolle wie gewünscht, Zugriffsrichtlinie **ManagedUsers** hinzufügen
3. *Zugriffsrechte leeren*

Der Menüpunkt unter *Extras* wird nur Benutzern mit dieser Berechtigung angezeigt. Sudo-Benutzer haben immer Zugriff.

## Sicherheit

- Aufgelistet und bearbeitet werden nur Benutzer, die **ausschließlich** Mitglied der konfigurierten Gruppe und **keine** Sudo-Benutzer sind. Benutzer, die zusätzlich in anderen Gruppen sind (z. B. Administratoren), werden nicht angezeigt und können auch nicht über die Prozessoren verändert werden.
- Die Prozessoren übernehmen nur die Felder `username`, `fullname`, `email`, `active` und das Passwort. Gruppen, Rollen, Sudo-Status usw. können darüber nicht gesetzt werden.
- Die verwaltete Gruppe sollte **nicht** die Gruppe der Redakteure selbst sein, die die Benutzerverwaltung nutzen – sonst könnten sich diese gegenseitig die Passwörter ändern.

## Entwicklung (Git Package Management)

GPM führt bei der Installation aus dem Repository keine Resolver aus. Die Berechtigung und Zugriffsrichtlinie für die Entwicklungsumgebung daher einmalig per CLI anlegen:

```
php _build/install.permissions.php
```

Entfernen: `php _build/install.permissions.php uninstall`
