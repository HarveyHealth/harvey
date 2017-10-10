export default function(key, value) {
  return localStorage.setItem(`harvey_${key}`, value);
}
