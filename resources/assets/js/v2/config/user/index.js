export default function(laravel) {
  return {
    info: laravel.user,
    intakeLink: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${laravel.user.id}`,
    isAdmin: laravel.user.user_type === 'admin',
    isLoggedIn: laravel.user.signedIn,
    isPatient: laravel.user.user_type === 'patient',
    isPractitioner: laravel.user.user_type === 'practitioner',
  }
}
