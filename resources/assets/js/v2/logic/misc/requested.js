// Indicates that a particular set of data was already requested
export default function(data) {
  App.setState(`wasRequested.${data}`, true);
}
