<template>
  <div>
    <div v-for="condition in conditions" v-if="!hasSelected">
      <a :href="'/conditions/' + condition.slug" class="block font-centered">
        <img :src="condition.image_url" style="width:80px; height:80px" /><br>
        <span>{{ condition.name }}</span>
      </a>
    </div>
    <div v-else>
      <div v-if="preface">
        <img :src="State('conditions.all')[0].image_url" style="width:80px; height:80px" /><br>
        <h3 class="heading-3-expand">{{ State('conditions.all')[0].name }}</h3>
        <p>{{ State('conditions.all')[0].description }}</p>
        <button class="button" @click="preface = false">Continue</button>
      </div>
      <div v-else-if="question < questions.length">
        <SlideIn v-for="(q, i) in questions" v-if="question === i" :key="i">
          <p>{{ q.question }}</p>
          <button class="button" v-for="a in q.answers" @click="nextQuestion(q.question, a)">{{ a }}</button>
        </SlideIn>
      </div>
      <div v-else>
        <SlideIn>
          <p>Zip codes are important for our business.</p>
          <input type="text" /><button class="button">Verify</button>
        </SlideIn>
      </div>
    </div>
  </div>
</template>

<script>
import { Util } from '../../base';

export default {
  name: 'conditions',
  components: {
    SlideIn: Util.SlideIn
  },
  data() {
    return {
      answers: [],
      preface: true,
      question: 0,
    }
  },
  computed: {
    conditions() {
      return App.State.conditions.all = this.hasSelected
        ? App.State.conditions.all.filter((c, i) => {
          return i === App.State.conditions.selectedIndex;
        })
        : App.State.conditions.all;
    },
    hasSelected() {
      return App.State.conditions.selectedIndex !== null && App.State.conditions.selectedIndex > -1;
    },
    questions() {
      if (this.hasSelected) {
        return JSON.parse(App.State.conditions.all[0].questions);
      }
    }
  },
  methods: {
    nextQuestion(question, answer) {
      App.State.conditions.answers.push({ question, answer });
      this.question++;
    }
  }
}
</script>
