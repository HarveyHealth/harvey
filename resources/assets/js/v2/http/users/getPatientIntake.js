export default function(id, response) {
    if (!id || typeof id !== 'string') {
        console.error('id is required and must be a string');
        return;
    }

    App.State.users.intake.isLoading = true;
    App.State.users.intake.wasRequested = true;

    axios.get(`${App.Config.misc.api}users/${id}?include=patient.intake`)
        .then(r => {
            const intakeData = r.data.included.filter(inc => inc.type === 'intake');

            if (intakeData.length) {
                App.State.users.intake.data.self = intakeData[0];
            }

            App.State.users.intake.isLoading = false;

            if (response) response(r);
        })
        .catch(error => {
            if (error.response) {
                console.warn(error.response);
            }
        });
}
