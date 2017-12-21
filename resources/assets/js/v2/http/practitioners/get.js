export default function(response) {
  if (!response || typeof response !== 'function') {
    console.error('response is required and must be a function');
    return;
  }

  App.setState('practitioners.isLoading', true);
  App.setState('practitioners.wasRequested', true);

  const endpointUrl = App.Logic.practitioners.endpointUrl();

  if (endpointUrl) {
    axios.get(endpointUrl)
    .then(r => response(r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    });
  }
}
