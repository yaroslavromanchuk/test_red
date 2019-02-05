<?php
$text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));
if (isset($this->error)) {
	echo "<div>";
	foreach ($this->error as $error) {
		echo '<h2>'.$error.'</h2>';
	}
	echo "</div>";
}
if($_SESSION['count_basket'] > 5){
echo '<div class="col-xs-10 col-xs-offset-1">
<div class="alert alert-danger">'.$this->trans->get('Обратите внимание, мы ограничили количество единиц товара в заказе для пунктов выдачи до 5 единиц.</br>
				У Вас в корзине').' '.$_SESSION['count_basket'].' '.$this->trans->get('единиц товара. Если Вы хотите оформить заказ через пункт выдачи, удалите из корзины').'  '.($_SESSION['count_basket']-5).' '.$this->trans->get('единиц').'.
				</div>
			</div>';
} ?>

<?php if ($this->getBasket()) { ?>

<form action="<?=wsActiveRecord::useStatic('Menu')->findByUrl('basket')->getPath()?>" method="post" id="basket1" class="cart-table1" >
        <div class="row m-auto">
    <?php

	$sum_order = 0.00;
        
	$total_price = 0.00;
        
	$to_pay = 0;
        
	$to_pay_minus = 0.00;
        
	$now_orders = 0;
        
	$skidka = 0;
        
	$kupon = 0;
        
	$event_skidka = 0;
        
	if(isset($_SESSION['kupon'])){
	$kupon = $_SESSION['kupon'];
	}elseif(isset($_SESSION['error_cod'])){
	echo '<tr><td colspan="6"><div class="alert alert-danger">'.$_SESSION['error_cod'].'!</div></td></tr>';
	}
        
	if ($this->ws->getCustomer()->getIsLoggedIn()) {
		$skidka = $this->ws->getCustomer()->getDiscont(false, 0, true);
		$event_skidka = EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
		$now_orders = $this->ws->getCustomer()->getSumOrderNoNew();
	}
       // echo $event_skidka;
	$mas_akciya = [];
	$mas_akciya_futbolki = [];
	$min = [];
	$aks = [];
        $sp = [];
	$a_p = 0;
	$chasy = '';
	foreach ($this->getBasket() as $key => $item) {
            $sp[] = $item['article_id']; 
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
			$sum_order += $article->getPriceSkidka($this->ws->getCustomer()->getId()) * $item['count'];
			
		}	
	}
        $list_id = implode(',', $sp);//rontar
	foreach ($this->getBasket() as $key => $item){
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
		$_SESSION['basket'][$key]['option_price'] = 0;
		$_SESSION['basket'][$key]['option_id'] = 0;
		$size = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $article->getId(), 'id_size' => $item['size'], 'id_color' => $item['color']]);
		
		if(/*$article->getId().'_'.$size->code == $chasy*/false){
		$price = $article->getPerc(($now_orders+$sum_order), $item['count'], $skidka, 99, $kupon, $sum_order);
                
                }else{
		$mes = '';
		$price = $article->getPerc(($now_orders+$sum_order), $item['count'], $skidka, $event_skidka, $kupon, $sum_order);
		}
		
		if($price['option_id']) {$_SESSION['basket'][$key]['option_id'] = $price['option_id'];}
		
		if($price['option_price']) {$_SESSION['basket'][$key]['option_price'] = $price['option_price'];}
		if($price['skidka_block']) {$_SESSION['basket'][$key]['skidka_block'] = $price['skidka_block'];}
			$to_pay += $price['price'];
			$to_pay_minus += $price['minus'];		
?>

        <div class="col-xl-8 m-auto">
