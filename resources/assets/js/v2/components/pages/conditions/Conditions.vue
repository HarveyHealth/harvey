<template>
  <div>
    <ConditionsAll v-if="!State('conditions.condition')" />
    <template v-else>
      <ConditionPreface v-if="!State('conditions.prefaceRead')" />
      <ConditionQuestions v-else-if="State('conditions.questionIndex') < State('conditions.condition.questions').length" />
      <VerifyZip v-else-if="!State('conditions.zipValidation')" />
      <OutOfRange v-else-if="State('conditions.zipValidation.serviceable') === false" />
    </template>
  </div>
</template>

<script>
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import ConditionsAll from './children/ConditionsAll';
import OutOfRange from './children/OutOfRange';
import VerifyZip from './children/VerifyZip';

export default {
  name: 'conditions',
  components: {
    ConditionQuestions,
    ConditionPreface,
    ConditionsAll,
    OutOfRange,
    VerifyZip,
  },
  computed: {
    selected() {
      return this.State('conditions.selectedIndex');
    }
  },
  watch: {
    selected(value) {
      if (value >= 0) {
        const condition = this.State('conditions.all')[value];
        const questions = JSON.parse(condition.questions);
        App.setState('conditions.condition', condition);
        App.setState('conditions.condition.questions', questions);
      }
    }
  }
}
</script>
