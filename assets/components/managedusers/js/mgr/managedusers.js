var ManagedUsers = function (config) {
    config = config || {};
    ManagedUsers.superclass.constructor.call(this, config);
};
Ext.extend(ManagedUsers, Ext.Component, {
    page: {}, window: {}, grid: {}, panel: {}, combo: {}, config: {}, utils: {}
});
Ext.reg('managedusers', ManagedUsers);

ManagedUsers = new ManagedUsers();
