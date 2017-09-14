export default function(response) {
  if (!response || typeof response !== 'function') {
    console.error('response is required and must be a function');
    return;
  }

  App.Util.misc.debug('GET: appointments?include=patient.user');
  App.Logic.misc.requested('appointments');

  axios.get(`${App.Config.misc.api}appointments?include=patient.user`)
    .then(r => response(r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    })
}
