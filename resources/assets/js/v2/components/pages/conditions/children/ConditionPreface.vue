<template>
  <div class="space-children-sm">
    <SlideIn class="space-children-sm max-width-xxl ">

      <!-- Description -->
      <div class="mw9 pv2">
        <div class="cf">
          <div class="fl w-100 w-25-l pa5">
            <SvgIcon :id="State('conditions.condition.slug')" :width="'150px'" :height="'150px'" />
          </div>
          <div class="fl w-100 w-75-l pl0 pl3-l">
            <Heading1 class="pv2">{{ State('conditions.condition.name') }}</Heading1>
            <Paragraph isLarge>{{ State('conditions.condition.description') }}</Paragraph>
            <p class="font-sm gray pt2">Not what you need? Go back to <a href="/#conditions" class="dim">Conditions</a>.</p>
            <InputButton
              :text="button"
              :type="'whiteFilled'"
              :on-click="() => setState('conditions.prefaceRead', true)"
              :width="'200px'"
              class="mt3 dim"
            />
          </div>
        </div>
      </div>

      <!-- Articles -->
      <Heading2 class="mb2">{{ State('conditions.condition.name') }} Articles</em></Heading2>
      <ul
        class="juicer-feed"
        :data-feed-id="State('conditions.condition.slug')+'-articles'"
        data-per="3"
        data-pages="1"
        data-truncate="250"
      ></ul>

      <!-- Videos -->
      <Heading2 class="mb2">{{ State('conditions.condition.name') }} Videos</em></Heading2>
      <ConditionVideos></ConditionVideos>

      <!-- Lab Tests -->
      <Heading2 class="mb2">Common Lab Tests</em></Heading2>
      <Grid :flexAt="'l'" :columns="[{l: '1of2'}, {l: '1of2'}]" :gutters="{ s:3, l:3 }">
          <div :slot="1" class="bg-white">
              <LabTestCard :testId="tests[0] || false" />
          </div>
          <div :slot="2" class="bg-white">
              <LabTestCard :testId="tests[1] || false" />
          </div>
      </Grid>

    </SlideIn>

    <!-- Quiz -->
    <div class="tc w-100 pv5 ph4">
      <Heading2 class="w-100 w-80-ns w-50-l margin-0a">Take our {{ State('conditions.condition.name') }} quiz to determine if Harvey Health is right for you.</Heading2>
      <InputButton
        :text="button"
        :type="'whiteFilled'"
        :on-click="() => setState('conditions.prefaceRead', true)"
        :width="'200px'"
        class="mt4 dim"
      />
    </div>

  </div>
</template>

<script>
import { InputButton } from 'inputs';
import { Grid, SlideIn, Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { Paragraph, Heading1, Heading2 } from 'typography';

<<<<<<< HEAD
import ConditionLabTests from './ConditionLabTests.vue';
import ConditionVideos from './ConditionVideos.vue';
=======
import LabTestCard from './LabTestCard.vue';
>>>>>>> render lab tests from the db to associated conditions page

export default {
  components: {
    Grid,
    InputButton,
    LabTestCard,
    SlideIn,
    Spacer,
    SvgIcon,
    Paragraph,
<<<<<<< HEAD
    ConditionLabTests,
    ConditionVideos,
=======
>>>>>>> render lab tests from the db to associated conditions page
    Heading1,
    Heading2
  },
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Get Started</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    };
  },
  computed: {
      tests() {
          return this.State('conditions.condition')
            ? this.Config.conditions.labTests[this.State('conditions.condition.slug')]
            : [];
      }
  }
};
</script>

<style>

  * {
    outline: 0;
  }

  .juicer-feed.modern li.feed-item {
    box-shadow: none;
    border: none;
  }

  .juicer-feed.juicer-widget li.feed-item {
    padding: 25px;
    box-shadow: none;
    border: none;
  }

  .j-poster {
    padding-left: 20px !important;
  }

  .j-poster img {
    margin: 0 5px 0 0 !important;
  }

  .j-poster a h3 {
    color: #82BEF2 !important;
    font-size: 15px !important;
  }

  .j-text p, .j-message p {
    color: #5f7278;
    font-family: "proxima-nova";
    font-weight: 500;
    font-size: 15px;
  }

  .j-title {
    display: block;
    min-height: 100px;
  }

  a.j-truncate {
    color: #82BEF2;
    margin-left: 3px;
  }

  .j-title p {
    font-size: 25px;
    line-height: 28px;
    font-weight: 600;
    font-family: "proxima-nova";
    padding-bottom: 8px;
    display: inline-block;
    color: #5f7278;
  }

</style>
