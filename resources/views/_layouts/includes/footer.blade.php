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
                <a href="/" class="nav-item">Home</a>
                <a href="/signup" class="nav-item">Book</a>
                <a href="/lab-tests" class="nav-item">Labs</a>
                <a href="/terms" class="nav-item">Terms</a>
                <a href="/privacy" class="nav-item">Privacy</a>
                <a href="/contact" class="nav-item">Contact</a>
            </p>
            <p class="has-small-lineheight">
                <small>&copy;{{date("Y")}} Harvey, Inc. All rights reserved.<br/>
                Harvey does not provide medical advice, diagnosis or treatment.</small>
            </p>
        </div>
    </div>
</footer>