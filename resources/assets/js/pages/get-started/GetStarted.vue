<template>
    <div>
        <PublicNav v-if="!isSignupForm" disableMobile giveSpace hasLogo hasPhone />
        <div class="bg-blue-fade"></div>
        <ProgressBar
            v-show="State('getstarted.signup.showProgress')"
            class="get-started-progress mha mt2 mw6 ph3"
            :progress="State('getstarted.signup.stepsCompleted')"
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
        isSignupForm() {
            return App.Router.history.current.name === 'sign-up';
        }
    },
    beforeMount() {
        const zipValidation = App.Logic.getstarted.getZipValidation();
        if (!zipValidation) {
            window.location.href = '/conditions';
        } else {
            App.setState({
                'getstarted.zipValidation': zipValidation,
                'getstarted.userPost.zip': zipValidation.zip
            });
      }
    },
    mounted() {
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
