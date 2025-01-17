jQuery( document ).ready( function() {

    /* === Checkbox Multiple Control === */

    jQuery( document ).on( 'change', '.customize-control-checkbox-multiple input[type="checkbox"]', 
        function(){

            checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );

} ); // jQuery( document ).ready