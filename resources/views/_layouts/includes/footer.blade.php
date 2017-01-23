    {{-- SCRIPTS --}}
    <script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>
    
    @if (Auth::guest())
        @script(/js/libs/modernizr-custom.js)
        @script(/js/app_public.js)
    @else
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        @script(/js/app_logged_in.js)
    @endif
    
    @stack('scripts')

</body>
</html>
