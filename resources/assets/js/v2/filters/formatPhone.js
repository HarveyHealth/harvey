export default function(value) {
  return value.replace(/(\d{3})(\d{3})(\d{3})/, '$1-$2-$3');
}
