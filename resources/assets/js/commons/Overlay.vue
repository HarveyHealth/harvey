<template>
  <div @click="onClick ? onClick() : false" :class="{ overlay:true, isactive: active }"></div>
</template>

<script>
export default {
    props: {
    // Whether the overlay is visible or not
        active: Boolean,
        // What happens when the overlay is clicked
        onClick: Function
    }
};
</script>
