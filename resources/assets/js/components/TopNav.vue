<template>
    <nav class="header nav">
        <div class="container">
            <div class="nav-left"></div>
            <div class="nav-center">
                <a href="/" class="nav-item"><img src="/images/logos/main-logo.png" alt="Harvey Logo"></a>
            </div>
            <template v-if="guest">
                <span
                    :class="['nav-toggle', {'is-active': nav_is_open} ]"
                    @click="toggleNav"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <div
                    :class="['nav-right', 'nav-menu', {'is-active': nav_is_open} ]"
                >
                    <span class="nav-item">
                        <a href="/login" class="button is-primary is-outlined">Log In</a>
                        <a href="/signup" class="button is-primary" @click="viewSignupPage">Get Started</a>
                    </span>
                </div>
            </template>
            <template v-else>
                <p class="nav-item">Hi, {{ capitalize(user.first_name) }}</p>
                <span
                    class="nav-item"
                    @click="toggleNav"
                >
                    <span class="icon"><i class="fa fa-user-circle-o"></i></span>
                </span>
                <div
                    :class="['nav-right', 'nav-menu', {'is-active': nav_is_open} ]"
                >
                    <router-link tag="a" to="/" class="nav-item">Dashboard</router-link>
                    <router-link tag="a" to="/new-appointment" class="nav-item">New Appointment</router-link>
                    <!-- <router-link tag="a" to="/profile" class="nav-item">Profile</router-link>
                    <router-link tag="a" to="/payment" class="nav-item">Payment</router-link> -->
                    <a class="nav-item has-border-top" @click="logout">Log out</a>
                </div>
                <!-- <div class="nav-item dropdown">
                    <div
                        :class="['nav-right', 'nav-menu', 'dropdown-list', 'dropdown-list_align-right', {'is-active': nav_is_open} ]"
                    >
                        <a class="nav-item" href="/">Dashboard</a>
                        <a class="nav-item" href="">New Appointment</a>
                        <a class="nav-item" href="">Edit Profile</a>
                        <a class="nav-item has-border-top" href="/logout">Log out</a>
                    </div>
                </div> -->
            </template>
        </div>
    </nav>
</template>

<script>
    import {capitalize} from '../filters/textformat.js';

    export default {
        name: 'TopNav',
        props: ['guest', 'user'],
        data() {
            return {
                nav_is_open: false
            }
        },
        methods: {
            capitalize,
            toggleNav() {
                this.nav_is_open = !this.nav_is_open;
            },
            logout() {
                this.$http.post('/logout').then(response => {
                    location.href = '/';
                });
            },
            viewSignupPage() {
                mixpanel.track("View Sign Up Page");
            }
        }
    }
</script>