export default function(response) {
  // Add user data associated with each appointment to the appointment object
  const appointments = App.Logic.appointments.combineUserData(response.data).reverse();

  // Filter appointment lists
  App.State.data.appointments.all = appointments;
  App.State.data.appointments.recent = App.Logic.appointments.filterRecent(appointments);
  App.State.data.appointments.upcoming = App.Logic.appointments.filterUpcoming(appointments);
  App.State.data.appointments.displayed = App.State.data.appointments.all;

  // Find updated row
  // App.Fn.diffAppointmentRows();

  // Update state for appointments page
  // App.Fn.closeAppointmentFlyout();

  // Mark as done
  App.State.received.appointments = true;
  App.Util.misc.debug('Response: GET appointments')(App.State.data.appointments);
}
