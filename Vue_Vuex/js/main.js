"use strict";

let app = new Vue({
    el: '#app',
    store,
    data: {
        isOpenAlert: false,
    },

    computed: {
        files() {
            return this.$store.state.storageFiles;
        },

        progress() {
            return this.$store.state.progressData;
        },

        status() {
            return this.$store.getters.status;
        },

        statusMessage() {
            const {status} = this.$store.getters;
            switch (status) {
                case 200:
                    this.isOpenAlert = true;
                    return 'Successfully';
                case 499:
                    this.isOpenAlert = true;
                    return 'Upload canceled';
                case 500:
                    this.isOpenAlert = true;
                    return 'Error occurred';
                default:
                    this.isOpenAlert = false;
                    return 'Loading...';
            }
        }
    },


    methods: {
        handlerFileUpload() {
            let file = this.$refs.file.files[0];
            if (file) {
                this.$store.commit('setFile', file);
                this.submitFile();
            } else {
                this.$store.commit('setFile', '');
            }
        },

        submitFile() {
            const store = this.$store;
            store.commit('setStatusUpload', 0);
            const {file} = store.state;
            if (file.size <= 0) {
                store.commit('setStatusUpload', 500);
                console.error('File size is zero!');
                return;
            }

            store.commit('setStatusUpload', 100);
            let blobArray = this.fileSplitting(file);
            this.prepareAndSend(blobArray);
            document.getElementById('file-input').value = null;
        },

        fileSplitting(file) {
            const chunkSize = 1048576; // 1Mb
            let offset = 0;
            let chunks = Math.ceil(file.size / chunkSize);
            let chunk = 0;
            const blobArray = [];

            while (chunks > chunk) {
                let blob = file.slice(offset, offset + chunkSize);
                blobArray.push(blob);
                offset += blob.size;
                chunk++;
            }
            return blobArray;
        },

        async prepareAndSend(blobsArray) {
            const min = 100000;
            const max = 1000000;
            const fileId = Math.floor(Math.random() * (max - min) + min);
            const chunks = blobsArray.length;
            this.$store.commit('setId', fileId);

            for (let data of blobsArray) {
                if (this.$store.getters.status !== 100) return;
                await this.requestSender({fileId, data, chunks});
            }
        },

        requestSender(data, attempts = 3) {
            const randomNumber = Math.floor(Math.random() * 6);
            return new Promise(((resolve, reject) => {
                setTimeout(() => {
                    if (randomNumber) {
                        resolve({...data});
                    } else {
                        reject(attempts);
                    }
                }, 500);
            }))
                .then((result) => {
                    this.$refs.server.serverSide('chunks', 'post', result);
                    this.$store.commit('setProgressData', result.chunks);
                })
                .catch((attempts) => {
                    if (attempts <= 0) {
                        this.$store.commit('setStatusUpload', 500);
                        this.$store.commit('setProgressData');
                        console.error('Package is not delivered!');
                        return;
                    }
                    console.log('Chunk is not loaded!');
                    return this.requestSender(data, --attempts);
                })
        },

        cancelUpload() {
            this.$store.commit('setStatusUpload', 499);
        },
    }
})