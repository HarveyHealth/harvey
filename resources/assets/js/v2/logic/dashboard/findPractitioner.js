// Extracts client's practitioner from the list of practitioners based
// on the practitioner name.
//  name = Laravel.user.doctor_name
export default function(name) {
  if (!name || typeof name !== 'string') {
    console.error('name is required and must be a string');
    return;
  }

  return App.Util.find(App.State.data.practitioners.licensed, [
    { path: 'attributes.name', resolve: name }
  ]);
}
