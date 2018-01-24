export default function(response) {
  // Turn off loading indicator
  App.setState('isLoading.zip', false);
  // Whatever the response may be, we always set local storage item.
  // The local storage will be removed at other points in the funnel's logic
  App.Util.data.toStorage('zip_validation', JSON.stringify(response.data));
  App.setState('getstarted.zipValidation', response.data);

  if (!response.data.is_serviceable) {
    analytics.track('Account Failed', {
      city: response.data.city,
      state: response.data.state,
      zip: response.data.zip
    });
  }
}
