<div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p>...</p>
        </div>
<div class="card pd-20 pd-sm-40">
 <h6 class="card-body-title">Информация о покупателе</h6>
<div class="card-body">
<ul class="list-group">
  <li class="list-group-item"><label for="date_create" class="ct-110">Имя:</label><?=$this->getOrder()->getName()?$this->getOrder()->getName():"&nbsp;"?></li>
  <li class="list-group-item"><label for="date_create" class="ct-110 control-label">Адресс:</label><?=$this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : "&nbsp;"?></li>
  <li class="list-group-item"><label for="date_create" class="ct-110 control-label">Индекс:</label><?=$this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : "&nbsp;"?></li>
  <li class="list-group-item"><label for="date_create" class="ct-110 control-label">Телефон:</label><?=$this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : "&nbsp;"?></li>
  <li class="list-group-item"><label for="date_create" class="ct-110 control-label">Email:</label><?=$this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : "&nbsp;"?></li>
</ul>

  </div>
  </div>

<script>
    $("body").keypress(function(e) {
         switch(e.originalEvent.code){
             case 'NumpadMultiply': if($('.chekAll').is(":checked")){ $('.chekAll').prop('checked', false); }else{$('.chekAll').prop('checked', true);} chekAll(); break;
             case 'NumpadAdd': pr('p_all'); break;
         }
         console.log(e.originalEvent.code);
         // if (e.which == 13) {
            //  return false;
          //}
     });
    function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
               
		}else{
                    
		$('.cheker').prop('checked', false);
		}
                 Calculat();
            return false;
        } 
     
         function Calculat(e){
var sum = 0.00;
//console.log(e);
if ($('.order-item:checked').val() && $('#order_status').val() == 1) {
jQuery.each($('.order-item:checked'), function () {
sum += Number($('#s_'+$(this).attr('name').substr(5,5)).val());
                    });
					sum+=Number($('#dop_suma').val());
					sum = sum.toFixed(2);
					$('#sum_voz').html(sum);
$('#sum_voz_all').val(sum);
return true;
					}else{
                                            $('#sum_voz').html(sum);
                                            $('#sum_voz_all').val(sum);
                                        }

return true;
}
function del_tovar(){
var list = '';
if ($('.order-item:checked').val()) {
var arr = [];
jQuery.each($('.order-item:checked'), function () {
arr.push($(this).attr('name').substr(5,5));
});
list = arr.join(',');

alert(list);
}else{
alert('Выберите товар для возврата!');
return false;
}
}
</script>

<?php if($this->error){ ?>
<div class="alert alert-danger <?php if($this->error) echo 'show';?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1">Возникли ошибки:</span>
    <ul>
		<?php foreach ($this->error as $error) { ?><li><?=$error?></li><?php } ?>
	</ul>
</div>
<?php } ?>

<form action="" method="post" class="form-horizontal" style="border: 0;padding: 0;">

<div class="card pd-20 pd-sm-40 mt-2">
    <h6 class="card-body-title">Возврат по заказу <?=$this->getOrder()->getId()?></h6>

