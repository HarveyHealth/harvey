jQuery(document).ready( function($) { 

    $('.page-title-action').hide();

    // Initialize the CodeMirror editor
    if ( $('#content').length > 0 ) {
        var editor_theme = $("#editor_theme").val();
        var content_mode = $("#content").attr('mode');
        var options = {
            lineNumbers: true,
            lineWrapping: true,
            mode: content_mode, 
            matchBrackets: true,
            theme: editor_theme, 
            extraKeys: {
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    fullscreen_buttons( true );
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                    fullscreen_buttons( false );
                },
            }

        };


        // If there are conflicts with other plugins, fall back to the simple scrollbar
        if ( typeof CCJ !== 'undefined' && CCJ.scroll !== '0' ) {
            options['scrollbarStyle'] = "simple";
        }


        // Make the editor resizable
        var cm_width = $('#title').width() + 16;
        var cm_height = 500;

        var editor = CodeMirror.fromTextArea(document.getElementById("content"), options );
        editor.setSize(cm_width, cm_height);

        $('.CodeMirror').resizable({
            resize: function() {
                editor.setSize($(this).width(), $(this).height());
            } ,
            maxWidth: cm_width,
            minWidth: cm_width,
            minHeight: 200
            
        });

        $(window).resize(function () { 
            var cm_width = $('#title').width() + 16;
            var cm_height = $('.CodeMirror').height();
            editor.setSize(cm_width, cm_height);
        });



        // Refresh the editor because of line wrapping
        var charWidth = editor.defaultCharWidth(), basePadding = 4;
        editor.on("renderLine", function(cm, line, elt) {
            var off = CodeMirror.countColumn(line.text, null, cm.getOption("tabSize")) * charWidth;
            elt.style.textIndent = "-" + off + "px";
            elt.style.paddingLeft = (basePadding + off) + "px";
        });
        editor.refresh();


        // Code Beautifier
        $("#ccj-beautifier").click(function(e){
            CodeMirror.commands["selectAll"](editor);
            editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
            editor.setCursor(0);
            e.preventDefault();
        });


        // Adjust the gutter size to the editor
        var gutter_size = parseInt( $(".code-mirror-after div").css('margin-left') );
        var current_gutter_size = parseInt( $(".CodeMirror-gutter-wrapper").css('left') );
        if ( gutter_size != current_gutter_size ) {
            gutter_size = -current_gutter_size -1 ;  
            gutter_size += 'px';
            $(".code-mirror-after div").css('margin-left', gutter_size );
            $(".code-mirror-before div").css('margin-left', gutter_size );
        }

        // Keep the adjustments to the gutter size after browser resize
        editor.on( 'update', function() {
            var current_gutter_size = parseInt( $(".CodeMirror-gutter-wrapper").css('left') );
            if ( gutter_size != current_gutter_size ) {
                gutter_size = -current_gutter_size -1 ;  
                gutter_size += 'px';
                $(".code-mirror-after div").css('margin-left', gutter_size );
                $(".code-mirror-before div").css('margin-left', gutter_size );
            }
        });


        var postID = document.getElementById('post_ID') != null ? document.getElementById('post_ID').value : 0;

        var getCookie = function (name) {
            var value = '; ' + document.cookie;
            var parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
        };


        // Saving cursor state
        editor.on('cursorActivity', function () {
            var curPos = editor.getCursor();
            document.cookie = 'hesh_plugin_pos=' + postID + ',' + curPos.line + ',' + curPos.ch;
        });

        // Restoring cursor state
        var curPos = (getCookie('hesh_plugin_pos') || '0,0,0').split(',');
        if (postID === curPos[0]) {
            editor.setCursor(parseFloat(curPos[1]), parseFloat(curPos[2]));
        }


    }

    // Pour the CodeMirror editor's content into <textarea id="content> before generating the preview
    if ( $('#ccj-preview').length > 0 ) {
        $('#ccj-preview').click( function(e) {
            $("#content").val( editor.getValue() );
        });
    }

    // Action for the `fullscreen` button
    $("#ccj-fullscreen-button").click( function() {
        var toggle = editor.getOption("fullScreen");
        editor.setOption("fullScreen", !toggle);
        fullscreen_buttons( !toggle );
    });

    $("#publish").click(function(e){
        if ( editor.getOption("fullScreen") === true ) {
            Cookies.set('fullScreen', 'true');
        }
    });

    // Show fullscreen
    if ( Cookies.get('fullScreen') == 'true' ) {
        var toggle = editor.getOption("fullScreen");
        editor.setOption("fullScreen", !toggle);
        fullscreen_buttons( !toggle );
        Cookies.remove('fullScreen');
    }

    // Enable the tipsy 
    $('span[rel=tipsy]').tipsy({fade: true, gravity: 's'});

    // Toggle the buttons when in fullscreen mode
    function fullscreen_buttons( mode ) {
        editor.focus();
        if ( mode === true ) {
            $("#publish").css({
                'position'  : 'fixed',
                'right'     : '40px',
                'bottom'    : '40px',
                'z-index'   : 100005,
            });
        } else {
            $("#publish").css({
                'position'  : 'static',
                'right'     : 'initial',
                'bottom'    : 'initial',
                'z-index'   : 10,
            });
        }
    }


    // Ask for name when "As shortcode" is chosen
    var old_code_name = '';
    $('input[type=radio][name=custom_code_type]').change(function() {
        if ( this.value == 'shortcode' ) {
            if ( $('#custom_code_name').length === 0 ) {
                var name_input = '<div id="custom_code_name_div"><label for="custom_code_name">Shortcode id: </label> <input type="text" name="custom_code_name" id="custom_code_name" value="'+old_code_name+'" /></div>';
                $(this.closest('div.radio-group')).append(name_input);
            }
            $('#custom_code_name').focus();
            toggle_fields_for_shortcode( false, 500 );
        } else {
            if ( $('#custom_code_name_div').length != 0 ) {
                old_code_name = $('#custom_code_name').val();
                $('#custom_code_name_div').remove();
            }
            toggle_fields_for_shortcode( true, 500 );
        }
    });

    // Toggle some fields when `shortcode` is enabled
    function toggle_fields_for_shortcode( toggle, time ) {
        var ids = ".options_meta_box h3:nth-child(3)" +
            ", .options_meta_box .radio-group:nth-child(4)" +
            ", .options_meta_box h3:nth-child(5)" +
            ", .options_meta_box .radio-group:nth-child(6)" +
            ", .options_meta_box h3:nth-child(7)" +
            ", .options_meta_box .radio-group:nth-child(8)" +
            ", #previewdiv" +
            ", #url-rules";

        if ( toggle === true ) {
            $(ids).show( time );
        } else {
            $(ids).hide( time );
        }
    }
    if ( $('input[name=custom_code_type]').length > 0 && $('input[name=custom_code_type]:checked').val() == 'shortcode' ) {
        toggle_fields_for_shortcode( false, 0 );
    }


    // Check for shortcode name before saving
    $('#post').submit(function(e) {
        if ( $('input[name=custom_code_type]:checked').val() === 'shortcode' && $('#custom_code_name').val().length === 0 ) {
            alert('You forgot to give an ID to this shortcode');
            $('#custom_code_name').focus();
            e.preventDefault();
        }
    });

    // Filter the admin Settings page
    if ( $('#ccj_settings').length > 0 ) {
        $('#ccj_settings').change( function() {
            
            // "Duration of Preview" > 0
            if ( $("#ccj_duration_preview").length > 0 ) {
                var value = $("#ccj_duration_preview").val();
                value = parseInt( value );
                if ( isNaN(value) || value <= 0 ) {
                    alert('"Duration of Preview" field accepts only integers > 0');
                }
            }

            if ( $("#ccj_role-current_user").length > 0 ) {
                var username = $("#ccj_role-current_user").val();
                var the_rights = null;

                var partial = $("#ccj_role-user_ids_partial").val();
                if ( partial.indexOf( username ) >= 0 ) {
                    the_rights = 'partial';
                }

                var none = $("#ccj_role-user_ids_none").val();
                if ( none.indexOf( username ) >= 0 ) {
                    the_rights = 'none';
                }

                if ( the_rights !== null ) {
                    alert('Your username is in the list of users with '+ the_rights +' rights. You are locking yourself out of the Settings page. Please remove your username, unless you know what you\'re doing.');
                }
            }
        }); 
    }


    // Make the inactive rows opaque
    if ( $('.dashicons-star-empty.ccj_row').length > 0 ) {
        $('.dashicons-star-empty.ccj_row').each(function(){
            $(this).parent().parent().parent().css('opacity', '0.4');
        });
    }

    // Activate/deactivate codes with AJAX
    $(".ccj_activate_deactivate").click( function(e) {
        var url = $(this).attr('href');
        var code_id = $(this).attr('data-code-id');
        e.preventDefault(); 
        $.ajax({
            url: url, 
            success: function(data){
                if (data === 'yes') {
                    ccj_activate_deactivate(code_id, false);
                }
                if (data === 'no') {
                    ccj_activate_deactivate(code_id, true);
                }
            }
        });
    });

    // Toggle the signs for activating/deactivating codes
    function ccj_activate_deactivate(code_id, action) {
        var row = $('tr#post-'+code_id);
        if ( action === true ) {
            row.css('opacity', '1');
            row.find('.row-actions .ccj_activate_deactivate')
                .text(CCJ.deactivate)
                .attr('title', CCJ.active_title);
            row.find('td.active .dashicons')
                .removeClass('dashicons-star-empty')
                .addClass('dashicons-star-filled');
            row.find('td.active .ccj_activate_deactivate')
                .attr('title', CCJ.active_title);
            $('#activate-action span').text(CCJ.active);
            $('#activate-action .ccj_activate_deactivate').text(CCJ.deactivate);
        } else {
            row.css('opacity', '0.4');
            row.find('.row-actions .ccj_activate_deactivate')
                .text(CCJ.activate)
                .attr('title', CCJ.deactive_title);
            row.find('td.active .dashicons')
                .removeClass('dashicons-star-filled')
                .addClass('dashicons-star-empty');
            row.find('td.active .ccj_activate_deactivate')
                .attr('title', CCJ.deactive_title);
            $('#activate-action span').text(CCJ.inactive);
            $('#activate-action .ccj_activate_deactivate').text(CCJ.activate);
        }
    }

});
