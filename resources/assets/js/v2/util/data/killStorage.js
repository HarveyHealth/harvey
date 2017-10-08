export default function(key) {
  if (typeof key === 'string') {
    return localStorage.removeItem(`harvey_${key}`);
  } else if (Array.isArray(key)) {
    key.forEach(k => localStorage.removeItem(`harvey_${k}`));
  }
}
