<template>
    <div class="typeform-container relative">
        <a href="/dashboard" class="close-link" v-if="shouldShowIntakeForm">
            <i class="fa fa-close" />
        </a>
        <div
            v-show="shouldShowIntakeForm"
            class="typeform-widget"
            :data-url="typeformUrl"
            data-transparency=20
            data-hide-headers=false
            data-hide-footer=false
            style="width: 100%; height: 100vh;"
        />
        <SlideIn v-if="isComplete" class="complete-container">
            <div class="complete-wrap tc mha mw6 pa4">
                <LogoIcon justIcon :width="'50px'" :height="'60px'" />
                <Spacer isBottom :size="4" />
                <Paragraph>Your form was submitted successfully. You are finished with client intake and are ready to talk to your doctor.</Paragraph>
                <Spacer isBottom :size="4" />
                <InputButton
                    :href="'/dashboard'"
                    :mode="'secondary'"
                    :text="'Dashboard'"
                />
            </div>
        </SlideIn>
    </div>
</template>

<script>
import { LogoIcon } from 'icons';
import { InputButton } from 'inputs';
import { SlideIn, Spacer } from 'layout';
import { Paragraph } from 'typography';

export default {
    name: 'Intake',
    components: {
        LogoIcon,
        InputButton,
        Paragraph,
        SlideIn,
        Spacer
    },
    data() {
        return {
            isComplete: false,
            typeformUrl: `https://goharvey.typeform.com/to/XGnCna?harvey_id=${Laravel.user.id}&intake_validation_token=${Laravel.user.intake_validation_token}`
        };
    },
    computed: {
        shouldShowIntakeForm() {
            return  this.State.users.intake.wasRequested &&
                    !this.State.users.intake.isLoading &&
                    !this.State.users.intake.data.self;
        }
    },
    mounted() {
        // If a user has a zipValidation object in local storage then they are not
        // done with the signup funnel and should not hit the intake form
        const zipValidation = App.Logic.getstarted.getZipValidation();

        if (zipValidation) {
            window.location.href = '/get-started#/welcome';
        } else {
            App.Http.users.getPatientIntake(App.Config.user.info.id, () => {
                // add Typeform script if intake data does not exist
                if (!this.State.users.intake.data.self) {
                    /* eslint-disable */
                    var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm", b="https://embed.typeform.com/";
                    if(!gi.call(d,id)) { js=ce.call(d,"script");
                    js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q); }
                    /* eslint-enable */
                } else {
                    this.isComplete = true;
                }
            });
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .close-link {
        color: $color-copy;
        display: inline-block;
        font-size: 1.2rem;
        padding: 0.8rem 1.2rem;
        position: fixed;
        z-index: 1000;
        right: 0;
    }

    .complete-container {
        height: 100vh;
    }

    .complete-wrap {
        @include vertical-center-absolute;
    }
</style>
