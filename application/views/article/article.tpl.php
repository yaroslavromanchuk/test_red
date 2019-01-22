<script src="/js/desires.js?v=1.4"></script>


		<!-- Dialog return -->
	<div class="modal fade comment-form" id="comment-modal_b_ord1" tabindex="-1" role="dialog" aria-labelledby="comment-modal_b_ord1">
		<div class="modal-dialog" role="document" id="f_order1">
	    	<div class="modal-content modal-md">
				<form id="qo1" action="/page/returnarticles/" method="post" class="disabled-while-empty" name="qo1" onsubmit="return hidenn();">
				<div id="hidennn"> 
				<input type="hidden" name="id_tovar" id="id_tovar" value="<?php echo $this->getShopItem()->getId(); ?>" />
						<div class="modal-header">
							<h5 class="modal-title"><?=$this->trans->get('Сообщить, когда появится в наличии')?>!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">						
							<div class="comment-types">
								<div class="comment-type">
								<span class="red">*</span> - <?php echo $this->trans->get('Поля, обязательные для заполнения');?>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<label for="name_r"><?php echo $this->trans->get('Имя');?><span class="red">*</span></label>
							<?php	if ($this->ws->getCustomer()->getIsLoggedIn()) {?>
								<input class="form-control" name="name_r" id="name_r" required value="<?php
								echo $this->ws->getCustomer()->getFirstName();?>"/>
								<?php }else{?>
								<input class="form-control" name="name_r" id="name_r" required value=""/><?php }?>
							</div>
							<div class="form-group form-group-sm">
								<label for="email_r">e-mail<span class="red">*</span></label>
							<?php 	if ($this->ws->getCustomer()->getIsLoggedIn()) {?>
							<input class="form-control" type="email" name="email_r" id="email_r" placeholder="sample@domen.com" required value="<?php
							echo $this->ws->getCustomer()->getEmail();?>" /> <?php }else{?>
							<input class="form-control" type="email" name="email_r" id="email_r" placeholder="sample@domen.com" required value=""/>
							<?php
							}?>
							</div>
							<div style="min-height: 48px;">
						<p>
							<?php echo $this->trans->get('Размер'); ?>:
						</p>
						<span id="size_return">
<?php
							$one_z_r = 0;
							$one_c_r = 0;
							$mas = array();
							foreach ($this->getShopItem()->getSizes() as $size) {
								if ($size->getCount() == 0) {
									$mas [$size->getSize()->getId()] = $size->getSize()->getSize();
								}
							}
							foreach (array_unique($mas) as $kay => $value) { 
							if (count($mas) == 1) { 
									$one_z_r = $kay;
									echo '<input hidden type="radio" value="' . $kay . '" name="size_return" id="'. $kay . 'size_return" checked="checked">';
									echo '<label for="'. $kay . 'size_return" class="lebb">' . $value . '</label>';
								} else {
									echo '<input hidden type="radio" value="' . $kay . '" name="size_return" id="'. $kay . 'size_return">';
									echo '<label for="'. $kay . 'size_return" class="lebb">' . $value . '</label>';
								}
							}
?>
						<!-- </select> -->
						</span>
						<span class="error size">
							<i class="arrow-left"></i>
							<span ><?=$this->trans->get('Выберите размер')?></span>
						</span>
						<div class="clear" style="padding:0;"></div>
					</div>
<div>
						<span id="color_return">
<?php
							$mass = array();
							foreach ($this->getShopItem()->getSizes() as $color) {
								if ($color->getCount() == 0) {
									$mass [$color->getColor()->getId()] = $color->getColor()->getName();
								}
							}
							foreach (array_unique($mass) as $kay => $value) {
							if (count($mass) == 1) {
									$one_c_r = $kay;
									echo '<input hidden type="radio" value="' . $kay . '" name="color_return" id="'. $kay . 'color_return" checked="checked">';
									
								} else {
									echo '<input hidden type="radio" value="' . $kay . '" name="color_return" id="'. $kay . 'color_return">';
									echo '<label for="'. $kay . 'color_return" class="lebb"><i>' . $value . '</i></label>';
								}
							}
?>
			<!-- </select> -->
						</span>
						<span class="error color">
							<i class="arrow-left"></i>
							<span><?php echo $this->trans->get('Выберите цвет');?></span>
						</span>
<?php
							if(!$one_c_r or !$one_z_r ){?><br>
							<br><span class="sarticle_return" style="display: none;"><?=$this->trans->get('Артикул');?>: <span class="red"></span></span>
							<input style="display: none;" class="form-control " type="text" name="articul" id="articul" value=""/>
<?php
							}
							else {
                                                            $cod = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article'=>$this->getShopItem()->getId(),'id_size'=>$one_z_r,'id_color'=>$one_c_r, 'count = 0'))->getCode();
							
?>
							<br><span class="sarticle_return"><?=$this->trans->get('Артикул')?>:<span class="red"><?=$cod?></span></span>
							<input style="display: none;" class="form-control " type="text" name="articul" id="articul" value="<?=$cod?>"/>

<?php
							}
