export default function(message) {
  if (App.Config.misc.debug) {
    console.log(`==>  ${message}`);
    return more => console.log(more);
  } else {
    return () => null;
  }
}
