<template>
  <div class="lab-tests">
    <div class="dib mw9 center">

      <div class="fl w-100 w-50-l mt1 pr2-l">
        <div class="bg-white pa4 bn">
          <a :href="ConditionVideoLink1" class="watch-video" frameborder="0" data-lity allowfullscreen>
            <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/discovery/amanda-disc.jpg" alt="">
          </a>
          <Heading3 class="pt2">Dr. Frick Series: {{ State('conditions.condition.name') }}</Heading3>
          <Paragraph>Harvey Health's Lead Naturopath Dr. Amanda Frick, ND discusses her medical philosophy on {{ State('conditions.condition.name') }}.</Paragraph>
        </div>
      </div>

      <div class="fl w-100 w-50-l mt1 pl2-l">
        <div class="bg-white pa4 bn">
          <a :href="ConditionVideoLink2" class="watch-video" frameborder="0" data-lity allowfullscreen>
            <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/discovery/mark-disc.jpg" alt="">
          </a>
          <Heading3 class="pt2">Dr. Hyman Series: {{ State('conditions.condition.name') }}</Heading3>
          <Paragraph>Learn from Dr. Mark Hyman, MD, one of the world's leading functional doctors, how integrative practitioners approach {{ State('conditions.condition.name') }}.</Paragraph>
        </div>
      </div>

    </div>
  </div>
</template>

<script>

import { Paragraph, Heading3 } from 'typography';

export default {
  components: { Paragraph, Heading3 },
  computed: {
    ConditionVideoLink1() {
        var youTubeId = '';
        var slug = this.State('conditions.condition.slug');
        if (slug == 'skin-issues'){
          youTubeId = 'dhl3eq2S7nQ';
        } else if (slug == 'food-allergies'){
          youTubeId = 'ATb2tzcS5cI';
        } else if (slug == 'stress-anxiety'){
          youTubeId = 'Sk7IRNIpI-E';
        } else if (slug == 'digestive-issues'){
          youTubeId = 'M7Q4lWHt4NE';
        } else if (slug == 'fatigue'){
          youTubeId = 'ToiI2LjfsFc';
        } else if (slug == 'weight-loss-gain'){
          youTubeId = 'jZgZIKqOLuc';
        } else if (slug == 'womens-health'){
          youTubeId = 'GsKaZ2s-UpI';
        } else if (slug == 'general-health'){
          youTubeId = 'nQJBCn1pZGI';
        } else youTubeId = 'nQJBCn1pZGI';
        return '//www.youtube.com/watch?v='+youTubeId+'&rel=0&modestbranding=0&autohide=1&showinfo=1&VQ=HD720';
    },
    ConditionVideoLink2() {
        var youTubeId = '';
        var slug = this.State('conditions.condition.slug');
        if (slug == 'skin-issues'){
          youTubeId = 'ayqkXeFwt_4';
        } else if (slug == 'food-allergies'){
          youTubeId = 'ej_f6Ks325Q';
        } else if (slug == 'stress-anxiety'){
          youTubeId = 'uBcRocFlbao';
        } else if (slug == 'digestive-issues'){
          youTubeId = 'ed0d5c636JE';
        } else if (slug == 'fatigue'){
          youTubeId = 'DOwBA5QIXS8';
        } else if (slug == 'weight-loss-gain'){
          youTubeId = 'F1kKP9HjMm0';
        } else if (slug == 'womens-health'){
          youTubeId = '2Nbr9wGyN4s';
        } else if (slug == 'general-health'){
          youTubeId = 'z_eHd7dPFGA';
        } else youTubeId = 'z_eHd7dPFGA';
        return '//www.youtube.com/watch?v='+youTubeId+'&rel=0&modestbranding=0&autohide=1&showinfo=1&VQ=HD720';
    }
  }
};
</script>
