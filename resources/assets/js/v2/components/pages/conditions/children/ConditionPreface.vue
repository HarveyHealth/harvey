<template>
  <div class="space-children-sm">
    <SlideIn class="space-children-md max-width-xxl ">

      <!-- Description -->
      <div class="mw9 pv2">
        <div class="cf">
          <div class="fl w-100 w-25-l pa5">
            <SvgIcon :id="State('conditions.condition.slug')" :width="'120px'" :height="'120px'" />
          </div>
          <div class="fl w-100 w-75-l pl0 pl3-l">
            <h3 class="font-bold font-xxl">{{ State('conditions.condition.name') }}</h3>
            <p class="font-lg">{{ State('conditions.condition.description') }}</p>
            <p class="font-sm gray">Not what you need? Go back to <a href="/#conditions" class="dim">Conditions</a>.</p>
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
      <h4 class="font-xl font-normal">Knowledge Base: <em>{{ State('conditions.condition.name') }}</em></h4>
      <ul
        class="juicer-feed"
        :data-feed-id="State('conditions.condition.slug')+'-articles'"
        data-per="3"
        data-pages="1"
        data-truncate="250"
      ></ul>
      
      <!-- Videos -->
      <!-- <ul class="juicer-feed" data-feed-id="food-allergies-video" data-per="3" data-pages="1"></ul> -->
      
      <!-- Lab Tests -->
      <div class="mw9 center">
        <div class="cf">
          <div class="fl w-100 w-50-l mt1 pr2-l">
            <div class="bg-white pa4 bn">
              <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png" class="max-width-xxs pa2">
              <div class="mw9 center">
                <div class="cf ph2-ns">
                  <div class="fl w-75">
                    <p class="font-xl font-bold margin-bottom-0">Micronutrient Test</p>
                    <p class="font-lg gray"><em>Sample: Blood draw</em></p>
                  </div>
                  <div class="fl w-25">
                    <p class="font-xxl font-right"><span class="green">$299</span></p>
                  </div>
                </div>
              </div>
              <p class="font-md pa2">Our micronutrient test is one of the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p>
              <a href="/lab-tests" class="button is-outlined ml2 font-lg dim"><i class="fa fa-flask mr1"></i> Learn More</a></p>
            </div>
          </div>
          <div class="fl w-100 w-50-l mt3 mt1-l pl2-l">
            <div class="bg-white pa4 bn">
              <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png" class="max-width-xxs pa2">
              <div class="mw9 center">
                <div class="cf ph2-ns">
                  <div class="fl w-75">
                    <p class="font-xl font-bold margin-bottom-0">Food Allergy Test</p>
                    <p class="font-lg gray"><em>Sample: Blood draw</em></p>
                  </div>
                  <div class="fl w-25">
                    <p class="font-xxl font-right"><span class="green">$299</span></p>
                  </div>
                </div>
              </div>
              <p class="font-md pa2">Our micronutrient test is one of the most accurate and scientifically proven method of assessing vitamin, mineral, antioxidant and amino acid deficiencies.</p>
              <a href="/lab-tests" class="button is-outlined ml2 font-lg dim"><i class="fa fa-flask mr1"></i> Learn More</a></p>
            </div>
          </div>
        </div>
      </div>
    </SlideIn>

    <!-- Quiz -->
    <div class="tc w-100 pv5 ph4">
      <h2 class="font-xl fw4 w-100 w-80-ns w-50-l margin-0a">Take our {{ State('conditions.condition.name') }} questionnaire to determine if Harvey is right for you.</h2>
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
import { Paragraph, Heading1 } from 'typography';

export default {
  components: {
    InputButton,
    SlideIn,
    Spacer,
    SvgIcon,
    Paragraph,
    Heading1
  },
  data() {
    return {
      button: '<span style="font-size:20px; padding-right:16px;">Start Quiz</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
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
