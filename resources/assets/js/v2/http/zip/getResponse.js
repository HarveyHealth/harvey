export default function(response) {
  App.setState('conditions.zipValidation', response.data);
  App.setState('isLoading.zip', false);
  App.Util.data.killStorage('zip_validation');

  if (response.data.is_serviceable) {
    // zip_validation will be checked on get-started. If it does not exist
    // the user will be pushed back out to the homepage.
    App.Util.data.toStorage('zip_validation', JSON.stringify(response.data));
    window.location.href = '/get-started';

  } else {
    App.setState('conditions.invalidZip', true);
    if (App.Logic.misc.shouldTrack()) {
      analytics.track('Account Failed', {
        city: response.data.city,
        state: response.data.state,
        zip: response.data.zip
      });
    }
  }
}
