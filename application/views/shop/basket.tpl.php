<?php
$text = explode(',', $this->trans->get('цвет,размер,количество,цена,всего,продолжить покупки,оформить заказ'));?>
<h1 class="violet"><?=$this->getCurMenu()->getName()?></h1>
<?php 
if (isset($this->error)) {
	echo "<div>";
	foreach ($this->error as $error) {
		echo '<h2>'.$error.'</h2>';
	}
	echo "</div>";
}
if(isset($_SESSION['error_cod'])){
echo "<div class='alert alert-danger'>".$_SESSION['error_cod']."</div>";
}
if($_SESSION['count_basket'] > 5){
echo '<div class="col-xs-10 col-xs-offset-1">
<div class="alert alert-danger">'.$this->trans->get('Обратите внимание, мы ограничили количество единиц товара в заказе для пунктов выдачи до 5 единиц.</br>
				У Вас в корзине').' '.$_SESSION['count_basket'].' '.$this->trans->get('единиц товара. Если Вы хотите оформить заказ через пункт выдачи, удалите из корзины').'  '.($_SESSION['count_basket']-5).' '.$this->trans->get('единиц').'.
				</div>
			</div>
				';
}

if ($this->getBasket()) {
?>
<div class="row mx-auto">
<div class="col-md-12">
<form action="<?=wsActiveRecord::useStatic('Menu')->findByUrl('basket')->getPath()?>" method="post" id="basket1" >
<div class="table-responsive" id="backet_reload">
<table class="table cart-table">
<thead>
<tr>
<th>Продукт</th><th></th><th><?=$text[3];?></th><th><?=$text[2];?></th><th><?=$text[4];?></th><th></th>
</tr>
</thead>
<?php
	$total = 0.0;
	$t_count = 0;
	$t_price = 0.00;
	$total_price = 0.00;
	$to_pay = 0;
	$to_pay_minus = 0.00;
	$now_orders = 0;
	$skidka = 0;
	$kupon = 0;
	$event_skidka = 0;
	if(isset($_GET['kupon']) and $_GET['kupon'] != ''){
	$today_c = date("Y-m-d H:i:s"); 
$ok_kupon = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$_GET['kupon'], "ctime <= '".$today_c."' ", " '".$today_c."' <= utime"));
	if(@$ok_kupon){
			//$find_count_orders_by_user_codd = 0;//wsActiveRecord::useStatic('Shoporders')->count(array('kupon'=>$_GET['kupon']));
			$kupon = $_GET['kupon'];
			}else{
			echo '<tr><td colspan="6"><span class="alert alert-danger" style="padding:0px;">Вы ввели не действительный промокод!</span></td></tr>';
			}
	}

	if ($this->ws->getCustomer()->getIsLoggedIn()) {
		$skidka = $this->ws->getCustomer()->getDiscont(false, 0, true);
		$event_skidka = EventCustomer::getEventsDiscont($this->ws->getCustomer()->getId());
		$all_orders = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery('
			SELECT
				IF(SUM(price*count) IS NULL, 0, SUM(price*count)) amount
			FROM
				ws_order_articles
				JOIN ws_orders
				ON ws_order_articles.order_id = ws_orders.id
			WHERE
				ws_orders.customer_id = '.$this->ws->getCustomer()->getId().'
				AND ws_orders.status IN (1,3,4,6,8,9,10,11,13,14,15,16)
		')->at(0);
		$now_orders = $all_orders->getAmount();
	}
	
	foreach ($this->getBasket() as $key => $item) {
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
		
			$t_price += $article->getPriceSkidka() * $item['count'];
			
		}
		
	}
	$now_orders += $t_price;
	$sum_order = $t_price;
	$t_price = 0.00;
	foreach ($this->getBasket() as $key => $item){
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
			//$t_price += $article->getPriceSkidka() * $item['count'];
			$price = $article->getPerc($now_orders, $item['count'], $skidka, $event_skidka, $kupon, $sum_order);
			$to_pay += $price['price'];
			$to_pay_minus += $price['minus'];
?>
	<tr>
		<td>
			<a href="<?=$article->getPath();?>" class="img_pre" rel="#imgiyem<?=$article->getId()?>">
				<img src="<?=$article->getImagePath('listing');?>" style="max-width:150px;"  alt="<?=htmlspecialchars($article->getTitle());?>" />
			</a>
			<div class="simple_overlay" id="imgiyem<?=$article->getId(); ?>" style="position: fixed;top: 20%;left: 30%">
                    <img src="<?=$article->getImagePath('detail'); ?>" 
                         alt="<?=htmlspecialchars($article->getTitle()); ?>"/>

                </div>
		</td>
		<td class="text-left">
		<b><?=$article->getTitle()?></b></br>
		<?=$text[0];?>:<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($item['color'])->getName();?> | <?=$text[1];?>:<?=wsActiveRecord::useStatic('Size')->findById($item['size'])->getSize();?>
		</td>
		<td><?php
		$FirsPrrice = $article->getFirstPrice();
		$pr = '';
						$pric = '';
						$skid = '';

						if($FirsPrrice != ($price['price']/$item['count'])){
						$pr = $FirsPrrice; 
						$skid = '  -'.ceil(100- (($price['price']/$pr)*100)).'%';
						}
						//if($this->ws->getCustomer()->getId() == 8005) echo $price['minus'];
						echo '<span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;">'.$pr.'</span><span style="font-size: 10px;color:red;font-weight: bold;position: relative;top: -5px;"> '.$skid.'</span><br>'.Shoparticles::showPrice($price['price']/$item['count']); ?> грн
		</td>
		<td>
			<select name="select" class="form-control"
				onchange="document.location='<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-change')->getPath()."point/{$key}/count/"; ?>'+this.value+'/';">
<?php
								for ($i = 1; $i <= wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $article->getId(), 'id_size' => $item['size'], 'id_color' => $item['color']))->getCount(); $i++)
									echo ($i != $item['count']) ? "<option value=\"{$i}\">{$i}</option>" : "<option value=\"{$i}\" selected=\"selected\">{$i}</option>";
