<template>
  <div>
    <div class="bg-green-fade"></div>
    <div v-if="!hasZip && !State('conditions.condition')">
      <p>Redirect this...</p>
    </div>
    <div v-else>
      <MainNav :context="'questions'" />
      <div class="margin-0a max-width-xxl pad-md">
        <ConditionPreface v-if="!hasZip && !State('conditions.prefaceRead')" />
        <ConditionQuestions v-else-if="!hasZip && State('conditions.questionIndex') < State('conditions.condition.questions').length" />
        <VerifyZip v-else-if="hasZip || (!State('conditions.zipValidation') || State('conditions.zipValidation.is_serviceable') === false)" />
      </div>
    </div>
  </div>
</template>

<script>
import { Util } from '../../base';
import Shared from '../../shared';
import ConditionQuestions from './children/ConditionQuestions.vue';
import ConditionPreface from './children/ConditionPreface.vue';
import ConditionsAll from './children/ConditionsAll.vue';
import VerifyZip from './children/VerifyZip.vue';
export default {
  name: 'conditions',
  components: {
    ConditionQuestions,
    ConditionPreface,
    ConditionsAll,
    MainNav: Shared.MainNav,
    SvgIcon: Util.SvgIcon,
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
      if (value >= 0) {
        const condition = this.State('conditions.all')[value];
        const questions = JSON.parse(condition.questions);
        App.setState('conditions.condition', condition);
        App.setState('conditions.condition.questions', questions);
      }
    }
  },
  beforeCreate() {
    App.setState('getstarted.userPost.zip', App.Logic.getstarted.getZipValidation());
  }
};
</script>
