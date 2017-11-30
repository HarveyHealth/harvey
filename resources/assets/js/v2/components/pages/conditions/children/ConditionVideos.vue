<template>
    <Grid :flexAt="'l'" :columns="[{l: '1of2'}, {l: '1of2'}]" :gutters="{ s:3, l:3 }">
        <div :slot="1" class="bg-white pa4 bn">
            <a :href="getVideoLink(linksA)" class="watch-video" frameborder="0" data-lity allowfullscreen>
                <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/discovery/amanda-disc.jpg" alt="">
            </a>
            <Heading3 :size="'large'" class="pt2">Dr. Frick Series: {{ State('conditions.condition.name') }}</Heading3>
            <Paragraph>
                Harvey Health's Lead Naturopath Dr. Amanda Frick, ND discusses her medical philosophy on {{ State('conditions.condition.name') }}.
            </Paragraph>
        </div>
        <div :slot="2" class="bg-white pa4 bn">
            <a :href="getVideoLink(linksB)" class="watch-video" frameborder="0" data-lity allowfullscreen>
                <img src="https://d35oe889gdmcln.cloudfront.net/assets/images/discovery/mark-disc.jpg" alt="">
            </a>
            <Heading3 :size="'large'" class="pt2">Dr. Hyman Series: {{ State('conditions.condition.name') }}</Heading3>
                <Paragraph>
                    Learn from Dr. Mark Hyman, MD, one of the world's leading functional doctors, how integrative practitioners approach {{ State('conditions.condition.name') }}.
                </Paragraph>
            </div>
        </div>
    </Grid>
</template>

<script>
import { Grid } from 'layout';
import { Paragraph, Heading3 } from 'typography';

export default {
  components: {
      Grid,
      Heading3,
      Paragraph
  },
  data() {
      return {
          linksA: {
              'skin-issues': 'dhl3eq2S7nQ',
              'food-allergies': 'ATb2tzcS5cI',
              'stress-anxiety': 'Sk7IRNIpI-E',
              'digestive-issues': 'M7Q4lWHt4NE',
              'fatigue': 'ToiI2LjfsFc',
              'weight-loss-gain': 'jZgZIKqOLuc',
              'womens-health': 'GsKaZ2s-UpI',
              'general-health': 'nQJBCn1pZGI'
          },
          linksB: {
              'skin-issues': 'ayqkXeFwt_4',
              'food-allergies': 'ej_f6Ks325Q',
              'stress-anxiety': 'uBcRocFlbao',
              'digestive-issues': 'ed0d5c636JE',
              'fatigue': 'DOwBA5QIXS8',
              'weight-loss-gain': 'F1kKP9HjMm0',
              'womens-health': '2Nbr9wGyN4s',
              'general-health': 'z_eHd7dPFGA'
          }
      }
  },
  methods: {
      getVideoLink(linkList) {
          const youTubeId = this.State('conditions.condition')
              ? linkList[this.State('conditions.condition.slug')]
              : linkList['general-health'];

          return `https://www.youtube.com/watch?v=${youTubeId}&rel=0&modestbranding=0&autohide=1&showinfo=1&VQ=HD720`;
      }
  }
};
</script>
