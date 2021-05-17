import './page/bal-contact-list';
import './page/bal-contact-detail';
import './page/bal-contact-create';
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;

Module.register('bal-contact', {
    type: 'plugin',
    name: 'Contact',
    title: 'bal-contact.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '#55ea16',
    icon: 'default-avatar-multiple',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'bal-contact-list',
            path: 'list'
        },
        detail: {
            component: 'bal-contact-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'bal.contact.list'
            }
        },
        create: {
            component: 'bal-contact-create',
            path: 'create',
            meta: {
                parentPath: 'bal.contact.list'
            }
        }
    },

    navigation: [{
        label: 'bal-contact.general.mainMenuItemGeneral',
        color: '#55ea16',
        path: 'bal.contact.list',
        icon: 'default-avatar-multiple',
        position: 100
    }]
});
