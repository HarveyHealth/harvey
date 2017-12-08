<template>
    <div>
        <PublicNav v-if="!isSignupForm" disableMobile giveSpace hasLogo hasPhone />
        <div class="bg-blue-fade"></div>
        <router-view />
    </div>
</template>

<script>
import { PublicNav } from 'nav';

export default {
    name: 'get-started',
    components: {
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
</style>
