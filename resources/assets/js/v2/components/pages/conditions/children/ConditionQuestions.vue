<template>
  <div>
    <div class="center db tc mw6">
      <SvgIcon :id="State('conditions.condition.image_url')" :width="'80px'" :height="'160px'" />
    </div>
    <div class="center mw6 relative">
      <button class="Button Button--condition-nav is-left" v-show="goToConditions()">
        <a href="/conditions" class="white">
          <i class="fa fa-undo"></i> Start Over
        </a>
      </button>
      <button class="Button Button--condition-nav is-left" v-show="displayBack()" @click="goBack">
        <i class="fa fa-chevron-left"></i>
      </button>
      <button class="Button Button--condition-nav is-right" v-show="displayForward()" @click="goForward">
        <i class="fa fa-chevron-right"></i>
      </button>
    </div>
    <SlideIn v-for="(obj, qIndex) in State('conditions.condition.questions')" v-if="State('conditions.questionIndex') === qIndex" :key="qIndex">
      <div class="center db tc mw6">
        <Heading1 isLight doesExpand>{{ obj.question }}</Heading1>
      </div>
      <Row :gutter="'md'" class="pa4">
        <Column v-for="(answer, aIndex) in obj.answers" :config="{ md: '1of2' }" :key="aIndex">
          <div :class="{'Button Button--answer':true, 'is-selected': answerIndex === aIndex}"
               @click="next(obj.question, answer, aIndex)">
            <span>{{ answer }}</span>
            <i class="fa fa-check"></i>
          </div>
        </Column>
      </Row>
    </SlideIn>
  </div>
</template>

<script>
import { Heading1 } from 'typography';
import { SvgIcon } from 'icons';
import { SlideIn, Column, Row } from 'layout';

export default {
  components: { SvgIcon, SlideIn, Heading1, Column, Row },
  data() {
    return {
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
    }
  }
};
</script>
