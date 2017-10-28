<template>
  <div>
    hi
  </div>
</template>

<script>
export default {
  name: 'dashboard',
  mounted() {
    App.setState('misc.currentPage', 'dashboard');
  }
}
</script>
