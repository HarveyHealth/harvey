    <div id="footer">
        <!-- Footer stuff -->
    </div>


    {{-- SCRIPTS --}}
    <script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>

    @script(/js/app.js)
    @stack('scripts')

</body>
</html>
