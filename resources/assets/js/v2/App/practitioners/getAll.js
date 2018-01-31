import filterByLicense from './filterByLicense';

export default function(responseHandler) {
    Util.startFetch('allPractitioners');

    axios.get(`${Store.API}practitioners?include=user`)
        .then(res => {
            // Set all
            Store.practitioners.all = res.data.data;

            // Get licensed by filter if patient
            Store.practitioners.licensed = Store.isPatient
                ? filterByLicense(res.data.data, Store.user.state)
                : res.data.data;

            // Conclude loading
            Store.isLoading.allPractitioners = false;

            // Populate index
            res.data.data.forEach(dr => {
                Store.practitioners.index[dr.id] = dr;
            });

            // Invoke callback
            if (responseHandler) responseHandler(r);
        })
        .catch(error => {
            if (error.response) {
                console.warn(error.response);
            }
        });
}