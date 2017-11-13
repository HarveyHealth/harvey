// abstracting this into a separate method in case environment dependencies change
export default function() {
  return Laravel.user.user_type === 'patient';
}
