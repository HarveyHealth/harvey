<template>
  <div class="lab-tests">
    <div class="dib mw9 center">

      <div class="fl w-100 w-50-l mt1 pr2-l">
        <div class="bg-white pa4 bn">
          <img :src="LabTestImage1" class="max-width-xxs pa2">
          <div class="mw9 center">
            <div class="cf ph2">
              <div class="fl w-75">
                <p class="font-xl font-bold margin-bottom-0">{{ LabTestName1 }}</p>
                <p class="font-lg gray"><em>Sample: {{ LabTestSample1 }}</em></p>
              </div>
              <div class="fl w-25">
                <p class="font-xxl font-right"><span class="green">{{ LabTestPrice1 }}</span></p>
              </div>
            </div>
          </div>
          <Paragraph isLarge class="ml2">{{ LabTestDescription1 }}</Paragraph>
          <a :href="LabTestLink1" class="button is-outlined ml2 mt3 font-lg dim">
            <i class="fa fa-flask mr1"></i> Learn More</a>
        </div>
      </div>

      <div class="fl w-100 w-50-l mt1 pl2-l">
        <div class="bg-white pa4 bn">
          <img :src="LabTestImage2" class="max-width-xxs pa2">
          <div class="mw9 center">
            <div class="cf ph2">
              <div class="fl w-75">
                <p class="font-xl font-bold margin-bottom-0">{{ LabTestName2 }}</p>
                <p class="font-lg gray"><em>Sample: {{ LabTestSample2 }}</em></p>
              </div>
              <div class="fl w-25">
                <p class="font-xxl font-right"><span class="green">{{ LabTestPrice2 }}</span></p>
              </div>
            </div>
          </div>
          <Paragraph isLarge class="ml2">{{ LabTestDescription2 }}</Paragraph>
          <a :href="LabTestLink2" class="button is-outlined ml2 mt3 font-lg dim">
            <i class="fa fa-flask mr1"></i> Learn More</a>
        </div>
      </div>

    </div>
  </div>
</template>

<script>

import { Paragraph } from 'typography';

