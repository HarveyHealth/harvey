export default function(name) {
    const steps = App.State.getstarted.signup.steps;
    const index = steps.indexOf(name);
    const next = steps[index + 1];

    App.State.getstarted.signup.stepsCompleted[name] = true;

    App.Router.push({ path: `/${next}` });
}
