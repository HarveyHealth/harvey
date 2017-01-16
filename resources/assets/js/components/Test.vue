<template>
    <div class="media">
        <div class="media-left">
            <span class="box is-circle test-marker test-marker--type-mn">MN</span>
        </div>
        <div class="media-content">
            <p><strong>Micronutrient Blood Test</strong></p>
            <small>Ordered on {{test.created_at | datetime('ddd, MMM Do, YYYY') }}</small>
        </div>
        <div class="media-right">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['test'],
        data() {
            return {
            }
        },
        mounted() {
        }
    }
</script>

<style lang="sass" scoped>
    .test-marker {
        color: #fff;
    }

    .test-marker--type-mn {
        background-color: hsl(348, 100%, 61%);
    }
</style>