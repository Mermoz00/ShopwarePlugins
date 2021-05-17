//{namespace name=backend/article/view/main}
//{block name="backend/article/swagExtendBackend/view/detail/three"}
Ext.define('Shopware.apps.Article.spkExtendBackend.view.detail.Three', {
    /**
     * Define that the category drop zone is an extension of the Ext.panel.Panel
     * @string
     */
    extend:'Ext.form.FieldSet',
    /**
     * List of short aliases for class names. Most useful for defining xtypes for widgets.
     * @string
     */
    alias:'widget.article-three-three',
    /**
     * Set css class for this component
     * @string
     */
    cls: Ext.baseCSSPrefix + 'article-three-three',

    /**
     * Layout for the component
     */
    layout: 'anchor',

    /**
     * Defaults for the panel items
     * @object
     */
    defaults: {
        anchor: '100%'
    },

    /**
     * The initComponent template method is an important initialization step for a Component.
     * It is intended to be implemented by each subclass of Ext.Component to provide any needed constructor logic.
     * The initComponent method of the class being created is called first,
     * with each initComponent method up the hierarchy to Ext.Component being called thereafter.
     * This makes it easy to implement and, if needed, override the constructor logic of the Component at any step in the hierarchy.
     * The initComponent method must contain a call to callParent in order to ensure that the parent class' initComponent method is also called.
     *
     * @return void
     */
    initComponent:function () {
        const me = this;
        //me.items =  me.createContent();


        me.threeStore = Ext.create('Ext.data.Store', {
            model: 'Shopware.model.Dynamic',
            proxy: {
                type: 'ajax',
                url: '{url controller="EntitySearch" action="search"}?model=SPKDreiDimBild\\Models\\Three',
                reader: Ext.create('Shopware.model.DynamicReader')
            }
        });
        me.threes=Ext.create('Ext.container.Container', {
            anchor: '100%',
            focusable: false,
            margin: 10,
            items: [{
                xtype: 'form',
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Id',
                        id:'fileId',
                        name:'fileId',
                        disabled: true,
                    },
                    {
                        xtype: 'combobox',
                        name: 'three',
                        fieldLabel: '3D-Bild auswählen',
                        store: me.threeStore,
                        allowBlank: false,
                        valueField: 'path',
                        displayField: 'name',
                        listeners: {
                            select: {
                                fn: function (filefield) {
                                    Ext.getCmp('fileId').setDisabled(false);
                                    let form = filefield.up('form').getForm();
                                    form.submit({
                                        url: '/backend/spk_extend_backend/list',
                                        waitMsg: 'Uploading',
                                        success: function (response, action) {
                                            Ext.Msg.alert('Success', '{s name=three/translation/message}erfolgreiches update{/s}',function (btn) {
                                                if (btn === "ok") {
                                                    Ext.ComponentQuery.query('[name=__attribute_three]')[0].setValue(action.result.data.message);
                                                }
                                            });
                                            Ext.getCmp('fileId').setDisabled(true);
                                        },
                                        failure: function (response, action) {
                                            Ext.Msg.alert('Failed', action.result.data.message ? action.result.data.message : '{s name=three/translation/message_fail}Beim Update ist ein unbekannter Fehler aufgetreten{/s}');
                                            Ext.getCmp('fileId').setDisabled(true);
                                        }
                                    });
                                }
                            }
                        }
                    }
                ]
            }]
        });

        me.threes.setVisible(true);
        //me.elementFieldset.add(me.createCustomFields);
        me.items =me.threes;
        me.callParent(arguments);

    },


});
//{/block}
