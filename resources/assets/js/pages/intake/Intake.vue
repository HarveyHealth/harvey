<template>
  <div class="signup-wrapper" :class="animClasses">
    <div class="container">
      <div class="typeform-widget" data-url="https://kyleahill.typeform.com/to/XGnCna" data-transparency="50" data-hide-headers=true data-hide-footer=true style="width: 100%; height: 80vh;" ></div>
      <div style="font-family: Sans-Serif;font-size: 12px;color: #999;opacity: 0.5; padding-top: 5px;" >powered by <a href="https://www.typeform.com/examples/?utm_campaign=XGnCna&amp;utm_source=typeform.com-9034666-Pro&amp;utm_medium=typeform&amp;utm_content=typeform-embedded-poweredbytypeform&amp;utm_term=EN" style="color: #999" target="_blank">Typeform</a></div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Intake',
    data() {
      return {
        animClasses: {
          'anim-fade-slideup': true,
          'anim-fade-slideup-in': false,
        },
      }
    },
    mounted() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', true, 300);

      // add Typeform script
      (function() {
        var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm", b="https://embed.typeform.com/";
        if(!gi.call(d,id)) { js=ce.call(d,"script");
        js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) }
      })();
    },
    beforeDestroy() {
      this.$eventHub.$emit('animate', this.animClasses, 'anim-fade-slideup-in', false);
    }
  }
</script>
