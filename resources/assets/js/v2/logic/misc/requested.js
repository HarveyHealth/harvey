// Indicates that a particular set of data was already requested
export default function(data) {
  const State = this.$root.$data.State;

  State.wasRequested[data] = true;
}
