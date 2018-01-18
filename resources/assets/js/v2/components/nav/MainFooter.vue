<template>
    <footer id="footer" class="footer-container">
        <div class="footer-content mha pa2 pa4-m pa4-l">
            <Grid :columns="[{xl:5}, {xl:7}]" :gutters="{ s:2, m:3 }">
                <div :slot="1">
                    <a href="/" class="nav-logo">
                        <LogoIcon alwaysShowText />
                    </a>
                    <Spacer isBottom :size="3" />
                    <p>Harvey allows you to buy preventative lab tests and clinical-grade vitamins and supplements under the guidance of integrative doctors.</p>
                </div>
                <div :slot="2">
                    Links
                </div>
            </Grid>
        </div>
    </footer>
</template>

<script>
import { LogoIcon } from 'icons';
import { Grid, Spacer } from 'layout';

export default {
  components: {
      Grid,
      LogoIcon,
      Spacer
  }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    // Styled to match the footer on store.goharvey.com
    .footer-container {
        background-color: #232323;

        p {
            color: #6c6c6c;
        }
    }

    .footer-content {
        max-width: 1160px;
    }
</style>
