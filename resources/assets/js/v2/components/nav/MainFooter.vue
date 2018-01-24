<template>
    <footer id="footer" class="footer-container ph4-l ph3-m ph2 bg-near-black o-90">
        <div class="footer-content mha pa2 pa4-m pa4-l">
            <Grid :columns="[{xl:5}, {xl:7}]" :gutters="{ s:2, m:4 }">
                <div :slot="1">
                    <a href="/" class="nav-logo">
                        <LogoIcon alwaysShowText />
                    </a>
                    <Spacer isBottom :size="3" />
                    <p class="pr2-ns">Harvey offers advanced home lab tests and professional-grade supplements—under the guidance of functional and integrative doctors.</p>
                    <Spacer isBottom :size="3" />
                    <div class="pv1">
                        <i class="fa fa-map-marker dib dib pt1" />
                        <span>Playa Vista, CA</span>
                    </div>
                    <div class="pv1">
                        <i class="fa fa-phone dib dib pt1" />
                        <a href="tel:8006909989">800-690-9989</a>
                    </div>
                    <div class="pv1">
                        <i class="fa fa-envelope-o dib dib pt1" />
                        <a href="mailto:support@goharvey.com">support@goharvey.com</a>
                    </div>
                </div>
                <div :slot="2">
                    <Grid :columns="[{l:4}, {l:4}, {l:4}]" :gutters="{s:2, l:3}">
                        <div :slot="1">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="ttu fw5 white">Company</span>
                                <div :slot="'content'">
                                    <a href="/" class="db pv1">Home</a>
                                    <a href="/about" class="db pv1">About</a>
                                    <a href="/consultations" class="db pv1">Stories</a>
                                    <a href="/blog" class="db pv1">Blog</a>
                                    <a href="/legal/terms" class="db pv1">Terms</a>
                                    <a href="/legal/privacy" class="db pv1">Privacy</a>
                                    <a href="https://help.goharvey.com" class="db pv1">Help</a>
                                </div>
                            </Accordian>
                        </div>
                        <div :slot="2">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="ttu fw5 white">Conditions</span>
                                <div :slot="'content'">
                                    <a href="https://store.goharvey.com/collections/skin-issues" class="db pv1">Skin Issues</a>
                                    <a href="https://store.goharvey.com/collections/food-allergies" class="db pv1">Food Allergies</a>
                                    <a href="https://store.goharvey.com/collections/stress-anxiety" class="db pv1">Stress &amp; Anxiety</a>
                                    <a href="https://store.goharvey.com/collections/fatigue" class="db pv1">Fatigue</a>
                                    <a href="https://store.goharvey.com/collections/weight-loss-gain" class="db pv1">Weight Loss/Gain</a>
                                    <a href="https://store.goharvey.com/collections/weight-loss-gain" class="db pv1">Women's Health</a>
                                    <a href="https://store.goharvey.com/collections/general-health" class="db pv1">General Health</a>
                                </div>
                            </Accordian>
                        </div>
                        <div :slot="3">
                            <Accordian :stop="'l'">
                                <span :slot="'trigger'" class="ttu fw5 white">Account</span>
                                <div :slot="'content'">
                                    <a href="/login" class="db pv1">Login</a>
                                    <a href="/get-started" class="db pv1">Signup</a>
                                </div>
                            </Accordian>
                            <div class="mt4 tl">
                                <SocialIcons isBranded :brands="['facebook', 'twitter', 'instagram', 'youtube']" />
                            </div>
                        </div>
                    </Grid>
                </div>
            </Grid>
        </div>
        <div class="break"></div>
        <div class="footer-content mha pa2 pa4-m pa4-l">
            <p>Copyright &copy;2018 Harvey Health, Inc. All Rights Reserved. Harvey does not diagnose, treat, cure or prevent any disease.</p>
            <p>These statements have not been evaluated by the Food and Drug Administration (FDA).</p>
        </div>
    </footer>
</template>

<script>
import { LogoIcon, SocialIcons } from 'icons';
import { Accordian, Grid, Spacer } from 'layout';

export default {
  components: {
      Accordian,
      Grid,
      LogoIcon,
      SocialIcons,
      Spacer
  }
};
</script>

<style lang="scss" scoped>

    @import '~sass';

    // Styled to match the footer on store.goharvey.com

    .footer-container {

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

        .login &,
        .page-404 &,
        .password-reset & {
            display: none;
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
        border-bottom: 2px solid #191919;
    }

</style>
