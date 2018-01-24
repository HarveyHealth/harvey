export default function(signup) {
    // If signup localStorage does not exist but the user has hit the welcome
    // step it means they came from the login page and have already created
    // an account but have not finished the funnel. Skip.
    // If account is created and the logged email is the same as the stored
    // email, the same user is logging in and should skip tracking.
    // If account_created has a different email it means a new user is signing
    // up on the same browser/machine and the user should be tracked.
    if (!signup) return null;
    if (signup.account_created === App.Config.user.info.email) return null;

    analytics.alias(App.Config.user.info.id);
    analytics.track('Account Created');
    analytics.identify(App.Config.user.info.id, {
        firstName: App.Config.user.info.first_name,
        lastName: App.Config.user.info.last_name,
        email: App.Config.user.info.email,
        city: App.Config.user.info.city,
        state: App.Config.user.info.state,
        zip: App.Config.user.info.zip
    }, {
        integrations: {
            Intercom : {
                user_hash: App.Config.user.info.intercom_hash
            }
        }
    });
    if (signup.facebook_connect) {
        analytics.track('Facebook Connect Signup');
    }
    App.Util.data.updateStorage('signup_mode', {
        account_created: App.Config.user.info.email,
        facebook_connect: false
    });
}
