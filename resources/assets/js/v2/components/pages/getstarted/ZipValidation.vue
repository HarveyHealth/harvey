<template>
    <div class="pa3 tc">
        <Icon :fill="'white'" :icon="'world'" :height="'120px'" :width="'120px'" />
        <Spacer isBottom :size="4" />
        <SlideIn v-if="!State('getstarted.zipValidation')">
            <Heading1 class="pv2" :color="'light'" doesExpand>What is your zip code?</Heading1>
            <Spacer isBottom :size="3" />
            <Paragraph :color="'light'">Harvey does not have licensed doctors in every state. Please enter your zip code to verify that we can work together.</Paragraph>
            <Spacer isBottom :size="3" />
            <form @submit.prevent>
                <CodeInput
                    isAutoFocused
                    :isDisabled="State('isLoading.zip') || (State('wasRequested.zip') && !State('isLoading.zip'))"
                    :mask="'#####'"
                    :onInput="zip => setState('getstarted.userPost.zip', zip)"
                    :theme="'inverse-light'"
                />
                <Spacer isBottom :size="4" />
                <InputButton
                    :isDisabled="State('getstarted.userPost.zip').length < 5"
                    :isDone="State('wasRequested.zip') && !State('isLoading.zip')"
                    :isProcessing="State('isLoading.zip')"
                    :onClick="() => Http.zip.get(State('getstarted.userPost.zip'), Http.zip.getResponse)"
                    :type="'whiteFilled'"
                    :text="'Verify'"
                    :ref="'submit'"
                    :width="'140px'"
                    :class="'mb3'"
                />
            </form>
        </SlideIn>

        <div v-if="State('getstarted.zipValidation.is_serviceable') === false">
            <SlideIn>
                <Heading1 doesExpand class="pv2" :color="'light'">Unfortunately, we cannot service patients in your state yet.</Heading1>
                <Spacer isBottom :size="3" />
                <Paragraph :color="'light'">
                    We will let you know as soon as we launch in your state. In the meantime, you can follow on us social media for free health tips from our team of Naturopathic Doctors.
                </Paragraph>
                <Spacer isBottom :size="3" />
                <a href="#" class="white" @click.prevent="Logic.getstarted.resetZip">
                    <i class="fa fa-undo"></i><Spacer isRight :size="3" />Try Again
                </a>
            </SlideIn>
        </div>
    </div>
</template>

<script>
import { Icon } from 'icons';
import { CodeInput, InputButton } from 'inputs';
import { SlideIn, Spacer } from 'layout';
import { Heading1, Paragraph } from 'typography';

export default {
    components: {
        CodeInput,
        Heading1,
        Icon,
        InputButton,
        Paragraph,
        SlideIn,
        Spacer
    }
};
</script>
