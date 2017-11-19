<template>
  <div id="sub-footer">
    <div class="tc w-100 pa5">
      <h2 class="font-xl font-thin w-50 margin-0a">Not ready to sign up? Subscribe to our newsletter.</h2>
      <div class="pa2-l">
        <form class="mw7 pa2 center">
          <fieldset class="cf bn">
            <div class="cf">
              <label class="clip" for="email-address">Email Address</label>
              <input class="font-lg input-reset bn fl pa3 lh-solid w-75-m w70-l br2 mr2 gray" placeholder="Your Email Address" type="text" name="email-address" value="" id="email-address">
              <input class="f6 f5-l fl pv3 mt2 tc bn dim white w-25-m w-30-l submit" type="submit" value="Subscribe">
            </div>
          </fieldset>
        </form>
      </div>

    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        button: '<span style="font-size:20px; padding-right:16px;">Start Quiz</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>'
      }
    }
  }
</script>

<style>

  #sub-footer {
    min-height: 100px;
    background-image: linear-gradient(120deg, #FFFFFF 0%, #F9F9F9 40%);
  }

  .submit {
    margin-top: 8px;
  }

</style>
