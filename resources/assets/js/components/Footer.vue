<template>
    <footer :class="['footer', {'is-simple': isSimple}]">
        <div class="container">
            <div class="content has-text-centered">
                <a v-if="!isSimple" href="/">
                    <img src="/images/logos/white-logo.png" alt="Harvey Logo">
                </a>
                <a v-else href="/">
                    <img src="/images/logos/main-logo.png" alt="Harvey Logo">
                </a>
                <p class="nav-menu nav-center">
                    <a href="/terms" class="nav-item">Terms of Service</a>
                    <a href="/privacy" class="nav-item">Privacy Policy</a>
                </p>
                <small>&copy; {{year}}, Harvey.  All rights reserved.All site information and services provided as information and education only. Harvey does not provide medical advice, diagnosis or treatment.</small>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
        name: 'footer',
        props: {
            isSimple: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                year: new Date().getFullYear()
            }
        }
    }
</script>