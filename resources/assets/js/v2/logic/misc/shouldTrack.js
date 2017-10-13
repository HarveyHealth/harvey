// abstracting this into a separate method in case environment dependencies change
export default function() {
  return App.Config.isProduction;
}