?>
					</div>
						</div>
						<div class="modal-footer">
							<input  type="submit" class="btn btn-danger" name="return_save" value="Сохранить">
						<button id="sev"  style="display: none;" type="button" class="btn btn-default exit" data-dismiss="modal" aria-label="Close"></button>
						</div>
						</div>
				    
					
				</form>
	    	</div>
	  	</div>

	</div>	
	<script>
					function hidenn() {
					//var article = $('#articul').val(); 
					//console.log(article);
					var size_id_x = $('input[name="size_return"]:checked').val();
                                        var color_id_x = $('input[name="color_return"]:checked').val();
			
					if(color_id_x > 0 && size_id_x > 0){
					alert("<?=$this->trans->get('Ваше пожелание сохранено')?>.");
					document.getElementsByClassName("exit")[0].click();
					return true;
					}else{
					alert("<?=$this->trans->get('Выберите розмер и цвет')?>!");
					return false; 
					}
				};
				
	</script>
	<!-- End dialog return -->
		<!-- !Comment modal -->
	<div class="modal fade comment-form" id="comment-modal_b_ord" tabindex="-1" role="dialog" aria-labelledby="comment-modal_b_ord">
		<div class="modal-dialog" role="document" id="f_order">
	    	<div class="modal-content modal-md"> 
				<form id="qo"  method="post" class="disabled-while-empty" name="qo">
				<div id="hide">
				<input type="hidden" name="id" id="quik_order-id" value="<?=$this->getShopItem()->getId()?>">
						<div class="modal-header">
							<h5 class="modal-title"><?=$this->trans->get('Быстрый заказ')?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
						<div id="qo-result" style="display:none;"></div>						
							<div class="comment-types">
								<div class="comment-type">
								<span class="red">*</span> - <?=$this->trans->get('Поля, обязательные для заполнения')?>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<label for="quik_order-name"><?=$this->trans->get('Имя')?><span class="red">*</span></label>
								<input class="form-control" name="name" id="quik_order-name" required value="<?php
								if (isset($this->basket_contacts['name']) and $this->basket_contacts['name']) {
									echo htmlspecialchars($this->basket_contacts['name']);
								}elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getFirstName();
								}
							?>" />
							</div>
                                                <div class="form-group form-group-sm">
								<label for="quik_order-middle_name"><?=$this->trans->get('Фамилия');?><span class="red">*</span></label>
								<input class="form-control" name="middle_name" id="quik_order-middle_name" required value="<?php
								if (isset($this->basket_contacts['middle_name']) and $this->basket_contacts['middle_name']) {
									echo htmlspecialchars($this->basket_contacts['middle_name']);
								}
								elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getMiddleName();
								}
							?>" />
							</div>
							<div class="form-group form-group-sm">
								<label  for="telephone">Телефон<span class="red">*</span><span style="color:red;" id="leb"></span></label>
<input type="tel" class="form-control phone_form" name="telephone" id="telephone"  placeholder="38(000)000-00-00" maxlength="16"  required  value="<?php
								if (isset($this->basket_contacts['telephone']) and $this->basket_contacts['telephone']) {
									echo htmlspecialchars($this->basket_contacts['telephone']);
								}
								elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getPhone1();
								}
							?>" />
							</div>
							<div class="form-group form-group-sm">
								<label  for="email">e-mail<span class="red">*</span></label>
							<input class="form-control" type="email" name="email" id="email" placeholder="sample@domen.com" required value="<?php
								if (isset($this->basket_contacts['email']) and $this->basket_contacts['email']) {
									echo htmlspecialchars($this->basket_contacts['email']);
								}elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getEmail();
								}
							?>" />
							
							</div>
							<div class="form-group">
								<label class="" for="quik_order-comment"><?=$this->trans->get('Комментарий')?></label>
								<textarea class="form-control" name="comment" id="quik_order-comment" rows="3" cols="16" style="max-width: 100%;"></textarea>
							</div>
							
						</div>
						<div class="modal-footer">
<input  type="submit" class="btn btn-danger" value="<?=$this->trans->get('Заказать')?>" />
						</div>
						</div>

				</form>
	    	</div>
	  	</div>

	</div>	
	<!-- End Comment modal -->
	<!--okno brenda-->
<div class="modal fade comment-form" tabindex="-1" role="dialog" id="comment-modal_brands" aria-labelledby="comment-modal_brands">
<div  class="modal-dialog"  role="document" >
	<div  class="modal-content">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title"><?=$this->trans->get('История бренда');?></h4>
	</div>
	<div class="modal-body brand_info"></div>
	</div>
</div>
</div>
<!--okno brenda-->
<?php
$Headers = get_headers('https://www.red.ua'.$this->getShopItem()->getImagePath('card_product'));
if(strpos($Headers[0], '200')) {
$rs = 'card_product';
}else{
$rs = 'detail';
}
	$label = false;
	if ($this->getShopItem()->getLabelId()) {
		$label = $this->getShopItem()->getLabel()->getImage();
	}
