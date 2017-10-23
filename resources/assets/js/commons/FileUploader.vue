<template>
    <form
        role="form"
        method="post"
        @submit.prevent="onSubmit"
    >
        <input id="fileInput"
            type="file"
            name="file"
            @change="onFileChange"
        >
        <div class="upload-action-box" :class="{'upload-action-box--has-file': hasFile}">
            <transition
                mode="out-in"
                enter-active-class="animated animated-fast fadeIn"
                leave-active-class="animated animated-fast fadeOut"
            >
                <label v-if="!hasFile" for="fileInput" class="is-block has-text-centered"
                    :class="{'is-ready': hasFile}"
                >
                    <span class="icon"><i class="fa fa-cloud-upload"></i></span>
                    <p><small>Drag a file or browse</small></p>
                </label>
                <div v-else>
                    <button type="button" class="delete is-inverted"
                        @click="removeFile"
                    ></button>
                    <p><small>{{fileName}}</small></p>
                    <button type="submit" class="button is-primary"
                        :class="{'is-loading': uploading && !upload_success, 'upload-success': upload_success}"
                    >
                        <span v-if="upload_success" class="icon"><i class="fa fa-check"></i></span>
                        <span class="text">Upload</span>
                    </button>
                </div>
            </transition>
        </div>
    </form>
</template>

<script>
    export default {
        props: ['action'],
        data() {
            return {
                hasFile: false,
                fileName: '',
                uploading: false,
                upload_success: false,
                form: new FormData()
            };
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files,
                    error = this.validateFiles(files);

                if (!error) {
                    this.updateFiles(files);
                } else {
                    // this.onError();
                }
                e.target.value = null;
            },
            validateFiles(files) {
                let error = false;
                if (!files.length) error = true;
                return error;
            },
            updateFiles(files) {
                let file = files[0];

                this.form.append('file', file);
                this.hasFile = true;
                this.fileName = file.name;
            },
            removeFile() {
                this.form.delete('file');
                this.hasFile = false;
                this.fileName = '';
            },
            onSubmit() {
                this.uploading = true;

                this.$http.post(this.$root.apiUrl + this.action, this.form)
                    .then(this.onSuccess)
                    .catch((error) => {
                        console.log(error);
                    });
            },
            onSuccess(data) {
                this.upload_success = true;
                setTimeout(()=> {
                    this.$emit('uploaded', data);
                }, 400);
            },
            onError() {

            }
        }
    };
</script>
