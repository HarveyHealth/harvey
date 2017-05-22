<template>
    <div>
        <aside :class="{ flyout: true, isactive: true }">
        
        </aside>
    </div>
</template>

<script>
    import Flyout from '../../../commons/Flyout.vue'
    export default {
        name: 'Preview',
        components: {
            Flyout
        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>

<style>

</style>