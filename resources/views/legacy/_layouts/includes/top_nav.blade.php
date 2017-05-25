<div class="header nav" @if (Auth::guest()) :class="{'is-inverted': navIsInverted}" @endif >
    <div class="container">
        <div class="nav-left">
            <a href="/" class="nav-item">
                <div class="logo-wrapper">
                    {!! $svgImages['logo'] !!}
                </div>
            </a>
        </div>
        <div class="nav-right">
            <span class="nav-item">
                <a href="tel:800-690-9989" class="button is-primary is-outlined">(800) 690-9989</a>
                <a href="/login" class="button is-primary is-outlined is-hidden-mobile">Log In</a>
                <a href="/signup" class="button is-primary">Book Now</a>
            </span>
        </div>
    </div>
</div>

@include('legacy._layouts.includes.messages')