<template>
  <div>
    <div class="bg-blue-fade"></div>
    <div v-if="!hasZip && !State('conditions.condition')">
      <MainNav :context="'conditions'" />
      <div class="margin-0a max-width-xl pad-md color-white">
        <div class="margin-0a max-width-icon">
          <SvgIcon :id="'harvey-icon-white'" :width="'100%'" :height="'60px'"/>
        </div>
        <div class="margin-0a max-width-lg font-centered space-children-lg">
          <h2 class="main-heading">Personalized for better health.</h2>
          <Paragraph class="lead-copy">Please select your most concerning health issue out of the list below. We will ask you a few basic questions to make sure Harvey is a good fit for you, then you can select your doctor and schedule your first video consultation.</Paragraph>
        </div>
        <ConditionsAll class="space-top-lg is-padding-lg" />
      </div>
    </div>
    <div v-else>
      <MainNav :context="'questions'" />
      <div class="margin-0a max-width-lg pad-md color-white">
        <ConditionPreface v-if="!hasZip && !State('conditions.prefaceRead')" />
        <ConditionQuestions v-else-if="!hasZip && State('conditions.questionIndex') < State('conditions.condition.questions').length" />
        <VerifyZip v-else-if="hasZip || (!State('conditions.zipValidation') || State('conditions.zipValidation.is_serviceable') === false)" />
      </div>
    </div>
  </div>
</template>

<script>
import components from 'components';
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import ConditionsAll from './children/ConditionsAll';
import VerifyZip from './children/VerifyZip';

components.ConditionQuestions = ConditionQuestions;
components.ConditionPreface = ConditionPreface;
components.ConditionsAll = ConditionsAll;
components.VerifyZip = VerifyZip;

export default {
  name: 'conditions',
  components,
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
