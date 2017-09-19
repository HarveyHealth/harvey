<template>
  <div>
    <WrapConditions v-if="!State('conditions.condition')" />
    <div v-else>
      <MultiInput :quantity="3" :get-value="(v) => setState('misc.foo', v)" />
      <ConditionPreface v-if="!State('conditions.prefaceRead')" />
      <div v-else-if="State('conditions.questionIndex') < State('conditions.condition.questions').length">
        <ConditionQuestions />
      </div>
      <div v-else>
        <SlideIn>
          <p>Zip codes are important for our business.</p>
          <input type="text" /><button class="button">Verify</button>
          <MultiInput :quantity="5" :get-value="(v) => console.log(v)" />
        </SlideIn>
      </div>
    </div>
  </div>
</template>

<script>
import { Inputs } from '../../base';
import { Util } from '../../base';
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import WrapConditions from './children/WrapConditions';

export default {
  name: 'conditions',
  components: {
    ConditionQuestions,
    ConditionPreface,
    MultiInput: Inputs.MultiInput,
    SlideIn: Util.SlideIn,
    WrapConditions,
  },
  computed: {
    selected() {
      return this.State('conditions.selectedIndex');
    }
  },
  watch: {
    selected(value) {
      if (value) {
        const condition = this.State('conditions.all')[value];
        const questions = JSON.parse(condition.questions);
        App.setState('conditions.condition', condition);
        App.setState('conditions.condition.questions', questions);
      }
    }
  }
}
</script>