<div class="card-body">
    <div class="row">
  <div class="col-lg-6">
		<div class="card m-2 bd  rounded-bottom">
                    <div class="card-header card-header-default">Заказ</div>

			<div class="card-body ">
			<ul class="list-group list-group-flush">
			<li class="list-group-item"><b>Товары</b></li>
                        <li class="list-group-item d-none"><label class="ckbox" data-tooltip="tooltip" title="Выделить все товары"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></li>
			<?php
			$articles = array();
			if($this->vozrat->tovar){
			$articles = explode(",", $this->vozrat->tovar);
			}
			
			if ($this->getOrder()->getArticles()->count()){
			 foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
			 if($article_rec->getCount() > 0){
			// if(!in_array($article_rec->getId(), $this->vozrat) and  in_array($this->vozrat->status, array(2,3))) continue;
			 
$price_real = $article_rec->getOldPrice()>0?$article_rec->getOldPrice():$article_rec->getPrice();
			 
			 $price_show = $article_rec->getPerc($this->getOrder()->getAllAmount());
			 
			 $sum_skudka += $price_show['minus'];
			 
$skid_show = round((1 - (($price_show['price']/$article_rec->getCount())/ $price_real)) * 100);
			 ?>
<li class="list-group-item"  >
			 <div style="    display: inline-flex;">
			 <label class="ckbox">
			 <input type="checkbox" class="order-item cheker" name="item_<?=$article_rec->getArticleId()?>_<?=$article_rec->artikul?>" onChange="return Calculat(this);">
			 <span></span>
			 </label>
<img class="prev" rel="#miesart<?=$article_rec->getId()?>" src="<?=$article_rec->getImagePath('small_basket')?>" alt="<?=htmlspecialchars($article_rec->getTitle())?>" style="width:36px;height:36px;">

<div class="simple_overlay" id="miesart<?=$article_rec->getId()?>" style="position: absolute;  margin-left: 170px; margin-top: -170px;z-index: 100;" >
<img src="<?=$article_rec->getImagePath('card_product')?>" style="max-width:500px;border-radius: 10px;">
</div>
В заказе: <?=trim($article_rec->getCount())?></br>
<?=trim($article_rec->getCode().' - '.$article_rec->sizes->getSize().' / '.$article_rec->colors->getName())?></br>
<?=trim($article_rec->getTitle())?><br>
<?php
if ($price_real != ($price_show['price']/$article_rec->getCount())){
echo '  <span style="text-decoration:line-through">'.$price_real.'</span></br>';
}?>
<?=$skid_show?'<span> - '.$skid_show.'% = </span>' : '=';?></br>
<b><?=Number::formatFloat($price_show['price'])?> грн</b>
<input type="text"  hidden id="<?='s_'.$article_rec->getArticleId()?>"value="<?=$price_show['price']?>">
					</div>
			 </li>
			<?php 
			}
			 }
			}
			?>
			<li class="list-group-item"><b>Сумма возврата: <span id="sum_voz"><?=$this->vozrat->amount+$this->vozrat->dop_suma?></span> грн.</b>
			<input type="text"  hidden id="sum_voz_all" name="sum_voz_all" value=""></li>
			</ul>
			</div>
			<div class="card-footer">
			<?php if($this->vozrat->sposob){
if($this->vozrat->dop_suma > 0){ echo 'Дополнительно зачислено '.$this->vozrat->dop_suma.' грн.<br>';}?>
			Сумма возмещения <?=$this->vozrat->amount?> грн.<br>
			<!--<button class="btn btn-small btn-default"  type="button" onClick="return del_tovar();">
			<i class="glyphicon glyphicon-copy" aria-hidden="true"></i> 
			Вернуть в продажу</button>-->
			<?php }else{ ?>
<div class="form-group row">
    <label for="comments" class="col-sm-3 col-form-label">Коментарий:</label>
    <div class="col-sm-9">
	<input type="text" class="form-control input" id="comments" value="" name="comments" placeholder="можете оствить комент">
    </div>
  </div>
	<div class="form-group row">
    <label for="dop_suma" class="col-sm-3 col-form-label">Доп.сумма:</label>
    <div class="col-sm-9">
	<input type="text" class="form-control input" value="" name="dop_suma" id="dop_suma" placeholder="введите сумму">
    </div>
  </div>
	<div class="form-group row">
    <label for="sposob" class="col-sm-3 col-form-label">Способ возврата:</label>
    <div class="col-sm-9">
	 <select name="sposob" class="form-control select2" id="sposob">
	 <option  value="0">Выберите способ возврата</option>
	 <option value="1" <?=$this->vozrat->sposob==1?"selected":''; ?>>На депозит</option>
	 <option value="2" <?=$this->vozrat->sposob==2?"selected":''; ?>>Почтовый перевод</option>
                </select>
    </div>
  </div>
			<?php }?>

  </div>
		</div>
  </div>
<div class="col-lg-6">
<div class="card m-2 bd  rounded-bottom">
    <div class="card-header card-header-default">
    Детали
  </div>
			<div class="card-body">
<div class="form-group row">
    <label for="date_create" class="col-sm-3 col-form-label">Получен:</label>
    <div class="col-sm-9">
        <input type="text" readonly class="form-control-plaintext" id="date_create" value="<?=$this->vozrat->date_create?>">
    </div>
  </div>
<div class="form-group row">
    <label for="order_status" class="col-sm-3 col-form-label">Статус:</label>
    <div class="col-sm-9">
	 <select name="order_status" id="order_status" class="form-control select2" onChange="this.form.submit(); return false;">
                    <?php foreach ($this->order_status as $key => $item) { ?>
                    <option value="<?=$key?>" <?=$key==$this->vozrat->status?"selected":''; ?>><?=$item?></option>
                    <?php } ?>
                </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="sposob" class="col-sm-3 col-form-label">Способ возврата:</label>
    <div class="col-sm-9">
	<span style="display: inline-flex;margin-top: 7px;">
	<?php $sp = array(1=>'На депозит', 2=>'Почтовый перевод'); ?>
	<?=$sp[$this->vozrat->sposob]?></span>
    </div>
  </div>

  <div class="form-group row">
    <label for="nakladna" class="col-sm-3 col-form-label">Накладная:</label>
    <div class="col-sm-9">
	<input type="text" class="form-control input" value="<?=$this->vozrat->nakladna?>" name="nakladna" id="nakladna" placeholder="Номар накладной">
    </div>
  </div>
  </div>
		</div>
  </div>

  </div>
  </div>
</div>
  </form>

<script>
$(document).ready(function () {

$('#sposob').change(function () {
var sum = 0.00;
if($(this).val() > 0){
if ($('.order-item:checked').val()) {
jQuery.each($('.order-item:checked'), function () {
sum += Number($('#s_'+$(this).attr('name').substr(5,5)).val());
});
sum+=Number($('#dop_suma').val());
sum = sum.toFixed(2);
$('#sum_voz').html(sum);
$('#sum_voz_all').val(sum);
//alert(sum);
return true;
}else{
alert('Выберите товар кторый вернули!');
$('#sposob option[value="0"]').prop('selected', true);
return false;
}
}
});


 $('.prev').hover(function () {
     $(this).parent().find('div.simple_overlay').show();
     }, function () {
     $(this).parent().find('div.simple_overlay').hide();
     });
	 
	 });


</script>

