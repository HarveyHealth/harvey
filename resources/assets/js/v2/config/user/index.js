export default function(laravel) {
  return {
    info: laravel.user,
    isAdmin: laravel.user.user_type === 'admin',
    isPatient: laravel.user.user_type === 'patient',
    isPractitioner: laravel.user.user_type === 'practitioner',
  }
}
