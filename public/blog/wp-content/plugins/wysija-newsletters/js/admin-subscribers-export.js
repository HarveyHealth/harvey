jQuery(function(c){function n(){c(this).attr("checked")?(c(".count-confirmed-only").show(),c(".count-all").hide()):(c(".count-confirmed-only").hide(),c(".count-all").show());}c("#confirmedcheck").change(n),c(document).ready(function(){c("#confirmedcheck").change();});});