import template from './bal-three-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('bal-three-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            threes: null
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
                label: this.$t('bal-three.list.columnId'),
                routerLink: 'bal.three.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            },
            {
                property: 'name',
                dataIndex: 'name',
                label: this.$t('bal-three.list.columnName'),
                inlineEdit: 'string',
                allowResize: true
            },
            {
                property: 'path',
                dataIndex: 'path',
                label: this.$t('bal-three.list.columnPath'),
                inlineEdit: 'string',
                allowResize: true
            }];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('bal_three');

        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.threes = result;
            });
    }
});
