<template>
    <SlideIn v-if="!$root.$data.signup.completedSignup" class="Container ph2 ph3-m pv4">
        <Card class="m0auto mw6 tc">
            <CardContent class="pv4 ph4-m">
                <Icon :fill="'gray-4'" :icon="'rocket'" :height="'80px'" :weight="'80px'" />
                <Spacer isBottom :size="3" />
                <Heading1 doesExpand>Welcome to Harvey</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">You will need to answer a few basic questions before you can schedule a consultation with a Naturopathic Doctor.</Paragraph>
                <Spacer isBottom :size="3" />
                <Paragraph :weight="'thin'">Please have your phone ready to validate your mobile number. Once confirmed, we will ask you to fill out a more detailed client intake form for your doctor.</Paragraph>
                <Spacer isBottom :size="4" />
                <InputButton
                    :onClick="() => $router.push('practitioner')"
                    :text="'Let\'s Go!'"
                />
            </CardContent>
        </Card>
    </SlideIn>
</template>

<script>
import { Icon } from 'icons';
import { InputButton } from 'inputs';
import { Card, CardContent, SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

export default {
    name: 'welcome',
    components: {
        Card,
        CardContent,
        Heading1,
        Icon,
        InputButton,
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
        if (!this.State('practitioners.wasRequested')) {
          App.Http.practitioners.get(App.Http.practitioners.getResponse);
        }
        App.Logic.getstarted.redirectDashboard();

        const steps = this.State('getstarted.signup.steps');
        // if (this.Config.user.info.phone) {
        //     const noPhoneStep = this.State('getstarted.signup.steps').filter(s => s !== 'phone');
        //     App.setState('getstarted.signup.steps', noPhoneStep);
        // }
        // if (this.Config.user.info.has_a_card) {
        //     const noPaymentStep = this.State('getstarted.signup.steps').filter(s => s !== 'payment');
        //     App.setState('getstarted.signup.steps', noPaymentStep);
        // }

        const zipValidation = JSON.parse(App.Util.data.fromStorage('zip_validation'));

        this.trackAccountCreation(zipValidation);

        analytics.page('Welcome');
        analytics.identify();
    }
};
// 937 395 0633
</script>

<style lang="scss" scoped>
    @import '~sass';

    .Container {
        @include query(lg) {
            margin-top: 8%;
        }
    }
</style>
