<nav class="header nav has-shadow">
    <div class="container">
        <div class="nav-left"></div>
        <div class="nav-center">
            <a href="/" class="nav-item">
                <img
                    src="/images/logos/main-logo.png"
                    srcset="/images/logos/main-logo@2x.png 2x"
                    alt="Harvey Logo">
            </a>
        </div>
        @if (Auth::guest())
            <span class="nav-toggle"
                :class="{'is-active': nav_is_open}"
                @click="toggleNav"
            >
                <span></span>
                <span></span>
                <span></span>
            </span>
            <div class="nav-right nav-menu"
                :class="{'is-active': nav_is_open}"
            >
                <span class="nav-item">
                    <a href="/login" class="button is-primary is-outlined">Log In</a>
                    <a href="/signup" class="button is-primary" @click="viewSignupPage">Get Started</a>
                </span>
            </div>
        @else
            <p class="nav-item">Hi, @{{ capitalize(user.first_name) }}</p>
            <span
                class="nav-item"
                @click="toggleNav"
            >
                <span class="icon"><i class="fa fa-user-circle-o"></i></span>
            </span>
            <div class="nav-right nav-menu"
                :class="{'is-active': nav_is_open}"
            >
                <router-link tag="a" to="/" class="nav-item">Dashboard</router-link>
                <router-link tag="a" to="/new-appointment" class="nav-item">New Appointment</router-link>
                <a class="nav-item has-border-top" @click="logout">Log out</a>
            </div>
        @endif
    </div>
</nav>

@include('_layouts.includes.messages')