<div class="card ">
<div class="row m-auto p-3 w-100 text-center">

    <div class="col-xl-3">
        <a href="<?=$article->getPath()?>" class="img_pre" rel="#imgiyem<?=$article->getId()?>">
				<img src="<?=$article->getImagePath('listing')?>" style="max-width:150px;"  alt="<?=htmlspecialchars($article->getTitle());?>" />
			</a>
			<div class="simple_overlay" id="imgiyem<?=$article->getId(); ?>" style="position: fixed;top: 20%;left: 30%; z-index:100">
                    <img src="<?=$article->getImagePath('detail'); ?>" 
                         alt="<?=htmlspecialchars($article->getTitle()); ?>"/>

                </div>
    </div>
    <div class="col-xl-3">
        <b><?=$article->getTitle()?></b></br>
		<?=$text[0]?>:<?=$size->color->name?> | <?=$text[1]?>:<?=$size->size->size?>
		<br><?=$mes?>	<?php if($price['comment']){ echo $price['comment'];} ?> 
    </div>
    <div class="col-xl-1">
        <?php
		$FirsPrrice = $article->getFirstPrice();
		$pr = '';
						$pric = '';
						$skid = '';

						if($FirsPrrice != ($price['price']/$item['count'])){
						$pr = $FirsPrrice; 
						$skid = '  -'.ceil(100- ((($price['price']/$item['count'])/$pr)*100)).'%';
						}
						echo '<span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;">'.$pr.'</span><span style="font-size: 10px;color:red;font-weight: bold;position: relative;top: -5px;"> '.$skid.'</span><br>'.Shoparticles::showPrice($price['price']/$item['count']); ?> грн
						
		
    </div>
    <div class="col-xl-2">
        <?php
if($size->count){
if($size->count > 1){
?>
<select name="select" class="form-control" style="width: 50%;"
				onchange="document.location='<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-change')->getPath()."point/{$key}/count/"; ?>'+this.value+'/';">
<?php
for ($i = 1; $i <= $size->count; $i++){
    echo ($i != $item['count']) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
    
    }
	?>
		</select>
<?php	
}else{
echo $size->count;
}
                }else{
                    echo 'С этим товаром произошла ошибка. Удалите его с корзины и заново добавьте.';
                    
                }
?>
    </div>
    <div class="col-xl-1">
        <p><?=Shoparticles::showPrice($price['price']);?> грн</p>
    </div>
    <div class="col-xl-1">
       <a class="delete_basket_item" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$key}"; ?>" onclick="return confirm('<?=$this->trans->get('Удалить товар из корзины?'); ?>')" data-tooltip="tooltip" title="Удалить">
		<i class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;top: -6px;"></i>
				</a>
    </div>


<?php if (in_array($article->getCategoryId(), array(74, 84, 137, 138, 139, 157, 158, 249, 140, 163, 306, 297, 307, 296, 3))) { ?>
    <div class="col-xl-12 m-auto">
			<div class="alert alert-danger">
			<span class="attention"><?=$this->trans->get('Будьте внимательны, заказывая этот товар! Бельё не подлежит обмену и возврату'); ?></span><br>
			<span class="attention"><?=$this->trans->get('Примарка и возврат белья возможены только в пунктах самовывоза после оплаты заказа.'); ?></span>
				</div>
				
</div>
<?php 	} ?>
      </div>
    </div>
             </div>
<?php 	}
	}
?>
   
        <div class="col-xl-8 m-auto">
  <div class="card "> 
    <div class="row m-auto w-100">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 m-auto">
