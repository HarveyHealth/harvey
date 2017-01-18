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
    import TopNav from './top_nav.vue';
    
    export default {
        props: {
            guest: true,
            user: {}
        },
        components: {
            TopNav
        },
        mounted() {
        }
    }
</script>
