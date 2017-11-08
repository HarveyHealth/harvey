<div class="header nav" :class="{'is-inverted': navIsInverted}">
    <div class="container">
        <div class="nav-left">
            <a href="/" class="nav-item">
                <div class="logo-wrapper">
                    {!! $svgImages['logo'] !!}
                </div>
            </a>
            <div class="nav-items">
                <a href="/about" class="link">About</a>
                <a href="/lab-tests" class="link is-hidden-mobile">Labs</a>
                <a href="/#prices" class="link is-hidden-mobile is-hidden-tablet-only">Pricing</a>
                <a href="/financing" class="link is-hidden-mobile is-hidden-tablet-only">Financing</a>
                <a :href="loginLink.href" class="link" v-text="loginLink.display"></a>
            </div>
        </div>
        <div class="nav-right">
            <div class="nav-items">
                <a href="tel:800-690-9989" class="button is-primary is-outlined is-hidden-mobile">(800) 690-9989</a>
                <a :href="getStartedLink.href" class="button is-primary" v-html="getStartedLink.display"></a>
            </div>
        </div>
    </div>
</div>

@include('legacy._layouts.includes.messages')
