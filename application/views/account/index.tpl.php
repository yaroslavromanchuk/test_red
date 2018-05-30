<?php
	$text = explode(',', $this->trans->get('Личный кабинет,Компания,Имя,Город,Адрес,Телефон,Дата рождения,Количество заказов,Депозит,Бонус,Cмотреть все заказы,Отследить,Редактировать,Смена пароля,Выйти,Пригласить друга,Подписаться/отписаться'));
 ?>
<h1 class="green"><?=$text[0]?></h1>
<table cellspacing="0" cellpadding="4" class="basket-cont view" align="center" >
    <tbody>
    <tr>
        <td class="info"><?=$text[1]?></td>
        <td><?=$this->user->getCompanyName()?></td>
    </tr>
    <tr>
        <td class="info"><?=$text[2]?></td>
        <td><?=$this->user->getFirstName()?></td>
    </tr>
    <tr>
        <td class="info"><?=$text[3]?></td>
        <td><?=$this->user->getCity()?></td>
    </tr>
    <tr>
        <td class="info"><?=$text[4]?></td>
        <td><?=$this->user->getAdress()?></td>
    </tr>
    <tr>
        <td class="info"><?=$text[5]?></td>
        <td><?=$this->user->getPhone1()?></td>
    </tr>
    <tr>
        <td class="info">E-mail</td>
        <td><?=$this->user->getEmail()?></td>
    </tr>
	<tr>
        <td class="info"><?=$text[6]?></td>
        <td><?=date('d-m-Y', strtotime($this->user->getDateBirth()))?></td>
    </tr>
    <tr>
        <td class="info"><?=$text[7]?></td>
        <td>
            <b><?=wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $this->user->getId()))->count()?></b>
        </td>
    </tr>
   
    <?php if ($this->user->getDeposit()) { ?>
        <tr>
            <td><?=$text[8]?></td>
            <td >
                <?php echo Shoparticles::showPrice($this->user->getDeposit());?> грн.
            </td>
        </tr>
    <?php } ?>
	<?php if ($this->user->getBonus() > 0) { ?>
        <tr>
            <td><?=$text[9]?></td>
            <td>
                <?php echo Shoparticles::showPrice($this->user->getBonus());?> грн.
            </td>
        </tr>
    <?php } ?>

    </tbody>
</table>
<link rel="stylesheet" type="text/css" href="/css/np/message.css"/>
<div align="center" style="min-width: 310px; width: 100%;  max-width: 500px;top: 200px;" id="popup" >
<img  id="loading" src="/img/loading_trac.gif">
</div>
<br/>
<br/>
<div align="center">
<a href="/account/orderhistory/" class="btn btn-danger" ><?=$text[10]?></a>
<br/>
</br>

<input class="form-control" style="    max-width: 200px;font-size: 13px;text-align: center;display: inline;" name="trec" type="text" id="trec" value="" pattern="[0-9]{9,14}"  maxlength="14" placeholder="ТТН">   <button  type="button" class="btn btn-danger" onclick="tracking($('#trec').val());"><?=$text[11]?></button>
</br>

<br/>
<a href="/account/edit/" class="btn btn-danger"><?=$text[12]?></a>
<a href="/account/epass/" class="btn btn-danger"><?=$text[13]?></a>
<a class="btn btn-danger" href="/account/logout/"><?=$text[14]?></a>
<br/>
<br/>
<br/>
<a class="btn btn-danger" href="/account/invite/"><?=$text[15]?></a>
<a class="btn btn-danger" href="/subscribe/"><?=$text[16]?></a>
</div>
<script type="text/javascript">
function fopen(){
$('#popup').fadeIn();
$('#popup').append('<a id="popup_close" onclick="FormClose()"></a>');
$('body').append('<div id="fade" onclick="FormClose()"></div>');
$('#fade').css({'filter':'alpha(opacity=40)'}).fadeIn();
return false;
}
function FormClose(){
$('#popup').fadeOut();
$('#fade').fadeOut();
$('#fade').remove();
$('#popup_close').remove();
}
function tracking(x) {
if(x.length == 13 || x.length == 14) {
fopen();
$('#popup').html('<img  id="loading" src="/img/loading_trac.gif">');
if(x.length == 9  && false){

$.get('/shop/tracing/ttn/'+x+'/metod/mest/',
		function (data) {
		if(data){
		 $('#popup').html(data);
		 }
		});
		 //$('#popup').html('Сервис временно недоступен. Приносим свои извинения.');
		return false;

}else if(x.length == 13){

$.get('/shop/tracing/ttn/'+x+'/metod/ukr/',
		function (data) {
		if(data){
		 $('#popup').html(data);
		 }
		});
		//$('#popup').html('Сервис временно недоступен. Приносим свои извинения.');
		return false;
}else if(x.length == 14){
$.get('/shop/tracing/ttn/'+x+'/metod/np/',
		function (data) {
		if(data){
		 $('#popup').html(data);
		 }
		});
		return false;
}
}else{
fopen();
$('#popup').html('Ошибка в номере ТТН');

return false;
}

}
</script>