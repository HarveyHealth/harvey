<template>
    <nav class="nav">
        <div class="container">
            <div class="nav-left">
                <a href="/" class="nav-item">Logo</a>
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
                    <a class="nav-item is-tab">How it works</a>
                    <a class="nav-item is-tab">Pricing</a>
                    <a class="nav-item is-tab">About</a>
                    <span class="nav-item">
                        <a href="/login" class="button">Log In</a>
                        <a href="/signup" class="button is-primary">Sign Up</a>
                    </span>
                </div>
            </template>
            <template v-else>
                <p class="nav-item">Hi, {{ user.first_name }}</p>
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
                    <router-link tag="a" to="/profile" class="nav-item">Edit Profile</router-link>
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
    export default {
        props: ['guest', 'user'],
        data() {
            return {
                nav_is_open: false
            }
        },
        methods: {
            toggleNav() {
                this.nav_is_open = !this.nav_is_open;
            },
            logout() {
                this.$http.post('/logout').then(response => {
                    location.href = '/';
                });
            }
        }
    }
</script>