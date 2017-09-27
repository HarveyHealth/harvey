<template>
  <div>
    <div class="bg-blue-fade"></div>
    <div v-if="!State('conditions.condition')">
      <MainNav :context="'conditions'" />
      <div class="margin-0a max-width-xl pad-md color-white">
        <div class="margin-0a max-width-icon">
          <SvgIcon :id="'harvey-icon-white'" :width="'100%'" />
        </div>
        <div class="margin-0a max-width-lg font-centered space-children-lg">
          <p class="font-normal font-xl">Harvey is on a mission to end human's reliance on pharamecautical drugs. We empower people to find natural and holistic remedies to common medical conditions.</p>
          <p>Harvey doctors hold the bold belief that with the right nutrition, lifestyle and environmental factors humans can prevent most chronic diseases. While integrative medicine has shown incredible success healing patients with a wide variety of medical issues, we are currently focused on just a few conditions.</p>
          <p class="font-normal font-xl">Choose a symptom below to get started.</p>
          <div class="margin-0a max-width-md" style="border-bottom: 1px solid white"></div>
        </div>
        <ConditionsAll class="space-top-xl" />
        <div class="space-top-xl space-children-md">
          <p class="font-centered">Harvey is available to patients in Arizona, California, Conneticut, Florida, Georgia, Illinois, Maryland, Michigan, Missouri, New York, Ohio, Oregon, Pennsylvania, Rhode Island, and Washington.</p>
          <p class="font-centered">&copy; 2017 Harvey Health, Inc. All Rights Reserved.</p>
        </div>
        <div class="font-centered space-top-xl">
          <img src="/images/conditions/logo-norton.png" :style="imgStyles" />
          <img src="/images/conditions/logo-bbb.png" :style="imgStyles" />
          <img src="/images/conditions/logo-hipaa.png" :style="imgStyles" />
        </div>
      </div>
    </div>
    <div v-else>
      <MainNav :context="'questions'" />
      <div class="margin-0a max-width-lg pad-md color-white">
        <ConditionPreface v-if="!State('conditions.prefaceRead')" />
        <ConditionQuestions v-else-if="State('conditions.questionIndex') < State('conditions.condition.questions').length" />
        <VerifyZip v-else-if="!State('conditions.zipValidation')" />
        <OutOfRange v-else-if="State('conditions.zipValidation.serviceable') === false" />
      </div>
    </div>
  </div>
</template>

<script>
import { Util } from '../../base';
import Shared from '../../shared';
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
    MainNav: Shared.MainNav,
    SvgIcon: Util.SvgIcon,
    OutOfRange,
    VerifyZip,
  },
  data() {
    return {
      imgStyles: 'display: inline-block; max-width: 80px; margin: 8px; vertical-align: middle;'
    }
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