?>
<div class="row article-detail-box p-2 m-0">
<?php if (count($this->getShopItem()->getImages()) > 0) { ?>
<div class="photo_min col-xs-12  col-lg-1 col-xl-1">
<a href='<?=$this->getShopItem()->getImagePath()?>' class="cloud-zoom-gallery" title="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" data-rel="useZoom: 'zoom', smallImage: '<?=$this->getShopItem()->getImagePath($rs)?>'"
>						
<div class="for_cloud_big_src" style="display:none"><?=$this->getShopItem()->getImagePath();?></div>
<img src="<?=$this->getShopItem()->getImagePath('listing');?>" alt="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" class="photo-small" onmouseover="$(this).parent().click()"/></a>
<?php foreach ($this->getShopItem()->getImages() as $image) { ?>
<a href='<?=$image->getImagePath()?>' class='cloud-zoom-gallery  <?=((int)$image->getColorId() > 0)?' color_click_'.$image->getColorId():''?>' title="<?=$image->getTitle()?>"  data-rel="useZoom: 'zoom', smallImage: '<?=$image->getImagePath($rs)?>'" 
>
<div class="for_cloud_big_src" style="display:none"><?=$image->getImagePath();?></div>
<img src="<?=$image->getImagePath('listing')?>" alt="<?=$image->getTitle()?>" class="photo-small"   onmouseover="$(this).parent().click()" />
</a>
<?php } ?>
</div>
<?php } ?>

<!--foto-->
<div class="photos  col-xs-12 col-sm-12 col-lg-5 col-xl-4">

<?php
if($label == '/storage/label/final_sale_1.png'){
	$pr = $this->getShopItem()->getPrice();
        if((float)$this->getShopItem()->getOldPrice()){ $pr  = $this->getShopItem()->getOldPrice();}
	$skid = (1-($this->getShopItem()->getPriceSkidka()/$pr))*100;

	?>
	 <div class="article_label_container_2">
             <div class="article_label_2">
                 <img src="<?=$label?>" alt="" style="width:100px;">
                 <p style="font-size: 95%;
    font-weight: bold;
    color: #e20404;
    transform: rotate(-45deg);
    position: absolute;
    top: 20px;
    left: 10px;
    padding: 0;
    margin: 0;"><?='-'.round($skid).'%';?></p></div>
         </div> 
	<?php
	} ?> 
    <div class="">  
 <?php if ($label) { ?>
    <div class="article_label_container_left"><div class="article_label"><img alt="label" src="<?=$label?>" style="width: 100%"/></div></div>
        <?php } ?>
     <?php
     
   $option =  $this->getShopItem()->getOptions();
    if($option->value){
        ?>
    <div class="article_label_container_right" >
        <div class="article_label">
            <img src="/storage/label/promotion.png" alt="promotion"  data-tooltip="tooltip"  data-original-title="<?=$option->option_text?>" >
        </div> 
    </div> 
       <?php } ?>
<div style="display:none" id="cloud_big_src"><?=$this->getShopItem()->getImagePath()?></div>

<a onclick="$('a.cloud-zoom').css('z-index',1);" href='<?=$this->getShopItem()->getImagePath()?>' class='cloud-zoom' id='zoom' >
<img class="photo-big" id="test" itemprop="image"  src="<?=$this->getShopItem()->getImagePath($rs)?>" alt='<?=htmlspecialchars($this->getShopItem()->getTitle())?>' title="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" >
</a>
</div>
	</div>
<!--/foto-->
<!-- /text-->
	<div class="texts col-xs-12 col-sm-12 col-lg-6  col-xl-7 px-0">
	<div class="model"><?=$this->getShopItem()->getModel()?></div>
<div class="shop_brand"  itemprop="brand" itemscope itemtype="http://schema.org/Brand">
<a href="/brands/id/<?=$this->getShopItem()->brand_id?>/<?=$this->getShopItem()->getBrand()?>/"><span itemprop="name"><?=$this->getShopItem()->getBrand()?></span></a>
</div>
<div class="article-category"><span  style="color: #969696;text-transform: uppercase;"><?=$this->trans->get('category')?> : </span><a  href="<?=$this->getShopItem()->getCategory()->getPath()?>"><?=$this->getShopItem()->getCategory()->getH1()?></a></div>

<hr>

