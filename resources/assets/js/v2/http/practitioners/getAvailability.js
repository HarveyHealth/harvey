export default function(id, cb) {
    if (!id || typeof id !== 'string') {
        console.error('id is required and must be a string');
        return;
    }

    App.setState({
        'practitioners.availability.isLoading': true,
        'practitioners.availability.wasRequested': true
    });

    axios.get(`${App.Config.misc.api}practitioners/${id}?include=availability`)
        .then(response => {
            const userType = App.Config.user.info.user_type;
            const payload = response.data.meta.availability;
            const availability = App.Logic.practitioners.transformAvailability(payload, userType);
            App.setState({
                'practitioners.availability.isLoading': false,
                'practitioners.availability.data': availability
            });
            if (cb) cb(availability);
        })
        .catch(error => {
            if (error.response) {
                console.warn(error.response);
            }
    });
}
