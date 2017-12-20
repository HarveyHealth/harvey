<template>
    <div>
        <button :class="{ 'avatar-button': true, 'is-active': isActive }" @click="onClick">
            <img alt=""  :src="image" class="avatar-image db" />
        </button>
        <Spacer isBottom :size="2" />
        <small v-if="caption" v-html="caption" class="db"></small>
    </div>
</template>

<script>
import { Spacer } from 'layout';

export default {
    props: {
        caption: String,
        image: String,
        isActive: Boolean,
        onClick: Function
    },
    components: {
        Spacer
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .avatar-button,
    .avatar-image {
        border-radius: 50%;
    }

    .avatar-button {
        @extend %button-reset;

        max-width: 80px;
    }

    .avatar-image {
        border: 4px solid transparent;

        .is-active & {
            border-color: $color-accent-dark;
        }
    }
</style>
