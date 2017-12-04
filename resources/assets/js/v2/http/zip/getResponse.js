export default function(response) {
  // Turn off loading indicator
  App.setState('isLoading.zip', false);
  // Whatever the response may be, we always set local storage item.
  // The local storage will be removed at other points in the funnel's logic
  App.Util.data.toStorage('zip_validation', JSON.stringify(response.data));

  if (response.data.is_serviceable) {
    // zip_validation will be checked on get-started. If it does not exist
    // the user will be pushed back out to the homepage.
    window.location.href = '/get-started';

  } else {
    App.setState('conditions.invalidZip', true);
    App.setState('conditions.zipValidation', response.data);
    analytics.track('Account Failed', {
      city: response.data.city,
      state: response.data.state,
      zip: response.data.zip
    });
  }
}