<form action="<?=$this->getShopItem()->getPath()?>" method="post" id="article">
<?php if ($this->getShopItem()->getCountArticles()) { ?>
		<!--<p>Выбор размер</p>-->
				<div class="buy-form">
					<div>
                                            <?php
if($this->getShopItem()->getModelId()){ 
       $model = $this->getShopItem()->getModels();
       $par = explode(',', $this->trans->get('rost,grud,taliya,bedra'));
    ?>
<p class="mb-0">
        <b><?=$this->trans->get('param_model')?>(см.): </b>
        <?=$par[0]?>:<?=$model->rost?>, <?=$par[1]?>:<?=$model->grud?>, <?=$par[2]?>:<?=$model->taliya?>, <?=$par[3]?>:<?=$model->bedra?>
    
</p>
   
<?php } ?>
<p class="mb-0">
    <b><?=$this->trans->get('Размер')?>:</b> 
	<span id="size" class="size">
<?php
							$one_z = 0;
							$one_c = 0;
							$mas = [];
							foreach ($this->getShopItem()->getSizes() as $size) {
								if ($size->getCount() > 0) {
									$mas [$size->getSize()->getId()] = $size->getSize()->getSize();
								}
							}
							foreach (array_unique($mas) as $kay => $value) {
								if (count($mas) == 1) {
									$one_z = $kay;
									echo '<input type="radio" value="'.$kay.'" name="size" id="'.$kay.'size" checked="checked">';
									echo '<label for="'.$kay.'size">'.$value. '</label>';
								} else {
									echo '<input type="radio" value="' .$kay.'" name="size" id="'.$kay. 'size">';
									echo '<label for="'.$kay.'size">'.$value.'</label>';
								}
							}
?>
						<!-- </select> -->
						</span>
                                                    
						<span class="error size">
							<i class="arrow-left"></i>
							<span><?=$this->trans->get('Выберите размер'); ?></span>
						</span>
                                                   
 <a href="#"  class="rozmerSetka1" onclick="
     $('.popap_blok').css('width',$(document).width()).css('height',$(document).height()).show();
	$('.mask').css('width',$(document).width()).css('height',$(document).height()).css({'opacity':'0.7'}).show();
	$('#rozmerSetka1').css('left',($(document).width()-$('#rozmerSetka1').width())/2);
	$('#rozmerSetka1').toggle('slow', function() {});
        return false;">
     (<?=$this->trans->get('размерная сетка')?>)
 </a>
<?php if ($this->getShopItem()->category->getSizeType() > 0 and $this->getShopItem()->getSizeType() != 100) {
	echo $this->render('/pages/razmersetka.tpl.php'); 
} ?>
          </p>
<p class="mb-0"> 
<b><?=$this->trans->get('Цвет')?>:</b>
<span id="color">
<?php
							$mass = [];
							foreach ($this->getShopItem()->getSizes() as $color) {
								if ($color->getCount() > 0) {
									$mass [$color->getColor()->getId()] = $color->getColor()->getName();
								}
							}
							foreach (array_unique($mass) as $kay => $value) {
								if (count($mass) == 1) {
									$one_c = $kay;
									echo '<input type="radio" value="' . $kay . '" name="color" id="'. $kay . 'color" checked="checked">';
									//echo '<label for="'. $kay . 'color"><i>' . $value . '</i></label>';
									echo '<a href="'.$this->getCategory()->getPath().'colors-'.$kay.'">'.$value.'</a>';
								} else {
									echo '<input type="radio" value="' . $kay . '" name="color" id="'. $kay . 'color">';
									echo '<label for="'. $kay . 'color"><i>' . $value . '</i></label>';
									//echo $value;
								}
							}
?>
</span>
						<span class="error color">
							<i class="arrow-left"></i>
							<span><?=$this->trans->get('Выберите цвет');?></span>
						</span>
</p>
<p class="mb-0"> 
<?php
							if(!$one_c or !$one_z ){
?>
								<span class="sarticle" style="display: none; margin-top: 10px; ">
								<b><?=$this->trans->get('Артикул')?>: </b>
								<span></span>
								<input type="text" hidden value="" name="artikul" id="artikul"></span>
<?php
							}else{
?>
							<span class="sarticle">
                                                            <b><?=$this->trans->get('Артикул')?>:</b>
                                                        <span>
<?php                                                       $art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article'=>$this->getShopItem()->getId(),'id_size'=>$one_z,'id_color'=>$one_c))->getCode();
										echo $art;
?>
									</span><input type="text" hidden value="<?=$art;?>" id="artikul" name="artikul">
								</span>
<?php
							}
?>
    </p>
		<!-- для сообщения про наличии-->
		<?php
	$mas = [];
		foreach ($this->getShopItem()->getSizes() as $size) {
				if ($size->getCount() == 0) {
					$mas [$size->getSize()->getId()] = $size->getSize()->getSize();
						}
					}
                                                        
$arr = array_unique($mas);
		if (count($mas) != 0){
		echo '<div class="napomnit">
				<a data-toggle="modal" href="#comment-modal_b_ord1">
				<span class="btn btn-secondary " data-placement="bottom"  data-tooltip="tooltip" id="quick_order_r" style="background: #bcbcbc;" title="'.$this->trans->get('Вам придет email сообщение, когда выбранный Вами товар появится в наличии').'.">
				<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i><span> '.$this->trans->get('Сообщить о наличии').'!</span>
				</span></a><div class="clearfix"></div>
				</div>';
		} ?>
				<!-- для сообщения про наличии-->
    
</div>
					<div class="clear" style="padding:0;"></div>
				</div>

		<?php	if (isset($this->error)) {
				echo "<div>";
				foreach ($this->error as $error) { ?>
					<h2 style="color: red;"><?=$error?></h2>
<?php

				}
				echo "</div>";
			}
?>
<hr>
<?php
	if($option->value and $option->type == 'final'){ ?>
	<div class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
            <meta itemprop="priceCurrency" content="UAH" />
            <span class="price">
	<?=$this->trans->get('Цена')?> 
                            
<?php         
  $price   = $this->getShopItem()->getPerc(100, 1);
            $pric = explode(',', trim(Number::formatFloat($price['price'], 2)));
				echo $pric[0];
?>
                <link itemprop="availability" href="http://schema.org/InStock" />
                <input type="text" hidden value="<?=$price['price']?>" itemprop="price" content="<?=$price['price']?>" name="price" id="price">
				<span style="font-size:11px; vertical-align: text-bottom; margin-left: -4px;">
<?php
					echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки
?>
				</span> грн
	</span>
			<span class="old-price">
			(<?php  $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getFirstPrice(), 2));
					echo $pric_sk[0];
					echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
			</span>
            
            <?php if(/*$this->getShopItem()->getCountArticles()*/true){
$chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }
			?>

  <label class="leeb" style="">
            <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox"  <?=$chek?>  onclick="setDesires(<?=$this->getShopItem()->getId()?>);">
	<span id="zet-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip" style="display: block;width: 25px; height: 25px;" >
	</span>
	</label>
	<?php } ?>
	</div>
	<?php }else{ ?>
			<div class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
                            <meta itemprop="priceCurrency" content="UAH" />
			<span class="price">
                            <link itemprop="availability" href="http://schema.org/InStock" />
			<input type="text" hidden value="<?=$this->getShopItem()->getPriceSkidka()?>" itemprop="price" content="<?=$this->getShopItem()->getPriceSkidka()?>" name="price" id="price">
				<?=$this->trans->get('Цена')?> 
<?php
				
				$pric = explode(',', Number::formatFloat($this->getShopItem()->getPriceSkidka(), 2));
				echo $pric[0];
?>
				<span style="font-size:11px; vertical-align: text-bottom; margin-left: -4px;">
<?php
					echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки
?>
				</span> грн
			</span>
			<span class="old-price">
			
<?php
			if ($this->getShopItem()->getDiscount()) {
?>
				(<?php  $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getOldPrice(), 2));
					echo $pric_sk[0];
					echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
				
<?php
			}?>
			</span>
                            <?php if(true/*$this->getShopItem()->getCountArticles()*/){
$chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }
			?>

  <label class="leeb" style="">
            <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox"  <?=$chek?>  onclick="setDesires(<?=$this->getShopItem()->getId()?>);">
	<span id="zet-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip" style="display: block;width: 25px; height: 25px;" >
	</span>
	</label>
	<?php }?>
                          
			</div>
			<?php }?>
			