<table class="table">
	<?php //для не активных пользователей
				if(false){
			//if ($this->ws->getCustomer()->getIsLoggedIn() and $total_price > 350) {
			if(/*$this->ws->getCustomer()->isNoActive() and $this->ws->getCustomer()->getId() == 8005*/$this->ws->getCustomer()->getIsLoggedIn()){
			?>
			<tr>
			<td class="text-left" colspan="4">
			<strong>
			<?php echo $this->trans->get('Код с рассылки'); ?>:
			</strong>
			</td>
			<td class="text-right1" style="text-align: -webkit-center;" >
			<input type="text" name="kupon" class="form-control" id="kupon" value="<?=@$this->kupon?$this->kupon:''?>" style="width: 150px; " pattern="[A-Za-z0-9]{7}" maxlength="7" placeholder="123f8T1"/>
			</td>
			<td class="text-right" >
			<input type="button" class="btn btn-secondary btn-sm"  name="perschet" id="perschet" onClick="Perschet(); return false;" value="<?=$this->trans->get('Пересчитать')?>">
			</td>
			</tr>
			<?php }
			}?>
			<tr>
				<td class="text-left" colspan="4">
					<strong>
					<?=$this->trans->get('Сумма скидки'); ?>:
					</strong>
				</td>
				<td class="text-right" colspan="2" >
					<strong>
						<?=Shoparticles::showPrice($to_pay_minus); ?> грн
					</strong>
				</td>
			</tr>
			<?php if ($this->ws->getCustomer()->getBonus()) { ?>
			<tr>
			<td class="text-left" colspan="4">
					<strong>
						<?=$this->trans->get('Ваши бонусы'); ?>:
					</strong>
				</td>
				<td></td>
			<td  class="text-right" >
					<strong class="val_bonus">
						<?=$this->ws->getCustomer()->getBonus()?> грн
					</strong>
					<div class="b_l" style="display: inline-flex;">
					<label class="ckbox" for="ck_bonus" >
						<input type="checkbox" name="bonus" id="ck_bonus" class="bonus_click" value="1"  <?php
							if ($this->ws->getCustomer()->getBonus() <= 0) { echo 'disabled="disabled"';}?>/>
							<span> <?=$this->trans->get('Использовать бонус')?></span>
							</label>
							</div>
				</td>
			</tr>					
			<?php }?>
			<?php if ($this->ws->getCustomer()->getDeposit()) { ?>
			<tr>
			<td class="text-left" colspan="4">
					<strong>
						<?=$this->trans->get('Ваш депозит'); ?>:
					</strong>
				</td>
			<td class="text-right" colspan="2" >
					<strong class="val_deposit">
						<?=Shoparticles::showPrice($this->ws->getCustomer()->getDeposit()); ?></strong><strong> грн
					</strong>
				</td>
			</tr>
			<tr>
			<td class="text-left" colspan="4">
					<strong>
						<?=$this->trans->get('Использовать депозит'); ?>:
					</strong>
				</td>
				<td class="text-right" colspan="2">
						<input type="checkbox" name="deposit" class="deposit_click" value="1" style="margin: 0;vertical-align: middle;" <?php
							if ($this->ws->getCustomer()->getDeposit() <= 0) { echo 'disabled="disabled"';}?>/>
				</td>
			</tr>
									
			<?php }?>
			<tr>
			<td class="text-left" colspan="4">
					<strong>
					<?php echo $this->trans->get('Всего к оплате'); ?>:</br><span  id="dop_s" style="font-size:10px; color:#9d9a9a;"></span>
					</strong>
				</td>
				<td class="text-right" colspan="2">
				<strong>
						<span class="val_sum">
<?php
							$_SESSION['total_price']  = $to_pay;
							echo Shoparticles::showPrice($to_pay);
?>
						</span>
						грн
					</strong>
				</td>
			</tr>
			
			<!-- для ввода кода на получение скидки -->
			
			<?php if(false){ //
			//echo $sum_order;
			//if (!$this->ws->getCustomer()->getIsLoggedIn() and $to_pay > 500) {$this->ws->getCustomer()->getId() == 8005
			?>
			<tr>
			<td class="text-left" colspan="4">
			<strong>
			<?=$this->trans->get('Код на скидку')?>:</br><span style="font-size:10px; color:red;">(<?=$this->trans->get('если он есть')?>)</span>
			</strong>
			</td>
			<td class="text-right" colspan="2">
			<input type="text" class="form-control" name="kupon" style="height: 25px; padding: 5px 5px;" id="kupon" value="<?php if(@$_GET['kupon']) echo $_GET['kupon']; ?>"  pattern="[A-Za-z0-9]{13}" maxlength="13" placeholder="123f8T1z"/></br>
			<input type="button" class="btn btn-default" style="padding: 5px 5px;font-size: 12px;margin-top: -15px;" name="perschet" id="perschet" onClick="Perschet(); return false;" value="<?=$this->trans->get('Пересчитать')?>">
			</td>
			</tr>
			<?php } ?> 
				<!-- //для ввода кода на получение скидки -->
			
			
				<!-- //для не активных пользователей -->	
			
</table>
</div>
</div>
</div>
    </div>
     

<div class="col-xl-8 m-auto">
<div class="card "> 
<div class="row w-100">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 text-center my-3">
<?php if ($this->get->metod != 'frame') { ?>
					<a class="btn btn-outline-secondary btn-lg" style="text-transform: uppercase;" role="button" href="<?php
						echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>"><?=$text[5]?>
					</a>
<?php } ?>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 co-xl-6 text-center my-3">
				<input type="submit" name="tostep2" style="text-transform: uppercase;" value="<?=$text[6]?>" class="btn btn-outline-danger btn-lg" >
			</div>
		</div>
      </div>
		</div>
            </div>
    </div>
	</form>

