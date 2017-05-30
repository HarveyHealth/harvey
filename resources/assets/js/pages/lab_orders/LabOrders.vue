<template>
    <div class="main-container">
        <UserNav />
        <div class="main-content">
            <div class="main-header">
                <div class="container">
                    <h1 class="title header-xlarge">
                    <span class="text">Lab Orders</span>
                    <button v-on:click="" class="button main-action circle">
                        <svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#addition"></use></svg>
                    </button>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import UserNav from '../../commons/UserNav.vue'
    export default {
        name: 'LabOrders',
        components: {
            UserNav
        },
        data() {
            return {

            }
        },
        methods: {

        },
        computed: {

        },
        mounted: {

        }
    }
</script>

<style>

</style>