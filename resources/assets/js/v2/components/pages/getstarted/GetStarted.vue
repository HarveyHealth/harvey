<template>
    <div>
        <PublicNav v-if="!isSignupForm" disableMobile giveSpace hasLogo hasPhone />
        <div class="bg-blue-fade"></div>
        <ProgressBar
            v-show="State('getstarted.signup.showProgress')"
            class="get-started-progress mha mt2 mw6 ph3"
            :progress="stepsCompleted"
            :total="State('getstarted.signup.steps').length"
        />
        <router-view />
    </div>
</template>

<script>
import { ProgressBar } from 'feedback';
import { PublicNav } from 'nav';

export default {
    name: 'get-started',
    components: {
        ProgressBar,
        PublicNav
    },
    computed: {
        // Number of steps completed equals the number of true values in stepsCompleted.
        // Create array of stepsCompleted keys and filter based on truthiness; grab length.
        stepsCompleted() {
            const steps = this.State('getstarted.signup.stepsCompleted');
            return Object.keys(steps).filter(step => steps[step]).length;
        },
        // We don't want to show the PublicNav on the signup form page
        isSignupForm() {
            return App.Router.history.current.name === 'sign-up';
        }
    },
    beforeMount() {
        // The blade template has determined that this user is logged in or that they have a zip validation object
        // If the user is logged in but does NOT have a zip validation object, it means they may have used a different
        // device to log in but have not finished the signup funnel. In this case, we set a temp zip validation
        // object so the user cannot go to the intake page.
        const zipValidation = App.Logic.getstarted.getZipValidation();
        if (!zipValidation) {
            App.Util.data.toStorage('zip_validation', 'temp');
        } else {
            App.setState({
                'getstarted.zipValidation': zipValidation,
                'getstarted.userPost.zip': zipValidation.zip
            });
      }
    },
    mounted() {
        // If the user is logged in it means they'll be taken to the funnel proper. We want
        // to make sure we warn them their information will be reset if they leave the page.
        if (App.Config.user.isLoggedIn) {
            window.onbeforeunload = () => {
                return 'All your information will be reset.';
            };
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .button {
        border-width: 1px;
        color: $color-white;
        float: right;
        font-weight: 400;
        margin: 24px 24px 0;
    }

    .height-100 {
        height: calc(100% - 100px);
    }

    .get-started-progress {
        color: rgba(255, 255, 255, 0.5);
    }
</style>
