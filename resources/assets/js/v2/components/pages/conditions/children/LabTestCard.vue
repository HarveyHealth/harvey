<template>
    <div class="bg-white pa4 bn" v-if="test">
        <img :src="test.image" class="max-width-xxs pa2" />
        <div class="mw9 center">
            <div class="cf ph2">
                <div class="fl w-75">
                    <p class="font-xl font-bold margin-bottom-0">{{ test.sku.name }}</p>
                    <p class="font-lg gray"><em>Sample: {{ test.sample }}</em></p>
                </div>
                <div class="fl w-25">
                    <p class="font-xl font-right"><span class="green">${{ test.sku.price }}</span></p>
                </div>
            </div>
        </div>
        <Paragraph :size="'large'" class="ml2">{{ (test.description || '') | descriptionFilter }}&hellip;</Paragraph>
        <a :href="'/lab-tests/' + test.sku.slug" class="button is-outlined ml2 mt3 font-lg dim">
            <i class="fa fa-flask mr1"></i> Learn More
        </a>
    </div>
</template>

<script>
import { Paragraph } from 'typography';

export default {
    props: {
        // test object from State.conditions.labTests
        test: {
            type: [Object, Boolean],
            required: true
        }
    },
    components: {
        Paragraph
    },
    filters: {
        descriptionFilter(text) {
            return text
                .replace(/<.+?>/g, '')
                .substring(0, 230)
                .replace(/([\s\W]+?\w*)$/gm, '');
        }
    }
};
</script>
