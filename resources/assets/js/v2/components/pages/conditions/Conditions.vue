<template>
    <div>
        <Background :color="Config.conditions.backgrounds[State('conditions.condition.slug')] || ''" />
        <PublicNav forceDark giveSpace hasLogo hasLinks hasPhone isSticky />
        <div class="center mw9 pa3 pa4-m">
            <ConditionPreface v-if="!State('conditions.prefaceRead')" />
            <ConditionQuestions v-else-if="State('conditions.questionIndex') < State('conditions.condition.questions').length" />
            <VerifyZip v-else-if="!State('conditions.zipValidation') || State('conditions.zipValidation.is_serviceable') === false" />
        </div>
        <MainSubFooter />
        <MainFooter />
    </div>
</template>

<script>
import { Background, Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { MainSubFooter, MainFooter, PublicNav } from 'nav';

import ConditionQuestions from './children/ConditionQuestions.vue';
import ConditionPreface from './children/ConditionPreface.vue';
import VerifyZip from './children/VerifyZip.vue';

export default {
    name: 'conditions',
    props: {
        shouldSkipToZip: Boolean
    },
    components: {
        Background,
        ConditionPreface,
        ConditionQuestions,
        MainSubFooter,
        MainFooter,
        PublicNav,
        Spacer,
        SvgIcon,
        VerifyZip
    },
    data() {
        return {
            imgStyles: 'display: inline-block; max-width: 80px; margin: 8px; vertical-align: middle;'
        };
    },
    computed: {
        hasZip() {
            return this.State('getstarted.userPost.zip') || this.State('conditions.invalidZip');
        },
        selected() {
            return this.State('conditions.selectedIndex');
        }
    },
    watch: {
        selected(value) {
            let condition, questions;
            condition = value >= 0
                ? this.State('conditions.all')[value]
                : this.State('conditoins.all')[0];
            questions = JSON.parse(condition.questions);
            App.setState('conditions.condition', condition);
            App.setState('conditions.condition.questions', questions);
        }
    },
    beforeCreate() {
        App.setState('getstarted.userPost.zip', App.Logic.getstarted.getZipValidation());
    }
};
</script>
