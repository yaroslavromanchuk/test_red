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
//if($this->user->id == 8005){
    //l($this->card);
    
   // echo count($this->card['articles']);
//}

if ($this->getCard()['article']){ ?>
<form action="<?=wsActiveRecord::useStatic('Menu')->findByUrl('basket')->getPath()?>" method="post" id="basket1" class="cart-table1" >
    <div class="row m-auto">
        <div class="col-sm-12 col-md-12 col-lg-12  p-1 m-auto">
            
                <div class="row m-auto">
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 p-1">
                        <div class="card">
                            <div class="card-header"><b><?=$this->trans->get('Товары')?></b> <small class="text-muted float-right"><a class="" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('card-clear')->getPath()?>" onclick="return confirm('<?=$this->trans->get('clear card')?>')" data-tooltip="tooltip" title="Очистить корзину">
                                        <i class="icon ion-ios-nuclear-outline clear_card"></i>
				</a></small></div>
                        <div class="card-body p-1 ">
                <?php
                foreach($this->getCard()['article'] as $key => $item){ if($item['count'] > 0){
                    $list_id_arr[] = $item['id'];
                    ?>
                    <div class="card mb-1">

        <div class="row m-0">
            <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 text-center">
                <a href="<?=$item['path']?>" class="img_pre" rel="#imgiyem<?=$item['id']?>"><img src="<?=$item['img']?>"  alt="<?=$item['title']?>" /></a>
                <div class="simple_overlay" id="imgiyem<?=$item['id']?>" style="position: fixed;top: 20%;left: 30%; z-index:100">
                                    <img src="<?=$item['img_big']?>" alt="<?=$item['title']?>"/>
                </div>
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
                       <select name="select" class="form-control" 
				onchange="document.location='<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-change')->getPath()."point/{$key}/count/"; ?>'+this.value+'/';">
                    <?php
                        for ($i = 1; $i <= $item['size_count']; $i++){
                                 echo ($i != $item['count']) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
    }
	?>
		</select>
                <?php }else{ echo $item['count']; }
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
                                <a class="delete_basket_item" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$key}/"; ?>" onclick="return confirm('<?=$this->trans->get('Удалить товар из корзины?'); ?>')" data-tooltip="tooltip" title="Удалить">
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
                         
                    </div>
                <?php } }?>
                   </div>
                    <div class="card-footer">
                        <small class="text-muted"><?=$this->trans->get('tovarov')?> <?=$_SESSION['count_basket']?> шт.</small>
                    </div>
                           </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 p-1">
                        <div class="card ">
                            <div class="card-header"><b><?=$this->trans->get('Всего')?></b></div>
                            <div class="card-body p-2">
                                <?php if(/*$this->ws->getCustomer()->id == 8005*/true){ 
                                    $coin = $this->ws->getCustomer()->getSummCoin('active');
                                    $deposit = $this->ws->getCustomer()->getDeposit();
                                    if($coin and $deposit){ ?>
                                        
                                        <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Бонус')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_coin">
						<?=Shoparticles::showPrice($coin)?>
					</strong>
                                        <strong> redcoin</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Ваш депозит')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_deposit"><?=Shoparticles::showPrice($deposit)?></strong><strong> грн.</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						Использовать:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <div class="">
                                            <label class="ckbox" >
  <input class=" coin_click" type="checkbox" name="coin"   >
  <span>Redcoin</span>
  </label>
                                            <label class="ckbox" >
  <input class=" deposit_click" type="checkbox" name="deposit">
  <span>Депозит</span> 
  </label>
</div>
                                    </div>
                                </div>
                                        
                                   <?php }else{
                                
                                     if($coin){ ?>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Бонус')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_coin">
						<?=Shoparticles::showPrice($coin)?>
					</strong>
                                        <strong> redcoin</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Использовать бонус')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                         <label class="ckbox" >
  <input class="coin_click" type="checkbox" name="coin">
  <span>Redcoin</span>
  </label>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if ($deposit) { ?>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Ваш депозит')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_deposit"><?=Shoparticles::showPrice($deposit)?></strong><strong> грн.</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Использовать депозит'); ?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                           <label class="ckbox" >
  <input class="deposit_click" type="checkbox" name="deposit">
  <span>Депозит</span>
  </label>

                                     <!--   <input type="checkbox" name="deposit" class="deposit_click m-0"  style="vertical-align: middle;" <?php //if ($this->ws->getCustomer()->getDeposit() <= 0) { echo 'disabled="disabled"';}?>/>-->
                                    </div>
                                </div>					
			<?php } 
                                    }
                                }else{
                                    if ($this->ws->getCustomer()->getDeposit()) { ?>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Ваш депозит')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_deposit"><?=Shoparticles::showPrice($this->ws->getCustomer()->getDeposit())?></strong><strong> грн.</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Использовать депозит'); ?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <input type="checkbox" name="deposit" class="deposit_click m-0"  style="vertical-align: middle;" <?php if ($this->ws->getCustomer()->getDeposit() <= 0) { echo 'disabled="disabled"';}?>/>
                                    </div>
                                </div>					
			<?php } 
}
                        ?>
                      <?php  if(/*$this->ws->getCustomer()->getBonus()*/ false){ ?>
                             <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Бонус')?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <strong class="val_bonus"><?=Shoparticles::showPrice($this->ws->getCustomer()->getBonus())?></strong><strong> грн.</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-7">
                                        <strong>
						<?=$this->trans->get('Использовать бонус'); ?>:
					</strong>
                                    </div>
                                    <div class="col-5">
                                        <input type="checkbox" name="bonus" class="bonus_click m-0" value="1" style="vertical-align: middle;" <?php
					if ($this->ws->getCustomer()->getBonus() <= 0) { echo 'disabled="disabled"';}?>/>
                                    </div>
                                </div>
                     <?php   } ?>
                                
                                
                               <!-- <div class="row m-2">
                                <div class="col-8"><b><?php //$this->trans->get('Сумма скидки')?>:</b></div>
                                <div class="col-4"><b><?php //Shoparticles::showPrice($this->card['total_price_minus'])?> грн.</b></div>
                            </div>-->
                            <div class="row m-2">
                                <div class="col-7"><b><?=$this->trans->get('Всего к оплате')?>:</b></div>
                                <div class="col-5"><b class="val_sum"><?=Shoparticles::showPrice($this->card['total_price'])?> грн.</b><strong>грн.</strong></div>
                                <!--<div class="col-12 alert " id="dop_s" style="font-size: 12px;"></div>-->
                            </div>
                            <?php if(false){ //$this->ws->getCustomer()->id == 8005
                                   ?>
                              
                                <?php if($this->kupon_a){
                                       echo '<div class="row m-2" style="font-size: 0.8em;">
                                                <div class="col-12">
                                                    <div class="alert alert-info">'.$this->kupon_a['message'].'</div>
                                                </div>
                                            </div>';
                                    }
                                    ?>
                                <?php if($this->kupon and $this->kupon['flag']){ ?>
                                   <div class="row m-2">
                                <div class="col-7"><b><?=$this->trans->get('Промокод')?>:</b></div>
                                <div class="col-5"><b><?=$this->kupon['cod']?></b><botoom data-tooltip="tooltip" style="padding: 5px;margin-left: 5px;cursor: pointer" onClick='dellKupon(); return false;' data-original-title="Удалить промокод" ><i class="icon ion-ios-close" style="font-size: 1rem"></i></botoom></div>
                            </div> 
                                <?php }else{ ?>
                                <div class="row m-2">
                                    <div class="col-12 text-right">
                                      <a tabindex="0" href="#" style="color: grey;" class="" data-container="body" data-popover="popover"   data-tooltip="tooltip" data-original-title="Активируйте свой промокод" 
  data-html="true"
data-content="<div class='form-group mx-sm-3 mb-2 text-center'><input type='text' autofocus class='form-control' id='kupon' pattern='[A-Za-z0-9_]{15}' maxlength='15'  ><button type='bottom' onClick='Perschet(); return false;' class='btn btn-primary my-2 btn-sm'>Активировать</button></div>">
                                
                                <?=$this->trans->get('Добавить промокод')?>
                                </a>
                                </div>
                                </div>
                              <?php  }
                                } ?>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                <div class="col-12 text-center">
                                    <input type="submit" name="tostep2" style="text-transform: uppercase;" value="<?=$text[6]?>" class="btn btn-outline-danger btn-lg" >
                                </div>
                                <?php if ($this->get->metod != 'frame') { ?>
                                 <div class="col-12 text-center mt-2">
                                     <a class="btn btn-outline-secondary " style="text-transform: uppercase;" role="button" href="<?php
						echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>"><?=$text[5]?>
                                    </a>
                                 </div>
                                <?php } ?>
                            </div>
                                <?php 
                                if($_SESSION['count_basket'] > 5){
echo '<div class="row pt-3"><div class="col-xs-10 col-xs-offset-1">
<div class="alert alert-danger">'.$this->trans->get('Обратите внимание, мы ограничили количество единиц товара в заказе для пунктов выдачи до 5 единиц.</br>
				У Вас в корзине').' '.$_SESSION['count_basket'].' '.$this->trans->get('единиц товара. Если Вы хотите оформить заказ через пункт выдачи, удалите из корзины').'  '.($_SESSION['count_basket']-5).' '.$this->trans->get('единиц').'.
				</div>
			</div></div>';
} ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            
            </div>
        </div>
	</form>

<?php
}else { ?>
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
    	$(document).ready(function () {
		$('a.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
        
							});                                            
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