import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'text-image-reversed',
    label: 'Image next to Text',
    category: 'text-image',
    component: 'sw-cms-block-text-image-reversed',
    previewComponent: 'sw-cms-preview-text-image-reversed',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        left: 'text',
        right: 'image'
    }
});
