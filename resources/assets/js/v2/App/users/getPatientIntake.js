export default function(id, response) {
    if (!id || typeof id !== 'string') {
        console.error('id is required and must be a string');
        return;
    }

    Util.startFetch('userIntake');

    axios.get(`${Store.API}users/${id}?include=patient.intake`)
        .then(r => {
            const intakeData = r.data.included.filter(inc => inc.type === 'intake');
            
            if (intakeData.length) {
                Store.users.intake.self = intakeData[0];
            }

            Store.isLoading.userIntake = false;

            if (response) response(r);
        })
        .catch(error => {
            if (error.response) {
                console.warn(error.response);
            }
        });
}