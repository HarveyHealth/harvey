<template>
    <div>
        <a
            v-for="brand in brands"
            :href="brandInfo[brand].href"
            class="circle dib h2 mh1 relative w2 white social"
            :style="{ backgroundColor: isBranded ? brandInfo[brand].color : '' }"
        >
            <i :class="brandInfo[brand].class + ' f4 center-absolute'"></i>
        </a>
    </div>
</template>

<script>
export default {
    props: {
        brands: {
            type: Array,
            default: function() {
                return ['instagram', 'facebook', 'twitter', 'youtube'];
            }
        },
        isBranded: Boolean
    },
    data() {
        return {
            brandInfo: {
                instagram: {
                    class: 'fa fa-instagram',
                    color: '#4c799f',
                    href: 'https://www.instagram.com/go_harvey'
                },
                facebook: {
                    class: 'fa fa-facebook',
                    color: '#3b5998',
                    href: 'https://www.facebook.com/goharveyapp'
                },
                twitter: {
                    class: 'fa fa-twitter',
                    color: '#00bdec',
                    href: 'https://twitter.com/goharveyapp'
                },
                youtube: {
                    class: 'fa fa-youtube',
                    color: '#ff0000',
                    href: 'https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw'
                }
            }
        };
    }
};
</script>

<style lang="scss" scoped>
    .social:hover {
        color: white;
    }
</style>
