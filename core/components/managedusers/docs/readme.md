# ManagedUsers

Custom Manager Page that allows editors to create and edit the users of **one specific user group** – without having access to any other users of the CMS.

## Features

- List all users of the configured user group (with search, sorting and paging)
- Edit **username, full name, email, password and active status** – no other fields can be changed
- Optionally edit additional profile fields such as comment or photo (see `managedusers.profile_fields`)
- Activate/deactivate users (the status is shown in the overview; editors cannot deactivate their own account)
- Create new users, which are automatically assigned to the user group (with the configured role)
- Set a password manually or have one generated automatically

## System Settings

| Key | Description |
|---|---|
| `managedusers.usergroup` | The user group to manage (ID or name). The "Administrator" group is not allowed. |
| `managedusers.role` | The role new users get within the user group (default: `1` = Member). |
| `managedusers.profile_fields` | Additional profile fields in the create and update window as JSON (default: empty = none), see below. |

### Additional profile fields

`managedusers.profile_fields` contains a JSON array with one object per field. The fields are shown below the email address:

```json
[
    {"name": "comment", "label": "comment", "xtype": "textarea"},
    {"name": "extended.comment_en", "label": "Comment (English)", "xtype": "textarea"},
    {"name": "photo", "label": "user_photo", "xtype": "modx-combo-browser"}
]
```

| Key | Description |
|---|---|
| `name` | Required. One of the profile fields `comment`, `photo`, `website`, `phone`, `mobilephone`, `fax`, `address`, `city`, `zip`, `state`, `country` – or `extended.fieldname` for a field of the extended profile data (letters, numbers, `_` and `-`). |
| `label` | Lexicon key or text (default: `name`). |
| `description` | Optional help text below the field, lexicon key or text. |
| `xtype` | `textfield` (default), `textarea` or `modx-combo-browser` for a file such as the photo. |
| `source` | Media source ID for `modx-combo-browser` (default: `photo_profile_source` or `default_media_source`). |

Invalid entries are skipped and logged to the error log.

## Permissions

On installation the access policy template **ManagedUsersTemplate** with the permission `managedusers` and the access policy **ManagedUsers** are created.

To grant a user group (e.g. "Editors") access:

1. *Users → Access Controls → edit the user group → Context Access*
2. Add context `mgr`, a role of your choice and the access policy **ManagedUsers**
3. *Flush permissions*

The menu entry under *Extras* is only shown to users with this permission. Sudo users always have access.

## Security

- Only users which are members of the configured group **exclusively** and are **not** sudo users are listed and can be edited. Users who are also members of other groups (e.g. administrators) are not shown and cannot be modified via the processors either.
- The processors only accept the fields `username`, `fullname`, `email`, `active`, the password and the profile fields configured in `managedusers.profile_fields` (limited to the profile fields listed above and extended fields, HTML tags are removed). User groups, roles, sudo status etc. cannot be set through them.
- The managed group should **not** be the group of the editors who use the user management themselves – otherwise they could change each other's passwords.

## Development (Git Package Management)

GPM does not run resolvers when installing a package from the repository. Therefore create the permission and access policy for the development environment once via CLI:

```
php _build/install.permissions.php
```

Remove: `php _build/install.permissions.php uninstall`
