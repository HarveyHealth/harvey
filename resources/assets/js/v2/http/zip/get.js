export default function(zip, response) {
  if (!zip || typeof zip !== 'string') {
    console.error('zip is required and must be a string');
    return;
  }
  if (!response || typeof response !== 'function') {
    console.error('response is required and must be a function');
    return;
  }

  App.State.wasRequested.zip = true;
  App.State.isLoading.zip = true;

  axios.get(`${App.Config.misc.api}visitors/verifications/zip/${zip}`)
    .then(r => response(r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    });
}
