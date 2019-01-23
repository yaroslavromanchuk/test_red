var RontarUtils=function(){function n(){}return n.GetQueryParams=function(n){var e="";return n.forEach(function(n){e.length>0&&(e+="&");var t=encodeURIComponent(n.name),r=encodeURIComponent(n.value);e+=t+"="+r}),e},n.AddPixel=function(n){var e=document.createElement("img");e.src=n,e.width=1,e.height=1,e.style.display="none",document.body.appendChild(e)},n.ToRontarProps=function(n){var e=[];if(n)for(var t in n)n.hasOwnProperty(t)&&e.push(new RontarProp(t,n[t]));return e},n}(),RontarEventManager=function(){function n(n){this.baseUrl="//uaadcodedsp.rontar.com/rnt_analytics.axd",this.baseRemarketingUrl="//uaadcodedsp.rontar.com/cp.axd",this.document=n}return n.prototype.add_event=function(n){n instanceof RontarEvent||(n=new RontarEvent(n)),n.AddProp(new RontarProp("referrer",document.referrer)),RontarUtils.AddPixel(n.ToUrl(this.baseUrl))},n.prototype.add_audience=function(n){n.audienceId&&(n.productId&&n.priceId?this.AddProductRemarketing(n.audienceId,n.priceId,n.productId):this.AddClassicRemarketing(n.audienceId))},n.prototype.add_category_event=function(n){var e=new RontarEvent(n);e.AddProp(new RontarProp("eventType","category")),this.add_event(e)},n.prototype.add_product_event=function(n){if(n.productId){var e=new RontarEvent(n);e.AddProp(new RontarProp("eventType","product")),this.add_event(e)}},n.prototype.AddProductRemarketing=function(n,e,t){var r={aud:n,rnt_aud_params:"pId|"+n+"|"+e+"--"+t,ref:encodeURIComponent(document.referrer)},o=new RontarEvent(r);RontarUtils.AddPixel(o.ToUrl(this.baseRemarketingUrl))},n.prototype.AddClassicRemarketing=function(n){var e={aud:n,ref:encodeURIComponent(document.referrer)},t=new RontarEvent(e);RontarUtils.AddPixel(t.ToUrl(this.baseRemarketingUrl))},n.prototype.add_shopping_cart_event=function(n){var e=new RontarEvent(n);e.AddProp(new RontarProp("eventType","shopping_cart")),this.add_event(e)},n.prototype.add_order_event=function(n){var e=new RontarEvent(n);e.AddProp(new RontarProp("eventType","order")),this.add_event(e)},n.prototype.ProcessEventsQueue=function(n){for(var e;null!=(e=n.pop());)this.ProcessEvent(e)},n.prototype.ProcessEvent=function(n){var e=Array.prototype.slice.call(n),t=e[0].toLowerCase();this[t].apply(this,e.slice(1,e.length))},n.prototype.ProcessEventsOld=function(){!function(n,e,t,r){for(var o,a={},d="",i="";o=n[t].pop();)""!=d&&(d+="|"),d+=o.id,a.audienceId=o.id,i=o.url;var p="";if("undefined"!=typeof n.rnt_aud_params&&n.rnt_aud_params.length>0){p="&rnt_aud_params=";for(var s=0;s<n.rnt_aud_params.length;s++){s>0&&(p+="__"),p+=n.rnt_aud_params[s].key+"--"+n.rnt_aud_params[s].val;var c=/^.*\|.*\|(.*?)$/i;a.priceId=c.exec(n.rnt_aud_params[s].key)[1],a.productId=n.rnt_aud_params[s].val}r.add_product_event(a)}r.add_audience(a)}(window,document,"rontar_aud",this)},n}(),RontarEvent=function(){function n(n){this.props=[],this.props=RontarUtils.ToRontarProps(n)}return n.prototype.AddProp=function(n){this.props.push(n)},n.prototype.ToUrl=function(n){var e=this.props.slice(),t=RontarUtils.GetQueryParams(e);return n+"/?"+t},n}(),RontarProp=function(){function n(n,e){this.name=n,this.value=e}return n}(),RontarEventManagerInstance=new RontarEventManager(document);window.rnt?RontarEventManagerInstance.ProcessEventsQueue(window.rnt.q):RontarEventManagerInstance.ProcessEventsOld(),window.rnt=function(){RontarEventManagerInstance.ProcessEvent(arguments)};