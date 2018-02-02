export default function() {
    App.Util.data.killStorage('zip_validation');
    App.State.wasRequested.zip = false;
    App.State.getstarted.zipValidation = null;
}
