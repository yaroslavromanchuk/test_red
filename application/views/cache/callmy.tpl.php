<link rel="stylesheet" type="text/css" href="/css/call/call.css"/>
<script type="text/javascript" src="/js/call/call.js"></script>
<script type="text/javascript" src="/js/call/jquery.mask.js"></script>
<div id="popup">
  <form id="contact_form" role="form" method="post" action="/account/callmy/">
        <table>
	   <tr>
	   <td>
	   <img src="/img/call/call_photo.png">
	   </td>
	   <td><p>У Вас есть вопросы?</br> Мы Вам поможем!</p>
	</td>
	</tr></table>
	<div>
        <input type="text" name="name_call" placeholder="Ваше имя?" >
        <input type="text"  id="phone_call" name="phone_call"  class="required" placeholder="(xxx)xxx-xx-xx">
        <textarea  id="message_call" name="message_call" placeholder="Ваш вопрос?" rows="5"></textarea>
        <a class="btn button form_submit" onclick="FormRequest()">Заказать звонок</a>
		</div>
		
		
    </form>
  </div>
  <a id="callback" onclick="FormOpen()">
  <div class="circlephone" style="transform-origin: center;"></div>
  <div class="circle-fill" style="transform-origin: center;"></div>
  <div class="img-circle" style="transform-origin: center;">
  <div class="img-circleblock" style="transform-origin: center;"></div>
  </div>
  </a>
<script type="text/javascript">
jQuery(function($){
  // $("#date").mask("99/99/9999");
   $("#phone_call").mask("(999)999-99-99");
   // $("#product").mask("99/99/9999",{placeholder:" "});
   //$("#tin").mask("99-9999999");
   //$("#ssn").mask("999-99-9999");
});
</script>