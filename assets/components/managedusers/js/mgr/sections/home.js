ManagedUsers.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'managedusers-panel-home'
            ,renderTo: 'managedusers-panel-home-div'
        }]
    });
    ManagedUsers.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ManagedUsers.page.Home, MODx.Component);
Ext.reg('managedusers-page-home', ManagedUsers.page.Home);
