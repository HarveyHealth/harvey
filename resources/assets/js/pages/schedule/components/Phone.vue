<template>
  <div :class="animClasses">
    <div class="container small">
      <ul class="signup_progress-indicator">
        <li class="signup_progress-step" v-on:click="lastStep"></li>
        <li class="signup_progress-step current"></li>
        <li class="signup_progress-step"></li>
      </ul>
      <div class="guide-block">
        <h1 class="header-xlarge">{{ title }}</h1>
        <p class="large">{{ subtitle }}</p>
      </div>

      <div class="error-container" v-show="responseErrors.length > 0">
        <p v-for="error in responseErrors" v-text="error.detail" class="error-text"></p>
      </div>

      <div class="signup-form-container">

        <div class="input-wrap">
          <input class="form-input form-input_text" name="first_name" type="text" placeholder="First Name" v-model="firstname" v-validate="'required'" />
          <span v-show="errors.has('first_name')" class="error-text">First name is required</span>
        </div>

        <div class="input-wrap">
          <input class="form-input form-input_text" name="last_name" type="text" placeholder="Last Name" v-model="lastname" v-validate="'required'" />
          <span v-show="errors.has('last_name')" class="error-text">Last name is required</span>
        </div>

        <div class="input-wrap">
          <input class="form-input form-input_text"
            name="phone_number"
            type="phone"
            placeholder="Phone Number"
            v-phonemask="phone"
            v-validate="{ required: true, regex: /\(\d{3}\) \d{3}-\d{4}/ }"
            data-vv-validate-on="blur"
          />

          <span v-show="errors.has('phone_number')" class="error-text">Please supply a valid U.S. phone number.</span>
        </div>
        <div class="text-centered">
          <a class="button" @click.prevent="nextStep">Continue</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        title: 'We need a few more details…',
        subtitle: 'Please enter your full name and the phone number you would like your doctor to call at the time of your phone consultation.',
        firstname: this.$parent.firstname || '',
        lastname: this.$parent.lastname || '',
        phone: this.$parent.phone || '',
        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false,
        },
        responseErrors: [],
      }
    },
    methods: {
      nextStep() {
        this.$validator.validateAll().then(() => {
          this.$root.global.user.attributes.first_name = this.firstname;
          this.$root.global.user.attributes.last_name = this.lastname;
          this.$root.global.user.attributes.phone = this.phone;
          this.$parent.firstname = this.firstname
          this.$parent.lastname = this.lastname
          this.$parent.phone = this.phone
          this.$parent.next();
        }).catch(() => {});
      },
      lastStep() {
        this.$parent.firstname = this.firstname
        this.$parent.lastname = this.lastname
        this.$parent.phone = this.phone
        this.$parent.previous();
      }
    },
    name: 'Phone',
    mounted() {
        this.$ma.trackEvent({
                fb_event: 'PageView',
                type: 'product',
                category: 'clicks',
                properties: { laravel_object: Laravel.user }
            });
        this.$ma.trackEvent({
          action: 'Additional Info',
          fb_event: 'ViewContent',
          category: 'clicks',
          properties: { laravel_object: Laravel.user },
          value: 'PageView'
        });
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);
    },
    beforeDestroy() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
  }
</script>

<style>

</style>
