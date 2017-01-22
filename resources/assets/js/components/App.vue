<template>
    <div>
        <alert></alert>
        <top-nav :guest="guest" :user="user"></top-nav>
        <div class="page-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    // components
    import Alert from './Alert.vue';
    import TopNav from './TopNav.vue';
    
    export default {
        props: {
            guest: { default: true },
            user: {
                type: Object,
                default: () => {}
            }
        },
        components: {
            Alert,
            TopNav
        }
    }
</script>
