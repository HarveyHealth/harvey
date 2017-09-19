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
                    <h2 class="heading-2">Payment Options</h2>
                </div>
                <div class="card-content-wrap">
                    <div v-if="$root.$data.global.loadingCreditCards" class="card-contact-info">
                        <div class="loading">
                            <p class="copy-muted font-md font-italic">Your credit cards are loading...</p>
                        </div>
                    </div>
                    <div v-if="!details" v-for="card in $root.$data.global.creditCards">
                        <div class="card-object">
                            <p class="copy-main font-md font-italic">
                                <i class="fa fa-credit-card"></i>
                                {{ card.brand }} **** **** **** {{ card.last4 }}
                            </p>
                        </div>
                        <div class="button-wrapper">
                            <button @click="openModal(card)" class="button">Delete Card</button>
                        </div>
                    </div>

                    <div v-if="!details && !$root.$data.global.creditCards.length && !$root.$data.global.loadingCreditCards" class="card-contact-info">
                        <div class="loading">
                            <p class="copy-muted font-md font-italic">You do not have any saved cards.</p>
                        </div>
                    </div>

                    <div v-if="!details && !$root.$data.global.creditCards.length && !$root.$data.global.loadingCreditCards" class="button-wrapper">
                        <button v-if="!edit" @click="addCard" class="button">Add Card</button>
                    </div>

                    <div v-if="details">
                        <form id="payment-form">
                            <div class="form-row">
                                <div id="card-element"></div>
                                <div id="card-errors" role="alert"></div>
                            </div>
                            <div class="button-wrapper">
                                <button class="button button--cancel" v-if="details" @click="closeDetails">Cancel</button>
                                <button type="submit" v-if="!edit" @click="submitAddCard" class="button">Save Card</button>
                            </div>
                        </form>
                    </div>

                    <Modal :active="deleteModalActive" :onClose="closeModal">
                        <div class="card-content-wrap">
                            <div class="inline-centered">
                                <h1 class="title header-xlarge"><span class="text">Delete Credit Card</span></h1>
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
import axios from 'axios'
import Modal from '../../commons/Modal.vue'
import NotificationPopup from '../../commons/NotificationPopup.vue'
export default {
    name: 'settings',
    components: {
        Modal,
        NotificationPopup
    },
    data() {
        return {
            details: false,
            firstName: '',
            lastName: '',
            month: '',
            year: '',
            cardNumber: '',
            cardExpiry: '',
            cardCvc: '',
            postalCode: '',
            edit: false,
            invalidCC: false,
            invalidModalActive: false,
            deleteModalActive: false,
            currentCard: null,
            notificationSymbol: '&#10003;',
            notificationMessage: '',
            notificationActive: false,
            notificationDirection: 'top-right',
            formAction: null,
            monthList: ['','1','2','3','4','5','6','7','8','9','10','11','12']
        }
    },
    methods: {
        addCard() {
            this.details = true
            setTimeout(() => this.stripeForm(), 100);
        },
        closeDetails() {
            this.details = false
        },
        closeModal() {
            this.deleteModalActive = false
        },
        closeInvalidCC() {
            this.invalidCC = false;
            this.invalidModalActive = false;
        },
        openModal(card) {
            this.deleteModalActive = true
            this.currentCard = card
        },
        submitAddCard() {
            this.formAction.submit();
        },
        updateMonth(e) {
            this.month = e.target.value
        },
        deleteCard() {
            axios.delete(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards/${this.currentCard.id}`)
                .then(response => {
                    this.$root.$data.global.creditCards = [];
                    this.notificationMessage = "Successfully deleted!";
                    this.notificationActive = true;
                    setTimeout(() => this.notificationActive = false, 3000);
                })
            this.closeModal()
        },
        submitUpdateCard() {
            this.details = false
            this.edit = false
            if (this.firstName && this.lastName && this.year && this.month && this.cardNumber && this.cardCvc && this.postalCode) {
                this.updateCard()
            }
        },
        updateCard() {
            axios.patch(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`, {
                card_id: this.currentCard.id,
                address_city: this.currentCard.address_city,
                address_state: this.currentCard.address_state,
                address_zip: this.postalCode || this.currentCard.address_zip,
                exp_month: this.month || this.currentCard.exp_month,
                exp_year: this.year || this.currentCard.exp_year,
                name: this.firstName && this.lastName ? `${this.firstName} ${this.lastName}` : this.currentCard.name
            })
            .then(response => {
                axios.get(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`)
                    .then(respond => {
                        this.$root.$data.global.creditCards = respond.data.cards
                        this.notificationMessage = "Successfully updated!";
                        this.notificationActive = true;
                        setTimeout(() => this.notificationActive = false, 3000);
                    })
                    .catch(error => {
                        console.log(`GET ISSUE`, error)
                    })
            })
            .catch(error => {
                console.log(`PATCH ISSUE`, error)
            })
        },
        submitNewCard(token) {
            axios.post(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`, {id: token})
                .then(resp => {
                    this.$root.$data.global.loadingCreditCards = true;
                    this.notificationMessage = "Successfully added!";
                    this.notificationActive = true;
                    setTimeout(() => this.notificationActive = false, 3000);
                    axios.get(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`)
                        .then(respond => {
                            this.$root.$data.global.creditCards = respond.data.cards
                            this.$root.$data.global.loadingCreditCards = false;
                            this.details = false
                        })
                })
        },
        stripeForm() {
            let stripe = Stripe(window.Laravel.services.stripe.key);
            let elements = stripe.elements();
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
            let card = elements.create('card', {style: style});
            card.mount('#card-element');
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            var self = this;
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        self.submitNewCard(result.token.id);
                    }
                });
            });
            this.formAction = form;
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'settings';
    }
}
</script>

<style>
    .card-content-wrap .loading {
        margin: 0;
    }

    .length {
        width: 100% !important;
    }

/*    .button--close.flyout-close svg {
        top: 132px;
        margin-left: 4px;
    }*/
</style>
