<template>
  <ul class="calendar-week-container_days-wrapper">
    <li class="calendar-item"><button class="calendar-item_link">Mon</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Tue</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Wed</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Thu</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Fri</button></li>
    <li class="calendar-item"><button class="calendar-item_link">Sat</button></li>
    <li class="calendar-item selected"><button class="calendar-item_link">Sun</button></li>
  </ul>
</template>

<script>
  export default {
    name: 'DatePicker',
  }
</script>