<img id="wait_circle" src="/images/loader.gif" alt="Идёт загрузка..." style="margin-left: 25px;display:none;" >
<br>
<?php if ($this->getShopItem()->getCountArticles()) { ?>
<div class="buttom_click" >			
<button onclick="getQuickOrder(); return false;" data-target="#comment-modal_b_ord" data-toggle="" class="btn btn-secondary btn-lg" data-placement="bottom"  data-tooltip="tooltip" id="quick_order"  title="<?=$this->trans->get('Вам потребуется ввести только имя, телефон и email. Всю дополнительную информацию узнает менеджер по телефону')?>">
<i class="glyphicon glyphicon-dashboard" aria-hidden="true"></i><span><?=$this->trans->get('Быстрый заказ')?></span>
</button>
<button id="sub_bascet"  onclick="getQuickCart(<?=$this->getShopItem()->getId()?>); return false;" class="btn btn-danger btn-lg" data-placement="bottom"  data-tooltip="tooltip"  title="<?=$this->trans->get('Выбраный Вами товар будет добавлен в корзину'); ?>"  >
					<i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i><span><?=$this->trans->get('В КОРЗИНУ')?></span>
                                        
			</button>
    <span class="error error_add"><i class="arrow-left"></i><span class="mes"></span></span>
    <span class="error ok_add"><i class="arrow-left"></i><span class="mes"></span></span>
</div>
<?php	
			}
    ?>
