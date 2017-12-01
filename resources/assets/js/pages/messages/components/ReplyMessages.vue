<template>
    <aside>
        <button class="button--close flyout-close" @click="reply()">
            <svg><use xlink:href="#close" /></svg>
        </button>
        <h2 class="heading-3-expand">Reply</h2>
        <div class="input__container">
            <label class="input__label" for="patient_name">{{ toUserType }}</label>
            <span class="custom-select">
                <select name="doctor_name">
                    <option>{{ name }}</option>
                </select>
            </span>
        </div>
        <div class="input__container">
            <label class="input__label" for="patient_name">Message</label>
            <textarea v-model="message" class="input--textarea"></textarea>
        </div>
        <div>
            <div class="inline-centered">
                <button
                class="button"
                @click="createMessage()"
                :disabled="!message">Send</button>
            </div>
        </div>
    </aside>
</template>

<script>
    import axios from 'axios';
    export default {
        props: {
            name: String,
            header: String,
            id: String,
            senderId: String
        },
        name: 'Reply',
        data() {
            return {
                reply: this.$parent.reply,
                message: ''
            };
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
                    recipient_user_id: this.$props.id,
                    subject: this.$props.header
                })
                .then(response => {
                    this.$root.$data.global.detailMessages[`${this.makeThreadId(response.data.data.attributes.sender_user_id, response.data.data.attributes.recipient_user_id)}-${response.data.data.attributes.subject}`].push(response.data.data);
                    this.$root.$data.global.detailMessages[`${this.makeThreadId(response.data.data.attributes.sender_user_id, response.data.data.attributes.recipient_user_id)}-${response.data.data.attributes.subject}`].sort((a, b) => new Date(a.attributes.created_at.date) - new Date(b.attributes.created_at.date));
                    this.$root.$data.global.messages = Object.values(this.$root.$data.global.detailMessages).map(e => e[e.length - 1]).sort((a, b) => b.id - a.id);
                    this.$parent.notificationActive = true;
                    setTimeout(() => this.$parent.notificationActive = false, 3000);
                });
                this.$parent.reply();
            }
        },
        computed: {
            toUserType() {
                if (this.$root.$data.permissions === 'patient') {
                    return "Doctor";
                } else if (this.$root.$data.permissions === 'practitioner') {
                    return "Client";
                } else if (this.$root.$data.permissions === 'admin') {
                    return "Recipient";
                }
            }
        }
    };
</script>
