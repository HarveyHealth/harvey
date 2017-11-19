<template>
  <div id="sub-footer">
    <div class="tc w-100 pa5 ph5-ns">
      <h2 class="font-xl font-thin w-50 margin-0a">Not ready to sign up? Subscribe to our newsletter.</h2>
      <div class="w-50" id="email-capture">
        <form>
<!--             <input type="text" name="_gotcha" style="display: none">
            <input type="email" name="email" v-model="guestEmail" placeholder="Personal Email" :disabled="emailCaptureSuccess">
            <button type="submit" class="button is-primary" @click.prevent="onEmailCaptureSubmit" :disabled="emailCaptureSuccess">Send Now</button>
            <div v-if="!emailCaptureSuccess" :class="emailCaptureClasses" v-text="emailCaptureError"></div>
            <div v-if="emailCaptureSuccess" class="success-text">Success! Check your email to download.</div> -->
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

</style>
