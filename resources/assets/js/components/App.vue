<template>
    <div>
        <top-nav :guest="guest" :user="user">
            <slot name="nav"></slot>
        </top-nav>
        <slot name="content"></slot>
    </div>
</template>

<script>
    // components
    import TopNav from './top_nav.vue';
    
    export default {
        props: {
            guest: true
        },
        data() {
            return {
                user: {}
            }
        },
        components: {
            TopNav
        },
        mounted() {
            this.$http.get('/api/user')
                .then( response => {
                    this.user = response.data;
                } )
                .catch( error => this.user = {} )
        }
    }
</script>
