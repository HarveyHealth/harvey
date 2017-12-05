export default function(name) {
    const steps = this.State('getstarted.signup.steps');
    const completed = this.State('getstarted.signup.stepsCompleted');
    const index = steps.indexOf(name);

    if (completed < index + 1) {
        App.Router.push({ path: `/${steps[completed]}` });
    }
}
