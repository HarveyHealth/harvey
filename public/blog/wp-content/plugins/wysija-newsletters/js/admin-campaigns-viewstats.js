jQuery(function(n){n(".bulksubmitcamp").click(function(){return n(".global-action").attr("name","action"),n("#_wpnonce").prop("disabled",null),n("#_wpnonce").val(n(".global-action option:selected").data("nonce")),!0;});});