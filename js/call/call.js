function FormRequest(){
var name=$('#contact_form input[name=name_call]').val();
var phone=$('#contact_form input[name=phone_call]').val();
if(isValidName(name)){
if(isValidPhone(phone)){
if(!document.getElementById('message_call').value==""){
$('#contact_form').before('<h3 id="contact_form_info">Оформление заявки. Подождите...</h3>');
$('#contact_form').hide();
order_url=$('#contact_form').attr('action');
$.post(order_url,{name:$('#contact_form input[name=name_call]').val(),phone:$('#contact_form input[name=phone_call]').val(),message:$('#contact_form textarea[name=message_call]').val()},function(data){
$('#contact_form_info').html(data);
},"html");}else{
alert('Укажите причину звонка!');
$('#contact_form textarea[name=message_call]').focus();}}else{alert('Неверный формат телефона!');$('#contact_form input[name=phone_call]').focus();}}else{alert('Введите имя!');$('#contact_form input[name=name_call]').focus();}return false;}

function FormOpen(){
if($('#contact_form').hide())
$('#contact_form').show();
$('#popup').fadeIn();
$('#popup').append('<a id="popup_close" onclick="FormClose()"></a>');$('body').append('<div id="fade" onclick="FormClose()"></div>');$('#fade').css({'filter':'alpha(opacity=40)'}).fadeIn();
return false;
}

function FormClose(){
$('#popup').fadeOut();
$('#fade').fadeOut();$('#fade').remove();
$('#popup_close').remove();$('#contact_form_info').remove();
}

function isValidName(valid_name){var pattern_name=new RegExp(/^[а-яА-ЯёЁa-zA-Z0-9. -]+$/);
return pattern_name.test(valid_name);}
function isValidPhone(valid_phone){
var pattern_phone=new RegExp(/[^d]/);
return pattern_phone.test(valid_phone);}