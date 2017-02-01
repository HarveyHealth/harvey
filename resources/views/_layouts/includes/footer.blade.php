<footer class="footer"
    :class="{'is-simple': !guest}"
>
    <div class="container">
        <div class="content has-text-centered">
            <a href="/">
                @if (Auth::guest())
                    <img src="/images/logos/white-logo.png" alt="Harvey Logo">
                @else
                    <img src="/images/logos/main-logo.png" alt="Harvey Logo">
                @endif
            </a>
            <p class="nav-center">
                <a href="/terms" class="nav-item">Terms of Service</a>
                <a href="/privacy" class="nav-item">Privacy Policy</a>
            </p>
            <p>
                <small>&copy; {{date("Y")}}, Harvey. All rights reserved. All site information and services provided as information and education only. Harvey does not provide medical advice, diagnosis or treatment.</small>
            </p>
        </div>
    </div>
</footer>