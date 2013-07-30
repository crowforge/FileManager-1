var baseUrl = Ext.select('base').elements[0].href;

var tabIdx = 0;

var contentPanel = Ext.widget('tabpanel', {
    region: 'center',
    enableTabScroll:true,
    items: [{
        tabIndex: 0,
        title: 'Dashboard'
        //html: '<iframe src="' + baseUrl + '/pages/display/phpinfo" style="height: 100%; width: 100%"></iframe>'
    }],
    addTab: function(title, path) {
        var id = md5(path);

        ++tabIdx;

        if (Ext.get(id) == null) {
            this.add({
                id: id,
                title: title,
                closable: true,
                //autoScroll: true,
                layout: 'fit',
                loader: {
                    url: baseUrl + '/file_manager/file_open.json',
                    params: {
                        node: path
                    },
                    autoLoad: true,
                    renderer: 'component',
                    scripts: true
                }
            }).show();
        } else {
            //tab = Ext.get(id).dom.tabIndex;
            this.setActiveTab(id);
        }
    }
});

var treeContextMenu = Ext.create('Ext.menu.Menu', {
    items: [{
        text: '<strong>Open</strong>',
        handler: function(widget, event) {
            var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
            if (rec.data.leaf == false) {
                rec.expand();
            } else {
                contentPanel.addTab(rec.data.text, rec.data.id);
            }
        }
    }, {
        text: 'Rename',
        handler: function() {
            var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
            Ext.MessageBox.prompt('Rename', 'Please enter new name:', function(btn, text) {
                Ext.Ajax.request({
                    url: baseUrl + '/file_manager/rename',
                    params: {
                        path: rec.data.id,
                        name: text
                    },
                    success: function(response) {
                        var json = Ext.JSON.decode(response.responseText);
                        Ext.MessageBox.alert('Rename', json.message);
                        Ext.getCmp('FileBrowserPanel').store.reload();
                    }
                });
            });
        }
    }, {
        text: 'Delete',
        handler: function() {
            var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
            Ext.MessageBox.confirm('Delete', 'Are you sure you want to permanently delete "' + rec.data.text + '"?', function(btn) {
                if (btn == 'yes') {
                    if (rec.data.leaf == true) {
                        sendUrl = '/file_manager/file_delete';
                    } else {
                        sendUrl = '/file_manager/directory_delete';
                    }

                    Ext.Ajax.request({
                        url: baseUrl + sendUrl,
                        params: {
                            path: rec.data.id
                        },
                        success: function(response) {
                            var json = Ext.JSON.decode(response.responseText);
                            Ext.MessageBox.alert('Delete', json.message);
                            Ext.getCmp('FileBrowserPanel').store.reload();
                        }
                    });
                }
            });
        }
    }, '-', {
        id: 'newBtn',
        text: 'New',
        menu: {
            items: [{
                text: 'Folder',
                handler: function() {
                    var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
                    Ext.MessageBox.prompt('Create New Folder', 'Please enter new folder name:', function(btn, text) {
                        Ext.Ajax.request({
                            url: baseUrl + '/file_manager/directory_create',
                            params: {
                                path: rec.data.id,
                                name: text
                            },
                            success: function(response) {
                                var json = Ext.JSON.decode(response.responseText);
                                Ext.MessageBox.alert('Create New Folder', json.message);
                                Ext.getCmp('FileBrowserPanel').store.reload();
                            }
                        });
                    });
                }
            }, {
                text: 'File',
                handler: function() {
                    var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
                    Ext.MessageBox.prompt('Create New File', 'Please enter new file name:', function(btn, text) {
                        Ext.Ajax.request({
                            url: baseUrl + '/file_manager/file_create',
                            params: {
                                path: rec.data.id,
                                name: text
                            },
                            success: function(response) {
                                var json = Ext.JSON.decode(response.responseText);
                                Ext.MessageBox.alert('Create New File', json.message);
                                Ext.getCmp('FileBrowserPanel').store.reload();
                            }
                        });
                    });
                }
            }]
        }
    }, '-', {
        id: 'uploadBtn',
        text: 'Upload',
        handler: function(widget, event) {
            var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];

            Ext.create('Ext.window.Window', {
                title: 'Upload',
                modal: true,
                width: 600,
                height: 300,
                html: rec.data.id
            }).show();
        }
    }],
    listeners: {
        beforeshow: function() {
            var rec = Ext.getCmp('FileBrowserPanel').getSelectionModel().getSelection()[0];
            if (rec.data.leaf) {
                Ext.getCmp('newBtn').disable();
                Ext.getCmp('uploadBtn').disable();
            } else {
                Ext.getCmp('newBtn').enable();
                Ext.getCmp('uploadBtn').enable();
            }
        }
    }
});

Ext.require([
    'Ext.data.Store',
    'Ext.tab.*'
]);
Ext.onReady(function() {
    var directories = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            url: baseUrl + '/file_manager/index.json'
        },
        root: {
            text: '/',
            expanded: true
        },
        folderSort: true,
        sorters: [{
            property: 'text',
            direction: 'asc'
        }]
    });

    Ext.create('Ext.Viewport', {
        layout: 'border',
        items: [{
            id: 'FileBrowserPanel',
            title: 'File Browser',
            collapsible: true,
            tools: [{
                type: 'refresh',
                handler: function(event, toolEl, panelHeader) {
                    //Ext.getCmp('FileBrowserPanel').store.reload();
                    //Ext.getCmp('FileBrowserPanel').store.reload();
                    directories.getRootNode().removeAll();
                    directories.load();
                }
            }],
            region: 'west',
            width: 250,
            xtype: 'treepanel',
            store: directories,
            useArrows: true,
            listeners: {
                itemcontextmenu: function(view, rec, item, index, eventObj) {
                    eventObj.stopEvent();
                    treeContextMenu.showAt(eventObj.getXY());
                    return false;
                },
                itemdblclick: function(view, rec, item, index, eventObj) {
                    if (rec.data.leaf == true) {
                        contentPanel.addTab(rec.data.text, rec.data.id);
                    }
                }
            }
        }, contentPanel],
        renderTo: Ext.getBody()
    });

/*    var hideMask = function () {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').animate({
            opacity:0,
            remove:true,
            callback: firebugWarning
        });
    };

    Ext.defer(hideMask, 250);*/

});