import './page/bal-three-list';
import './page/bal-three-detail';
import './page/bal-three-create';
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;

Module.register('bal-three', {
    type: 'plugin',
    name: 'Three',
    title: 'bal-three.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'default-shopping-paper-bag-product',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'bal-three-list',
            path: 'list'
        },
        detail: {
            component: 'bal-three-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'bal.three.list'
            }
        },
        create: {
            component: 'bal-three-create',
            path: 'create',
            meta: {
                parentPath: 'bal.three.list'
            }
        }
    },

    navigation: [{
        label: 'bal-three.general.mainMenuItemGeneral',
        color: '#ff3d58',
        path: 'bal.three.list',
        icon: 'default-shopping-paper-bag-product',
        position: 100
    }]
});
