<template>
  <div>
    <div class="block margin-0a max-width-md">
      <SvgIcon :id="State('conditions.condition.image_url')" />
    </div>
    <button v-if="displayBack" @click="goBack">Back</button>
    <button v-if="displayForward" @click="goForward">Forward</button>
    <SlideIn v-for="(obj, qIndex) in State('conditions.condition.questions')" v-if="State('conditions.questionIndex') === qIndex" :key="qIndex">
      <p>{{ obj.question }}</p>
      <button v-for="(answer, aIndex) in obj.answers" :class="buttonClasses(aIndex)" @click="next(obj.question, answer, aIndex)">
        {{ answer }}
      </button>
    </SlideIn>
  </div>
</template>

<script>
import { Util } from '../../../base';

export default {
  components: {
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon,
  },
  computed: {
    displayBack() {
      return this.State('conditions.questionIndex') > 0;
    },
    displayForward() {
      return this.State('conditions.answers').length > this.State('conditions.questionIndex');
    }
  },
  methods: {
    buttonClasses(answerIndex) {
      const answers = this.State('conditions.answers');
      const questionIndex = this.State('conditions.questionIndex');
      return answers[questionIndex]
        ? { button: true, 'is-selected': answers[questionIndex].index === answerIndex }
        : { button: true };
    },
    goBack() {
      return App.setState('conditions.questionIndex', this.State('conditions.questionIndex') - 1);
    },
    goForward() {
      return App.setState('conditions.questionIndex', this.State('conditions.questionIndex') + 1);
    },
    next(question, answer, index) {
      const answerIndex = this.State('conditions.questionIndex');
      this.State('conditions.answers')[answerIndex] = { question, answer, index };
      this.goForward();
    }
  }
}
</script>

<style>
  .button.is-selected {
    background: black;
  }
</style>
