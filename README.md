# ManagedUsers

Custom Manager Page for MODX Revolution 2.x which allows editors to create and edit the users of **one specific user group** – without having access to any other users of the CMS.

## Features
- List all users of the configured user group (search, sorting, paging)
- Edit username, full name, email, password and active status – nothing else
- Create new users, which are automatically assigned to the user group with the configured role
- Set a password manually or generate one

## Settings
- `managedusers.usergroup` – the user group to manage (the "Administrator" group is not allowed)
- `managedusers.role` – the role new users get within the user group (default: Member)

## Permissions
On install the access policy **ManagedUsers** (permission `managedusers`) is created. Assign it to the editors' user group via *Access Controls → User Group → Context Access* for the `mgr` context and flush permissions.

Only users which are members of the configured group **exclusively** and are not sudo users are listed and can be edited.

## Development
The package is built with [Git Package Management](https://github.com/TheBoxer/Git-Package-Management). GPM does not run resolvers when installing from the repository, so create the access policy once via CLI:

```
php _build/install.permissions.php
```

See [core/components/managedusers/docs/readme.md](core/components/managedusers/docs/readme.md) for the full documentation (German).
