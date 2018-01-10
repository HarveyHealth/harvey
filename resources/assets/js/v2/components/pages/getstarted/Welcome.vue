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

    beforeMount() {
        App.Logic.getstarted.refuseStepSkip.call(this, 'welcome');
    },

    mounted () {
        if (!this.State('practitioners.wasRequested')) {
          App.Http.practitioners.get(App.Http.practitioners.getResponse);
        }

        App.Logic.getstarted.trackAccountCreation(this.State('getstarted.signupMode'));

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
