export default function(name) {
    const steps = 'getstarted.signup.steps';
    const index = this.State(steps).indexOf(name);
    const next = this.State(steps)[index + 1];

    App.setState(`getstarted.signup.stepsCompleted.${name}`, true);

    App.Router.push({ path: `/${next}` });
}
