// This function blocks a user from loading a signup step prior to
// completing the steps before it. Since it calls the State get method
// it must be invoked using apply, bind, or call. This should be done
// on a component's beforeMount hook.
//   name (string) = the name of the component to bar
export default function(name) {
    const isComplete = this.State('getstarted.signup.hasCompletedSignup');

    // Route to or away from 'success' depending on completion
    if (!isComplete && name === 'success') {
        App.Router.push({ path: '/confirmation' });
    } else if (isComplete && name !== 'success') {
        App.Router.push({ path: '/success'});
    }

    const steps = this.State('getstarted.signup.steps');
    const completed = this.State('getstarted.signup.stepsCompleted');
    const index = steps.indexOf(name);

    const incompleteSteps = steps.filter((step, i) => {
        const isPrevious = i < index;
        const isNotComplete = !completed[step];

        return isPrevious && isNotComplete;
    });

    // If incomplete steps exist, route to first incomplete step
    if (incompleteSteps.length) {
        App.Router.push({ path: `/${incompleteSteps[0]}` });
    } else {
        return false;
    }
}
