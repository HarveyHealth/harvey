<template>
    <div class="bg-white pa4 bn" v-if="testId">
        <img :src="testInfo.image" class="max-width-xxs pa2" />
        <div class="mw9 center">
            <div class="cf ph2">
                <div class="fl w-75">
                    <p class="font-xl font-bold margin-bottom-0">{{ testInfo.lab_name }}</p>
                    <p class="font-lg gray"><em>Sample: {{ testInfo.sample }}</em></p>
                </div>
                <div class="fl w-25">
                    <p class="font-xxl font-right"><span class="green">${{ testInfo.sku.price }}</span></p>
                </div>
            </div>
        </div>
        <div class="font-lg ml2" v-html="testInfo.description"></div>
        <a :href="testInfo.sku.slug" class="button is-outlined ml2 mt3 font-lg dim">
            <i class="fa fa-flask mr1"></i> Learn More
        </a>
    </div>
</template>

<script>
import { Paragraph } from 'typography';

export default {
    props: {
        testId: {
            type: [Number, Boolean],
            required: true
        }
    },
    components: {
        Paragraph
    },
    computed: {
        testInfo() {
            if (this.State('conditions.condition') && this.State('conditions.labTests')) {
                const condition = this.State('conditions.condition.slug');
                const tests = this.State('conditions.labTests');
                const testInfo = tests.filter(test => test.id === this.testId)[0];
                return testInfo;
            }
        }
    }
}
</script>

<style lang="scss" scoped>
</style>
