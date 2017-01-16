    <div id="footer">
        <!-- Footer stuff -->
    </div>


    {{-- SCRIPTS --}}
    <script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>
    
    @if (Auth::guest())

    @else
        {{-- @script(/js/app.js) --}}
        <script src="/js/app.js"></script>
    @endif
    
    @stack('scripts')

</body>
</html>
