ManagedUsers.panel.Home = function (config) {
    config = config || {};

    var content;
    if (ManagedUsers.config.usergroup) {
        content = [{
            html: '<p>' + _('managedusers.desc', {usergroup: Ext.util.Format.htmlEncode(ManagedUsers.config.usergroup)}) + '</p>'
            ,xtype: 'modx-description'
        },{
            xtype: 'managedusers-grid-users'
            ,cls: 'main-wrapper'
            ,preventRender: true
        }];
    } else {
        content = [{
            html: '<p>' + _('managedusers.err_no_usergroup') + '</p>'
            ,xtype: 'modx-description'
        }];
    }

    Ext.applyIf(config, {
        id: 'managedusers-panel-home'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false, autoHeight: true }
        ,items: [{
            html: _('managedusers')
            ,id: 'managedusers-header'
            ,xtype: 'modx-header'
        },{
            layout: 'form'
            ,items: content
        }]
    });
    ManagedUsers.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(ManagedUsers.panel.Home, MODx.FormPanel);
Ext.reg('managedusers-panel-home', ManagedUsers.panel.Home);
