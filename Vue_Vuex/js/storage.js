"use strict";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        id: 0,
        file: '',
        storageChunks: {},
        storageFiles: [],
        statusUpload: 0, // 0-before upload | 100-loading | 200-success | 499-cancel | 500-error
        progressData: 0,
    },

    getters: {
        files: state => {
            return state.storageFiles;
        },
        chunks: state => {
            return state.storageChunks;
        },
        status: state => {
            return state.statusUpload;
        },
        id: state => {
            return state.id;
        },
    },

    mutations: {
        setId(state, id = 0) {
            state.id = id;
        },

        setFile(state, data) {
            state.file = data;
        },

        setStatusUpload(state, statusId) {
            state.statusUpload = statusId;
        },

        setStorageChunks(state, data) {
            const chunksObj = state.storageChunks[data.fileId];

            state.storageChunks = {
                ...state.storageChunks,
                [data.fileId]: {
                    'chunks': data.chunks,
                    'data': chunksObj ? [...chunksObj.data, data.data] : [data.data],
                },
            }
        },

        setStorageFiles(state, dataFile) {
            state.storageFiles = [
                ...state.storageFiles,
                {
                    ...dataFile,
                },
            ]
        },

        setProgressData(state, totalChunks = 0) {
            if (!totalChunks) {
                state.progressData = 0;
                return;
            }
            const maxProgress = 100;
            const step = Math.round(maxProgress / totalChunks);

            if (state.statusUpload === 100) {
                if (state.progressData + step >= maxProgress) state.progressData = maxProgress;
                else state.progressData += step;
            }
        },

        deleteStorageChunks(state, id) {
            let chunks = JSON.parse(JSON.stringify(state.storageChunks));
            delete chunks[id];
            state.storageChunks = chunks;
        },

        deleteStorageFiles(state, id) {
            let files = state.storageFiles;
            let keyFile = 0;
            for (let key in files) {
                if (+files[key].id === +id) {
                    keyFile = key;
                    break;
                }
            }
            files.splice(keyFile, 1)
            state.storageFiles = files;
        },
    }
})