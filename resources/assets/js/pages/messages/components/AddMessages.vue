<template>
    <aside>
        <button class="button--close flyout-close" @click="close()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="heading-3-expand">New Message</h2>
        <div class="no-message-banner" v-if="!loading && userList && userList.length === 0">
            You are not currently assigned to any doctors. Please <router-link to="/appointments">book a consultation</router-link > before sending any new message. You may respond back to messages sent to you in the meantime.<br/><br/>For general questions, you can email <a href="mailto:support@goharvey.com">support@goharvey.com</a>, give us a call at <a href="tel:8006909989">800-690-9989</a>, or talk with a representative by clicking the chat button at the bottom corner of the page.
        </div>
        <div v-else>
            <div class="input__container">
                <label class="input__label" for="patient_name">Recipient</label>
                <autocomplete
                    anchor="search_name"
                    label=false
                    url=true
                    placeholder="Search name, email or birthday..."
                    :debounce="500"
                    :onShouldGetData="getData"
                    :on-select="handlePatientSelect"
                >
                </autocomplete>
                <div v-if="loading" class="font-italic font-sm copy-muted">
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
                :disabled="!subject || !selected || userList.length === 0">Send</button>
            </div>
        </div>
    </aside>
</template>

<script>
import axios from 'axios';
import Flyout from '../../../commons/Flyout.vue';
import Autocomplete from '../../../commons/Autocomplete.vue';

export default {
    name: 'Preview',
    components: {
        Flyout,
        Autocomplete
    },
    data() {
        return {
            close: this.$parent.close,
            selected: '',
            subject: '',
            message: ''
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
        handlePatientSelect(obj) {
            this.resetting = false;
            this.selected = obj.user_id;
        },
        getData(value){
            return new Promise((resolve) => {
                if (value != ""){
                    this.$root.requestConfirmedUsers(value, (users) => {
                        resolve(users);
                    });
                }
                else{
                    resolve([]);
                }
            });
        }
    },
    computed: {
        userList() {
            this.$root.getConfirmedUsers();
            const store = this.$root.$data.global;
            if (this.$root.$data.permissions === 'patient') {
                return store.confirmedDoctors;
            } else if (this.$root.$data.permissions === 'practitioner') {
                return store.confirmedPatients;
            } else if (this.$root.$data.permissions === 'admin') {
                return (store.confirmedPatients).concat(store.confirmedDoctors);
            }
        },
        loading() {
            return this.$root.$data.global.loadingConfirmedUsers;
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
        loading(val) {
            if (val) {
                return this.$root.$data.global.loadingConfirmedUsers;
            }
        },
        userList(val) {
            if (!val) {
                this.$root.getConfirmedUsers();
                const store = this.$root.$data.global;
                const permission = this.$root.$data.permissions;
                if (permission === 'patient') {
                    return store.confirmedDoctors;
                } else if (permission === 'practitioner') {
                    return store.confirmedPatients;
                } else if (permission === 'admin') {
                    return (store.confirmedPatients).concat(store.confirmedDoctors);
                }
            }
        },
        toUserType(val) {
            if (!val) {
                const permission = this.$root.$data.permissions;
                if (permission === 'patient') {
                    return "doctor";
                } else if (permission === 'practitioner') {
                    return "patient";
                } else if (permission === 'admin') {
                    return "all";
                }
            }
        }
    }
};
</script>
