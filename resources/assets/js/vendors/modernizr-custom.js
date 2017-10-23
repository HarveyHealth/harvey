/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-svg-mq-prefixed-setclasses !*/
!function(e,n,t){function r(e,n){return typeof e===n;}function o(){var e,n,t,o,i,s,a;for(var f in y)if(y.hasOwnProperty(f)){if(e=[],n=y[f],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(o=r(n.fn,"function")?n.fn():n.fn,i=0;i<e.length;i++)s=e[i],a=s.split("."),1===a.length?Modernizr[a[0]]=o:(!Modernizr[a[0]]||Modernizr[a[0]]instanceof Boolean||(Modernizr[a[0]]=new Boolean(Modernizr[a[0]])),Modernizr[a[0]][a[1]]=o),g.push((o?"":"no-")+a.join("-"));}}function i(e){var n=w.className,t=Modernizr._config.classPrefix||"";if(S&&(n=n.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(r,"$1"+t+"js$2");}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),S?w.className.baseVal=n:w.className=n);}function s(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase();}).replace(/^-/,"");}function a(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):S?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments);}function f(){var e=n.body;return e||(e=a(S?"svg":"body"),e.fake=!0),e;}function l(e,t,r,o){var i,s,l,u,p="modernizr",c=a("div"),d=f();if(parseInt(r,10))for(;r--;)l=a("div"),l.id=o?o[r]:p+(r+1),c.appendChild(l);return i=a("style"),i.type="text/css",i.id="s"+p,(d.fake?d:c).appendChild(i),d.appendChild(c),i.styleSheet?i.styleSheet.cssText=e:i.appendChild(n.createTextNode(e)),c.id=p,d.fake&&(d.style.background="",d.style.overflow="hidden",u=w.style.overflow,w.style.overflow="hidden",w.appendChild(d)),s=t(c,e),d.fake?(d.parentNode.removeChild(d),w.style.overflow=u,w.offsetHeight):c.parentNode.removeChild(c),!!s;}function u(e,n){return!!~(""+e).indexOf(n);}function p(e,n){return function(){return e.apply(n,arguments);};}function c(e,n,t){var o;for(var i in e)if(e[i]in n)return t===!1?e[i]:(o=n[e[i]],r(o,"function")?p(o,t||n):o);return!1;}function d(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase();}).replace(/^ms-/,"-ms-");}function m(n,r){var o=n.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(d(n[o]),r))return!0;return!1;}if("CSSSupportsRule"in e){for(var i=[];o--;)i.push("("+d(n[o])+":"+r+")");return i=i.join(" or "),l("@supports ("+i+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position;});}return t;}function v(e,n,o,i){function f(){p&&(delete P.style,delete P.modElem);}if(i=r(i,"undefined")?!1:i,!r(o,"undefined")){var l=m(e,o);if(!r(l,"undefined"))return l;}for(var p,c,d,v,h,g=["modernizr","tspan","samp"];!P.style&&g.length;)p=!0,P.modElem=a(g.shift()),P.style=P.modElem.style;for(d=e.length,c=0;d>c;c++)if(v=e[c],h=P.style[v],u(v,"-")&&(v=s(v)),P.style[v]!==t){if(i||r(o,"undefined"))return f(),"pfx"==n?v:!0;try{P.style[v]=o;}catch(y){}if(P.style[v]!=h)return f(),"pfx"==n?v:!0;}return f(),!1;}function h(e,n,t,o,i){var s=e.charAt(0).toUpperCase()+e.slice(1),a=(e+" "+b.join(s+" ")+s).split(" ");return r(n,"string")||r(n,"undefined")?v(a,n,o,i):(a=(e+" "+N.join(s+" ")+s).split(" "),c(a,n,t));}var g=[],y=[],C={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e]);},0);},addTest:function(e,n,t){y.push({name:e,fn:n,options:t});},addAsyncTest:function(e){y.push({name:null,fn:e});}},Modernizr=function(){};Modernizr.prototype=C,Modernizr=new Modernizr;var w=n.documentElement,S="svg"===w.nodeName.toLowerCase();Modernizr.addTest("svg",!!n.createElementNS&&!!n.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var _=function(){var n=e.matchMedia||e.msMatchMedia;return n?function(e){var t=n(e);return t&&t.matches||!1;}:function(n){var t=!1;return l("@media "+n+" { #modernizr { position: absolute; } }",function(n){t="absolute"==(e.getComputedStyle?e.getComputedStyle(n,null):n.currentStyle).position;}),t;};}();C.mq=_;var x="Moz O ms Webkit",b=C._config.usePrefixes?x.split(" "):[];C._cssomPrefixes=b;var E=function(n){var r,o=prefixes.length,i=e.CSSRule;if("undefined"==typeof i)return t;if(!n)return!1;if(n=n.replace(/^@/,""),r=n.replace(/-/g,"_").toUpperCase()+"_RULE",r in i)return"@"+n;for(var s=0;o>s;s++){var a=prefixes[s],f=a.toUpperCase()+"_"+r;if(f in i)return"@-"+a.toLowerCase()+"-"+n;}return!1;};C.atRule=E;var N=C._config.usePrefixes?x.toLowerCase().split(" "):[];C._domPrefixes=N;var z={elem:a("modernizr")};Modernizr._q.push(function(){delete z.elem;});var P={style:z.elem.style};Modernizr._q.unshift(function(){delete P.style;}),C.testAllProps=h;C.prefixed=function(e,n,t){return 0===e.indexOf("@")?E(e):(-1!=e.indexOf("-")&&(e=s(e)),n?h(e,n,t):h(e,"pfx"));};o(),i(g),delete C.addTest,delete C.addAsyncTest;for(var T=0;T<Modernizr._q.length;T++)Modernizr._q[T]();e.Modernizr=Modernizr;}(window,document);