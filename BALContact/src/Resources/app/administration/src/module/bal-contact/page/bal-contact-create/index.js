const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapGetters } = Component.getComponentHelper();

Component.extend('bal-contact-create', 'bal-contact-detail', {
    methods: {
        getBalContact() {
            this.contact = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;
            this.saveBalContact();
        }
        ,
        saveBalContact(){
            this.repository
                .save(this.contact, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'bal.contact.detail', params: { id: this.contact.id } });
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
