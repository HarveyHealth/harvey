export default function(response) {
  App.setState('conditions.zipValidation', response.data);
  App.setState('isLoading.zip', false);

  localStorage.removeItem('zip_validation');

  if (response.data.serviceable) {
    // zip_validation will be checked on get-started. If it does not exist
    // the user will be pushed back out to the homepage.
    localStorage.setItem('zip_validation', JSON.stringify(response.data));
    window.location.href = '/get-started';
  }
}
