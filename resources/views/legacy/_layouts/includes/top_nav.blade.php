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
                <a href="/about" class="link is-hidden-mobile">About</a>
                <a href="/lab-tests" class="link">Lab Tests</a>
                <a href="/#prices" class="link is-hidden-mobile is-hidden-tablet-only">Pricing</a>
                <a href="tel:800-690-9989" class="button is-primary is-outlined is-hidden-mobile">(800) 690-9989</a>
                <a href="/login" class="button is-primary is-outlined is-hidden-mobile is-hidden-tablet-only">Log In</a>
                <a href="/get-started" class="button is-primary">Get Started</a>
            </span>
        </div>
    </div>
</div>

@include('legacy._layouts.includes.messages')