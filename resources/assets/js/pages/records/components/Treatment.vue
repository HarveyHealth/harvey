<template>
    <div class="records-container">
        <div class="treatment-plan">
            <div 
                disabled 
                v-html="plan"
             />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        patient: Object
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
    }
};
</script>

<style lang="scss">
    .treatment-plan {
        padding: 20px 10px;
    }
</style>
