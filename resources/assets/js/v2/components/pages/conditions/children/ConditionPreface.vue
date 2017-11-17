<template>
  <div class="space-children-sm">
<!--     <div class="block font-centered margin-0a max-width-xxl margin-top-md">
      <img :src="State('conditions.condition.image_url')" class="max-width-xs">
    </div> -->
    <SlideIn class="space-children-md max-width-xxl ">

      <!-- Heading -->
      <h3 class="font-bold font-xxxl">{{ State('conditions.condition.name') }}</h3>
      <p class="font-lg">{{ State('conditions.condition.description') }}</p>
      <a href="/#get-started" class="button font-lg"><i class="fa fa-book"></i> Explore Conditions</a>

      <!-- Articles -->
      <h4 class="font-xl font-thin">Recent Articles</h4>
      <ul class="juicer-feed" data-feed-id="food-allergies-articles" data-per="3" data-pages="1" data-truncate="350"></ul>

      <!-- Videos -->
      <h4 class="font-xl font-thin">Doctor Interviews</h4>
      <ul class="juicer-feed" data-feed-id="food-allergies-video" data-per="3" data-pages="1"></ul>

      <!-- Lab Tests -->
      <h4 class="font-xl font-thin">Most Common Tests</h4>
      <div class="mw9 center">
        <div class="cf">
          <div class="fl w-50 is-paddingless-top is-padding-right">
            <div class="bg-white pa4 bn">
              <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png" class="max-width-xs pa2">
              <div class="mw9 center">
                <div class="cf ph2-ns">
                  <div class="fl w-50">
                    <p class="font-xl font-bold margin-bottom-0">Food Allergy Test</p>
                    <p class="font-lg gray"><em>Sample: Blood draw</em></p>
                  </div>
                  <div class="fl w-50">
                    <p class="font-xxl font-right"><span class="green">$299</span></p>
                  </div>
                </div>
              </div>
              <p class="font-md pa2">Our micronutrient test is one of the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p>
              <a href="/lab-tests" class="button font-lg">Learn More</a></p>
            </div>
          </div>
          <div class="fl w-50 is-paddingless-top is-padding-left">
            <div class="bg-white pa4 bn">
              <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png" class="max-width-xs pa2">
              <div class="mw9 center">
                <div class="cf ph2-ns">
                  <div class="fl w-50">
                    <p class="font-xl font-bold margin-bottom-0">Food Allergy Test</p>
                    <p class="font-lg gray"><em>Sample: Blood draw</em></p>
                  </div>
                  <div class="fl w-50">
                    <p class="font-xxl font-right"><span class="green">$299</span></p>
                  </div>
                </div>
              </div>
              <p class="font-md pa2">Our micronutrient test is one of the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p>
              <a href="/lab-tests" class="button font-lg">Learn More</a></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quiz -->
      <h4 class="font-xl font-thin font-centered max-width-md block margin-0a is-padding-top-xl">To get started, take our {{ State('conditions.condition.name') }} quiz to determine if Harvey is right for you.</h4>
      <div class="font-centered is-padding-bottom">
        <ButtonInput
          :text="button"
          :type="'whiteFilled'"
          :on-click="() => setState('conditions.prefaceRead', true)"
          :width="'200px'" />
      </div>

    </SlideIn>
  </div>
</template>

<script>
import { Inputs, Util } from '../../../base';

export default {
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Start Quiz</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
    };
  },
  components: {
    ButtonInput: Inputs.ButtonInput,
    SlideIn: Util.SlideIn,
    SvgIcon: Util.SvgIcon
  }
};
</script>

<style>

  .juicer-feed.juicer-widget li.feed-item {
    padding: 25px;
  }

  .j-poster {
    padding-left: 20px !important;
  }

  .j-poster img {
    margin: 0 5px 0 0 !important;
  }

  .j-poster a h3 {
    color: #AAA !important;
    font-size: 15px !important;
  }

  .j-text p, .j-message p {
    color: #555;
    font-family: "proxima-nova";
    font-weight: 500;
    font-size: 15px;
  }

  .j-title {
    display: block;
    min-height: 100px;
  }

  .j-title p {
    font-size: 25px;
    line-height: 28px;
    font-weight: 600;
    font-family: "proxima-nova";
    padding-bottom: 8px;
    display: inline-block;
    color: #555;
  }

</style>