?>
							</select>
		</td>
		<td>
			<div class="price-cart">
				<p><?=Shoparticles::showPrice($price['price']);?> грн</p>
			</div>
		</td>
		<td>
		<a class="delete_basket_item" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$key}"; ?>" onclick="return confirm('<?=$this->trans->get('Удалить товар из корзины?'); ?>')" data-tooltip="tooltip" title="Удалить">
		<i class="glyphicon glyphicon-remove" aria-hidden="true" style="color: red;top: -6px;"></i>
				</a>
		</td>
	</tr>
<?php if (in_array($article->getCategoryId(), array(74, 84, 137, 138, 139, 157, 158, 249, 140, 163))) { ?>
			<tr><td colspan="6" class="t_bord">
				<span class="attention"><?=$this->trans->get('Будьте внимательны, заказывая этот товар! Бельё не подлежит обмену и возврату'); ?></span>
			</td></tr>
<?php 	} ?>
<?php 	}
	}
?>
</table>
<table class="table cart-table">
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
							if ($this->ws->getCustomer()->getDeposit() < 0) { echo 'disabled="disabled"';}?>/>
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
							$total_price = $to_pay;
							$_SESSION['total_price'] = $total_price;
							echo Shoparticles::showPrice($total_price);
?>
						</span>
						грн
					</strong>
				</td>
			</tr>
			
			<!-- для ввода кода на получение скидки -->
			
			<?php if ( date("Y-m-d H:i:s") < '2017-11-01 00:00:00' ) { //
			//echo $sum_order;
			//if (!$this->ws->getCustomer()->getIsLoggedIn() and $total_price > 500) {$this->ws->getCustomer()->getId() == 8005
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
			
				<?php //для не активных пользователей
				if(false){
			//if ($this->ws->getCustomer()->getIsLoggedIn() and $total_price > 350) {
			if($this->ws->getCustomer()->isNoActive()){
			?>
			<tr>
			<td class="text-left" colspan="4">
			<strong>
			<?php echo $this->trans->get('Код с рассылки'); ?>:
			</strong>
			</td>
			<td class="text-right" colspan="2">
			<input type="text" name="kupon" id="kupon" value="" style="width: 120px; " pattern="[A-Za-z0-9]{10}" maxlength="10" placeholder="123f8T1zA7"/>
			</td>
			</tr>
			<?php }
			}?>
				<!-- //для не активных пользователей -->	
			
		</table>
</div>
<div class="row mx-0 my-3">
<div class="col-xs-12 col-md-6 col-lg-6 text-left">
<?php if ($this->get->metod != 'frame') { ?>
					<a class="btn btn-secondary btn-lg" style="text-transform: uppercase;font-size: 100%;" role="button" href="<?php
						echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>"><?=$text[5]?>
					</a>
<?php } ?>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-6 text-right">
				<input type="submit" name="tostep2" style="text-transform: uppercase;font-size: 100%;" value="<?=$text[6]?>" class="btn btn-danger btn-lg" >
			</div>
		</div>
	</form>
	</div>
</div>

<?php
}else {
	echo "<p>" . $this->trans->get('Корзина пуста') . "</p>";
}
echo $this->getCurMenu()->getPageBody();
?>

<script type="text/javascript">
function Perschet(){
var kp = $('#kupon').val();
location.replace("/basket/?kupon="+kp);
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
								var real_sum = $('.val_sum').html();
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

									} else {
									$('#dop_s').html('');

										$('.val_deposit').html(real_dep);
										$('.val_sum').html(real_sum);
									}
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