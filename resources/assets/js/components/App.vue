<template>
    <div>
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
        computed: {
            guest() {
                return this.$root.guest;
            }
        }
    }
</script>
