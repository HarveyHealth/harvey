export default function(name) {
    const steps = 'getstarted.signup.steps';
    const completed = 'getstarted.signup.stepsCompleted';
    const index = this.State(steps).indexOf(name);
    const next = this.State(steps)[index + 1];

    if (this.State(completed) < index + 1) {
        App.setState(completed, this.State(completed) + 1);
    }

    App.Router.push({ path: `/${next}` });
}
