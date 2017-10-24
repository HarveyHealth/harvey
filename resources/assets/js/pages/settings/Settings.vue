<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                        <span class="text">Settings</span>
                    </h1>
                </div>
            </div>
            <div class="card" style="width: 450px;">
                <div class="card-heading-container">
                    <h2 class="heading-2">
                        Payment Options
                        <span v-if="user_id">for {{ user.attributes.first_name }} {{ user.attributes.last_name }} (#{{ user_id }})</span>
                    </h2>
                </div>
                <div class="card-content-wrap">
                    <div v-if="$root.$data.global.loadingCreditCards" class="card-contact-info">
                        <div class="loading">
                            <p class="copy-muted font-md font-italic">Your credit cards are loading...</p>
                        </div>
                    </div>
                    <div v-if="!details && !$root.$data.global.loadingCreditCards" >
                        <div v-for="card in $root.$data.global.creditCards">
                            <div class="card-object">
                                <p class="copy-main font-md font-italic">
                                    <i class="fa fa-credit-card"></i>
                                    {{ card.brand == 'American Express' ? 'Amex' : card.brand }} **** **** **** {{ card.last4 }}
                            <div class="button-wrapper">
                            </div>
                        </div>
                    </div>

                    <div v-if="!details && !$root.$data.global.creditCards.length && !$root.$data.global.loadingCreditCards" class="card-contact-info">
                        <div class="loading">
                            <p class="copy-muted font-md font-italic">You do not have any saved cards.</p>
                        </div>
                    </div>

                    <div v-if="!details && !$root.$data.global.creditCards.length && !$root.$data.global.loadingCreditCards" class="button-wrapper">
                        <button v-if="!edit" @click="mountStripeForm" class="button">Add Card</button>
                    </div>

                    <div v-show="details">
                        <form @submit.prevent="generateCardTokenWithStripeForm" id="payment-form" name="billing-info">
                            <div class="form-row">
                                <div id="card-element"></div>
                                <div id="card-errors" role="alert"></div>
                            </div>
                            <div class="button-wrapper">
                                <button class="button button--cancel" @click="closeDetails">Cancel</button>
                                <button type="submit" :disabled="sent" class="button">Save Card</button>
                            </div>
                        </form>
                    </div>

                    <Modal :active="deleteModalActive" :onClose="closeModal">
                        <div class="card-content-wrap">
                            <div class="inline-centered">
                                <h1 class="header-xlarge"><span class="text">Delete Credit Card</span></h1>
                                <p>Are you sure you want to permanently delete this credit card from your Harvey account?</p>
                            </div>
                            <div class="button-wrapper">
                                <button @click="closeModal" class="button button--cancel">Cancel</button>
                                <button @click="deleteCard" class="button">Yes, Confirm</button>
                            </div>
                        </div>
                    </Modal>

                    <Modal :active="invalidModalActive" :onClose="closeInvalidCC">
                        <div class="inline-centered">
                            <h1>Invalid Credit Card</h1>
                            <p>The credit card you entered is invalid.</p>
                            <div class="inline-centered">
                                <button @click="closeInvalidCC" class="button">Try Again</button>
                            </div>
                        </div>
                    </Modal>

                    <NotificationPopup
                        :active="notificationActive"
                        :comes-from="notificationDirection"
                        :symbol="notificationSymbol"
                        :text="notificationMessage"
                    />

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Modal from '../../commons/Modal.vue';
import NotificationPopup from '../../commons/NotificationPopup.vue';
export default {
    name: 'settings',
    components: {
        Modal,
        NotificationPopup
    },
    data() {
        return {
            cardCvc: '',
            cardExpiry: '',
            cardNumber: '',
            currentCard: null,
            deleteModalActive: false,
            details: false,
            edit: false,
            firstName: '',
            formAction: null,
            invalidCC: false,
            invalidModalActive: false,
            lastName: '',
            month: '',
            monthList: ['','1','2','3','4','5','6','7','8','9','10','11','12'],
            notificationActive: false,
            notificationDirection: 'top-right',
            notificationMessage: '',
            notificationSymbol: '&#10003;',
            postalCode: '',
            sent: false,
            user: {
                attributes: {
                    first_name: '',
                    last_name: ''
                }
            },
            user_id: this.$route.params.id,
            year: '',
            stripe: this.$root.$data.stripe,
            card: null
        };
    },
    methods: {
        closeDetails() {
            this.details = false;
        },
        closeModal() {
            this.deleteModalActive = false;
        },
        closeInvalidCC() {
            this.invalidCC = false;
            this.invalidModalActive = false;
        },
        openModal(card) {
            this.deleteModalActive = true;
            this.currentCard = card;
        },
        updateMonth(e) {
            this.month = e.target.value;
        },
        deleteCard() {
            axios.delete(`${this.$root.$data.apiUrl}/users/${this.user_id || window.Laravel.user.id}/cards/${this.currentCard.id}`)
                .then(() => {
                    this.$root.$data.global.creditCards = [];
                    this.notificationMessage = "Your card has been deleted.";
                    this.notificationActive = true;
                    Laravel.user.has_a_card = false;
                    setTimeout(() => this.notificationActive = false, 3000);
                });
            this.closeModal();
        },
        submitUpdateCard() {
            this.details = false;
            this.edit = false;
            if (this.firstName && this.lastName && this.year && this.month && this.cardNumber && this.cardCvc && this.postalCode) {
                this.updateCard();
            }
        },
        updateCard() {
            let userId = this.user_id || window.Laravel.user.id;
            axios.patch(`${this.$root.$data.apiUrl}/users/${userId}/cards`, {
                card_id: this.currentCard.id,
                address_city: this.currentCard.address_city,
                address_state: this.currentCard.address_state,
                address_zip: this.postalCode || this.currentCard.address_zip,
                exp_month: this.month || this.currentCard.exp_month,
                exp_year: this.year || this.currentCard.exp_year,
                name: this.firstName && this.lastName ? `${this.firstName} ${this.lastName}` : this.currentCard.name
            })
            .then(() => {
                axios.get(`${this.$root.$data.apiUrl}/users/${this.user_id || window.Laravel.user.id}/cards`)
                    .then(respond => {
                        this.$root.$data.global.creditCards = respond.data.cards;
                        this.notificationMessage = "Successfully updated!";
                        this.notificationActive = true;
                        setTimeout(() => this.notificationActive = false, 3000);
                    })
                    .catch(error => {
                        console.log(`GET ISSUE`, error);
                    });
            })
            .catch(error => {
                console.log(`PATCH ISSUE`, error);
            });
        },
        submitNewCard(token) {
            axios.post(`${this.$root.$data.apiUrl}/users/${this.user_id || window.Laravel.user.id}/cards`, {id: token})
                .then(() => {
                    this.$root.$data.global.loadingCreditCards = true;
                    this.notificationMessage = "Successfully added!";
                    this.notificationActive = true;
                    Laravel.user.has_a_card = true;
                    setTimeout(() => this.notificationActive = false, 3000);
                    axios.get(`${this.$root.$data.apiUrl}/users/${userId}/cards`)
                        .then(respond => {
                            this.$root.$data.global.creditCards = respond.data.cards;
                            this.$root.$data.global.loadingCreditCards = false;
                            this.details = false;
                            this.sent = false;
                        });
                });

            this.stopListeningForStripeFormErrors();
        },
        getCards() {
            this.$root.$data.global.loadingCreditCards = true;
            let userId = this.user_id || window.Laravel.user.id;
            axios.get(`${this.$root.$data.apiUrl}/users/${userId}/cards`).then(response => {
                this.$root.$data.global.creditCards = response.data.cards;
                this.$root.$data.global.loadingCreditCards = false;
            });
        },
        getUser() {
            axios.get(`${this.$root.$data.apiUrl}/users/${this.user_id}`)
                .then(response => {
                  this.user = response.data.data;
                })
                .catch(() => {
                    this.$router.push('/profile');
                });
        },
        mountStripeForm() {
            this.details = true;
            this.stripe = this.$root.$data.stripe;
            let style = {
                base: {
                    color: '#32325d',
                    lineHeight: '24px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            this.card = this.stripe.elements().create('card', {style: style});
            this.card.mount('#card-element');

            this.listenForStripeFormErrors();
        },
        generateCardTokenWithStripeForm() {
            if(!this.stripe || !this.card) return;

            this.stripe.createToken(this.card).then((result) => {
                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error.message;
                } else {
                    this.sent = true;
                    this.submitNewCard(result.token.id);
                }
            });
        },
        handleStripeFormChange() {
            document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
        },
        listenForStripeFormErrors() {
            this.card.addEventListener('change', this.handleStripeFormChange);
        },
        stopListeningForStripeFormErrors() {
            this.card.removeEventListener('change', this.handleStripeFormChange);
        },
        setUserId(id) {
            this.user_id = id;
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'settings';
        this.getCards();
        if (this.user_id) {
            this.getUser();
        }
    },
    watch: {
        _user_id(id) {
            if (id && 'admin' === Laravel.user.user_type) {
                this.setUserId(id);
                this.getUser();
                this.getCards();
            } else {
                this.setUserId(null);
            }
        }
    },
    computed: {
        _user_id() {
          return this.$route.params.id;
        }
    }
};
</script>

<style>
    .card-content-wrap .loading {
        margin: 0;
    }
</style>
