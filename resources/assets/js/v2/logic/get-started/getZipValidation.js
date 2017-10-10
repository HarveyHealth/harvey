export default function() {
  const zipValidation = App.Util.data.fromStorage('zip_validation');
  return zipValidation ? JSON.parse(zipValidation) : null;
}
