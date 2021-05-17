//{block name="backend/article/view/detail/window" append}
Ext.define('Shopware.apps.Article.view.detail.Window', {
    override:'Shopware.apps.Article.view.detail.Window',

    /**
     * Creates the main tab panel which displays the different tabs for the article sections.
     * To extend the tab panel this function can be override.
     *
     * @return Ext.tab.Panel
     */
    createBaseTab: function() {
        let me = this,mainWindow = me.subApp.articleWindow;
        let tabPanel = me.callParent(arguments);
        me.tabPanel = tabPanel;
        mainWindow.on('storesLoaded', me.onConfigurator3DSDArticleLoaded, me);

        return tabPanel;

    },
    onConfigurator3DSDArticleLoaded: function (article) {
        var me = this;

        if (parseInt(article.raw.id)) {
            me.article = article;
            Ext.getCmp('fileId').setValue(me.article.raw.mainDetail.attribute.articleDetailId);
            me.threeTab.setDisabled(false);
        }
    },
    createMainTabPanel: function() {
        var me = this;

        var formTab = me.callParent(arguments);
        //Der Container für das Tab => Der Tab selber
        me.threeTab = Ext.create('Ext.container.Container', {
            title: 'Neues 3D Bild',
            disabled: true,
            name: 'three',
            layout: 'border'
        });

        me.mainTab.insert(me.threeTab);

        return formTab;
    },
    //Der Inhalt des Tabs
    createThreeContent: function() {
        var me = this;

        me.threeContent = Ext.create('Shopware.apps.Article.spkExtendBackend.view.detail.Three', {
            height: '100%',
            width: '100%',
            autoScroll:true,
            margin: 10
        });

        return me.threeContent;
    },
    onStoresLoaded: function(article, stores) {
        var me = this;
        me.callParent(arguments);
        me.threeTab.add(me.createThreeContent());
    },
});
//{/block}
