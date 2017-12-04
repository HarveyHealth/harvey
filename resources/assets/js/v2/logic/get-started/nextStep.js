export default function(name) {
    const next = this.State('getstarted.signup.steps').indexOf(name) + 1;
    return this.State('getstarted.signup.steps')[next];
}
