export default function(response) {
  App.setState('conditions.zipValidation', response.data);
  App.setState('isLoading.zip', false);
}
