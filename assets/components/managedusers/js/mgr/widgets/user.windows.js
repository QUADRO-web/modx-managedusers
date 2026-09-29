ManagedUsers.window.User = function (config) {
    config = config || {};
    this.ident = config.ident || 'managedusers-user-' + Ext.id();

    var isUpdate = config.isUpdate === true;

    Ext.applyIf(config, {
        title: isUpdate ? _('managedusers.user_update') : _('managedusers.user_create')
        ,url: ManagedUsers.config.connectorUrl
        ,baseParams: {
            action: isUpdate ? 'mgr/user/update' : 'mgr/user/create'
        }
        ,width: 500
        ,autoHeight: true
        ,modal: true
        ,closeAction: 'close'
        ,submitEmptyText: false
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('username')
            ,name: 'username'
            ,id: this.ident + '-username'
            ,anchor: '100%'
            ,allowBlank: false
            ,maxLength: 100
            ,autoCreate: {tag: 'input', type: 'text', autocomplete: 'off'}
        },{
            xtype: 'textfield'
            ,fieldLabel: _('user_full_name')
            ,name: 'fullname'
            ,id: this.ident + '-fullname'
            ,anchor: '100%'
            ,maxLength: 255
            ,autoCreate: {tag: 'input', type: 'text', autocomplete: 'off'}
        },{
            xtype: 'textfield'
            ,fieldLabel: _('email')
            ,name: 'email'
            ,id: this.ident + '-email'
            ,anchor: '100%'
            ,allowBlank: false
            ,vtype: 'email'
            ,maxLength: 255
            ,autoCreate: {tag: 'input', type: 'text', autocomplete: 'off'}
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('active')
            ,name: 'active'
            ,id: this.ident + '-active'
            ,hideLabel: true
            ,checked: true
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident + '-active'
            ,html: _('managedusers.active_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('managedusers.password_generate')
            ,name: 'generate_password'
            ,id: this.ident + '-generate-password'
            ,hideLabel: true
            ,inputValue: 1
            ,checked: false
            ,listeners: {
                'check': {fn: this.togglePasswordFields, scope: this}
            }
        },{
            xtype: 'textfield'
            ,inputType: 'password'
            ,fieldLabel: isUpdate ? _('managedusers.password_new') : _('password')
            ,name: 'password'
            ,id: this.ident + '-password'
            ,anchor: '100%'
            ,autoCreate: {tag: 'input', type: 'password', autocomplete: 'new-password'}
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident + '-password'
            ,html: isUpdate
                ? _('managedusers.password_update_desc', {min: ManagedUsers.config.passwordMinLength})
                : _('managedusers.password_desc', {min: ManagedUsers.config.passwordMinLength})
            ,cls: 'desc-under'
        },{
            xtype: 'textfield'
            ,inputType: 'password'
            ,fieldLabel: _('managedusers.password_confirm')
            ,name: 'password_confirm'
            ,id: this.ident + '-password-confirm'
            ,anchor: '100%'
            ,autoCreate: {tag: 'input', type: 'password', autocomplete: 'new-password'}
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: function () { this.submit(); }
            ,scope: this
        }]
    });
    ManagedUsers.window.User.superclass.constructor.call(this, config);
};
Ext.extend(ManagedUsers.window.User, MODx.Window, {
    togglePasswordFields: function (cb, checked) {
        Ext.each([this.ident + '-password', this.ident + '-password-confirm'], function (id) {
            var field = Ext.getCmp(id);
            if (field) {
                field.reset();
                field.setDisabled(checked);
            }
        });
    }
});
Ext.reg('managedusers-window-user', ManagedUsers.window.User);
