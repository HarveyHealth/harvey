<template>
    <SlideIn v-if="!$root.$data.signup.completedSignup" class="Container ph3 pv4">
        <Card class="margin-0a mw6 tc">
            <CardContent>
                <div class="signup-main-icon">
                    <svg class="interstitial-icon icon-rocket"><use xlink:href="#rocket" /></svg>
                </div>
                <Heading1>Welcome to Harvey</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph>You will need to answer a few basic questions before you can schedule a consultation with a Naturopathic Doctor.</Paragraph>
                <Spacer isBottom :size="3" />
                <Paragraph>Please have your phone ready to validate your mobile number. Once confirmed, we will ask you to fill out a more detailed client intake form for your doctor.</Paragraph>
                <Spacer isBottom :size="4" />
                <button class="button button--blue" @click="$router.push('practitioner')">Let's Go!</button>
            </CardContent>
        </Card>
    </SlideIn>
</template>

<script>
import { Card, CardContent, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

export default {
    name: 'welcome',
    components: {
        Card,
        CardContent,
        Heading1,
        Paragraph,
        SlideIn,
        Spacer
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

        const zipValidation = JSON.parse(App.Util.data.fromStorage('zip_validation'));

        this.trackAccountCreation(zipValidation);

        analytics.page('Welcome');
        analytics.identify();
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .Container {
        @include query(lg) {
            margin-top: 8%;
        }
    }
</style>
