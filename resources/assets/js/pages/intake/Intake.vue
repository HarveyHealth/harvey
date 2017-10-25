<template>
  <div class="signup-wrapper" :class="animClasses">
    <div class="container">

      <div class="close-bar">
        <div class="icon-container">
          <a href="/dashboard" class="button--close button--close-large flyout-close page-close" aria-label="Close">
            <svg aria-hidden="true"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use></svg>
          </a>
        </div>
      </div>

      <div class="typeform-widget" :data-url="typeformUrl" data-transparency="50" data-hide-headers=true data-hide-footer=true style="width: 100%; height: 80vh;" ></div>
      <div style="font-family: Sans-Serif;font-size: 12px;color: #999;opacity: 0.5; padding-top: 5px;" >powered by <a href="https://www.typeform.com/examples/?utm_campaign=XGnCna&amp;utm_source=typeform.com-9034666-Pro&amp;utm_medium=typeform&amp;utm_content=typeform-embedded-poweredbytypeform&amp;utm_term=EN" style="color: #999" target="_blank">Typeform</a></div>
    </div>
  </div>
</template>

<style>
  .close-bar {
    text-align: right;
    padding: 2em 0;
    position: relative;
    vertical-align: middle;
  }

  .icon-container {
    display: inline-block;
    height: 40px;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
  }
    .icon-container .page-close {
      height: 0;
      left: 50%;
      padding: 1em;
      position: absolute;
      right: auto;
      top: 50%;
      transform: translate(-50%, -50%);
      width: 0;
    }
</style>

<script>
  export default {
    name: 'Intake',
    data() {
      return {
        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false
        },
        typeformUrl: `https://hello.typeform.com/to/XGnCna?harvey_id=${Laravel.user.id}&intake_validation_token=${Laravel.user.intake_validation_token}`
      }
    },
    mounted() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);

      // add Typeform script
      /* eslint-disable */
      (function() {
        var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm", b="https://embed.typeform.com/";
        if(!gi.call(d,id)) { js=ce.call(d,"script");
        js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q); }
      })();
      /* eslint-enable */
    },
    beforeDestroy() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
  };
</script>
