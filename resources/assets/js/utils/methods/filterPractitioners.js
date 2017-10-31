// Filters practitioner list by state licensing regulations
// practitioners = practitioner list from backend or from appointments page
// state = user state to test against
export default function(practitioners, state) {
  return practitioners.filter(practitioner => {
    // First check if the user's state is regulated or not
    const userRegulatedState = this.global.regulatedStates.indexOf(state) > -1;
    // Get licenses from global list or from appointments page list
    const licenses = practitioner.attributes ? practitioner.attributes.licenses : practitioner.data.info.licenses;
    // If the user's state is regulated, filter dr list for drs with licenses in that state
    return userRegulatedState
      ? licenses.filter(license => license.state === state).length
      : true
  })
}
