<public-nav
    has-logo
    has-links
    has-phone
    has-start
    is-sticky
    keep-logo-text
    :on-menu-click="toggleMenu"
    :force-dark="State.navIsInverted"
></public-nav>

@include('legacy._layouts.includes.messages')