<div id="message" style="text-align: center;font-size: 10px;font-weight: 600;display:inline-block;opacity:0;"></div>
		<div class="clear" style="padding:0;"></div>
		<hr>
		<div id="fb-root"></div>
                <?php
			}else{
?>
				<div class="tnx">
				<?php echo $this->trans->get('ТОВАРА НЕТ НА СКЛАДЕ'); ?>.
				</div>
				<?php	if (count($mas) != 0){
		echo '<div class="b_order1">
				<a data-toggle="modal" href="#comment-modal_b_ord1">
				<span class="btn btn-secondary" data-placement="bottom"  data-tooltip="tooltip" id="quick_order_r"  original-title="Вам придет email сообщение, когда выбранный Вами товар появится в наличии.">
				<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> <span>'.$this->trans->get('Сообщить о наличии').'!</span>
				</span>
					</a>
					<div class="clearfix"></div>
				</div>';
		
		} ?>
                <hr>
<?php
	if($option->value and $option->type == 'final'){ ?>
	<div class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
            <span itemprop="priceCurrency" content="UAH" ></span>
            <span class="price">
	<?=$this->trans->get('Цена')?> 
                            
<?php         
  $price   = $this->getShopItem()->getPerc(100, 1);
            $pric = explode(',', trim(Number::formatFloat($price['price'], 2)));
				echo $pric[0];
?>
                <input type="text" hidden value="<?=$price['price']?>" itemprop="price" name="price" id="price">
				<span style="font-size:11px; vertical-align: text-bottom; margin-left: -4px;">
<?php
					echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки
?>
				</span> грн
	</span>
			<span class="old-price">
			(<?php  $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getFirstPrice(), 2));
					echo $pric_sk[0];
					echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
			</span>
            
            <?php if(/*$this->getShopItem()->getCountArticles()*/true){
$chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }
			?>

  <label class="leeb" style="">
            <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox"  <?=$chek?>  onclick="setDesires(<?=$this->getShopItem()->getId()?>);">
	<span id="zet-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip" style="display: block;width: 25px; height: 25px;" >
	</span>
	</label>
	<?php } ?>
	</div>
	<?php }else{ ?>
			<div class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
                           <span itemprop="priceCurrency" content="UAH" ></span>
			<span class="price">
			<input type="text" hidden value="<?=$this->getShopItem()->getPriceSkidka()?>" itemprop="price" name="price" id="price">
				<?=$this->trans->get('Цена')?> 
<?php
				
				$pric = explode(',', Number::formatFloat($this->getShopItem()->getPriceSkidka(), 2));
				echo $pric[0];
?>
				<span style="font-size:11px; vertical-align: text-bottom; margin-left: -4px;">
<?php
					echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки
?>
				</span> грн
			</span>
			<span class="old-price">
			
<?php
			if ($this->getShopItem()->getDiscount()) {
?>
				(<?php  $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getOldPrice(), 2));
					echo $pric_sk[0];
					echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
				
<?php
			}?>
			</span>
                            <?php if(true){
$chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }
			?>

  <label class="leeb" style="">
            <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox"  <?=$chek?>  onclick="setDesires(<?=$this->getShopItem()->getId()?>);">
	<span id="zet-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip" style="display: block;width: 25px; height: 25px;" >
	</span>
	</label>
	<?php }?>
                          
			</div>
			<?php }?>

<?php
				
			} ?> 
</form>
		<?php
$code = array(14101,14106,14110,14112,14114,14116,14117,14118,14120,14121,14123,14125,14127,14129,14131,14133,14134,14137,14140,14141,14142,14145,14148,14150,14156);
if(in_array($this->getShopItem()->getCode(), $code)){

$n = wsActiveRecord::useStatic('Shoparticles')->findFirst(array("`code` LIKE  '".$this->getShopItem()->getCode()."' ", " `model` NOT LIKE  '".$this->getShopItem()->getModel()."' ", "`stock` NOT LIKE  '0'", "`active` =  'y'"));
if($n){ ?>
<div class="complect">
<p style="margin-bottom: 0;">Собери комплект:</p>
<div class="col-xs-4 col-sm-4 col-md-12 all_blok">
<div class="foto" >
<img src="<?=$this->getShopItem()->getImagePath('listing');?>" alt="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" style="width:100%;max-width:100px;" class="photo-small1"/><br>
<?=$this->getShopItem()->getPrice()?> грн.
</div>
<div class="simvol"><span>+</span></div>
<a href="<?=$n->getPath()?>" target="_blank"><div class="foto" >
<img src="<?=$n->getImagePath('listing');?>" alt="<?=htmlspecialchars($n->getTitle())?>" style="width:100%;max-width:100px;" class="photo-small1"/><br>
<?=$n->getPrice()?> грн.<br>
<button class="btn btn-danger btn-sm">Купить</button>
</div></a>
<div class="simvol" ><span>=</span></div>
<div class="foto" >
<?php if($this->getShopItem()->getModel() == 'Плавки'){ ?>
<img src="<?=$n->getImagePath('listing');?>" alt="<?=htmlspecialchars($n->getTitle())?>" style="width:100%;max-width:50px;" class="photo-small1"/><br>
<img src="<?=$this->getShopItem()->getImagePath('listing');?>" alt="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" style="max-width:50px;" class="photo-small1"/>
<?php }else{ ?>
<img src="<?=$this->getShopItem()->getImagePath('listing');?>" alt="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" style="max-width:50px;" class="photo-small1"/><br>
<img src="<?=$n->getImagePath('listing');?>" alt="<?=htmlspecialchars($n->getTitle())?>" style="width:100%;max-width:50px;" class="photo-small1"/>
<?php }?>
<br><?=$n->getPrice()+$this->getShopItem()->getPrice()?> грн.
</div>
</div>
<hr>
</div>
<?php }

} ?>
<div class="description">
		<ul class="nav nav-tabs">
			<?php if ($this->getShopItem()->getLongText() or $this->getShopItem()->getLongTextUk() ) {
			echo '<li class="nav-item"><a class="nav-link active" href="#tabs-1" data-toggle="tab" >'.$this->trans->get('Описание').'</a></li>'; 
			} ?>
			<?php if ($this->getShopItem()->getSostav() or $this->getShopItem()->getSostavUk()) { echo '<li class="nav-item"><a class="nav-link" href="#tabs-2" data-toggle="tab">'.$this->trans->get('Cостав').'</a></li>'; } ?>
			<?php if ($this->getShopItem()->getSootRozmer()) { echo '<li class="nav-item"><a class="nav-link" href="#tabs-3" data-toggle="tab">'.$this->trans->get('Соответствие').'</a></li>'; } ?>
			<li class="nav-item"><a class="nav-link" href="#tabs-4" data-toggle="tab"><?=$this->trans->get('Способ доставки')?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tabs-5" data-toggle="tab"><?=$this->trans->get('Способ оплаты')?></a></li>
		</ul>
		<div class="tab-content">
		<?php if ($this->getShopItem()->getLongText()) { ?> 
		<div id="tabs-1"  class="tab-pane fade show active"  itemprop="description" >
		<p style="line-height: 2.5;">
                    Сезон: <a href="<?=$this->getCategory()->getPath()?>sezons-<?=$this->getShopItem()->getNameSezon()->translate?>"><?=$this->getShopItem()->getNameSezon()->getName()?></a>
		<br><?=$this->getShopItem()->getLongText()?>
		</p></div>
		<?php }?>
		<?php if ($this->getShopItem()->getSostav()) { ?> <div id="tabs-2" class="tab-pane fade"><p><?=$this->getShopItem()->getSostav()?></p></div> <?php } ?>
		<?php if ($this->getShopItem()->getSootRozmer()) { ?> 
		<div id="tabs-3" class="tab-pane fade"><p><?=$this->getShopItem()->getSootRozmer()?></p><br>
		<a href="#"  class="rozmerSetka1"  style="color: red;"
								onclick="$('.popap_blok').css('width',$(document).width()).css('height',$(document).height()).show();
								$('.mask').css('width',$(document).width()).css('height',$(document).height()).show();
								$('#rozmerSetka1').css('left',($(document).width()-$('#rozmerSetka1').width())/2);
								$('#rozmerSetka1').toggle('slow', function() {}); return false;">(<?=$this->trans->get('соответствие размеров')?>)</a>
								 </div>
								<?php }?>
	<div id="tabs-4" class="tab-pane fade">
	<?php foreach(DeliveryType::find('DeliveryType', ['active_user'=> 1, 'id != 16'],['sort'=>'ASC']) as $dely){ ?>
					<p><?=strip_tags($dely->name)?></p>
					<?php } ?>
	</div>
		<div id="tabs-5" class="tab-pane fade">
	<?php foreach(PaymentMethod::find('PaymentMethod',['active'=> 1]) as $pay){ ?>
					<p><?=strip_tags($pay->name)?></p>
					<?php } ?>
	</div>
