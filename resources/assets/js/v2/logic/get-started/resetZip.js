export default function() {
    App.Util.data.killStorage('zip_validation');
    App.setState({
        'wasRequested.zip': false,
        'getstarted.zipValidation': null
    });
}
