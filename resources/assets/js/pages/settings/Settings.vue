<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="title header-xlarge">
                        <span class="text">Settings</span>
                    </h1>
                </div>
            </div>
            <div class="card" style="width: 450px;">
                <div class="card-heading-container">
                    <h1 class="card-header">Payment Details</h1>
                </div>
                <div>
                    <div v-if="$root.$data.global.loadingCreditCards">
                        <p style="text-align: center; font-size: 18px; padding: 10px;"><i>Your credit cards are loading.</i></p>
                    </div>
                    <div v-if="!details" v-for="card in $root.$data.global.creditCards">
                        <div style="height: 40px; margin: 20px auto;">
                            <div style="float: left; margin: 0 160px 0 40px;">{{`•••• •••• •••• ${card.last4}`}}</div>
                            <a @click="pressEdit(card)" style="margin: 0 10px; float: left;">Edit</a>
                            <a @click="openModal(card)" style="margin: 0 10px; float: left;">Delete</a>
                        </div>
                    </div>
                    <div v-if="!details && !cards.length && !$root.$data.global.loadingCreditCards" class="inline-centered">
                        <button v-if="!edit" @click="addCard" class="button" style="margin: 35px 0;">Add Card</button>
                    </div>

                    <div v-if="details" style="padding: 20px;">
                        <div class="input__container length" style="margin-bottom: 1.5em;">
                            <label class="input__label" for="patient_name">card number</label>
                            <input placeholder="Enter card number" v-model="cardNumber" class="input--text" type="text">
                        </div>
                        <div class="input__container length">
                            <label class="input__label" for="patient_name">name on card</label>
                            <input placeholder="First name" style="width: 48%; float: left;" v-model="firstName" class="input--text" type="text">
                            <input placeholder="Last name" style="width: 48%; float: right;" v-model="lastName" class="input--text" type="text">
                        </div>
                        <div class="input__container length" style="padding-top: 25px;">
                            <label class="input__label" for="patient_name">expiry date</label>
                            <span class="custom-select" style="float: left; width: 48%;">
                                <select @change="updateMonth($event)">
                                    <option v-for="month in monthList">{{ month }}</option>
                                </select>
                            </span>
                            <input placeholder="Year" style="width: 48%; float: right;" v-model="year" class="input--text" type="text">
                        </div>
                        <div class="input__container length" style="padding-top: 25px;">
                            <label style="width: 53%; float: left;" class="input__label" for="patient_name">security code</label>
                            <label style="width: 47%; float: left;" class="input__label" for="patient_name">zip code</label>
                            <input placeholder="CVV" style="width: 48%; float: left;" v-model="cardCvc" class="input--text" type="text">
                            <input placeholder="Enter zip" style="width: 48%; float: right;" v-model="postalCode" class="input--text" type="text">
                        </div>
                        <div class="inline-centered">
                            <button v-if="!edit" @click="submitAddCard" class="button" style="margin-top: 35px;">Create Card</button>
                            <button v-if="edit" @click="submitUpdateCard" class="button" style="margin-top: 35px;">Update Card</button>
                        </div>
                    </div>

                    <Modal :active="deleteModalActive" :onClose="closeModal">
                        <div class="inline-centered">
                            <h1>Delete Credit Card</h1>
                            <p>Are you sure you want to delete this credit card?</p>
                            <div class="inline-centered">
                                <button @click="deleteCard" class="button">Yes, Confirm</button>
                            </div>
                        </div>
                    </Modal>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import Modal from '../../commons/Modal.vue'
export default {
    name: 'settings',
    components: {
        Modal
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
            deleteModalActive: false,
            currentCard: null,
            cards: this.$root.$data.global.creditCards,
            monthList: ['','1','2','3','4','5','6','7','8','9','10','11','12']
        }
    },
    methods: {
        addCard() {
            this.details = true
            this.edit = false
        },
        closeModal() {
            this.deleteModalActive = false
        },
        openModal(card) {
            this.deleteModalActive = true
            this.currentCard = card
        },
        submitAddCard() {
            this.details = false
            this.edit = false
            if (this.firstName && this.lastName && this.year && this.month && this.cardNumber && this.cardCvc && this.postalCode) {
                this.submitNewCard()
            }
        },
        updateMonth(e) {
            this.month = e.target.value
        },
        deleteCard() {
            axios.delete(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards/${this.currentCard.id}`)
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
                address_country: this.currentCard.address_country,
                address_line1: this.currentCard.address_line1,
                address_line2: this.currentCard.address_line2,
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
                    })
            })
        },
        submitNewCard() {
            let card = Stripe.card.createToken({
                number: this.cardNumber,
                exp_month: this.month,
                exp_year: this.year,
                cvc: this.cardCvc,
                address_zip: this.postalCode,
                name: `${this.firstName} ${this.lastName}`
            }, (status, response) => {
                axios.post(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`, {id: response.id})
                    .then(resp => {
                        axios.get(`${this.$root.$data.apiUrl}/users/${window.Laravel.user.id}/cards`)
                            .then(respond => {
                                this.$root.$data.global.creditCards = respond.data.cards
                            })
                    })
            })
        },
        pressEdit(card) {
            let names = card.name
            let nameArray = names.split(' ')
            this.firstName = nameArray[0]
            this.lastName = nameArray[nameArray.length - 1]
            this.month = card.exp_month
            this.year = card.exp_year
            this.postalCode = card.address_zip
            this.currentCard = card
            this.edit = true
            this.details = true
        }
    },
    mounted() {
        this.$root.$data.global.currentPage = 'settings';
    }
}
</script>

<style>
    .length {
        width: 100% !important;
    }
</style>
