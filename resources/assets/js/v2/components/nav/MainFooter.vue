<template>
  <div id="footer">

    <div v-if="State('conditions.condition.slug') == 'skin-issues'" class="background-fade skin-issues"></div>
    <div v-else-if="State('conditions.condition.slug') == 'food-allergies'" class="background-fade food-allergies"></div>
    <div v-else-if="State('conditions.condition.slug') == 'stress-anxiety'" class="background-fade stress-anxiety"></div>
    <div v-else-if="State('conditions.condition.slug') == 'digestive-issues'" class="background-fade digestive-issues"></div>
    <div v-else-if="State('conditions.condition.slug') == 'fatigue'" class="background-fade fatigue"></div>
    <div v-else-if="State('conditions.condition.slug') == 'weight-loss-gain'" class="background-fade weight-loss-gain"></div>
    <div v-else-if="State('conditions.condition.slug') == 'womens-health'" class="background-fade womens-health"></div>
    <div v-else-if="State('conditions.condition.slug') == 'general-health'" class="background-fade general-health"></div>
    <div v-else-if="State('conditions.condition.slug') == 'general-health'" class="background-fade"></div>

    <div class="pa5 tc">
      <!-- Instagram -->
      <a class="link dim gray dib h2 w2 br-100 mr3" href="https://www.instagram.com/go_harvey" target="_blank">
        <i class="fa fa-instagram color-white font-xxl" aria-hidden="true"></i>
      </a>
      <!-- Facebook -->
      <a class="link dim gray dib h2 w2 br-100 mr3" href="https://www.facebook.com/goharveyapp" target="_blank">
        <i class="fa fa-facebook color-white font-xxl" aria-hidden="true"></i>
      </a>
      <!-- YouTube -->
      <a class="link dim gray dib br-100 h2 w2 mr3" href="https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw" target="_blank">
        <i class="fa fa-youtube color-white font-xxl" aria-hidden="true"></i>
      </a>
      <!-- Twitter -->
      <a class="link dim gray dib br-100 h2 w2 mr3" href="https://twitter.com/goharveyapp" target="_blank">
        <i class="fa fa-twitter color-white font-xxl" aria-hidden="true"></i>
      </a>
      <!-- Medium -->
      <a class="link dim gray dib br-100 h2 w2 mr3" href="https://blog.goharvey.com" target="_blank">
        <i class="fa fa-medium color-white font-xxl" aria-hidden="true"></i>
      </a>
      <div class="mt4 font-md color-white">
        <a href="/" class="dim pa1">Home</a>
        <a href="/about" class="dim pa1">About</a>
        <a href="/lab-tests" class="dim pa1">Labs</a>
        <a href="/#conditions" class="dim pa1">Conditions</a>
        <a href="/financing" class="dim pa1">Financing</a>
        <a href="/blog" class="dim pa1">Blog</a>
        <a href="http://help.goharvey.com" class="dim pa1">Help</a>
      </div>
      <div class="mt4 color-white">
        <p class="font-sm copyright">&copy;2018 Harvey Services, Inc. All Rights Reserved.<br/>
        Harvey does not offer medical advice, diagnosis or treatment.<br/>
        Built with <i class="fa fa-heart" aria-hidden="true"></i> in Los Angeles. Read <a href="http://help.goharvey.com/legal/terms" class="is-hidden-mobile dim legal">Terms</a> and <a href="http://help.goharvey.com/legal/privacy" class="is-hidden-mobile dim legal">Privacy</a>.</p>
      </div>
    </div>
  </div>
</template>

<script>
  export default {};
</script>

<style>

  .footer {
    min-height: 100px;
  }

  .background-fade {
    width: 100%;
    height: 100%;
  }

  .color-white a {
    color: #FFF;
  }

  .copyright {
    line-height: 20px;
  }

  .fa-heart {
    color: orangered;
  }

  a.legal {
    color: #FFF;
    text-decoration: underline;
  }

</style>
