"use strict";

Vue.component('server', {
    computed: {
        files() {
            return this.storageManager();
        },
    },

    methods: {
        serverSide(storageType = 'files', requestType = 'get', data = null) {
            if (storageType === 'chunks' && requestType === 'post') {
                const file = this.combineChunks(data);
                if (file) {
                    setTimeout(() => {
                        const {status} = this.$store.getters;
                        if (status === 100 || status === 499) {
                            this.storageManager('chunks', 'delete', data.fileId);
                        }
                        if (status !== 100) return;

                        this.storageManager('files', 'post', file);
                        this.$store.commit('setStatusUpload', 200);
                        this.$store.commit('setProgressData');
                    }, 1000);
                }
            }
        },

        combineChunks(data) {
            this.storageManager('chunks', 'post', data);
            let allChunks = this.storageManager('chunks', 'get');

            if (allChunks && allChunks[data.fileId]) {
                let chunksFile = allChunks[data.fileId];
                if (chunksFile.data.length === chunksFile.chunks) {
                    return this.setDataForFile(chunksFile, data);
                }
                return null;
            }
        },

        setDataForFile(chunksFile, data) {
            const {file} = this.$store.state;
            const newFile = new File(chunksFile.data, file.name, {type: file.type});
            const dataFile = {id: data.fileId, data: newFile};
            dataFile.size = this.createSizeForUI(newFile.size);

            const type = dataFile.data.type;
            if (type === 'image/jpeg' || type === 'image/png' || type === 'image/gif') {
                dataFile.url = URL.createObjectURL(dataFile.data);
            }
            return dataFile;
        },

        createSizeForUI(size) {
            const kb = 1024;
            const mb = 1024 * 1024;
            if (size >= mb) {
                return (size / mb).toFixed(2) + ' Mb';
            } else if (size >= kb) {
                return (size / kb).toFixed(2) + ' Kb';
            } else {
                return size + ' B';
            }
        },

        removeFile(id) {
            this.storageManager('files', 'delete', id);
        },

        storageManager(storageType = 'files', requestType = 'get', data = null) {
            const store = this.$store;

            if (storageType === 'files') {
                if (requestType === 'get') {
                    return store.getters.files;
                } else if (requestType === 'post') {
                    store.commit('setStorageFiles', data);
                } else if (requestType === 'delete') {
                    store.commit('deleteStorageFiles', data);
                }
            } else if (storageType === 'chunks') {
                if (requestType === 'get') {
                    return store.getters.chunks;
                } else if (requestType === 'post') {
                    store.commit('setStorageChunks', data);
                } else if (requestType === 'delete') {
                    store.commit('deleteStorageChunks', data);
                    store.commit('setId');
                }
            }
        }
    },

    template: `<div class="server">
                    <h3 class="server_title">Storage</h3>
                    <div class="server_storage">
                        <div v-for="file in files" :keys="file.id" class="file">
                            <p @click="removeFile(file.id)" class="cross cross__file">x</p>
                            <div>
                                <p class="file_name">{{file.data.name}}</p> <br/>
                                <p class="file_size">File size: <br/> {{file.size}}</p>
                            </div>
                            <img class="file_img" v-if="file.url" :src="file.url" :alt="file.data.name">
                            <img class="file_img file_img__placeholder" v-else src="style/img/placeholder.png" :alt="file.data.name">
                        </div>
                    </div>
                </div>`,
})