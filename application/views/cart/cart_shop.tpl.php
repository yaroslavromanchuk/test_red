<?php
unset($_SESSION['cart']);
$text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));
if ($this->error) {
	echo "<div>";
	foreach ($this->error as $error) {
		echo '<h2>'.$error.'</h2>';
	}
	echo "</div>";
}
 ?>
<div class="container">
<?php
$list_id_arr = [];


$bool = false;
 if($this->errors) {
     $bool = true;
 }

if ($this->getCard()['articles']){
    $articles =  $this->getCard()['articles'];
   // if($this->user->id == 8005){ l( $this->getCard()['articles']); }
    $_SESSION['orders'] = $this->getCard()['articles'];
    ?>
        <form action="" method="post" name="orders" class="was-validated"  data-parsley-validate >
             <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-8  card px-0">
        <div class="card-body p-1 p-md-3">
        <h5 class="card-title mb-4">ОФОРМЛЕНИЕ ЗАКАЗА</h5>
         <?php if(count($this->card['articles']) > 1) {?>
        <div class="card-message">
                        <p class="card-text"><b><?=count($this->card['articles'])?> заказа на общую сумму <?=$_SESSION['total_price']?> грн.</b></p>
                        <div class="alert alert-warning" role="alert">В корзине находятся товары нескольких продавцов. Они будут доставлены разными посылками. Оплата каждой посылки производится отдельно.</div>
                    </div>
       <?php } ?>
        <div class="card-message">
          <!--  <div class="alert alert-danger" role="alert">
  <h4 class="alert-heading">Обратите внимание!!!</h4>
  <p>В связи с ситуацией в стране пункт выдачи заказов временно прекращает работу до окончания карантина.<br> Приносим свои извенения за временные неудобства.</p>
   <hr>
   <p><b>В связи с этим, мы делаем бесплатную доставку Justin, УкрПочта, Курьером по Киеву.</b></p>
</div>-->
        </div>    
    <?php
   // l($this->promo);
    $j = 1;
    foreach ($articles as $key => $card) {
        $s_order = 0;
        $count_ithem_order = 0;
        foreach ($card as $c) {
            $s_order+=$c['price']*$c['count'];
            $count_ithem_order+=$c['count'];
        }
       //№=$j 
        ?>
        <div class="card mb-3 ">
            <div class="card-header"><b>Заказ от <?=Shop::getNameId($key)?> на сумму <?=$s_order?> грн.</b></div>
            <div class="card-body p-1 p-md-3">
                <ul class="list-group list-group-flush">
            <?php foreach ($card as $k => $item) {
                $list_id_arr[] = $item['id'];
                ?>
                    
                    <li class="list-group-item p-1 p-md-3">
                        <div class="row m-0">
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 text-center">
                <a href="<?=$item['path']?>" class="img_pre" >
                    <img src="<?=$item['img']?>"  alt="<?=$item['title']?>" />
                </a>
            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 pt-lg-4">
                                <b><?=$item['title']?></b></br>
                                <?=$text[0]?>:<?=$item['color']?> | <?=$text[1]?>:<?=$item['size']?>
                            </div>
                            <div class="col-4 col-sm-2 col-md-4 col-lg-2 col-xl-2 text-center pt-lg-4">
                                <?php if($item['first_price'] != $item['price']){ ?>
                                <span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;"><?=$item['first_price']?></span><br>
                                <?php } ?>
                                <?php //$item['skidka']?'<span style="font-size: 10px;color:red;font-weight: bold;position: relative;top: -5px;"> '.$item['skidka'].'</span><br>':''?>
                                <span class="d-block"><?=Shoparticles::showPrice($item['price'])?> грн</span>
                  
    
                            </div>
                            <div class="col-2 col-sm-2 col-md-3 col-lg-1 col-xl-2 pt-3 pt-lg-5 text-center">
                <?php
                if($item['size_count']){
                if($item['size_count'] > 1){ ?>
                       <select  class="form-control" 
				onchange="document.location='<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-change')->getPath()."point/{$k}/count/"; ?>'+this.value+'/';">
                    <?php
                        for ($i = 1; $i <= $item['size_count']; $i++){
                                 echo ($i != $item['count']) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
    }
	?>
		</select>
                <?php }else{
                    echo $item['count']; }
                }else{ ?>
                                <div class="alert alert-danger" style="font-size: 10px;">
                                    <span>ОШИБКА<br>Сожелеем, но этот товар уже купили.</span>
                                </div>
              <?php  }
                ?>
                                
                            </div>
                            <div class="col-4 col-sm-2 col-md-4 col-lg-2 col-xl-2 pt-3 pt-lg-5 text-center">
                                 <p><?=Shoparticles::showPrice($item['price']*$item['count']);?> грн</p>
                            </div>
                            <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 text-center pt-3 pt-lg-5">
                                <a class="delete_basket_item" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$k}/"; ?>" onclick="return confirm('<?=$this->trans->get('Удалить товар из корзины?'); ?>')" data-tooltip="tooltip" title="Удалить">
		<i class="" aria-hidden="true" ></i>
				</a>
                            </div>
            <?=$item['comment']?'<div class="col-xs-12 m-auto">'.$item['comment'].'</div>':''?>
                            <?php 
                            if($item['warning']){ ?>
                            <div class="col-xl-12 m-auto">
			<div class="alert alert-danger">
			<span class="attention"><?=$this->trans->get('Будьте внимательны, заказывая этот товар! Бельё не подлежит обмену и возврату'); ?></span><br>
			<span class="attention"><?=$this->trans->get('Примарка и возврат белья возможены только в пунктах самовывоза после оплаты заказа.'); ?></span>
				</div>
                            </div>  
                           <?php }
                            ?>
                        </div>
                    </li>
               <?php  } ?>
                      </ul>
            </div>
            <div class="card-footer">
                <div class="row delivery_box">
                    <div class="col-sm-12 col-md-4">
                        <?php if($count_ithem_order > 5){
    echo '<div class="alert alert-danger" role="alert">
  <p>Доставка в розничные магазины: ограничение 5 товаров.<br>В заказе '.$count_ithem_order.'.</p>
</div>';
} ?>
<input type="text" name="dostavka[<?=$key?>][amount]" value="<?=$s_order?>" class="hidden" >
<div class="form-group form-group-sm ">
<label for="delivery_type_id_<?=$key?>"><?=$this->trans->get('Способ доставки')?> <span class="red">*</span> <?php if($bool and @$this->errors['delivery']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['delivery'].'</span>'; ?></label>
<div id="delivery_<?=$key?>">
<select name="dostavka[<?=$key?>][delivery_type_id]" id="delivery_type_id_<?=$key?>" data-placeholder="Выбрать" onchange="loadDelivery(this, <?=(int)$s_order?>)" class="form-control select2" data-parsley-class-handler="#delivery_<?=$key?>" data-parsley-errors-container="#d_sl_<?=$key?>_ErrorContainer" required>
<option label="Выбрать"></option>
<?php
$sql = "SELECT ws_delivery_payments.*
FROM `ws_delivery_payments`
inner join ws_delivery_types ON `ws_delivery_payments`.delivery_id = ws_delivery_types.id ";
$sql .= " WHERE ws_delivery_payments.`active` = 1
AND ws_delivery_payments.delivery_id !=16 
and  ws_delivery_types.active_user = 1
";
/*
if(!$this->ws->getCustomer()->isAdmin()){
    $sql .= " and ws_delivery_types.active_user = 1";
     
}else{
    $sql .= " or ws_delivery_types.id = 20";
}*/

//$sql .= " AND delivery_id !=3 ";

//$data = [
   // 'active'=> 1,
   // 'delivery_id != 16',
//];
if($count_ithem_order > 5){
   $sql .= " AND ws_delivery_payments.delivery_id !=20 ";
   // $data[] = 'delivery_id != 3';
} 
$sql .=" GROUP BY ws_delivery_payments.`delivery_id`";
//wsActiveRecord::useStatic('DeliveryPayment')->findAll($data, array('sort'=>'ASC'))
foreach(wsActiveRecord::useStatic('DeliveryPayment')->findByQuery($sql) as $dely){ ?>
<option value="<?=$dely->delivery_id?>"><?=$dely->delivery->getName()?></option>
<?php } ?>
</select>
    <div id="d_sl_<?=$key?>_ErrorContainer"></div>
</div>
</div>
<div class="dop_delivery_pay_<?=$key?>"></div>
                    </div>
<div class=" col-sm-12 col-md-8">
<div class="row dop_delivery_info_<?=$key?> "></div>
</div>

                </div>
            </div>
        </div>
    <?php $j++; } ?>
        </div>
        
        
                </div>
            
       
        <div class="col-sm-12 col-md-12 col-lg-4">
            <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?=$this->trans->get('Контактные данные')?></h5>
                        
        <div class="row">
<div class="form-group form-group-sm col-sm-12 col-md-6">
<label for="email">E-mail <span class="red">*</span>  <?php if($bool and $this->errors['email']){ echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['email'].'</span>';} ?></label>
<?php if($this->ws->getCustomer()->getIsLoggedIn()){ ?>
<input class="form-control" name="email" id="email" type="email" required value="<?=$this->ws->getCustomer()->getEmail()?>" >
<?php }else{?>
<input class="form-control" name="email" id="email" type="email" required value="<?=$this->post->email?>" >
<?php }?>
</div>
<div class="form-group form-group-sm col-sm-12 col-md-6">
<label for="telephone"><?=$this->trans->get('Телефон')?> <span class="red">*</span> <?php if($bool and @$this->errors['telephone']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['telephone'].'</span>'; ?></label>
<?php if($this->ws->getCustomer()->getIsLoggedIn()){ ?>
<input class="form-control" name="telephone" data-parsley-pattern="\+38\s?[\(]{0,1}0[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}" type="tel" id="telephone" required placeholder="+38(000)000-00-00" value="<?=$this->ws->getCustomer()->getPhone1()?$this->ws->getCustomer()->getPhone1():''?>" >
<?php }else{?>
   <input class="form-control" name="telephone" data-parsley-pattern="\+38\s?[\(]{0,1}0[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}" type="tel" id="telephone" required placeholder="+38(000)000-00-00" value="<?=$this->post->telephone?>" > 
<?php }?>
<div id="maske_phone1"></div>
</div>
<div class="form-group form-group-sm col-sm-12 col-md-6">
<label for="name"><?=$this->trans->get('Имя')?> <span class="red">*</span> <?php if($bool and @$this->errors['name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['name'].'</span>'; ?></label>
<?php if($this->ws->getCustomer()->getIsLoggedIn()){ ?>
<input class="form-control" name="name" type="text" id="name" required="" value="<?=$this->ws->getCustomer()->first_name?>">
<?php }else{ ?>
<input class="form-control" name="name" type="text" id="name" required="" value="<?=$this->post->name?>">
<?php }?>
</div>
<div class="form-group form-group-sm col-sm-12 col-md-6">
<label for="middle_name"><?=$this->trans->get('Фамилия')?> <span class="red">*</span> <?php if($bool and @$this->errors['middle_name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['middle_name'].'</span>'; ?></label>
<?php if($this->ws->getCustomer()->getIsLoggedIn()){ ?>
<input class="form-control" name="middle_name" type="text" id="middle_name" required value="<?=$this->ws->getCustomer()->middle_name?>">
<?php }else{ ?>
<input class="form-control" name="middle_name" type="text" id="middle_name" required value="<?=$this->post->middle_name?>">
<?php }?>
</div>

<div class="form-group form-group-sm col-sm-12 col-md-12">
<label for="comments"><?=$this->trans->get('Коментарий к заказу')?> </label>
<input class="form-control" name="comments" id="comments" type="text"  value="<?=$this->post->comments?>" >
</div> 
<div class="form-group col-sm-12 col-md-12">
     <?php $track = '';
                            if(isset($_COOKIE["track"])){
                                $track = $_COOKIE["track"];
                            }elseif(isset($_COOKIE['utm_email_track'])){
                                 $track = $_COOKIE['utm_email_track'];
                            }elseif(isset($_COOKIE['utm_source'])){
                                 $track = $_COOKIE['utm_source'];
                            }
                            ?>
                        <input type="text" name="track" value="<?=$track?>" class="hidden">
                        <input type="text" name="customer_id" value="<?=$this->ws->getCustomer()->getIsLoggedIn()?$this->ws->getCustomer()->id:''?>" class="hidden">
                        <input type="text" name="skidka" value="<?=$this->ws->getCustomer()->getIsLoggedIn()?$this->ws->getCustomer()->real_skidka:''?>" class="hidden">
                        <input type="text" name="kupon" value="<?=$this->promo->cod?$this->promo->cod:NULL?>" class="hidden">
                        <input type="text" name="kupon_price" value="<?=$this->promo->value?$this->promo->value:0?>" class="hidden">
                        
<div id="cbWrapper" class="parsley-checkbox mg-b-0">
  <label class="ckbox">
    <input type="checkbox" name="soglsiye[]" value="soglas"
           data-parsley-mincheck="2"
    data-parsley-class-handler="#cbWrapper"
    data-parsley-errors-container="#cbErrorContainer" required><span><?=$this->trans->get('Я выражаю согласие на обработку моих персональных данных')?></span>
  </label>
  <label class="ckbox">
    <input type="checkbox" name="soglsiye[]" value="oznak"><span><?=$this->trans->get('Я ознакомлен с <a style="text-decoration: underline;color: blue;" href="/terms/" target="_blank">условиями предоставления услуг</a> интернет-магазина RED.ua"')?></span>
  </label>
</div><!-- parsley-checkbox --> 
<div id="cbErrorContainer"></div>
</div>
   <?php if (!$this->ws->getCustomer()->getIsLoggedIn()) { ?>
            <div class="form-group form-group-sm col-sm-12 col-md-12">
	<div class="alert alert-warning"  role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<p>Делали у нас заказ ранее?<br>Для оформления заказа, войдите в свой личный кабинет.</p>
  <a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style=" text-decoration: none;">
  <button class="btn btn-primary m" data-dismiss="alert"  aria-hidden="true">Войти</button>
	</a>
</div>
            </div>
<?php } ?>
            </div>

                    </div>
                    <div class="card-footer">
                          <div class="text-center mb-3" >
           <button class="btn btn-danger btn-lg" type="submit" value="1" name="orders_create_submit"><?=$text[6]?></button>
           <!-- <a class="btn btn-outline-secondary " style="text-transform: uppercase;" role="button" href="<?php
						//echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>"><?=$text[5]?>
                                    </a>-->
            </div>
                        <?php if(true){
                            if(!empty($_SESSION['promo'])){ ?>
                        <div class="text-center" >
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
    <span class="input-group-text">Промокод:</span>
  </div>
                                <input type="text" class="form-control" disabled="true" value="<?=$_SESSION['promo']?>">
  <div class="input-group-append">
      <button class="btn btn-outline-danger" onclick="deletePromo();return false;"  type="button">Удалить</button>
  </div>
</div>
                        </div>
                        <script>function deletePromo(){
 $.ajax({  url: "/ajax/deletepromo/",  method: "POST",  dataType: "json"});  
  setTimeout(location.reload(), 1000);
}
</script>
                          <?php  }else{
                            ?>
                        <div id="all_promo_box">
                        <div id="promo_box" class="text-center" >
                            <button class="btn btn-link" data-toggle="collapse" id="promo_button" data-target="#promo" aria-expanded="false" aria-controls="promo">
          Активировать промокод
        </button>
                        </div>
                        <div  id="promo" class="collapse" aria-labelledby="promo_box"  >
		<div class="card-body"></div>
                
		</div>
                        <script>
                            
$('#promo').on('show.bs.collapse', function () {
var inp = '<div class="input-group mb-3"><input type="text" class="form-control"  id="active_promo"  data-parsley-errors-container="#promo_error" placeholder="промокод" aria-label="Промокод" ><div class="input-group-append"><button class="btn btn-outline-secondary" onclick="activePromo($(\'#active_promo\').val()); return false;" type="button">Активировать</button></div></div><div id="promo_error"></div>';
$('#promo .card-body').html(inp);
});
$('#promo').on('hidden.bs.collapse', function () {$('#promo .card-body').html('');});
function activePromo(val){
    if(val !==''){
       // alert(val);
        $.ajax({  url: "/ajax/setpromo/",  method: "POST",  data: { promo : val },  dataType: "json",
            success: function (res) {
                if(res.flag){
                    $('#all_promo_box').html('<span style="color:green">'+res.message+'</span>');
                 //location.reload();
                 setTimeout(location.reload(), 1000);
                }else{
                    $('#promo_error').html('<span style="color:red">'+res.message+'</span>'); 
                }
                
            }, error: function(e){
                $('#promo_error').html(e);
            }
        });   
    }else{
        alert('Введите промокод!!!');
        $('#active_promo').focus();
    }
    return false;
}

                        </script>
                        </div>
                        <?php } }?>
        </div>
                </div>
        </div>
           
        </div>
             </form>
<?php } else { ?>
<div class="row m-auto">
    <div class="col-12 text-center">
        <div class="card">
            <div class="card-body">
    <h3><?=$this->trans->get('Корзина пуста')?></h3>
    <a class="btn btn-outline-secondary btn-lg mt-3 " style="text-transform: uppercase;" role="button" href="/new/all/"><?=$text[5]?>
                                    </a>
  </div>
        </div>
        
    </div>
</div>
	
<?php }
$list_id = implode(',', $list_id_arr);
?>
</div>
<div class="row m-auto"><?=$this->render('/pages/sliders/basket.php')?></div>

<script>

    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_shopping_cart_event', {advId: '20676', priceId: '3047', productIds: '<?=$list_id?>'});
    //<!-- EVENTS FINISH -->
</script>
<script>
                                           
var dep = false;
var coin = false;

function Perschet(){
    if($('#kupon').val()){
location.replace("/basket/kupon/"+$('#kupon').val()+"/");
    }else{
        $('#kupon').focus();
    }
    return false;
}
function dellKupon(){
    location.replace("/basket/dellkupon/dell/");
}

	var real_dep = parseFloat($('.val_deposit').html());
	var real_sum = parseFloat($('.val_sum').html());
        var real_bonus = parseFloat($('.val_bonus').html());
        var real_coin = parseFloat($('.val_coin').html());
                                                                
$('.deposit_click').change(function () {
	if ($(this).prop('checked')) {
            dep = true;
            var s = real_sum;
             if(coin){
                  $('.val_coin').html(real_coin.toFixed(2).replace('.', ','));
                        $('.coin_click').attr('checked', false);
                        coin = false;
		/*var c = real_coin;
                 if((s-c) < 0){
                     c -= s;
                     s = 0;
                 }else{
                     s -=c;
                     c = 0;
                 } */
                }
                //if(s <= 0){ return false;}
		var d = real_dep;
                if((s-d) < 0){
                    d-=s;
                    s = 0;
                }else{
                    s -= d;
                    d = 0;
                }                                           
		$('.val_deposit').html(d.toFixed(2).replace('.', ','));
		$('.val_sum').html(s.toFixed(2).replace('.', ','));                                                                
   $.ajax({  url: "/ajax/setorderamountbasket/",  method: "POST",  data: { amount : s },  dataType: "json"});   
	} else {
            dep = false;
            if(coin){
                var s = parseFloat(real_sum);
		var c = parseFloat(real_coin);
                 if((s-c) < 0){
                     c -= s;
                     s = 0;
                 }else{
                     s -=c;
                     c = 0;
                 } 
                  var rede = real_dep;
                    var rels = s;
                }else{
                    var rede = real_dep;
                    var rels = real_sum;
                }
		$('.val_deposit').html(rede.toFixed(2).replace('.', ','));
		$('.val_sum').html(rels.toFixed(2).replace('.', ','));
$.ajax({  url: "/ajax/setorderamountbasket/",  method: "POST",  data: { amount : rels },  dataType: "json"});
	}
});
        $('.coin_click').change( function(){
            if($(this).is(":checked")){
                coin = true;
                var s = real_sum;
                var c = real_coin;
                if((s-c) < 0){
                    c-=s;
                    s = 0;
                }else{
                    s -= c;
                    c = 0;
                }
              /*  if(s <= 0){ 
                    if(dep){
                        $('.val_deposit').html(real_dep.toFixed(2).replace('.', ','));
                        $('.deposit_click').attr('checked', false);
                        dep = false;
                    }
            }else*/ if(dep){
                $('.val_deposit').html(real_dep.toFixed(2).replace('.', ','));
                        $('.deposit_click').attr('checked', false);
                        dep = false;
		/*var d = real_dep;
                 if((s-d) < 0){
                     d -= s;
                     s = 0;
                 }else{
                     s -=d;
                     d = 0;
                 } */
                }
                
            $(".val_coin").html(c.toFixed(2).replace('.', ','));
            $('.val_sum').html(s.toFixed(2).replace('.', ','));
               $.ajax({  url: "/ajax/setorderamountbasket/",  method: "POST",  data: { amount : s },  dataType: "json"});  
            }else{
                coin = false;
                
                if(dep){
                    var s = real_sum;
                    var d = real_dep;
                    if((s-d) < 0){
                    // d -= s;
                     rels = 0;
                 }else{
                     rels = s-d;
                    // d = 0;
                 }
                    var relc = real_coin;
                }else{
                    var relc = real_coin;
                    var rels = real_sum;
                }

                $(".val_coin").html(relc.toFixed(2).replace('.', ','));
            $('.val_sum').html(rels.toFixed(2).replace('.', ','));
            
               $.ajax({  url: "/ajax/setorderamountbasket/",  method: "POST",  data: { amount : rels },  dataType: "json"});  
            }
        });						
</script>