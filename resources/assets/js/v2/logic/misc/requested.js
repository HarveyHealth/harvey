// Indicates that a particular set of data was already requested
export default function(data) {
  const Store = this.$root.$data.State;

  Store.wasRequested[data] = true;
}
