<template>
    <div id="sub-footer">
        <div class="tc w-100 pv5 ph3">
            <Heading2 class="w-100 w-80-ns w-50-l margin-0a">Not ready to sign up? Subscribe to our newsletter.</Heading2>
            <div class="pa2-l">
                <form class="mw7 pa2 center">
                    <fieldset class="cf bn">
                        <div class="cf">
                            <label class="clip" for="email-address">Email Address</label>
                            <input class="font-lg input-reset bn fl pa3 w-100 w-75-l br2 gray" placeholder="Your Email Address" type="text" name="email-address" value="" id="email-address">
                            <input class="w-25 mt3 mt1-l submit" type="submit" value="Subscribe">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Heading2 } from 'typography';

 export default {
     components: {
         Heading2
     }
 };
</script>

<style lang="scss" scoped>

  #sub-footer {
    min-height: 100px;
    background-image: linear-gradient(120deg, #F2F2F2 0%, #F2F2F2 40%);
    border: 1px solid #EEE;
  }

</style>
