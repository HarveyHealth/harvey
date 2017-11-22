<template>
    <div
        v-if="State('conditions.condition.slug') == 'skin-issues'"
        class="background-fade skin-issues">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'food-allergies'"
        class="background-fade food-allergies">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'stress-anxiety'"
        class="background-fade stress-anxiety">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'digestive-issues'"
        class="background-fade digestive-issues">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'fatigue'"
        class="background-fade fatigue">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'weight-loss-gain'"
        class="background-fade weight-loss-gain">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'womens-health'"
        class="background-fade womens-health">
    </div>
    <div
        v-else-if="State('conditions.condition.slug') == 'general-health'"
        class="background-fade general-health">
    </div>
</template>

<script>
export default {
  name: 'background'
};
</script>
