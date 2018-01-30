export default function(doctors) {
  doctors.some(data => {
    if (data.attributes.name === App.Config.user.info.doctor_name) {
      App.State.practitioners.userDoctor = data;
      return true;
    }
  });
}
