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

      <!-- Research -->
      <Heading2 class="mb2">Participate in Research</em></Heading2>
      <div class="w-100 mt1 ph3 pv4 pa5-l tc bg-white">
        <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/discovery/amazon.png" class="w-50 w-40-m w-25-l pa2">
        <Heading2 class="ph5-l">Take our health survey and earn some cash.</Heading2>
        <Paragraph class="ph5-l">Fill out our 25-question multiple choice health survey as to help the Harvey Health research team in our mission to improve human healthspan. Each participant will receive a <strong>$25 Amazon e-gift card</strong> with no strings attached. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nec dignissim tortor, eu finibus libero. Donec scelerisque dolor at urna tincidunt.</Paragraph>
        <a href="https://goharvey.typeform.com/to/XGnCna"
          class="button is-outlined ml2 mt3 font-lg dim" target="_blank">
          <i class="fa fa-gift mr1"></i> Start Survey</a>
      </div>

      <!-- Lab Tests -->
      <Heading2 class="mb2">Common Lab Tests</em></Heading2>
      <ConditionLabTests></ConditionLabTests>

    </SlideIn>

    <!-- Quiz -->
    <div class="tc w-100 pv5 ph4">
      <Heading2 class="w-100 w-80-ns w-50-l margin-0a">Take our {{ State('conditions.condition.name') }} questionnaire to determine if Harvey is right for you.</Heading2>
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
import { SlideIn, Spacer } from 'layout';
import { SvgIcon } from 'icons';
import { Paragraph, Heading1, Heading2 } from 'typography';

import ConditionLabTests from './ConditionLabTests.vue';
import ConditionVideos from './ConditionVideos.vue';

export default {
  components: {
    InputButton,
    SlideIn,
    Spacer,
    SvgIcon,
    Paragraph,
    ConditionLabTests,
    ConditionVideos,
    Heading1,
    Heading2
  },
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Get Started</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    };
  }
};
</script>

<style>

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
