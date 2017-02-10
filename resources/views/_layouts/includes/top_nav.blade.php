<nav class="header nav"
    @if (Auth::guest())
        :class="{'is-inverted': nav_is_inverted}"
    @endif
>
    <div class="container">
        <div class="nav-left">
            <a href="/" class="nav-item">
                <div class="logo-wrapper">
                    <?php include("images/logos/main-logo.svg"); ?>
                </div>
            </a>
        </div>
        @if (Auth::guest())
{{--             <span class="nav-toggle"
                :class="{'is-active': nav_is_open}"
                @click="toggleNav"
            >
                <span></span>
                <span></span>
                <span></span>
            </span> --}}
            <div class="nav-right">
                <span class="nav-item">
                    @if (!App::environment('production'))
                        <a href="/login" class="button is-primary is-outlined">Log In</a>
                    @endif
                    <a href="/signup" class="button is-primary" @click="viewSignupPage">Get Started</a>
                </span>
            </div>
        @else
            <p class="nav-item">Hi, @{{ capitalize(user.first_name) }}</p>
            <div class="nav-item dropdown" tabindex="-1" @blur.stop="onBlur">
                <span
                    class="nav-item dropdown-button"
                    @click="toggleNav"
                >
                    <span class="icon"><i class="fa fa-user-circle-o"></i></span>
                </span>
                <div class="nav-right nav-menu dropdown-list dropdown-list_align-right"
                    :class="{'is-active': nav_is_open}"
                >
                    <router-link tag="a" to="/" class="nav-item">Dashboard</router-link>
                    <router-link tag="a" to="/new-appointment" class="nav-item">New Appointment</router-link>
                    <a class="nav-item has-border-top" @click="logout">Log out</a>
                </div>
            </div>
        @endif
    </div>
</nav>

@include('_layouts.includes.messages')
