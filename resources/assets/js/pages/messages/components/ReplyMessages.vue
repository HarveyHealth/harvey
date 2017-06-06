<template>
    <aside>
        <button class="button--close flyout-close" @click="reply()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="title">Reply</h2>
        <div class="input__container">
            <label class="input__label" for="patient_name">{{ toUserType }}</label>
            <span class="custom-select">
                <select name="doctor_name">
                    <option>{{ name }}</option>
                </select>
            </span>
        </div>
        <div class="input__container">
            <label class="input__label" for="patient_name">message</label>
            <textarea v-model="message" class="input--textarea"></textarea>
        </div>
        <div>
            <div class="inline-centered">
                <button class="button" @click="createMessage()">Create Message</button>
            </div>
        </div>
    </aside>
</template>

<script>
    import axios from 'axios';
    export default {
        props: ['name', 'header', 'id'],
        name: 'Reply',
        components: {

        },
        data() {
            return {
                reply: this.$parent.reply,
                message: ''
            }
        },
        methods: {
            updateUser(e) {
                this.selected = e.target.children[e.target.selectedIndex].dataset.id;
            },
            createMessage() {
                axios.post(`/api/v1/messages`, {
                    message: this.message,
                    recipient_user_id: this.$props.id,
                    subject: this.$props.header
                })
                .then(response => {
                    this.$root.$data.global.detailMessages[this.$props.header].push(response.data.data);
                    axios.put(`/api/v1/messages/${response.data.data.id}/read`)
                })
                this.$parent.reply();
            }
        },
        computed: {
            userList() {
                if (this.$root.$data.global.user.attributes.user_type === 'patient') {
                    return [''].concat(this.$root.$data.global.practitioners);
                } else if (this.$root.$data.global.user.attributes.user_type === 'practitioner') {
                    return [''].concat(this.$root.$data.global.patients);
                } else if (this.$root.$data.global.user.attributes.user_type === 'admin') {
                    return [''].concat(this.$root.$data.global.practitioners)
                        .concat(this.$root.$data.global.patients);
                }
            },
            toUserType() {
                if (this.$root.$data.global.user.attributes.user_type === 'patient') {
                    return "doctor";
                } else if (this.$root.$data.global.user.attributes.user_type === 'practitioner') {
                    return "patient";
                } else if (this.$root.$data.global.user.attributes.user_type === 'admin') {
                    return "all";
                }
            }
        }
    }
</script>