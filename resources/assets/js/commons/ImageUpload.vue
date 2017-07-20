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
        props: ['label', 'type', 'route'],
        methods: {
            upload() {
                this.$emit('uploading');
                const send = new Promise((resolve, reject) => {
                    const formData = new FormData(document.getElementById(this.type));
                    let request = new XMLHttpRequest();
                    request.open('POST', this.route);
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
                .catch(err => {
                    this.$emit('uploaded', null);
                    console.log(err)
                });
            },
        },
    }
</script>

<style>
    .button-label {
        margin-bottom: 10px;
    }
</style>
