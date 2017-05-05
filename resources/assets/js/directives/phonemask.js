export default {
  bind(el, binding, vnode) {
    // Need keydown listener to test which key is pressed
    el.addEventListener('keydown', event => {
      vnode.context.phoneKeydown = event.keyCode;
      vnode.context.phoneLength = vnode.context.phone.length;
      vnode.context.caretStart = event.target.selectionStart;
    });
    // Need input listener otherwise there is delay when adjusting format
    el.addEventListener('input', (event) => {

      // The number of digits after input event fires
      let digits = 0;
      // Get the key pressed
      const key = vnode.context.phoneKeydown;

      // If user presses a digit when the digits are full, do nothing
      // else, get the number of digits
      if (key !== 8 && key !== 46 && vnode.context.phone.length === 10) {
        digits = vnode.context.phone;
      } else {
        digits = el.value.replace(/[^\d]/g,'');
      }

      // Assign cursor position before and after input event
      const caretStart = vnode.context.caretStart;
      const caretEnd = event.target.selectionEnd;

      // Eventually will be set to whever the user will logically expect it to be after their input
      let caretSet = 0;

      // Log the prior length and assign new digit data
      vnode.context.phoneLength = vnode.context.phone.length;
      vnode.context.phone = digits;

      // Phone input mask format: (###) ###-####
      if (digits.length > 6) {
        el.value = digits.replace(/(\d{3})(\d{3})(\d+)/,'($1) $2-$3');
      } else if (digits.length > 3) {
        el.value = digits.replace(/(\d{3})(\d+)/,'($1) $2');
      } else {
        el.value = digits;
      }

      // The following logic determines where the caret should be repositioned
      // after the directive updates the input data

      // If keydown is Backspace
      if (key === 8) {
        if (caretStart === 10) {
          caretSet = 9;
        } else if (caretStart === 6 || caretStart === 5) {
          caretSet = 4;
        } else if (vnode.context.phoneLength === 4 && caretStart >= 2 && caretStart <= 4) {
          caretSet = caretStart - 1;
        } else if (caretStart === 1 && digits.length > 3) {
          caretSet = 1;
        } else {
          caretSet = caretEnd;
        }
      }
      // If keydown is digit
      if (key >= 48 && key <= 57) {
        if (vnode.context.phoneLength === 10) {
          caretSet = caretStart;
        } else if (vnode.context.phoneLength === 3 && caretStart <= 2) {
          caretSet = caretStart + 2;
        } else if (caretStart === 3 && digits.length === 4) {
          caretSet = 7;
        } else if (caretStart === 4 && digits.length >= 4) {
          caretSet = 7;
        } else if (caretStart === 9) {
          caretSet = 11;
        } else {
          caretSet = caretEnd;
        }
      }
      // If keydown is delete
      if (key === 46) {
        if (vnode.context.phoneLength === 4) {
          caretSet = caretStart - 1;
        } else {
          caretSet = caretStart;
        }
      }

      event.target.setSelectionRange(caretSet, caretSet);
    });
  }
}
