export default function(userState) {
  const isRegulated = App.Config.misc.regulatedStates.indexOf(userState) > -1;
  console.log(isRegulated)
  return isRegulated
    ? App.State.data.practitioners.all.filter(practitioner => {
      return App.Util.data.find(practitioner.attributes.licenses, [
        { path: 'state', resolve: userState }
      ])
    })
    : App.State.data.practitioners.all;
}
