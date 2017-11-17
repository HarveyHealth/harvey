<template>
  <div class="footer">
    <div class="pa5 ph5-ns tc">
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
  import { Util } from '../base';
  export default {}
</script>

<style>

  .footer {
    min-height: 100px;
    background-image: linear-gradient(120deg, #82BEF2 0%, #BDCEBE 40%);
  }

  .color-white a {
    color: #FFF;
  }

  .copyright {
    line-height: 20px;
  }

  .fa-heart {
    color: #E3BAB3;
  }

  a.legal {
    color: #FFF;
    text-decoration: underline;
  }

</style>
