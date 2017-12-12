<template>
    <div :class="href ? buttonClasses : 'dib'" :style="href ? style : ''">
        <a v-if="href" :href="href" v-html="text" />
        <button v-else
            :class="buttonClasses"
            :disabled="isDisabled || isProcessing"
            @click="handleClick"
            :style="style"
        >
            <LoadingSpinner
                v-if="isProcessing"
                class="di margin-right-xxs"
                :color="config[mode].loadingColor"
                :size="config[mode].loadingSize"
            />
            <div v-else-if="isDone">
                <i :class="'margin-right-xxs inline fa ' + config[mode].doneIcon" style="font-size:12px"></i>
                <span>{{ config[mode].doneText }}</span>
            </div>
            <span v-else v-html="text"></span>
        </button>
    </div>
</template>

<script>
import { LoadingSpinner } from 'feedback';

export default {
    props: {
        doneText: { type: String, default: '' },
        doneIcon: { type: String, default: 'fa-check' },
        href: { type: String },
        isDisabled: Boolean,
        isDone: Boolean,
        isProcessing: Boolean,
        mode: { type: String, default: 'primary' },
        onClick: { type: Function },
        text: { type: String, required: true },
        width: String
    },
    components: {
        LoadingSpinner
    },
    data() {
        return {
            config: {
                primary: {
                    class: '',
                    doneIcon: this.doneIcon,
                    doneText: this.doneText,
                    loadingColor: 'light',
                    loadingSize: 'sm'
                },
                secondary: {
                    class: 'gray-button',
                    doneIcon: this.doneIcon,
                    doneText: this.doneText,
                    loadingColor: 'light',
                    loadingSize: 'sm'
                },
                inverse: {
                    class: 'Button--white-filled',
                    doneIcon: this.doneIcon,
                    doneText: this.doneText,
                    loadingColor: 'dark',
                    loadingSize: 'sm'
                }
            }
        };
    },
    methods: {
        handleClick() {
            if (!this.href) this.onClick();
        }
    },
    computed: {
        buttonClasses() {
            return {
                'Button': true,
                'container': true,
                'dib': true,
                'is-link': this.href,
                [`${this.config[this.mode].class}`]: true
            }
        },
        style() {
            return `width:${this.width || 'auto'}`;
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '~sass';

    .container {
        height: 48px;
        padding: 0;

        button {
            padding: 12px;
        }
    }

    .gray-button {
        background: $color-copy-muted-1;
        border-color: $color-copy-muted-1;
    }

    .is-link a {
        color: inherit;
        display: inline-block;
        font-weight: 600;
        line-height: 48px;
        padding: 0 12px;
        width: 100%;
    }
</style>
