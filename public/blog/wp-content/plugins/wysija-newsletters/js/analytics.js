!function(e,t){if(!t.__SV){var a,n,i,l;window.mixpanel=t,t._i=[],t.init=function(e,a,n){function p(e,t){var a=t.split(".");2==a.length&&(e=e[a[0]],t=a[1]),e[t]=function(){e.push([t].concat(Array.prototype.slice.call(arguments,0)));};}var r=t;for("undefined"!=typeof n?r=t[n]=[]:n="mixpanel",r.people=r.people||[],r.toString=function(e){var t="mixpanel";return"mixpanel"!==n&&(t+="."+n),e||(t+=" (stub)"),t;},r.people.toString=function(){return r.toString(1)+".people (stub)";},i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" "),l=0;l<i.length;l++)p(r,i[l]);t._i.push([e,a,n]);},t.__SV=1.2,a=e.createElement("script"),a.type="text/javascript",a.async=!0,a.src="//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js",n=e.getElementsByTagName("script")[0],n.parentNode.insertBefore(a,n);}}(document,window.mixpanel||[]),mixpanel.init("f683d388fb25fcf331f1b2b5c4449798",{api_host:"https://api.mixpanel.com"});var mixpanel_set={},mx_length=analytics_data.length,label="",value="";for(key in analytics_data)label=analytics_data[key].label,value=analytics_data[key].value,mixpanel_set[label]=value;mixpanel.track("Wysija Usage",mixpanel_set);