</div>
</div>
<div class="clear" style="padding:0;"></div>
</div>
</div>
<div class="row m-auto"><?=$this->render('/pages/sliders/articles.php')?></div>
<script src="/js/call/jquery.mask.js"></script>
<script>
	function getArticle(sizeid, colorid) {
		if (sizeid > 0) {
			if (colorid > 0) {
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: '/page/getarticle/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>,
					beforeSend: function(){
					$("#message").fadeOut(300);
						$('#article').css('opacity', '0.1');
						$('#wait_circle').show();
					},
					success: function (result) {
						if (result.type === 'error') {
							$('#article').css('opacity', '1');
							$('#wait_circle').hide();
							$('.sarticle span').html('соответствия размер - цвет не найдено');
							$('.sarticle').show();
						}else {
							$('.sarticle span').html(result.code);
							$('#artikul').val(result.code);
							$('.sarticle').show();
						}
					},
					error:function(e){
						$('.sarticle span').html('error_ajax');
						$('.sarticle').show();
					},
					complete: function(){
					}
				});
			}
		}
	}
	// для напоминалки
	function getArticleReturn(sizeid, colorid) {
		if (sizeid > 0) {
			if (colorid > 0) {
				$.ajax({
					type: 'GET',
					dataType: 'json', 
					url: '/page/getarticlereturn/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>,
					beforeSend: function(){
						$('#article').css('opacity', '0.1');
						$('#wait_circle').show();
					},
					success: function (result) {
						if (result.type === 'error') {
							$('#article').css('opacity', '1');
							$('#wait_circle').hide();
							$('.sarticle_return span').html('соответствия размер - цвет есть в наличии');
							$('.sarticle_return').show();
						}
						else {
							$('.sarticle_return span').html(result.code);
							$('#articul').val(result.code);
							$('.sarticle_return').show();
						}
					},
					error:function(e){
						$('.sarticle_return span').html('error_ajax');
						$('.sarticle_return').show();
					},
					complete: function(){
					}
				});
			}
		}
	}
	// выход для напоминалки

	$(document).ready(function () {
		$(".tab-content").tab();
	$(".phone_form").mask("38(999)999-99-99");
	
		$('a.cloud-zoom').lightBox({fixedNavigation: true,overlayOpacity: 0.6});
	
		$('#size').on('change', 'input[name="size"]', function() {
			$( ".error.size" ).fadeOut();
			var size_id = $('input[name="size"]:checked').val() || 0;
			var color_id = $('input[name="color"]:checked').val() || 0;
			if (color_id > 0 && size_id > 0) {
				getArticle(size_id, color_id);
			}
			if (size_id == '0') {
				window.location.reload(true);
				return(false);
			}
			$('#color').html('загрузка...');
			$('#article').css('opacity', '0.5');
			$('#wait_circle').show();
			var url = '/page/getcolor/&'+"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>;
			$.get(
				url,
				"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				function (result) {
					if (result.type == 'error') {
						alert('error');
						return(false);
					}
					else {
						var options = '';
						var checked = '';
						$(result.color).each(function () {
							checked = '';
							if (color_id == $(this).attr('id')) checked = 'checked="checked"';
							options +=	'<input type="radio" value="' + $(this).attr('id') + '" name="color" id="' + $(this).attr('id') + 'color" '+ checked +'><span>'+$(this).attr('title')+'</span>';
						
						});
						$('#color').html(options);
						$('#article').css('opacity', '1');
						$('#wait_circle').hide();
					}
				},
				"json"
			);
			
		});

		$('#color').on('change', 'input[name="color"]', function() {
			$( ".error.color" ).fadeOut();
			var size_id = $('input[name="size"]:checked').val() || 0;
			var color_id = $('input[name="color"]:checked').val() || 0;
			$('.color_click_' + color_id).click();
			if (color_id > 0 && size_id > 0) {
				getArticle(size_id, color_id);
			}
			if (color_id == '0') {
				window.location.reload(true);
				return(false);
			}
			$('#article').css('opacity', '0.5');
			$('#size').html('загрузка...');
			$('#wait_circle').show();
			var url = '/page/getsize/&'+"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>;
			$.get(
				url,
				"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				function (result) {
					if (result.type == 'error') {
						alert('error');
						return(false);
					}
					else {
						var options = '';
						var checked = '';
						$(result.size).each(function () {
							checked = '';
							if (size_id == $(this).attr('id')) checked = 'checked="checked"';
							options +=	'<input type="radio" value="' + $(this).attr('id') + '" name="size" id="' + $(this).attr('id') + 'size" '+ checked +'>'+
										'<label for="' + $(this).attr('id') + 'size"><i>' + $(this).attr('title') + '</i></label>';
						});
						$('#size').html(options);
						$('#article').css('opacity', '1');
						$('#wait_circle').hide();
					}
				},
				"json"
			);
		});
		
		//для напоминалки
		
		$('#size_return').on('change', 'input[name="size_return"]', function() {
			$( ".error.size_return" ).fadeOut();
			var size_id = $('input[name="size_return"]:checked').val() || 0;
			var color_id = $('input[name="color_return"]:checked').val() || 0;
			if (color_id > 0 && size_id > 0) {
				getArticleReturn(size_id, color_id);
			}
			if (size_id == '0') {
				window.location.reload(true);
				return(false);
			}
			$('#color_return').html('загрузка...');
			$('#article').css('opacity', '0.5');
			$('#wait_circle').show();
			var url = '/page/getcolorreturn/&'+"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>;
			$.get(
				url,
				"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				function (result) {
					if (result.type == 'error') {
						alert('error');
						return(false);
					}
					else {
						var options = '';
						var checked = '';
						$(result.color).each(function () {
							checked = '';
							if (color_id == $(this).attr('id')) checked = 'checked="checked"';
							options +=	'<input hidden type="radio" value="' + $(this).attr('id') + '" name="color_return" id="' + $(this).attr('id') + 'color_return" '+ checked +'>'
										'<label for="' + $(this).attr('id') + 'color_return" class="lebb"><i>' + $(this).attr('title') + '</i></label>';
						});
						$('#color_return').html(options);
						$('#article').css('opacity', '1');
						$('#wait_circle').hide();
					}
				},
				"json"
			);
			
		});

		$('#color_return').on('change', 'input[name="color_return"]', function() {
			$( ".error.color" ).fadeOut();
			var size_id = $('input[name="size_return"]:checked').val() || 0;
			var color_id = $('input[name="color_return"]:checked').val() || 0;
			$('.color_click_' + color_id).click();
			if (color_id > 0 && size_id > 0) {
				getArticleReturn(size_id, color_id);
			}
			if (color_id == '0') {
				window.location.reload(true);
				return(false);
			}
			$('#article').css('opacity', '0.5');
			$('#size_return').html('загрузка...');
			$('#wait_circle').show();
			var url = '/page/getsizereturn/&'+"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>;
			$.get(
				url,
				"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				function (result) {
					if (result.type === 'error') {
						alert('error');
						return(false);
					}
					else {
						var options = '';
						var checked = '';
						$(result.size).each(function () {
							checked = '';
							if (size_id === $(this).attr('id')) { checked = 'checked="checked"';}
							options +=	'<input hidden type="radio" value="' + $(this).attr('id') + '" name="size_return" id="' + $(this).attr('id') + 'size_return" '+ checked +'>'+
										'<label for="' + $(this).attr('id') + 'size_return"class="lebb"><i>' + $(this).attr('title') + '</i></label>';
						});
						$('#size_return').html(options);
						$('#article').css('opacity', '1');
						$('#wait_circle').hide();
					}
				},
				"json"
			);
		});
		
	});
</script>