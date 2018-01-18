<template>
    <footer id="footer" class="footer-container">
        <div class="footer-content mha pa2 pa4-m pa4-l">
            <Grid :columns="[{xl:5}, {xl:7}]" :gutters="{ s:2, m:4 }">
                <div :slot="1">
                    <a href="/" class="nav-logo">
                        <LogoIcon alwaysShowText />
                    </a>
                    <Spacer isBottom :size="3" />
                    <p>Harvey allows you to buy preventative lab tests and clinical-grade vitamins and supplements under the guidance of integrative doctors.</p>
                    <Spacer isBottom :size="3" />
                    <div class="pv2">
                        <i class="fa fa-map-marker dib mr3" />
                        <span>Playa Vista, CA</span>
                    </div>
                    <div class="pv2">
                        <i class="fa fa-phone dib mr3" />
                        <a href="tel:8006909989">800-690-9989</a>
                    </div>
                    <div class="pv2">
                        <i class="fa fa-envelope-o dib mr3" />
                        <a href="mailto:support@goharvey.com">support@goharvey.com</a>
                    </div>
                </div>
                <div :slot="2">
                    Links
                </div>
            </Grid>
        </div>
        <div class="break"></div>
        <div class="footer-content mha pa2 pa4-m pa4-l">
            <p>Copyright &copy;2018 Harvey Health, Inc. All Rights Reserved. Harvey does not diagnose, treat, cure or prevent any disease.</p>
            <p>These statements have not been evaluated by the Food and Drug Administration.</p>
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

        &, p, a {
            color: #6c6c6c;
        }

        i {
            font-size: 16px;
            width: 20px;
        }

        a:hover {
            color: $color-accent;
        }
    }

    .footer-content {
        max-width: 1160px;
        @include query(md) { width: map-get($breakpoints, md); }
        @include query(lg) { width: map-get($breakpoints, lg); }
        @include query(xl) { width: map-get($breakpoints, xl); }
        @include query(xxl) { width: map-get($breakpoints, xxl); }
    }

    .break {
        border-bottom: 1px solid #404040;
    }
</style>
