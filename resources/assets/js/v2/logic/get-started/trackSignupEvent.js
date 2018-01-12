export default function(email, first_name, last_name) {
    if (App.Config.isDevEnv) return null;

    window.datacoral('setUserId', email);
    window.datacoral('trackUnstructEvent', {
        name: 'harvey_sign_up',
        data: {
            formId: 'harvey_sign_up_form',
            formFields: { email, first_name, last_name }
        }
    });
}
