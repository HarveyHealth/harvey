<template>
    <div>
        <form :id="type" method="POST">
            <div class="input__label">
                {{ label }}
            </div>
            <label :for="'file-select-' + type">
                <div class="border-upload-container">
                    <div class="upload-container">
                            <i class="fa pdf-icons" :class="icon"></i>
                            <p class="pdf-upload-text">{{ name }} (PDF)</p>
                        </div>
                    </div>
                </div>
            </label>
            <input @change="upload" type="file" :id="'file-select-' + type" name="image" accept=".pdf" hidden/>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'image-upload',
        props: {
            label: String,
            type: String,
            route: String,
            name: String,
            icon: String,
        },
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
