export default function(doctors) {
  doctors.some(data => {
    if (data.attributes.name === App.Config.user.info.doctor_name) {
      App.setState('practitioners.userDoctor', data);
      return true;
    }
  });
}
