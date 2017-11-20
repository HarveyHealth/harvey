<template>
  <div>
    <div class="bg-green-fade"></div>
    <div v-if="!hasZip && !State('conditions.condition')">
      <p>You shouldn't be here.</p>
    </div>
    <div v-else>
      <MainNav :context="'questions'" />
      <div class="margin-0a max-width-xxl pad-md">
        <ConditionPreface v-if="!hasZip && !State('conditions.prefaceRead')" />
        <ConditionQuestions v-else-if="!hasZip && State('conditions.questionIndex') < State('conditions.condition.questions').length" />
        <VerifyZip v-else-if="hasZip || (!State('conditions.zipValidation') || State('conditions.zipValidation.is_serviceable') === false)" />
      </div>
      <MainFooter />
    </div>
  </div>
</template>

<script>
import { Heading1, Paragraph } from 'typography';
import { Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { MainFooter, MainNav } from 'nav';
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import ConditionsAll from './children/ConditionsAll';
import VerifyZip from './children/VerifyZip';

export default {
  name: 'conditions',
  components: {
    MainFooter,
    MainNav,
    Heading1,
    Paragraph,
    Spacer,
    SvgIcon,
    ConditionQuestions,
    ConditionPreface,
    ConditionsAll,
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
