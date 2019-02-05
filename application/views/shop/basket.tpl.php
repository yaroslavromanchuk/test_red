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

<?php
$list_id_arr = [];
if ($this->getCard()['article']){ ?>
<form action="<?=wsActiveRecord::useStatic('Menu')->findByUrl('basket')->getPath()?>" method="post" id="basket1" class="cart-table1" >
    <div class="row m-auto">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8 p-1 m-auto">
            
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
                                <br><?=$item['comment']?$item['comment']:''?> 
                            </div>
                            <div class="col-4 col-sm-2 col-md-4 col-lg-2 col-xl-2 text-center pt-lg-4">
                                <?php if($item['first_price'] != $item['price']){ ?>
                                <span style="text-decoration: line-through;color: #666;font-weight: normal;font-size: 11px;"><?=$item['first_price']?></span><br>
                                <?php } ?>
                                <?=$item['skidka']?'<span style="font-size: 10px;color:red;font-weight: bold;position: relative;top: -5px;"> '.$item['skidka'].'</span><br>':''?>
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
                                <a class="delete_basket_item" href="<?=wsActiveRecord::useStatic('Menu')->findByUrl('shop-checkout-step1-delete')->getPath()."point/{$key}"; ?>" onclick="return confirm('<?=$this->trans->get('Удалить товар из корзины?'); ?>')" data-tooltip="tooltip" title="Удалить">
		<i class="glyphicon glyphicon-remove" aria-hidden="true" ></i>
				</a>
                            </div>
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
                                <?php if ($this->ws->getCustomer()->getDeposit()) { ?>
                                <div class="row m-2">
                                    <div class="col-6">
                                        <strong>
						<?=$this->trans->get('Ваш депозит')?>:
					</strong>
                                    </div>
                                    <div class="col-6">
                                        <strong class="val_deposit">
						<?=Shoparticles::showPrice($this->ws->getCustomer()->getDeposit())?></strong><strong> грн
					</strong>
                                    </div>
                                </div>
                                <div class="row m-2">
                                    <div class="col-6">
                                        <strong>
						<?=$this->trans->get('Использовать депозит'); ?>:
					</strong>
                                    </div>
                                    <div class="col-6">
                                        <input type="checkbox" name="deposit" class="deposit_click m-0" value="1" style="vertical-align: middle;" <?php
							if ($this->ws->getCustomer()->getDeposit() <= 0) { echo 'disabled="disabled"';}?>/>
                                    </div>
                                </div>					
			<?php }?>
                                <div class="row m-2">
                                <div class="col-6"><b><?=$this->trans->get('Сумма скидки')?>:</b></div>
                                <div class="col-6"><b><?=Shoparticles::showPrice($this->card['total_price_minus'])?> грн.</b></div>
                            </div>
                            <div class="row m-2">
                                <div class="col-6"><b><?=$this->trans->get('Всего к оплате')?>:</b></div>
                                <div class="col-6"><b class="val_sum"><?=Shoparticles::showPrice($this->card['total_price'])?> грн.</b></div>
                                <div class="col-12" id="dop_s"></div>
                            </div>
                            
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                <div class="col-12 text-center"><input type="submit" name="tostep2" style="text-transform: uppercase;" value="<?=$text[6]?>" class="btn btn-outline-danger btn-lg" ></div>
                                <?php if ($this->get->metod != 'frame') { ?>
                                 <div class="col-12 text-center mt-2">
                                     <a class="btn btn-outline-secondary " style="text-transform: uppercase;" role="button" href="<?php
						echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>"><?=$text[5]?>
                                    </a>
                                 </div>
                                <?php } ?>
                            </div>
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
	var real_bonus = $('.val_bonus').html();
	var real_sum = $('.val_sum').html();
													
	$('.bonus_click').change(function () {
			
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
			var s = $('.val_sum').html();
			s = s.replace(',', ".");
			s = parseFloat(s);
			var d = $('.val_bonus').html();
			d = d.replace(',' , ".");
			d = parseFloat(d);
										
			var sd =(s+d);
			sd = sd.toString();
			//console.log(sd);
			sd = sd.replace('.', ",");
			$('.val_sum').html(sd);
		}
			//console.log(real_sum);
	});
							
						</script>