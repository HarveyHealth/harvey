<template>
    <aside>
        <button class="button--close flyout-close" @click="close()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="title">Create Messages</h2>
        <div class="input__container">
            <label class="input__label" for="patient_name">{{ toUserType }}</label>
            <span class="custom-select">
                <select @change="updateUser($event)">
                    <option  v-for="user in userList" :data-id="user.user_id">{{ user.name }}</option>
                </select>
            </span>
        </div>
        <div class="input__container">
            <label class="input__label" for="patient_name">subject</label>
            <input v-model="subject" class="input--text" type="text">
        </div>
        <div class="input__container">
            <label class="input__label" for="patient_name">message</label>
            <textarea v-model="message" class="input--textarea"></textarea>
        </div>
        <div>
            <div class="inline-centered">
                <button class="button" 
                @click="createMessage()"
                :disabled="!subject || !selected">Send</button>
            </div>
        </div>
    </aside>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';
    import Flyout from '../../../commons/Flyout.vue';
    export default {
        props: [],
        name: 'Preview',
        components: {
            Flyout
        },
        data() {
            return {
                close: this.$parent.close,
                selected: '',
                subject: '',
                message: ''
            }
        },
        methods: {
            updateUser(e) {
                this.selected = e.target.children[e.target.selectedIndex].dataset.id;
            },
            createMessage() {
                axios.post(`${this.$root.$data.apiUrl}/messages`, {
                    message: this.message,
                    recipient_user_id: Number(this.selected),
                    subject: this.subject
                })
                .then(response => {
                    this.$root.$data.global.detailMessages[response.data.data.attributes.subject] = [response.data.data];
                    this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length - 1]);
                    this.$parent.messageList = this.$root.$data.global.messages
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                })
                .catch(error => {
                    console.log(`ERROR`, error);
                })
                this.$parent.close();
            }
        },
        computed: {
            userList() {
                if (this.$root.$data.global.user.attributes.user_type === 'patient') {
                    return [''].concat(this.$root.$data.global.practitioners);
                } else if (this.$root.$data.global.user.attributes.user_type === 'practitioner') {
                    return [''].concat(this.$root.$data.global.patients);
                } else if (this.$root.$data.global.user.attributes.user_type === 'admin') {
                    return [''].concat(this.$root.$data.global.practitioners).concat(this.$root.$data.global.patients);
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