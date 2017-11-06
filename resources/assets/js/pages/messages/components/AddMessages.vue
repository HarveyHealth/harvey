<template>
    <aside>
        <button class="button--close flyout-close" @click="close()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="heading-3-expand">New Message</h2>
        <div class="no-message-banner" v-if="!loading && userList.length <= 1">
            You are not currently assigned to any doctors. Please <a href="/dashboard#/appointments">book a consultation</a> before sending any messages.<br/><br/>For general questions, you can email <a href="mailto:support@goharvey.com">support@goharvey.com</a>, give us a call at <a href="tel:8006909989">800-690-9989</a>, or talk with a representative by clicking the chat button at the bottom corner of the page.
        </div>
        <div v-else>
            <div class="input__container">
                <label class="input__label" for="patient_name">Recipient</label>
                <span class="custom-select" v-if="!loading && userList.length > 1">
                    <select @change="updateUser($event)">
                        <option  v-for="user in userList" :data-id="user.user_id">{{ user.name }}</option>
                    </select>
                </span>
                <div v-else class="font-italic font-sm copy-muted">
                    Loading users...
                </div>
            </div>
            <div class="input__container">
                <label class="input__label" for="patient_name">Subject</label>
                <input v-model="subject" class="input--text" type="text">
            </div>
            <div class="input__container">
                <label class="input__label" for="patient_name">Message</label>
                <textarea v-model="message" class="input--textarea"></textarea>
            </div>
            <div class="button-wrapper">
                <button class="button"
                @click="createMessage()"
                :disabled="!subject || !selected || userList.length <= 1">Send</button>
            </div>
        </div>
    </aside>
</template>

<script>
import axios from 'axios';
import Flyout from '../../../commons/Flyout.vue';
export default {
    name: 'Preview',
    components: {
        Flyout
    },
    data() {
        return {
            close: this.$parent.close,
            selected: '',
            subject: '',
            message: '',
            loading: this.$root.$data.global.loadingConfirmedUsers
        };
    },
    mounted() {
        this.$root.getConfirmedUsers();
    },
    methods: {
        updateUser(e) {
            this.selected = e.target.children[e.target.selectedIndex].dataset.id;
        },
        makeThreadId(userOne, userTwo) {
            return userOne > userTwo ? `${userTwo}-${userOne}` : `${userOne}-${userTwo}`;
        },
        createMessage() {
            axios.post(`${this.$root.$data.apiUrl}/messages`, {
                message: this.message,
                recipient_user_id: Number(this.selected),
                subject: this.subject
            })
            .then(response => {
                this.$root.$data.global.detailMessages[`${this.makeThreadId(response.data.data.attributes.sender_user_id, response.data.data.attributes.recipient_user_id)}-${response.data.data.attributes.subject}`] = [response.data.data];
                this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length - 1]).sort((a, b) => new Date(b.attributes.created_at.date) - new Date(a.attributes.created_at.date));
                this.$parent.messageList = this.$root.$data.global.messages;
                this.$parent.notificationActive = true;
                setTimeout(() => this.$parent.notificationActive = false, 3000);
            })
            .catch(error => {
                console.log(`ERROR`, error);
            });
            this.$parent.close();
        },
        setLoading(bool) {
            this.loading = bool;
        }
    },
    computed: {
        userList() {
            const store = this.$root.$data.global;
            if (this.$root.$data.permissions === 'patient') {
                let data = [''].concat(store.confirmedDoctors);
                if (data.length > 1) {
                    this.$root.$data.global.loadingConfirmedUsers = false;
                    this.setLoading(false);
                }
                return data;
            } else if (this.$root.$data.permissions === 'practitioner') {
                let data = [''].concat(store.confirmedPatients);
                if (data.length > 1) {
                    this.$root.$data.global.loadingConfirmedUsers = false;
                    this.setLoading(false);
                }
                return [''].concat(data);
            } else if (this.$root.$data.permissions === 'admin') {
                let data = [''].concat(store.practitioners).concat(store.patients);
                if (data.length > 1) {
                    this.$root.$data.global.loadingConfirmedUsers = false;
                    this.setLoading(false);
                }
                return data
            }
        },
        toUserType() {
            const store = this.$root.$data.global;
            if (store.user.attributes.user_type === 'patient') {
                return "doctor";
            } else if (store.user.attributes.user_type === 'practitioner') {
                return "patient";
            } else if (store.user.attributes.user_type === 'admin') {
                return "all";
            }
        }
    },
    watch: {
        userList(val) {
            if (!val) {
                const store = this.$root.$data.global;
                if (this.$root.$data.permissions === 'patient') {
                    let data = [''].concat(store.confirmedDoctors);
                    if (data.length > 1) {
                        this.$root.$data.global.loadingConfirmedUsers = false;
                        this.setLoading(false);
                    }
                    return data;
                } else if (this.$root.$data.permissions === 'practitioner') {
                    let data = [''].concat(store.confirmedPatients);
                    if (data.length > 1) {
                        this.$root.$data.global.loadingConfirmedUsers = false;
                        this.setLoading(false);
                    }
                    return [''].concat(data);
                } else if (this.$root.$data.permissions === 'admin') {
                    let data = [''].concat(store.practitioners).concat(store.patients);
                    if (data.length > 1) {
                        this.$root.$data.global.loadingConfirmedUsers = false;
                        this.setLoading(false);
                    }
                    return data
                }
            }
        },
        toUserType(val) {
            if (!val) {
                const store = this.$root.$data.global;
                if (store.user.attributes.user_type === 'patient') {
                    return "doctor";
                } else if (store.user.attributes.user_type === 'practitioner') {
                    return "patient";
                } else if (store.user.attributes.user_type === 'admin') {
                    return "all";
                }
            }
        }
    }
};
</script>
