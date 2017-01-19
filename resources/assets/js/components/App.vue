<template>
    <div>
        <top-nav :guest="guest" :user="user"></top-nav>
        <div class="page-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    // components
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
            TopNav
        },
        mounted() {
        }
    }
</script>
