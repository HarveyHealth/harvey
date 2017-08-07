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
                <div :style="{height: details ? '460px' : '125px'}">

                    <div v-if="!details" class="inline-centered">
                        <button @click="addCard" class="button" style="margin-top: 35px;">Add Card</button>
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
                            <input placeholder="Month" style="width: 48%; float: left;" v-model="month" class="input--text" type="text">
                            <input placeholder="Year" style="width: 48%; float: right;" v-model="year" class="input--text" type="text">
                        </div>
                        <div class="input__container length" style="padding-top: 25px;">
                            <label style="width: 53%; float: left;" class="input__label" for="patient_name">security code</label>
                            <label style="width: 47%; float: left;" class="input__label" for="patient_name">zip code</label>
                            <input placeholder="CVV" style="width: 48%; float: left;" v-model="cardCvc" class="input--text" type="text">
                            <input placeholder="Enter zip" style="width: 48%; float: right;" v-model="postalCode" class="input--text" type="text">
                        </div>
                        <div class="inline-centered">
                            <button @click="updateCard" class="button" style="margin-top: 35px;">Create Card</button>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    name: 'settings',
    components: {
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
            postalCode: ''
        }
    },
    methods: {
        addCard() {
            this.details = true
        },
        updateCard() {
            this.details = false
            if (this.firstName && this.lastName && this.year && this.month && this.cardNumber && this.cardCvc && this.postalCode) {
                this.submitNewCard()
            }
        },
        submitNewCard() {
            Stripe.setPublishableKey(window.Laravel.services.stripe.key)
            let card = Stripe.card.createToken({
                number: this.cardNumber,
                exp_month: this.month,
                exp_year: this.year,
                cvc: this.cardCvc,
                address_zip: this.postalCode,
                name: `${this.firstName} ${this.lastName}`
            }, (status, response) => {
                axios.post(`${this.$root.$data.apiUrl}/users/${this.$root.$data.global.user.id}/cards`, {id: response.id})
                .then(resp => {
                    this.$root.$data.global.creditCardTokens[resp.last4] = resp
                })
            })
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
