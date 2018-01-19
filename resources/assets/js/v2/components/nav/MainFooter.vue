<template>
    <footer id="footer" class="footer-container pt4 pb5">
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
                    <Grid :columns="[{l:4}, {l:4}, {l:4}]" :gutters="{s:2, l:3}">
                        <div :slot="1">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="uppercase white">Company</span>
                                <div :slot="'content'">
                                    <a href="/" class="dib pv1">Home</a><br />
                                    <a href="/about" class="dib pv1">About</a><br />
                                    <a href="/blog" class="dib pv1">Blog</a><br />
                                    <a href="/legal/terms" class="dib pv1">Terms</a><br />
                                    <a href="/legal/privacy" class="dib pv1">Privacy</a><br />
                                    <a href="help.goharvey.com" class="dib pv1">Help</a>
                                </div>
                            </Accordian>
                        </div>
                        <div :slot="2">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="uppercase white">Research</span>
                                <div :slot="'content'">
                                    <a href="/collections/skin-issues" class="dib pv1">Skin Issues</a><br />
                                    <a href="/collections/food-allergies" class="dib pv1">Food Allergies</a><br />
                                    <a href="/collections/stress-anxiety" class="dib pv1">Stress &amp; Anxiety</a><br />
                                    <a href="/collections/fatigue" class="dib pv1">Fatigue</a><br />
                                    <a href="/collections/weight-loss-gain" class="dib pv1">Weight Loss/Gain</a><br />
                                    <a href="/collections/weight-loss-gain" class="dib pv1">Women's Health</a><br />
                                    <a href="/collections/general-health" class="dib pv1">General Health</a>
                                </div>
                            </Accordian>
                        </div>
                        <div :slot="3">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="uppercase white">Account</span>
                                <div :slot="'content'">
                                    <a href="/login" class="dib pv1">Login</a><br />
                                    <a href="/consultations" class="dib pv1">Full Consultation</a>
                                </div>
                            </Accordian>
                        </div>
                    </Grid>
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
import { Accordian, Grid, Spacer } from 'layout';

export default {
  components: {
      Accordian,
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
