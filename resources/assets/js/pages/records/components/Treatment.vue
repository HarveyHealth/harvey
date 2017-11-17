<template>
    <div class="records-container">
        <div class="treatment-plan">
            <textarea disabled v-model="plan" class="input--textarea soap-textarea" />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        patient: Object,
    },
    computed: {
        plan() {
            return this.$parent.propData.attributes.plan;
        }
    },
    watch: {
        plan(val) {
            if (!val) {
                return this.$parent.propData.attributes.plan;
            }
        }
    },
};
</script>

<style lang="scss">
    .treatment-plan {
        padding: 20px 10px;
    }
</style>
