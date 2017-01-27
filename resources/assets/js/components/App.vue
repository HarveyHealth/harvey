<template>
    <div class="app-wrapper" :style="{ paddingBottom: paddingBottom + 'px' }">
        <alert v-if="!guest"></alert>
        <top-nav></top-nav>
        <div class="page-content">
            <slot></slot>
        </div>
        <footer-view :is-simple="!guest"></footer-view>
    </div>
</template>

<script>
    // components
    import Alert from './Alert.vue';
    import TopNav from './TopNav.vue';
    import FooterView from './Footer.vue';
    
    export default {
        components: {
            Alert,
            TopNav,
            FooterView
        },
        data() {
            return {
                paddingBottom: 0
            }
        },
        computed: {
            guest() {
                return this.$root.guest;
            }
        },
        mounted() {
            if (!this.guest) {
                this.paddingBottom = document.querySelector('footer').offsetHeight + 50;
            }
        }
    }
</script>