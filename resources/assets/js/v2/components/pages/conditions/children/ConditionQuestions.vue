<template>
  <div>
    <SlideIn v-for="(obj, i) in State('conditions.condition.questions')" v-if="State('conditions.questionIndex') === i" :key="i">
      <p>{{ obj.question }}</p>
      <button class="button" v-for="answer in obj.answers" @click="next(obj.question, answer)">{{ answer }}</button>
    </SlideIn>
  </div>
</template>

<script>
import { Util } from '../../../base';

export default {
  components: {
    SlideIn: Util.SlideIn
  },
  methods: {
    next(question, answer) {
      App.State.conditions.answers.push({ question, answer });
      return App.State.conditions.questionIndex++;
    }
  }
}
</script>
