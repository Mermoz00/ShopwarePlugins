const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapGetters } = Component.getComponentHelper();

Component.extend('bal-three-create', 'bal-three-detail', {
    methods: {
        getThree() {
            this.three = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;
            this.submitFile();
        },
        saveThree(){
            this.repository
                .save(this.three, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'bal.three.detail', params: { id: this.three.id } });
                }).catch((exception) => {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$t('bal-three.detail.errorTitle'),
                    message: exception
                });
            });
        }

    }
});
