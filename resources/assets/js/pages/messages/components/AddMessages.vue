<template>
    <div>
        <aside :class="{ flyout: true, isactive: true }">
            <button class="button--close flyout-close" @click="close()">
                <svg><use xlink:href="#close" /></svg>
            </button>

        </aside>
    </div>
</template>

<script>
    export default {
        props: [],
        name: 'Preview',
        components: {

        },
        data() {
            return {
                close: this.$parent.close
            }
        },
        methods: {
            handleOverlayClick(e) {
                if (/.*appointment-modal.*/.test(e.target.className)) {
                    this.isActive = false;
                }
            },
            handleCancelClick() {
                this.isActive = false;
            },
            handleAffirmClick() {
                this.isActive = false;
                this.$eventHub.$emit(this.affirmEvent);
            }
        }
    }
</script>

<style>

</style>