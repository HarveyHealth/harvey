<template>
    <form id="file-form" method="POST">
        <label for="file-select">
            <div class="button">
                Upload Image
            </div>
        </label>
        <input @change="upload" type="file" id="file-select" name="image" accept=".jpg, .jpeg, .png" hidden/>
    </form>
</template>

<script>
    export default {
        name: 'image-upload',
        props: ['type', 'model', 'model_id'],
        methods: {
            upload() {
                const send = new Promise((resolve, reject) => {
                    const formData = new FormData(document.getElementById('file-form'));
                    let request = new XMLHttpRequest();
                    request.open('POST', `api/v1/${this.model}/${this.model_id}/image?type=${this.type}`);
                    request.setRequestHeader('X-CSRF-TOKEN', Laravel.app.csrfToken);
                    request.send(formData);
                });

                send.then(data => {
                    // set the new image to display
                })
                .catch(err => console.log(err));
            }
        },
    }
</script>
