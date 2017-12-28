<template>
    <div class="center tc mw6">
        <SvgIcon :id="'map'" :width="'120px'" :height="'120px'" />
        <Spacer isBottom :size="4" />
        <SlideIn v-if="!State('conditions.zipValidation')">
            <Heading1 class="pv2">What is your zip code?</Heading1>
            <Spacer isBottom :size="4" />
            <Paragraph :size="'large'">Harvey does not have licensed doctors in every state. Please enter your zip code to verify that we can work together.</Paragraph>
            <Spacer isBottom :size="4" />
            <form @submit.prevent>
                <CodeInput
                    isAutoFocused
                    :isDisabled="State('isLoading.zip') || (State('wasRequested.zip') && !State('isLoading.zip'))"
                    :mask="'#####'"
                    :onInput="zip => setState('conditions.zip', zip)"
                    :theme="'inverse-dark'"
                />
                <Spacer isBottom :size="4" />
                <InputButton
                    :isDisabled="State('conditions.zip').length < 5"
                    :isDone="State('wasRequested.zip') && !State('isLoading.zip')"
                    :isProcessing="State('isLoading.zip')"
                    :onClick="() => Http.zip.get(State('conditions.zip'), Http.zip.getResponse)"
                    :type="'whiteFilled'"
                    :text="'Verify'"
                    :ref="'submit'"
                    :width="'140px'"
                    :class="'mb3'"
                />
            </form>
        </SlideIn>
        <div v-if="State('conditions.zipValidation.is_serviceable') === false">
            <SlideIn>
                <Heading1 doesExpand class="pv2">Unfortunately, we cannot service patients in your state yet.</Heading1>
                <Spacer isBottom :size="4" />
                <Paragraph>
                    We will let you know as soon as we launch in your state. In the meantime, you can follow on us social media for free health tips from our team of Naturopathic Doctors.
                </Paragraph>
                <Spacer isBottom :size="4" />
                <a href="#" @click="reEnterZip" class="dark-gray">
                    <i class="fa fa-undo"></i><Spacer isRight :size="3" />Try Again
                </a>
                <Spacer isBottom :size="4" />
            </SlideIn>
        </div>
    </div>
</template>

<script>
import { CodeInput, InputButton, MultiInput } from 'inputs';
import { Heading1, Paragraph } from 'typography';
import { SlideIn, Spacer } from 'layout';
import { SvgIcon } from 'icons';

export default {
    components: {
        CodeInput,
        InputButton,
        Heading1,
        Paragraph,
        MultiInput,
        SlideIn,
        Spacer,
        SvgIcon
    },
    methods: {
        reEnterZip() {
            App.setState({
                'conditions.zipValidation': null,
                'wasRequested.zip': false
            });
        }
    }
};
</script>

<style lang="scss" scoped>
    .social-icon {
        padding: 4%;
    }
</style>
