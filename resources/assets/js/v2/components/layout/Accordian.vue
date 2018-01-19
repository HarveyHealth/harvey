<template>
    <div :class="containerStyles">
        <div class="pv2 accordian-trigger" @click="isOpen = !isOpen">
            <slot :name="'trigger'"></slot>
            <i class="fa fa-chevron-down white" />
        </div>
        <div class="accordian-wrapper">
            <slot :name="'content'"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // The breakpoint you want the accordian to stop being an accordian
        stop: String
    },
    data() {
        return {
            isOpen: false
        };
    },
    computed: {
        containerStyles() {
            return {
                'is-open': this.isOpen,
                'overflow-hidden': true,
                [`stop-at-${this.stop}`]: this.stop
            };
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    @mixin stop-at {
        .accordian-trigger { cursor: default; }
        .accordian-trigger i { display: none; }
        .accordian-wrapper { max-height: 20em; }
    }

    .accordian-trigger {
        cursor: pointer;
        position: relative;

        i {
            font-size: 10px;
            right: 0;
            position: absolute;
            top: 10px;
            transition: transform .4s;

            .is-open & {
                transform: rotate(.5turn);
            }
        }
    }

    .accordian-wrapper {
        max-height: 0;
        transition: max-height .4s;

        .is-open & {
            max-height: 20em;
        }
    }

    .stop-at-s {
        @include query(sm) { @include stop-at; }
    }
    .stop-at-m {
        @include query(md) { @include stop-at; }
    }
    .stop-at-l {
        @include query(lg) { @include stop-at; }
    }
    .stop-at-xl {
        @include query(xl) { @include stop-at; }
    }
    .stop-at-xxl {
        @include query(xxl) { @include stop-at; }
    }
</style>
