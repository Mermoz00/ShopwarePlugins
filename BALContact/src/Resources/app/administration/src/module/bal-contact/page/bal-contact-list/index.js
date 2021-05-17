import template from './bal-contact-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('bal-contact-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            contacts: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [{
                property: 'id',
                dataIndex: 'id',
                label: this.$t('bal-contact.list.columnId'),
                routerLink: 'bal.contact.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            },
            {
                property: 'firstname',
                dataIndex: 'firstname',
                label: this.$t('bal-contact.list.columnFirstName'),
                inlineEdit: 'string',
                allowResize: true
            }
            ,
            {
                property: 'lastname',
                dataIndex: 'lastname',
                label: this.$t('bal-contact.list.columnLastName'),
                inlineEdit: 'string',
                allowResize: true
            },
            {
                property: 'email',
                dataIndex: 'email',
                label: this.$t('bal-contact.list.columnEmail'),
                inlineEdit: 'string',
                allowResize: true
            },
            {
                property: 'phonenumber',
                dataIndex: 'phonenumber',
                label: this.$t('bal-contact.list.columnPhone'),
                inlineEdit: 'string',
                allowResize: true
            }
            ];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('bal_contact');
        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.contacts = result;
                console.log('result',result)
            });
    }
});
