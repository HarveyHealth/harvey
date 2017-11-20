<template>
    <div :class="containerClasses" v-if="!$root.$data.signup.completedSignup">
        <div class="vertical-center">
            <div class="signup-container small naked">
                <div class="signup-main-icon">
                    <svg class="interstitial-icon icon-rocket"><use xlink:href="#rocket" /></svg>
                </div>
                <h2 class="heading-1 font-normal">Welcome to Harvey</h2>
                <p>You will need to answer a few basic questions before you can book a consultation with a Naturopathic Doctor.</p>
                <p>This process will take 3-5 minutes. You will need to have your mobile phone ready to validate your phone number. After booking a consultation, we will ask you to fill out a more detailed client intake form for your doctor.</p>
                <button class="button button--blue" @click="$router.push('practitioner')">Let's Go!</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'welcome',
    data() {
        return {
            containerClasses: {
                'anim-fade-slideup': true,
                'anim-fade-slideup-in': false,
                'container': true,
                'pad-md': true,
                'flex-wrapper': true,
                'height-100': true,
                'justify-center': true
            }
        };
    },
    methods: {
        trackAccountCreation(validation) {
            if (!validation.account_created) {
                analytics.track('Account Created');
                analytics.identify(App.Config.user.info.id, {
                    firstName: App.Config.user.info.first_name,
                    lastName: App.Config.user.info.last_name,
                    email: App.Config.user.info.email,
                    city: App.Config.user.info.city,
                    state: App.Config.user.info.state,
                    zip: App.Config.user.info.zip
                }, {
                    integrations: {
                        Intercom : {
                            user_hash: App.Config.user.info.intercom_hash
                        }
                    }
                });
                if (validation.facebook_connect) {
                    analytics.track('Facebook Connect Signup');
                }
                App.Util.data.updateStorage('zip_validation', {
                    account_created: true,
                    facebook_connect: false
                });
            }
        }
    },
    mounted () {
        this.$root.toDashboard();
        this.$root.getPractitioners();

        if (Laravel.user.phone) this.$root.$data.signup.phoneConfirmed = true;
        if (Laravel.user.has_a_card) this.$root.$data.signup.billingConfirmed = true;

        this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);

        const zipValidation = JSON.parse(App.Util.data.fromStorage('zip_validation'));

        this.trackAccountCreation(zipValidation);

        analytics.page('Welcome');
        analytics.identify();
    },
    beforeDestroy() {
        this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
    }
};
</script>
