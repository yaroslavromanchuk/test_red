<?php
	$text = explode(',', $this->trans->get('Личный кабинет,Компания,Имя,Город,Адрес,Телефон,Дата рождения,Количество заказов,Депозит,Бонус,Cмотреть все заказы,Отследить,Редактировать,Смена пароля,Выйти,Пригласить друга,Подписаться/отписаться'));
 ?>
<div class="modal fade" id="trek" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title"><?=$text[11]?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body" >

		</div>
		<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?=$this->trans->get('Закрыть')?></button>
		</div>
		</div>
	</div>
</div>
<div class="card text-center">
  <div class="card-header">
   <h1 class="green"><?=$text[0]?></h1>
  </div>
  <div class="card-body">
    <h5 class="card-title"><?=$this->user->getMiddleName().' '.$this->user->getFirstName()?></h5>
    <p class="card-text">
	<table cellspacing="0" cellpadding="4" class="basket-cont view" align="center" >
    <tbody>
    <tr>
        <td class="info"><?=$text[1]?></td>
        <td><?=$this->user->getCompanyName()?></td>
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
            <b><?=$this->user->getCountAllOrder()?></b>
        </td>
    </tr>
   
    <?php if (/*$this->user->getDeposit()*/true) { ?>
        <tr>
            <td><?=$text[8]?></td>
            <td >
                <?=Shoparticles::showPrice($this->user->getDeposit())?> грн.
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
	</p>
  </div>
  <div class="card-footer text-muted">
  <div class="btn-group" role="group" aria-label="Basic example">
  <a href="/account/orderhistory/" class="btn btn-danger" ><?=$text[10]?></a>
  <a class="btn btn-danger" href="/account/invite/"><?=$text[15]?></a>
<a class="btn btn-danger" href="/subscribe/"><?=$text[16]?></a>
</div><br><br>
  <div class="btn-group" role="group" aria-label="Basic example">
<a href="/account/edit/" class="btn btn-danger"><?=$text[12]?></a>
<a href="/account/epass/" class="btn btn-danger"><?=$text[13]?></a>
<a class="btn btn-danger" href="/account/logout/"><?=$text[14]?></a>
</div><br><br>
<input class="form-control" style="    max-width: 200px;font-size: 13px;text-align: center;display: inline;" name="trec" type="text" id="trec" value="" pattern="[0-9]{9,14}"  maxlength="14" placeholder="ТТН">   <button  type="button" class="btn btn-danger" onclick="tracking($('#trec').val());"><?=$text[11]?></button>
  </div>
</div>
<script>
function tracking(x) {
if(x.length == 13 || x.length == 14) {
			$("#trek .modal-body").html('<img  id="loading" src="/img/loading_trac.gif">');
			$('#trek').modal('show');
if(x.length == 9  && false){

$.get('/shop/tracing/ttn/'+x+'/metod/mest/',
		function (data) {
		if(data){
		 $("#trek .modal-body").html(data);
		 }
		});
		 //$('#popup').html('Сервис временно недоступен. Приносим свои извинения.');
		return false;

}else if(x.length == 13){

$.get('/shop/tracing/ttn/'+x+'/metod/ukr/',
		function (data) {
		if(data){
		 $("#trek .modal-body").html(data);
		 }
		});
		//$('#popup').html('Сервис временно недоступен. Приносим свои извинения.');
		return false;
}else if(x.length == 14){
$.get('/shop/tracing/ttn/'+x+'/metod/np/',
		function (data) {
		if(data){
		 $("#trek .modal-body").html(data);
		 }
		});
		return false;
}
}else{
$("#trek .modal-body").html('Ошибка в номере ТТН');
			$('#trek').modal('show');
return false;
}
}
</script>