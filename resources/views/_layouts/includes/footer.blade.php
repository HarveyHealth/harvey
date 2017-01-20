    <div id="footer">
        <!-- Footer stuff -->
    </div>


    {{-- SCRIPTS --}}
    <script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>
    
    @if (Auth::guest())
        @script(/js/app_public.js)
    @else
        @script(/js/app_logged_in.js)
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <script type="text/javascript">
            Stripe.setPublishableKey('{{env('STRIPE_PUBLISHABLE')}}');
        </script>
    @endif
    
    @stack('scripts')

</body>
</html>
