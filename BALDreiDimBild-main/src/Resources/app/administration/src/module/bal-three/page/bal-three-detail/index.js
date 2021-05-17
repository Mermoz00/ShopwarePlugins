import template from './bal-three-detail.html.twig';
import * as axios from "./axios.min.js";

const { Component, Mixin } = Shopware;

Component.register('bal-three-detail', {
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
            entityName:'bal_three',
            three: null,
            file: '',
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('bal_three');
        this.getThree();
    },

    methods: {
        getThree() {

            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.three = entity;
                });
        },
        onClickSave() {
            this.isLoading = true;
            if(this.file){
                this.submitFile();
            }
            else{
                this.saveThree();
            }

        },

        saveFinish() {
            this.processSuccess = false;
        },
        onFileSelected(event){
            this.file = event;
        },
        submitFile(){
            let formData = new FormData();
            formData.append('file', this.file);
            //formData.append('id', this.three.id);
            axios.post( '/upload/file',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((response)=>{
                this.three.path=response.data.file;
                this.saveThree();
            })
            .catch((error)=>{
                console.log('FAILURE!!',error.response.data);
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$t('bal-three.detail.errorTitle'),
                    message: error.response.data.message
                });
            });
        },
        saveThree(){
            this.repository
                .save(this.three, Shopware.Context.api)
                .then(() => {
                    this.getThree();
                    this.isLoading = false;
                    this.processSuccess = true;
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
