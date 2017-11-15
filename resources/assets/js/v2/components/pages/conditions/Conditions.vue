<template>
  <div>
    <div class="bg-blue-fade"></div>
    <div v-if="!hasZip && !State('conditions.condition')">
      <MainNav :context="'conditions'" />
      <div class="center mw8 pa3 pa4-m">
        <div class="center mw6 tc">
          <div class="center mw4">
            <SvgIcon :id="'harvey-icon-white'" :width="'100%'" :height="'60px'" />
          </div>
          <Spacer isBottom :size="3" />
          <Heading1 isLight>Personalized for better health.</Heading1>
          <Spacer isBottom :size="4" />
          <Paragraph isLight>Please select your most concerning health issue out of the list below. We will ask you a few basic questions to make sure Harvey is a good fit for you, then you can select your doctor and schedule your first video consultation.</Paragraph>
        </div>
        <Spacer isBottom :size="4" />
        <ConditionsAll />
      </div>
    </div>
    <div v-else>
      <MainNav :context="'questions'" />
      <div class="center mw8 pa3 pa4-m">
        <ConditionPreface v-if="!hasZip && !State('conditions.prefaceRead')" />
        <ConditionQuestions v-else-if="!hasZip && State('conditions.questionIndex') < State('conditions.condition.questions').length" />
        <VerifyZip v-else-if="hasZip || (!State('conditions.zipValidation') || State('conditions.zipValidation.is_serviceable') === false)" />
      </div>
    </div>
  </div>
</template>

<script>
import { Heading1, Paragraph } from 'typography';
import { Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { MainNav } from 'nav';
import ConditionQuestions from './children/ConditionQuestions';
import ConditionPreface from './children/ConditionPreface';
import ConditionsAll from './children/ConditionsAll';
import VerifyZip from './children/VerifyZip';

export default {
  name: 'conditions',
  components: {
    MainNav, Heading1, Paragraph, Spacer, SvgIcon,
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
