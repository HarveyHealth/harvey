<template>
  <div>
    <img :src="State('conditions.all')[0].image_url" style="width:80px; height:80px" /><br>
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
    SlideIn: Util.SlideIn
  },
  computed: {
    displayBack() {
      const index = App.State.conditions.questionIndex;
      return index > 0;
    },
    displayForward() {
      return App.State.conditions.answers.length > App.State.conditions.questionIndex;
    }
  },
  methods: {
    buttonClasses(answerIndex) {
      const answers = this.$root.State.conditions.answers;
      const questionIndex = this.$root.State.conditions.questionIndex;
      return answers[questionIndex]
        ? { button: true, 'is-selected': answers[questionIndex].index === answerIndex }
        : { button: true };
    },
    goBack() {
      return App.State.conditions.questionIndex--;
    },
    goForward() {
      return App.State.conditions.questionIndex++;
    },
    next(question, answer, index) {
      const answerIndex = App.State.conditions.questionIndex;
      App.State.conditions.answers[answerIndex] = { question, answer, index };
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
