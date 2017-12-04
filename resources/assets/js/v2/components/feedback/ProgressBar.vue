<template>
    <div class="progress-container cf">
        <div class="progress-wrap">
            <div :class="barClasses" :style="{ width: percent }"></div>
        </div>
        <span class="progress-percent">{{ percent }}</span>
    </div>
</template>

<script>
export default {
    props: {
        progress: Number,
        total: Number
    },
    computed: {
        background() {
            return this.progress === this.total
                ? 'bg-success'
                : 'bg-blue';
        },
        barClasses() {
            return `progress-bar ${this.background}`;
        },
        percent() {
            return `${Math.round((this.progress / this.total) * 100)}%`;
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';

    .progress-container {
        position: relative;
    }

    .progress-wrap {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        height: 12px;
        float: left;
        overflow: hidden;
        width: calc(100% - 40px);
    }

    .progress-bar {
        height: 12px;
        transition: width 500ms ease-in-out, background 500ms ease-in-out;
        width: 0;
    }

    .progress-percent {
        bottom: 1px;
        float: right;
        font-size: 14px;
        text-align: right;
        position: relative;
        width: 40px;
    }
</style>
