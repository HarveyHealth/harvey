function WYSIJA_SYNC_AJAX(s){$("ajax-loading").show(),$("wysija_notice_msg").update(void 0!==s.loading_message?s.loading_message:Wysija_i18n.savingnl),$("wysija_notices").show(),$("wysija_notice_msg").show(),$("wysija_notices").writeAttribute("class","notice").setStyle({opacity:1}),new Ajax.Request(wysijaAJAX.ajaxurl,{method:"post",parameters:wysijaAJAX,asynchronous:!0,onSuccess:function(e){void 0!==s.success&&s.success(e);var i=Wysija_i18n.savednl;void 0!==s.message&&(i=s.message),"msgs"in e.responseJSON&&"error"in e.responseJSON.msgs&&($("wysija_notices").writeAttribute("class","error"),i=e.responseJSON.msgs.error),"msgs"in e.responseJSON&&"updated"in e.responseJSON.msgs&&(i=e.responseJSON.msgs.updated),$("wysija_notice_msg").update(i),$("ajax-loading").hide(),new Effect.Fade($("wysija_notices"),{duration:1,from:1,to:0}),ajaxOver=!0;},onFailure:function(e){void 0!==s.failure&&s.failure(e),$("wysija_notices").writeAttribute("class","error"),$("ajax-loading").hide(),$("wysija_notices").hide(),$("wysija_notice_msg").update(Wysija_i18n.errorsavingnl),ajaxOver=!0;}});}function WYSIJA_AJAX_POST(s){var e=s||{};$("ajax-loading").show(),$("wysija_notice_msg").update(void 0!==e.loading_message?e.loading_message:Wysija_i18n.savingnl),$("wysija_notices").show(),$("wysija_notice_msg").show(),$("wysija_notices").setAttribute("class","notice"),$("wysija_notices").writeAttribute("class","notice").setStyle({opacity:1}),new Ajax.Request(wysijaAJAX.ajaxurl,{method:"post",parameters:wysijaAJAX,onSuccess:function(s){$("wysija_notices").writeAttribute("class","notice");var i=Wysija_i18n.savednl;void 0!==e.success_message&&(i=e.success_message),void 0!=e.success&&e.success(s),"msgs"in s.responseJSON&&"error"in s.responseJSON.msgs&&($("wysija_notices").writeAttribute("class","error"),i=s.responseJSON.msgs.error),"msgs"in s.responseJSON&&"updated"in s.responseJSON.msgs&&(i=s.responseJSON.msgs.updated),$("wysija_notice_msg").update(i),$("ajax-loading").hide(),new Effect.Fade($("wysija_notices"),{duration:1,from:1,to:0}),ajaxOver=!0;},onFailure:function(s){$("wysija_notices").setAttribute("class","error"),void 0!=e.failure&&e.failure(s),$("ajax-loading").hide(),$("wysija_notices").hide(),$("wysija_notice_msg").update(void 0!==e.error_message?e.error_message:Wysija_i18n.errorsavingnl),ajaxOver=!0;}});}