<template>
    <form
        role="form"
        @submit.prevent="onSubmit"
        @keydown="form.errors.clear($event.target.name)"
        class="section"
        id="payment"
    >
        <div class="has-max-width">
            <label class="label">Card Number</label>
            <p class="control has-icon">
                <input
                    v-model="form.number"
                    name="number"
                    :class="['input', form.errors.has('number') ? 'is-danger' : '']"
                    type="text"
                    size="20"
                    placeholder="Card Number"
                    required
                    :autofocus="!form.errors.length|| form.errors.has('number')"
                >
                <span class="icon is-small"><i class="fa fa-credit-card"></i></span>
                <template v-if="form.errors.has('number')">
                    <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                    <span class="help is-danger" v-text="form.errors.get('number')"></span>
                </template>
            </p>

            <div class="control is-horizontal">
                <div class="is-expanded">
                    <label class="label">Expiration (MM/YY)</label>
                    <div class="control is-grouped">
                        <p :class="['control', 'is-expanded', {'has-icon has-icon-right': form.errors.has('exp_month')}]">
                            <input
                                v-model="form.exp_month"
                                name="exp_month"
                                :class="['input', form.errors.has('exp_month') ? 'is-danger' : '']"
                                type="text"
                                size="2"
                                placeholder="MM"
                                required
                                :autofocus="form.errors.has('exp_month')"
                            >
                            <template v-if="form.errors.has('exp_month')">
                                <span class="icon is-small is-danger"><i class="fa fa-warning"></i></span>
                            </template>
                        </p>
                        <span class="delimiter"> / </span>
                        <p :class="['control', 'is-expanded', {'has-icon has-icon-right': form.errors.has('exp_year')}]">
                            <input
                                v-model="form.exp_year"
                                name="exp_year"
                                :class="['input', form.errors.has('exp_year') ? 'is-danger' : '']"
                                type="text"
                                size="2"
                                placeholder="YY"
                                required
                                :autofocus="form.errors.has('exp_year')"
                            >
                            <template v-if="form.errors.has('exp_year')">
                                <span class="icon is-small is-danger"><i class="fa fa-warning"></i></span>
                            </template>
                        </p>
                    </div>
                    <template v-if="form.errors.has('exp_month')">
                        <span class="help is-danger" v-text="form.errors.get('exp_month')"></span>
                    </template>
                    <template v-if="form.errors.has('exp_year')">
                        <span class="help is-danger" v-text="form.errors.get('exp_year')"></span>
                    </template>
                </div>
                <div class="is-expanded">
                    <label class="label">CVC</label>
                    <p class="control has-icon">
                        <input
                            v-model="form.cvc"
                            name="cvc"
                            :class="['input', form.errors.has('cvc') ? 'is-danger' : '']"
                            type="text"
                            size="4"
                            placeholder="CVC"
                            required
                            :autofocus="form.errors.has('cvc')"
                        >
                        <span class="icon is-small"><i class="fa fa-lock"></i></span>
                        <template v-if="form.errors.has('cvc')">
                            <span class="icon is-small align-right is-danger"><i class="fa fa-warning"></i></span>
                            <span class="help is-danger" v-text="form.errors.get('cvc')"></span>
                        </template>
                    </p>
                </div>
            </div>

            <p>Your card will not be charged right now.</p>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'payment',
        props: {
            form: Object
        },
        // data() {
        //     return {
        //         form: new Form({
        //             number: '',
        //             exp_month: '',
        //             exp_year: '',
        //             cvc: '',
        //             address_zip: ''
        //         })
        //     }
        // },
        methods: {
            onSubmit() {
                // Request a token from Stripe:
                Stripe.card.createToken(this.form.data(), this.stripeResponseHandler);
            },
            stripeResponseHandler(status, response) {
                console.log(status);
                console.log(response);
            }
        },
        mounted() {
          // this.$eventHub.$emit('mixpanel', "View Payments Page");
        }
    };
</script>
