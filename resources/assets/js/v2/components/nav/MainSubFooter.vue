<template>
    <div id="sub-footer">
        <div class="tc pv5 ph3">
            <div class="w-100 w-80-ns w-50-l margin-0a">
                <Heading2>Not ready to sign up? Subscribe to our newsletter.</Heading2>
            </div>
            <div class="pa2-l">
                <form class="mw7 pa2 center">
                    <fieldset class="cf bn">
                        <div class="cf">
                            <input type="text" name="_gotcha" style="display: none">
                            <label class="clip" for="email-address">Email Address</label>
                            <input
                                class="font-lg input-reset bn fl pa3 w-100 w-75-l br2 gray"
                                :disabled="emailCaptureSuccess"
                                id="email-address"
                                name="email-address"
                                placeholder="Your Email Address"
                                type="text"
                                v-model="guestEmail"
                            />
                            <input
                                class="w-25 mt3 mt1-l submit"
                                type="submit"
                                value="Subscribe"
                                v-if="!emailCaptureSuccess"
                                @click.prevent="handleEmailCapture"
                            />
                            <SlideIn v-if="emailCaptureSuccess">
                                <span class="dark-gray dib mt3" v-if="emailCaptureSuccess">Success! Check your email to download.</span>
                            </SlideIn>
                            <div :class="emailCaptureClasses">{{ emailCaptureFeedback }}</div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Heading2 } from 'typography';
import { SlideIn } from 'layout';

 export default {
     components: {
         Heading2,
         SlideIn
     },
     data() {
         return {
             button: '<span style="font-size:20px; padding-right:16px;">Start Quiz</span><i class="fa fa-chevron-right" style="font-size: 14px"></i>',
             emailCaptureClasses: {
                 'tc': true,
                 'mt2': true,
                 'error-text': true,
                 'is-visible': false
             },
             emailCaptureError: 'Not a valid email address',
             emailCaptureFeedback: '',
             emailCaptureSuccess: false,
             guestEmail: ''
         };
     },
     methods: {
         handleEmailCapture() {
             this.emailCaptureClasses['is-visible'] = false;
             const isPassing = (/[^@]+@\w+\.\w{2,}/).test(this.guestEmail);

             if (isPassing) {
                 const visitorData = {
                     to: this.guestEmail,
                     template: 'subscribe',
                     _token: Laravel.app.csrfToken
                 };

                 axios.post('/api/v1/visitors/send_email', visitorData).then(() => {
                     this.emailCaptureSuccess = true;

                     analytics.identify({
                         email: this.guestEmail
                     });
                 }).catch(error => {
                     this.emailCaptureFeedback = error.response.status === 429
                        ? 'Oops, we\'ve already registered that email.'
                        : 'Oops, error sending email. Please contact support.';
                     this.emailCaptureClasses['is-visible'] = true;
                 });
             } else {
                 this.emailCaptureFeedback = 'Oops, that is not a valid email address.';
                 this.emailCaptureClasses['is-visible'] = true;
             }
         }
     }
 };
</script>

<style lang="scss" scoped>

  #sub-footer {
    min-height: 100px;
    background-image: linear-gradient(120deg, #F2F2F2 0%, #F2F2F2 40%);
    border: 1px solid #EEE;
  }

  .error-text {
      display: none;
  }

  .is-visible {
      display: block;
  }

</style>
