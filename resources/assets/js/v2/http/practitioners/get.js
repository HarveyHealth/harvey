// Must be run with .call to pass in Vue component instance as the context
export default function(response) {
  if (!response || typeof response !== 'function') {
    console.error('response is required and must be a function');
    return;
  }

  App.Logic.misc.requested.call(this, 'practitioners');

  axios.get(`${App.Config.misc.api}practitioners?include=user`)
    .then(r => response.call(this, r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    })
}
