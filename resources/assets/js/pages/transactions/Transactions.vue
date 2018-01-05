<template>
    <div class="main-container">
        <div class="main-content">
            <div class="main-header">
                <div class="container container-backoffice">
                    <h1 class="heading-1">
                        <span class="text">Transactions</span>
                    </h1>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: 'transactions',
        components: {},
        data() {
            return {

            };
        },
        computed: {

        },
        watch: {

        },
        mounted() {
            this.$root.$data.global.currentPage = 'transactions';
        }
    };
</script>