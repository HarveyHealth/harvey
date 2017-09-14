<template>
  <div class="Alert">
    <div class="Row align-middle gutter-sm">
      <div class="Column-lg-8 space-children-xs">
        <h2 class="heading-2">Patient Intake Form</h2>
        <p>Please note: You must finish your patient intake form before your first appointment.</p>
      </div>
      <div class="Column-lg-4 ow-margin-bottom-0">
        <a :href="Config.user.intakeLink" class="button-light-inverse" target="_blank">Edit Intake Form</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>
