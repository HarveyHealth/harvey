<nav class="header nav"
    @if (Auth::guest())
        :class="{'is-inverted': navIsInverted}"
    @endif
>
    <div class="container">
        <div class="nav-left">
            <a href="/" class="nav-item">
                <div class="logo-wrapper">
                    {!! $svgImages['logo'] !!}
                </div>
            </a>
        </div>
        @if (Auth::guest())
    {{-- <span class="nav-toggle"
                :class="{'is-active': nav_is_open}"
                @click="toggleNav"
            >
                <span></span>
                <span></span>
                <span></span>
            </span> --}}
            <div class="nav-right">
                <span class="nav-item">
                    <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
                    <a href="/login" class="button is-primary is-outlined is-hidden-mobile">Log In</a>
                    <a href="/signup" class="button is-primary">Get Started</a>
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

@include('legacy._layouts.includes.messages')
