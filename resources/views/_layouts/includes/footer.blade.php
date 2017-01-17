    <div id="footer">
        <!-- Footer stuff -->
    </div>


    {{-- SCRIPTS --}}
    <script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>
    
    @if (Auth::guest())
        @script(/js/app_public.js)
    @else
        @script(/js/app_logged_in.js)
    @endif
    
    @stack('scripts')

</body>
</html>
