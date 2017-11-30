<template>
    <div class="center mw7 mt3 min-height">
        <SlideIn v-for="(obj, qIndex) in State('conditions.condition.questions')" v-if="State('conditions.questionIndex') === qIndex" :key="qIndex">
            <div class="center db tc mw6">
                <Heading1 doesExpand class="pv2">{{ obj.question }}</Heading1>
            </div>
            <div class="pagination-container">
                <button class="Button Button--condition-nav is-left" v-show="!displayBack()" @click="reset">
                    <i class="fa fa-home"></i> Home
                </button>
                <button class="Button Button--condition-nav is-left" v-show="displayBack()" @click="goBack">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="Button Button--condition-nav is-right" v-show="displayForward()" @click="goForward">
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            <Grid :flexAt="'l'" :columns="obj.answers.map(a => answerColumn)" :gutters="{ s: 3 }">
                <div :slot="aIndex + 1" v-for="(answer, aIndex) in obj.answers">
                    <div :class="{'Button Button--answer font-lg':true, 'is-selected': answerIndex === aIndex}"
                        @click="next(obj.question, answer, aIndex)">
                        <span>{{ answer }}</span>
                        <i class="fa fa-check"></i>
                    </div>
                </div>
            </Grid>
        </SlideIn>
    </div>
</template>

<script>
import { Heading1 } from 'typography';
import { SvgIcon } from 'icons';
import { Grid, SlideIn } from 'layout';

export default {
    components: {
        Grid,
        Heading1,
        SlideIn,
        SvgIcon
    },
    data() {
        return {
            answerColumn: { m: '1of2' },
            hasAnswered: 0
        };
    },
    computed: {
        answerIndex() {
            const answers = this.State('conditions.answers');
            const questionIndex = this.State('conditions.questionIndex');
            return answers[questionIndex] ? answers[questionIndex].index : false;
        }
    },
    methods: {
        displayBack() {
            return this.State('conditions.questionIndex') > 0;
        },
        displayForward() {
            return this.hasAnswered > this.State('conditions.questionIndex');
        },
        goToConditions() {
            return this.State('conditions.questionIndex') < 1;
        },
        goBack() {
            return App.setState('conditions.questionIndex', this.State('conditions.questionIndex') - 1);
        },
        goForward() {
            if (this.State('conditions.answers').length > this.hasAnswered) {
                this.hasAnswered++;
            }
            return App.setState('conditions.questionIndex', this.State('conditions.questionIndex') + 1);
        },
        next(question, answer, index) {
            const questionIndex = this.State('conditions.questionIndex');
            Vue.set(this.State('conditions.answers'), questionIndex, { question, answer, index });
            setTimeout(this.goForward, 400);
        },
        reset() {
            App.setState('conditions.questionIndex', 0);
            App.setState('conditions.answers', []);
            App.setState('conditions.prefaceRead', false);
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .Button {
        color: $color-copy;
    }

    .min-height {
        min-height: 358px;
    }

    .pagination-container {
        height: 60px;
    }

</style>
