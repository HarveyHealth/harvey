<nav class="nav">
    <div class="container">
        <div class="nav-left">
            <a href="{{ url('/') }}" class="nav-item">{{ config('app.name') }}</a> {{-- App Logo --}}
        </div>
        <span class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </span>
        <div class="nav-right nav-menu">
            @if (Auth::guest())
                <a class="nav-item is-tab">How it works</a>
                <a class="nav-item is-tab">Pricing</a>
                <a class="nav-item is-tab">About</a>
                <span class="nav-item">
                    <a href="/login" class="button">Log In</a>
                    <a href="/signup" class="button is-primary">Sign Up</a>
                </span>
            @else
                <p class="nav-item">Hi, {{ $current_user->first_name }}</p>
                <div class="nav-item dropdown">
                    <a href="/" class="dropdown-button">
                        <span class="icon"><i class="fa fa-user-circle-o"></i></span>
                    </a>
                    <div class="dropdown-list dropdown-list_align-right">
                        <a href="/">Dashboard</a>
                        <a href="">New Appointment</a>
                        <a href="">Edit Profile</a>
                        <a href="/logout" class="has-border-top">Log out</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</nav>

@include('_layouts.includes.messages')
