<template>
    <transition
        mode="out-in"
        enter-active-class="animated animated-medium fadeInUp"
        leave-active-class="is-hidden"
    >
        <div class="tab copy-has-max-width"
            v-show="tabId === $parent.activeTab"
            :id="tabId"
        >
            <slot></slot>
        </div>
    </transition>
</template>

<script>
    import {uniqueId} from 'lodash';

    export default {
        props: {
            id: [String, Number],
            label: [String, Number],
            url: String
        },
        data() {
            return {
                tabId: this.id || 'tab-' + uniqueId()
            };
        },
        mounted() {
            let parent = this.$parent;

            if (parent) {
                parent.updateTab({
                    id: this.tabId,
                    label: this.label,
                    url: this.url
                });
            }
        }
    };
</script>
