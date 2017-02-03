<template>
    <div class="panel">
        <h2 class="panel-heading">Laboratory Tests</h2>

        <template v-if="recent_tests.length || pending_tests.length">
            <div v-for="test in pending_tests" class="panel-block">
                <Test
                    :test="test"
                >
                    <button class="button is-disabled">Pending</button>
                </Test>
            </div>
            
            <div v-for="test in recent_tests" class="panel-block">
                <Test
                    :test="test"
                >
                    <button class="button is-info">View Results</button>
                </Test>
            </div>
        </template>

        <div v-else class="panel-block">
            <p>There are no tests.</p>
        </div>
    </div>
</template>

<script>
    import Test from './Test.vue';

    export default {
        data() {
            return {
                pending_tests: [],
                recent_tests: []
            }
        },
        components: {
            Test
        },
        mounted() {
            this.$http.get('/api/dashboard').then(response => {
                this.pending_tests = response.data.pending_tests;
                this.recent_tests = response.data.recent_tests;
            });
        }
    }
</script>