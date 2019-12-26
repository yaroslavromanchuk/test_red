<script src="/js/desires.js?v=1.4"></script>
<?php  
if($this->user->id == 8005){
 //echo print_r($this->getShopItem()->getTop());
}
//$c = Skidki::getActivCat($this->getShopItem()->getCategoryId(), $this->getShopItem()->getDopCatId());
$c = false;//Skidki::getActiv($this->getShopItem()->getId());
 ?>

		<!-- !Comment modal -->
	<!--<div class="modal fade comment-form" id="comment-modal_b_ord" tabindex="-1" role="dialog" aria-labelledby="comment-modal_b_ord">
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
								}
								elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
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

	</div>	-->
	<!-- End Comment modal -->
<?php

$Headers = get_headers('https://www.red.ua'.$this->getShopItem()->getImagePath('card_product'));
if(strpos($Headers[0], '200')) {
$rs = 'card_product';
}else{
$rs = 'detail';
}
//$rs = 'detail';
	$label = false;
	if ($this->getShopItem()->getLabelId()) {
		$label = $this->getShopItem()->getLabel()->getImage();// wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $this->getShopItem()->getLabelId()))->getImage();
	}
?>
<div class="row article-detail-box p-2 m-0">


<!--foto-->
<div class="photos  col-xs-12 col-sm-12 col-lg-12 col-xl-12">

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
 <?php if ($label and false) { ?>
    <div class="article_label_container_left"><div class="article_label"><img alt="label" src="<?=$label?>" style="width: 100%"/></div></div>
        <?php } ?>
     <?php
     if($this->getShopItem()->label_id){ ?>
         <span class="sale-icon">
                    <?=$this->getShopItem()->label->name?$this->getShopItem()->label->name:''?>
                </span>
    <?php }
   $option =  $this->getShopItem()->getOptions();
    if($option->value){
        ?>
    <div class="article_label_container_right" >
        <div class="article_label">
            <img src="/storage/label/promotion.png" alt="promotion"  data-tooltip="tooltip"  data-original-title="<?=$option->option_text?>" >
        </div> 
    </div> 
       <?php } ?>
<div style="display:none" id="cloud_big_src"><?=$this->getShopItem()->getImagePath(); ?></div>

<a onclick="$('a.cloud-zoom').css('z-index',1);" href='<?=$this->getShopItem()->getImagePath(); ?>' class='cloud-zoom' id='zoom' >

<img class="photo-big" style="    margin: auto;border: 0; box-shadow: 0 0 0 0;" id="test" itemprop="image"  src="<?=$this->getShopItem()->getImagePath($rs)?>" alt='<?=htmlspecialchars($this->getShopItem()->getTitle()); ?>' title="<?=htmlspecialchars($this->getShopItem()->getTitle()); ?>" >
</a>
</div>
	</div>
<?php if (count($this->getShopItem()->getImages()) > 0) { ?>
<div class="photo_min col-xs-12  col-lg-12 col-xl-12 ">
    <div class="row p-2">
        <div class="col-xs-12  col-lg-2 col-xl-2">
<a href='<?=$this->getShopItem()->getImagePath()?>' class="cloud-zoom-gallery" title="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" data-rel="useZoom: 'zoom', smallImage: '<?=$this->getShopItem()->getImagePath($rs)?>'">						
    <div class="for_cloud_big_src" style="display:none"><?=$this->getShopItem()->getImagePath();?></div>
    <img src="<?=$this->getShopItem()->getImagePath('listing');?>" alt="<?=htmlspecialchars($this->getShopItem()->getTitle())?>" class="photo-small" style="max-width: 100%;" onmouseover="$(this).parent().click()"/>
</a>
            </div>
<?php foreach ($this->getShopItem()->getImages() as $image) { ?>
        <div class="col-xs-12  col-lg-2 col-xl-2">
<a href='<?=$image->getImagePath()?>' class='cloud-zoom-gallery  <?=((int)$image->getColorId() > 0)?' color_click_'.$image->getColorId():''?>' title="<?=$image->getTitle()?>"  data-rel="useZoom: 'zoom', smallImage: '<?=$image->getImagePath($rs)?>'" 
>
<div class="for_cloud_big_src" style="display:none"><?=$image->getImagePath();?></div>
<img src="<?=$image->getImagePath('listing')?>" alt="<?=$image->getTitle()?>" class="photo-small" style="max-width: 100%;"   onmouseover="$(this).parent().click()" />
</a>
            </div>
<?php } ?>
            
        </div>
</div>
<?php } ?>
<!--/foto-->
<!-- /text-->
	<div class="texts col-xs-12 col-sm-12 col-lg-12  col-xl-12 px-0">
            <!--
<div class="model d-inline-block"><?=$this->getShopItem()->getModel();?></div>
<div class="shop_brand d-inline-block"  itemprop="brand" itemscope itemtype="http://schema.org/Brand">
<a href="/brands/id/<?=$this->getShopItem()->brand_id?>/<?=$this->getShopItem()->getBrand()?>/"><span itemprop="name"><?=$this->getShopItem()->getBrand()?></span></a>
</div>
<div class="article-category  d-inline-block">
    <span  style="color: #969696;text-transform: uppercase;"><?=$this->trans->get('category')?> : </span>
    <a  href="<?=$this->getShopItem()->getCategory()->getPath()?>"><?=$this->getShopItem()->getCategory()->getH1()?></a>
</div>-->
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
<p class="mb-0">                      <b><?=$this->trans->get('Размер')?>:</b> 
						
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
			
<div class="buttom_click d-inline-block" >			
<button id="sub_bascet"  onclick="getQuickCart(<?=$this->getShopItem()->getId()?>); return false;" class="btn btn-danger btn-lg" data-placement="bottom"  data-tooltip="tooltip"  title="<?=$this->trans->get('Выбраный Вами товар будет добавлен в корзину'); ?>"  >
					<i class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></i><span><?=$this->trans->get('В КОРЗИНУ')?></span>  
</button>
    <span class="error error_add"><i class="arrow-left"></i><span class="mes"></span></span>
    <span class="error ok_add"><i class="arrow-left"></i><span class="mes"></span></span>
</div>

<img id="wait_circle" src="/images/loader.gif" alt="Идёт загрузка..." style="margin-left: 25px;display:none;" >
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
                            <?php if($this->getShopItem()->getCountArticles()){
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
<script src="/js/call/jquery.mask.js"></script>
<script>
	function getArticle(sizeid, colorid) {
		if (sizeid > 0) {
			if (colorid > 0) {
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: '/page/getarticle/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/',
					beforeSend: function(){
					$("#message").fadeOut(300);
					//$("#message").html('');
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
	$(document).ready(function () {
		$(".tab-content").tab();
	//$(".phone_form").mask("38(999)999-99-99");
	
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
			var url = '/page/getcolor/&'+"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/';
			$.get(
				url,
				"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/',
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
							/*+'<label for="' + $(this).attr('id') + 'color"><i>' + $(this).attr('title') + '</i></label>';*/
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
			var url = '/page/getsize/&'+"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/';
			$.get(
				url,
				"color_id=" + color_id + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/',
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
	});
</script>