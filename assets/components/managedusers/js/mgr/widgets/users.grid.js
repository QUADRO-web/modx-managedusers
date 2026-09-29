ManagedUsers.grid.Users = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'managedusers-grid-users'
        ,url: ManagedUsers.config.connectorUrl
        ,baseParams: {
            action: 'mgr/user/getlist'
        }
        ,fields: ['id', 'username', 'fullname', 'email', 'active']
        ,paging: true
        ,remoteSort: true
        ,autoExpandColumn: 'email'
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,scrollOffset: 0
            ,autoFill: true
            ,getRowClass: function (rec) {
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('username')
            ,dataIndex: 'username'
            ,width: 150
            ,sortable: true
            ,renderer: function (value, p, record) {
                return String.format('<a href="javascript:void(0);" title="{0}" class="x-grid-link">{1}</a>', _('managedusers.user_update'), Ext.util.Format.htmlEncode(value));
            }
        },{
            header: _('user_full_name')
            ,dataIndex: 'fullname'
            ,width: 180
            ,sortable: true
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('email')
            ,dataIndex: 'email'
            ,id: 'email'
            ,width: 180
            ,sortable: true
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('active')
            ,dataIndex: 'active'
            ,width: 80
            ,sortable: true
            ,renderer: this.rendYesNo
        }]
        ,tbar: [{
            text: _('managedusers.user_create')
            ,handler: this.createUser
            ,scope: this
            ,cls: 'primary-button'
        }, '->', {
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'managedusers-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function (cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                }, scope: this}
            }
        },{
            xtype: 'button'
            ,id: 'managedusers-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
                ,'mouseout': {fn: function () {
                    this.removeClass('x-btn-focus');
                }}
            }
        }]
    });
    ManagedUsers.grid.Users.superclass.constructor.call(this, config);

    this.on('rowdblclick', function (grid, rowIndex, e) {
        if (!e.getTarget('a.x-grid-link')) {
            this.updateUser();
        }
    }, this);
    this.on('cellclick', function (grid, rowIndex, columnIndex, e) {
        if (e.getTarget('a.x-grid-link')) {
            e.preventDefault();
            this.updateUser();
        }
    }, this);
};
Ext.extend(ManagedUsers.grid.Users, MODx.grid.Grid, {
    getMenu: function () {
        return [{
            text: _('managedusers.user_update')
            ,handler: this.updateUser
        }];
    }

    ,createUser: function (btn, e) {
        var win = MODx.load({
            xtype: 'managedusers-window-user'
            ,isUpdate: false
            ,listeners: {
                'success': {fn: this.onSaved, scope: this}
            }
        });
        win.show(e ? e.target : undefined);
    }

    ,updateUser: function (btn, e) {
        var record = this.getSelectionModel().getSelected();
        if (!record) {
            return false;
        }
        var win = MODx.load({
            xtype: 'managedusers-window-user'
            ,isUpdate: true
            ,listeners: {
                'success': {fn: this.onSaved, scope: this}
            }
        });
        win.fp.getForm().setValues(record.data);
        win.show(e ? e.target : undefined);
    }

    ,onSaved: function (r) {
        var result = r.a.result.object || {};
        if (result.password) {
            MODx.msg.alert(_('managedusers.password_generated'), _('managedusers.password_generated_msg', {
                username: Ext.util.Format.htmlEncode(result.username)
                ,password: Ext.util.Format.htmlEncode(result.password)
            }));
        }
        this.refresh();
    }

    ,search: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function () {
        this.getStore().baseParams.query = '';
        Ext.getCmp('managedusers-search').reset();
        this.getBottomToolbar().changePage(1);
    }
});
Ext.reg('managedusers-grid-users', ManagedUsers.grid.Users);
