export default {
  bind(el, binding, vnode) {
    el.addEventListener('input', (event) => {
      // User can only input digits
      let digits = el.value.replace(/[^\d]/g,'');
      // Only 10 digits accepted
      if (digits.length > 10) {
        digits = digits.substring(0,10);
      }
      // Update the Vue component data
      vnode.context.phone = digits;
      // Phone input mask format: (###) ###-####
      if (digits.length > 6) {
        el.value = digits.replace(/(\d{3})(\d{3})(\d+)/,'($1) $2-$3');
      } else if (digits.length > 3) {
        el.value = digits.replace(/(\d{3})(\d+)/,'($1) $2');
      } else {
        el.value = digits;
      }
    });
  }
}
