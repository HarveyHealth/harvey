<template>
    <section class="section">
        <div class="container">
            <header class="content has-text-centered">
                <h2 class="title is-3">Payment Page</h2>
            </header>
            <div class="card">
                <div class="card-content">
                    <form
                        role="form"
                        @submit.prevent="onSubmit"
                        @keydown="form.errors.clear($event.target.name)"
                    >
  <span class="payment-errors"></span>

  <div class="form-row">
    <label>
      <span>Card Number</span>
      <input type="text" size="20" v-model="form.number">
    </label>
  </div>

  <div class="form-row">
    <label>
      <span>Expiration (MM/YY)</span>
      <input type="text" size="2" v-model="form.exp_month">
    </label>
    <span> / </span>
    <input type="text" size="2" v-model="form.exp_year">
  </div>

  <div class="form-row">
    <label>
      <span>CVC</span>
      <input type="text" size="4" v-model="form.cvc">
    </label>
  </div>

  <div class="form-row">
    <label>
      <span>Billing ZIP Code</span>
      <input type="text" size="6" v-model="form.address_zip">
    </label>
  </div>

  <button
    type="submit"
    class="button is-primary"
    :disabled="form.errors.any()"
    >Save Card</button>
</form>
                </div>
                <footer class="card-footer">
                </footer>
            </div>
        </div>
    </section>
</template>

<script>
    import Form from '../helpers.js';

    export default {
        name: 'payment',
        props: ['user'],
        data() {
            return {
                form: new Form({
                    number: '',
                    exp_month: '',
                    exp_year: '',
                    cvc: '',
                    address_zip: ''
                })
            }
        },
        methods: {
            onSubmit() {
                // Request a token from Stripe:
                Stripe.card.createToken(this.form.data(), this.stripeResponseHandler);
            },
            stripeResponseHandler(status, response) {
                console.log(status)
                console.log(response)
            }
        },
        mounted() {
            mixpanel.track("View Payments Page");
        }
    }
</script>