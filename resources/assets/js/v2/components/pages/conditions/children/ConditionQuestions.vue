<template>
  <div class="space-children-sm color-white">
    <div class="block font-centered margin-0a max-width-md margin-top-md">
      <img :src="State('conditions.condition.image_url')" class="max-width-xs">
    </div>
    <SlideIn v-for="(obj, qIndex) in State('conditions.condition.questions')" v-if="State('conditions.questionIndex') === qIndex" :key="qIndex">
      <div class="margin-0a max-width-md" style="position: relative">
        <button class="Button Button--condition-nav is-left" v-show="goToConditions()">
          <a :href="State('conditions.condition.slug')" class="color-white font-normal">
            <i class="fa fa-chevron-left"></i> {{ State('conditions.condition.name') }}
          </a>
        </button>
        <button class="Button Button--condition-nav is-left" v-show="displayBack()" @click="goBack">
          <i class="fa fa-chevron-left"></i>
        </button>
        <button class="Button Button--condition-nav is-right" v-show="displayForward()" @click="goForward">
          <i class="fa fa-chevron-right"></i>
        </button>
      </div>
      <div class="font-centered margin-0a max-width-md">
        <p class="heading-1">{{ obj.question }}</p>
      </div>
      <Row :gutter="'md'" class="space-top-lg is-padding-lg">
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
import { Layout, Util } from '../../../base';

export default {
  components: {
    Column: Layout.Column,
    Row: Layout.Row,
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon
  },
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
