<template>
    <div>
        <form :id="type" method="POST">
            <div class="input__label">
                {{ label }}
            </div>
            <label :for="'file-select-' + type">
                <div class="button">
                    Upload Image
                </div>
            </label>
            <input @change="upload" type="file" :id="'file-select-' + type" name="image" accept=".jpg, .jpeg, .png" hidden/>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'image-upload',
        props: {
            label: String,
            type: String,
            route: String
        },
        methods: {
            upload() {
                this.$emit('uploading');
                const send = new Promise((resolve, reject) => {
                    const formData = new FormData(document.getElementById(this.type));
                    let request = new XMLHttpRequest();
                    request.open('POST', this.route);
                    request.setRequestHeader('X-CSRF-TOKEN', Laravel.app.csrf_token);
                    request.send(formData);
                    request.onreadystatechange = function() {
                        if (request.readyState === 4) {
                            if (request.status === 200) {
                                resolve(request.response);
                            } else {
                                reject(request.response);
                            }
                        }
                    };
                });

                send.then(data => {
                    this.$emit('uploaded', JSON.parse(data));
                })
                .catch(err => {
                    this.$emit('uploadError', JSON.parse(err));
                });
            }
        }
    };
</script>

<style>
    .button-label {
        margin-bottom: 10px;
    }
</style>
