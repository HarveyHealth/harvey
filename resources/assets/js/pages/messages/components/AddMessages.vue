<template>
    <aside>
        <button class="button--close flyout-close" @click="close()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="title">Create Messages</h2>
        <div class="input__container">
            <label class="input__label" for="patient_name">{{ toUserType }}</label>
            <span class="custom-select">
                <select @change="updateUser($event)" name="doctor_name">
                    <option  v-for="user in userList" :data-id="user.id">{{ user.name }}</option>
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
                <button class="button" @click="createMessage()">Create Message</button>
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
                axios.post(`/api/v1/messages`, {
                    message: this.message,
                    recipient_user_id: Number(this.selected),
                    subject: this.subject
                })
                .then(resp => {
                    axios.get(`/api/v1/messages`)
                        .then(response => {
                            let data = {};
                            response.data.data.forEach(e => {
                            data[e.attributes.sender_user_id] = data[e.attributes.sender_user_id] ?  
                                data[e.attributes.sender_user_id] :
                                {};
                            data[e.attributes.sender_user_id][e.attributes.subject] = data[e.attributes.sender_user_id][e.attributes.subject] ?
                                data[e.attributes.sender_user_id][e.attributes.subject] :
                                [];
                            data[e.attributes.sender_user_id][e.attributes.subject].push(e);
                            if (data[window.Laravel.user.id] && data[window.Laravel.user.id][e.attributes.subject]) {
                                data[window.Laravel.user.id][e.attributes.subject].push(e);
                            }
                            });
                            let object = {}
                            _.each(data, (val, key) => {
                                _.extend(object, val);
                            });
                            if (object) {
                                this.$root.$data.global.messages = Object.values(object).map(e => e[e.length - 1])
                                this.$root.$data.global.detailMessages = object;
                            }
                            this.messageList = this.$root.$data.global.messages;
                    })
                    .catch(err => {
                        console.log(`ERROR`);
                    })
                })
                .catch(error => {
                    console.log(`ERROR`);
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
                    return [''].concat(this.$root.$data.global.practitioners);
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

<style>

</style>