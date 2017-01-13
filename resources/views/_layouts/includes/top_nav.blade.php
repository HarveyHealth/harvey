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
                <a class="nav-item is-tab">Home</a>
                <a class="nav-item is-tab">How it works</a>
                <a class="nav-item is-tab">Pricing</a>
                <a class="nav-item is-tab">About</a>
                <span class="nav-item">
                    <a href="/signup" class="button is-primary">Sign Up</a>
                    <a href="/login" class="button">Log In</a>
                </span>
            @else
                <p class="nav-item">Hi, {{ $current_user->first_name }}</p>
                <a class="nav-item"><span class="icon has-border-circle"><i class="fa fa-user"></i></span></a>
                <div class="nav-menu is-active">
                    <a class="nav-item is-active">Dashboard</a>
                    <a class="nav-item">Log out</a>
                </div>
                
{{--                 <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form> --}}
            @endif
        </div>
    </div>
</nav>

@include('_layouts.includes.messages')
