<template>
    <div>
        <Background />
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
import { Heading1, Paragraph, Background } from 'typography';
import { Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { MainSubFooter, MainFooter, PublicNav } from 'nav';
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import ConditionsAll from './children/ConditionsAll';
import VerifyZip from './children/VerifyZip';

export default {
    name: 'conditions',
    props: {
        shouldSkipToZip: Boolean
    },
    components: {
        Background,
        ConditionsAll,
        ConditionPreface,
        ConditionQuestions,
        MainSubFooter,
        MainFooter,
        Heading1,
        Paragraph,
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
