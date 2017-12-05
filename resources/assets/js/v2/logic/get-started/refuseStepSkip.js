// This function blocks a user from loading a signup step prior to
// completing the steps before it. Since it calls the State get method
// it must be invoked using apply, bind, or call. This should be done
// on a component's beforeMount hook.
//   name (string) = the name of the component to bar
export default function(name) {
    const steps = this.State('getstarted.signup.steps');
    const completed = this.State('getstarted.signup.stepsCompleted');
    const index = steps.indexOf(name);

    if (completed < index + 1) {
        App.Router.push({ path: `/${steps[completed]}` });
    }
}