<?php
}else {
	echo "<p>" . $this->trans->get('Корзина пуста') . "</p>";
}
echo $this->getCurMenu()->getPageBody();
?>

<div class="row m-auto"><?=$this->render('/pages/sliders/basket.php')?></div>

<script>

    window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments);};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_shopping_cart_event', {advId: '20676', priceId: '3047', productIds: '<?=$list_id?>'});
    //<!-- EVENTS FINISH -->

function Perschet(){
location.replace("/basket/?kupon="+$('#kupon').val());
}
							$(document).ready(function () {
								 $('a.img_pre').hover(function () {
								 console.log('+');
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		console.log('-');
		$(this).parent().find('div.simple_overlay').hide();
        });
							
								var real_dep = $('.val_deposit').html();
								var real_sum = $('.val_sum').html().replace(/^\s+/g, '');
								$('.deposit_click').change(function () {
									if ($(this).prop('checked')) {
										d = $('.val_deposit').html();
										d = d.replace(',', ".");
										d = parseFloat(d);
										s = $('.val_sum').html();
										s = s.replace(',', ".");
										s = parseFloat(s);
										depos = d - s;
										if (depos < 0) depos = 0;
										sum = s - d;
										if (sum < 0) sum = 0;
										depos = depos.toFixed(2);
										sum = sum.toFixed(2);
										$('#dop_s').html('(без учета дополнительных скидок и доставки)');
										$('.val_deposit').html(depos.replace('.', ','));
										$('.val_sum').html(sum.replace('.', ','));
                                                                                
   $.ajax({
  url: "/ajax/setorderamountbasket/",
  method: "POST",
  data: { amount : sum },
  dataType: "json"
});
                                                                                
                                                                               // var xhr = new XMLHttpRequest();
                                                                               // xhr.open('GET', 'phones.json', false);
                                                                                //$.session.set("total_price", sum);
                                                                                 //sessionStorage['total_price'] = sum;

									} else {
									$('#dop_s').html('');

										$('.val_deposit').html(real_dep);
										$('.val_sum').html(real_sum);
                                                                                $.ajax({
  url: "/ajax/setorderamountbasket/",
  method: "POST",
  data: { amount : real_sum.replace(',','.') },
  dataType: "json"
});
                                                                               //sessionStorage['total_price'] = real_sum;
                                                                                // $.session.set("total_price", real_sum);
									}
								});
								
								$('.bonus_click').change(function () {
								var real_bonus = $('.val_bonus').html();
								var real_sum = $('.val_sum').html();
								
									//if (prop('checked'))
									if($(this).is(":checked")){
										d = $('.val_bonus').html();
										d = d.replace(',', ".");
										d = parseFloat(d);
										s = $('.val_sum').html();
										s = s.replace(',', ".");
										s = parseFloat(s);
										depos = d - s;
										if (depos < 0) depos = 0;
										sum = s - d;
										if (sum < 0) sum = 0;
										depos = depos.toFixed(2);
										sum = sum.toFixed(2);
										$('#dop_s').html('(без учета дополнительных скидок и доставки)');
										//$('.val_bonus').html(depos.replace('.', ','));
										$('.val_sum').html(sum.replace('.', ','));

									}else{
									$('#dop_s').html('');
											s = $('.val_sum').html();
										s = s.replace(',', ".");
										s = parseFloat(s);
										d = $('.val_bonus').html();
										d = d.replace(',', ".");
										d = parseFloat(d);
										
										//$('.val_bonus').html(d);
										var sd =(s+d);
										sd = sd.toString();
										console.log(sd);
										sd = sd.replace('.', ",");
										$('.val_sum').html(sd);
									}
									console.log(real_sum);
								});
								
<?php
								if (isset($_SESSION['deposit']) and false) {
?>
									d = $('.val_deposit').html();
									d = d.replace(',', ".");
									d = parseFloat(d);
									s = $('.val_sum').html();
									s = s.replace(',', ".");
									s = parseFloat(s);
									depos = d - s;
									if (depos < 0) depos = 0;
									sum = s - d;
									if (sum < 0) sum = 0;
									depos = depos.toFixed(2);
									sum = sum.toFixed(2);
									$('.val_deposit').html(depos.replace('.', ','));
									$('.val_sum').html(sum.replace('.', ','));
<?php
								}
?>
							});
						</script>