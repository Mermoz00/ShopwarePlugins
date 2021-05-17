import template from './bal-contact-detail.html.twig';

const { Component, Mixin } = Shopware;

Component.register('bal-contact-detail', {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            entityName:'bal_contact',
            contact: null,
            file: '',
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('bal_contact');
        this.getBalContact();
    },

    methods: {
        getBalContact() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.contact = entity;
                });
        },
        onClickSave() {
            this.isLoading = true;

            this.saveBalContact();


        },

        saveFinish() {
            this.processSuccess = false;
        },
        onFileSelected(event){
            this.file = event;
        },
        saveBalContact(){
            this.repository
                .save(this.contact, Shopware.Context.api)
                .then(() => {
                    this.getBalContact();
                    this.isLoading = false;
                    this.processSuccess = true;
                }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('bal-contact.detail.errorTitle'),
                    message: exception
                });
            });
        }
    }
});
