export default function(response) {
  if (!response || typeof response !== 'function') {
    console.error('response is required and must be a function');
    return;
  }

  App.Util.misc.debug('GET: practitioners?include=user');

  axios.get(`${App.Config.misc.api}practitioners?include=user`)
    .then(r => response(r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    })
}
