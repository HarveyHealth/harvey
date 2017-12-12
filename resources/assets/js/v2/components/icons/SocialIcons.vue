<template>
    <div class="social-icons">
        <a v-for="icon in socialIcons" :href="icon.href" class="dib f2 mh2 white">
            <i :class="icon.class"></i>
        </a>
    </div>
</template>

<script>
export default {
    data() {
        return {
            socialIcons: [
              { class: 'fa fa-medium', href: 'https://www.goharvey.com/blog' },
              { class: 'fa fa-instagram', href: 'https://www.instagram.com/go_harvey' },
              { class: 'fa fa-facebook', href: 'https://www.facebook.com/goharveyapp' },
              { class: 'fa fa-twitter', href: 'https://twitter.com/goharveyapp' },
              { class: 'fa fa-youtube', href: 'https://www.youtube.com/channel/UCNW4aHA1yCPUdk7OM65oNDw' }
            ]
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '~sass';
</style>