export default {
  components: { Paragraph },
  computed: {
    LabTestImage1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png';
        } else if (conditionsSlug == 'food-allergies'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png';
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/hormones.png';
        } else if (conditionsSlug == 'digestive-issues'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/microbiome.png';
        } else if (conditionsSlug == 'fatigue'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/adrenals.png';
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png';
        } else if (conditionsSlug == 'womens-health'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/hormones.png';
        } else if (conditionsSlug == 'general-health'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png';
        } else return '';
    },
    LabTestName1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Food Allergies'; // Food Allergies
        } else if (conditionsSlug == 'food-allergies'){
          return 'Food Allergies'; // Food Allergies
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Hormone'; // Hormone
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Microbiome'; // Microbiome
        } else if (conditionsSlug == 'fatigue'){
          return 'Adrenals'; // Adrenals
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Food Allergies'; // Food Allergies
        } else if (conditionsSlug == 'womens-health'){
          return 'Hormone'; // Hormone
        } else if (conditionsSlug == 'general-health'){
          return 'Micronutrients'; // Micronutrients
        } else return '';
    },
    LabTestSample1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Blood Draw'; // Food Allergies
        } else if (conditionsSlug == 'food-allergies'){
          return 'Blood Draw'; // Food Allergies
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Blood Draw'; // Hormone
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Stool'; // Microbiome
        } else if (conditionsSlug == 'fatigue'){
          return 'Saliva'; // Adrenals
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Blood Draw'; // Food Allergies
        } else if (conditionsSlug == 'womens-health'){
          return 'Blood Draw'; // Hormone
        } else if (conditionsSlug == 'general-health'){
          return 'Blood Draw'; // Micronutrient
        } else return '';
    },
    LabTestPrice1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return '$299'; // Food Allergies
        } else if (conditionsSlug == 'food-allergies'){
          return '$299'; // Food Allergies
        } else if (conditionsSlug == 'stress-anxiety'){
          return '$115'; // Hormone
        } else if (conditionsSlug == 'digestive-issues'){
          return '$229'; // Microbiome
        } else if (conditionsSlug == 'fatigue'){
          return '$145'; // Adrenals
        } else if (conditionsSlug == 'weight-loss-gain'){
          return '$229'; // Food Allergies
        } else if (conditionsSlug == 'womens-health'){
          return '$115'; // Hormone
        } else if (conditionsSlug == 'general-health'){
          return '$339'; // Micronutrient
        } else return '';
    },
    LabTestDescription1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Food Allergies
        } else if (conditionsSlug == 'food-allergies'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Food Allergies
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Hormone
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Microbiome
        } else if (conditionsSlug == 'fatigue'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Adrenals
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Food Allergies
        } else if (conditionsSlug == 'womens-health'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Hormone
        } else if (conditionsSlug == 'general-health'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Micronutrient
        } else return '';
    },
    LabTestLink1() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return '/lab-tests/food-allergies'; // Food Allergies
        } else if (conditionsSlug == 'food-allergies'){
          return '/lab-tests/food-allergies'; // Food Allergies
        } else if (conditionsSlug == 'stress-anxiety'){
          return '/lab-tests/hormones'; // Hormone
        } else if (conditionsSlug == 'digestive-issues'){
          return '/lab-tests/microbiome-gut'; // Microbiome
        } else if (conditionsSlug == 'fatigue'){
          return '/lab-tests/adrenals'; // Adrenals
        } else if (conditionsSlug == 'weight-loss-gain'){
          return '/lab-tests/food-allergies'; // Food Allergies
        } else if (conditionsSlug == 'womens-health'){
          return '/lab-tests/hormones'; // Hormone
        } else if (conditionsSlug == 'general-health'){
          return '/lab-tests/adrenals'; // Micronutrient
        } else return '';
    },
    LabTestImage2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/microbiome.png';
        } else if (conditionsSlug == 'food-allergies'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/microbiome.png';
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png';
        } else if (conditionsSlug == 'digestive-issues'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png';
        } else if (conditionsSlug == 'fatigue'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png';
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/micronutrients.png';
        } else if (conditionsSlug == 'womens-health'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/thyroid.png';
        } else if (conditionsSlug == 'general-health'){
          return 'https://d35oe889gdmcln.cloudfront.net/assets/images/lab_tests/food-allergies.png';
        } else return '';
    },
    LabTestName2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Microbiome'; // Microbiome
        } else if (conditionsSlug == 'food-allergies'){
          return 'Microbiome'; // Microbiome
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Micronutrient'; // Micronutrient
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Food Allergies'; // Food Allergies
        } else if (conditionsSlug == 'fatigue'){
          return 'Micronutrient'; // Micronutrients
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Micronutrients'; // Micronutrients
        } else if (conditionsSlug == 'womens-health'){
          return 'Thyroid'; // Thyroid
        } else if (conditionsSlug == 'general-health'){
          return 'Food Allergies'; // Food Allergies
        } else return '';
    },
    LabTestSample2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Stool'; // Microbiome
        } else if (conditionsSlug == 'food-allergies'){
          return 'Stool'; // Microbiome
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Blood Draw'; // Micronutrients
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Blood Draw'; // Food Allergies
        } else if (conditionsSlug == 'fatigue'){
          return 'Blood Draw'; // Micronutrients
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Blood Draw'; // Micronutrients
        } else if (conditionsSlug == 'womens-health'){
          return 'Blood Draw'; // Thyroid
        } else if (conditionsSlug == 'general-health'){
          return 'Blood Draw'; // Food Allergies
        } else return '';
    },
    LabTestPrice2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return '$199'; // Microbiome
        } else if (conditionsSlug == 'food-allergies'){
          return '$199'; // Microbiome
        } else if (conditionsSlug == 'stress-anxiety'){
          return '$299'; // Micronutrients
        } else if (conditionsSlug == 'digestive-issues'){
          return '$199'; // Food Allergies
        } else if (conditionsSlug == 'fatigue'){
          return '$299'; // Micronutrients
        } else if (conditionsSlug == 'weight-loss-gain'){
          return '$299'; // Micronutrients
        } else if (conditionsSlug == 'womens-health'){
          return '$99'; // Thyroid
        } else if (conditionsSlug == 'general-health'){
          return '$199'; // Food Allergies
        } else return '';
    },
    LabTestDescription2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (conditionsSlug == 'skin-issues'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Microbiome
        } else if (conditionsSlug == 'food-allergies'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Microbiome
        } else if (conditionsSlug == 'stress-anxiety'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Micronutrients
        } else if (conditionsSlug == 'digestive-issues'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Food Allergies
        } else if (conditionsSlug == 'fatigue'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Micronutrients
        } else if (conditionsSlug == 'weight-loss-gain'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Micronutrients
        } else if (conditionsSlug == 'womens-health'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Thyroid
        } else if (conditionsSlug == 'general-health'){
          return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus a aliquet diam. Cras ultricies dictum volutpat. Aliquam eget nunc nec diam ultrices eleifend.'; // Food Allergies
        } else return '';
    },
    LabTestLink2() {
        var conditionsSlug = this.State('conditions.condition.slug');
        if (this.State('conditions.condition.slug') == 'skin-issues'){
          return '/lab-tests/microbiome'; // Microbiome
        } else if (conditionsSlug == 'food-allergies'){
          return '/lab-tests/microbiome'; // Microbiome
        } else if (conditionsSlug == 'stress-anxiety'){
          return '/lab-tests/micronutrients'; // Micronutrients
        } else if (conditionsSlug == 'digestive-issues'){
          return '/lab-tests/food-allergies'; // Food Allergies
        } else if (conditionsSlug == 'fatigue'){
          return '/lab-tests/micronutrients'; // Micronutrients
        } else if (conditionsSlug == 'weight-loss-gain'){
          return '/lab-tests/micronutrients'; // Micronutrients
        } else if (conditionsSlug == 'womens-health'){
          return '/lab-tests/thyroid'; // Thyroid
        } else if (conditionsSlug == 'general-health'){
          return '/lab-tests/food-allergies'; // Food Allergies
        } else return '';
    }
  }
};
</script>
