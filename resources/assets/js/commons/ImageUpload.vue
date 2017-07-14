<template>
    <form :id="type" method="POST">
        <div class="button-label">
            {{ label }}
        </div>
        <label :for="'file-select' + type">
            <div class="button">
                Upload Image
            </div>
        </label>
        <input @change="upload" type="file" :id="'file-select' + type" name="image" accept=".jpg, .jpeg, .png" hidden/>
    </form>
</template>

<script>
    export default {
        name: 'image-upload',
        props: ['label', 'type', 'model', 'model_id'],
        methods: {
            upload() {
                // We can run file size checker if needed:
                console.log('File Size', document.getElementById(`file-select${this.type}`).files[0].size);

                const send = new Promise((resolve, reject) => {
                    const formData = new FormData(document.getElementById(this.type));
                    let request = new XMLHttpRequest();
                    request.open('POST', `api/v1/${this.model}s/${this.model_id}/image?type=${this.type}`);
                    request.setRequestHeader('X-CSRF-TOKEN', Laravel.app.csrfToken);
                    request.send(formData);
                    request.onreadystatechange = function() {
                        if (request.readyState === 4) {
                            resolve(request.response);
                        }
                    }
                });

                send.then(data => {
                    this.$emit('uploaded', data);
                })
                .catch(err => console.log(err));
            }
        },
    }
</script>

<style>
    .button-label {
        margin-bottom: 10px;
    }
</style>
