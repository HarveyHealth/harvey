<template>
  <div id="footer">
    <div class="tc pt3 pb3">
      <a href="/" class="nav-logo">
          <LogoIcon alwaysShowText hasDarkIcon hasDarkText />
      </a>
    </div>
    <div class="tc ph4 nav-links">
      <a class="dim dib br-100 h2 w2" href="https://www.instagram.com/go_harvey" target="_blank">
        <i class="fa fa-instagram font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://www.facebook.com/goharveyapp" target="_blank">
        <i class="fa fa-facebook font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw" target="_blank">
        <i class="fa fa-youtube font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://twitter.com/goharveyapp" target="_blank">
        <i class="fa fa-twitter font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://blog.goharvey.com" target="_blank">
        <i class="fa fa-medium font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://pinterest.com/go_harvey" target="_blank">
        <i class="fa fa-pinterest font-xxl" aria-hidden="true"></i>
      </a>
      <a class="dim dib br-100 h2 w2 ml1 ml2-l" href="https://t.me/ghrvy" target="_blank">
        <i class="fa fa-telegram font-xxl" aria-hidden="true"></i>
      </a>
      <div class="mt3 nav-links">
        <a href="/about" class="dim pa2 fw4">About</a>
        <a href="/lab-tests" class="dim pa2 fw4">Labs</a>
        <a href="/#conditions" class="dim pa2 fw4">Conditions</a><br class="d dn-l"/>
        <a href="/financing" class="dim pa2 fw4">Financing</a>
        <a href="/blog" class="dim pa2 fw4">Blog</a>
        <a href="http://help.goharvey.com" class="dim pa2 fw4">Help</a>
      </div>
      <div class="mt4 w-100 w-60-ns margin-0a">
        <p class="font-xs copyright fw4 pb4">
          &copy;2017 Harvey Services, Inc. <span class="dn dib-l">We reserve rights.</span><br/>
          <span class="dn dib-l">Harvey does not provide medical advice, diagnosis or treatment.</span><br class="dn dib-l"/>
          <span class="dn dib-l">Built with <i class="fa fa-heart" aria-hidden="true"></i> in Los Angeles.</span> Read <a href="http://help.goharvey.com/legal/terms" class="dim legal">Terms</a> and <a href="http://help.goharvey.com/legal/privacy" class="dim legal">Privacy</a>.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { LogoIcon } from 'icons';

export default {
  components: {
      LogoIcon
  }
}
</script>

<style lang="scss" scoped>

  .footer {
    min-height: 100px;
  }

  .nav-logo svg {
    width: 170px;
    margin-top: 8px;
  }

  .background-fade {
    width: 100%;
    height: 100%;
  }

  .nav-links a {
    color: #5f7278;
  }

  .copyright {
    line-height: 20px;
  }

  .fa-heart {
    color: orangered;
  }

  a.legal {
    color: #5f7278;
    font-weight: 400;
    text-decoration: underline;
  }

</style>
