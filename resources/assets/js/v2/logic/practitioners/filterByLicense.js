// Returns a list of viable practitioners given the specified user's state.
// If the user's state is regulated, it checks the practitioners' licenses
// to filter out non matches. If the user's state is unregulated, it returns
// all available practitioners.
export default function(practitioners, userState) {
  const isRegulated = App.Config.misc.regulatedStates.indexOf(userState) > -1;

  if (isRegulated) {
    return practitioners.filter(practitioner => {
      const licenses = practitioner.attributes.licenses;
      const valid = [{ where: 'state', is: userState }];
      return App.Util.data.find(licenses, valid);
    });
  }
  return practitioners;
}
