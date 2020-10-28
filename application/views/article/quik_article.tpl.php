<script src="/js/desires.js?v=1.4"></script>
<link rel="stylesheet" href="/css/article/article.css?v=1.3">
<link rel="stylesheet" href="/js/gallery/lightgallery.css?v=1.3">
<script src="/js/gallery/lightgallery.js?v=1.3"></script>
<script src="/js/owl/owl.carousel.min.js?v=1.3"></script>
<?php  
if($this->user->id == 8005){
 //echo print_r($this->getShopItem()->getTop());
}
 $option =  $this->getShopItem()->getOptions();
//$c = Skidki::getActivCat($this->getShopItem()->getCategoryId(), $this->getShopItem()->getDopCatId());
$c = false;//Skidki::getActiv($this->getShopItem()->getId());
 ?>

<?php

$Headers = get_headers('https://www.red.ua'.$this->getShopItem()->getImagePath('card_product'));
if(strpos($Headers[0], '200')) {
$rs = 'card_product';
}else{
$rs = 'detail';
}
//$rs = 'detail';
?>
<div class="row article-detail-box p-2 m-0"

<!--foto-->
<div class="photos  col-xs-12 col-sm-12 col-lg-12 col-xl-12">
    <div class="thumbnails-single owl-carousel carousel-inner gallery list-unstyled" id="image-gallery">  
    <?php 
  
 ?>

<a  href='<?=$this->getShopItem()->getImagePath(); ?>' class="zoom carousel-item1 "   data-rel="prettyPhoto[product-gallery]" >

<img  itemprop="image"  src="<?=$this->getShopItem()->getImagePath($rs)?>" alt='<?=htmlspecialchars($this->getShopItem()->getTitle()); ?>' title="<?=htmlspecialchars($this->getShopItem()->getTitle()); ?>"  style="max-width: 100%;" >
</a>
        <?php if (count($this->getShopItem()->getImages()) > 0) { 
                
             foreach ($this->getShopItem()->getImages() as $image) { ?>
<a href='<?=$image->getImagePath()?>' class="zoom carousel-item1 "   data-rel="prettyPhoto[product-gallery]" title="<?=$image->getTitle()?>" >
<img itemprop="image" src="<?=$image->getImagePath($rs)?>" alt="<?=$image->getTitle()?>"  style="max-width: 100%;"   />
</a>

            <?php } } ?>
</div>
	</div>
<!--/foto-->
<!-- /text-->
	<div class="texts col-xs-12 col-sm-12 col-lg-12  col-xl-12 px-0">
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
  $price   = $this->getShopItem()->getPerc();
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
            
            <?php if(/*$this->getShopItem()->getCountArticles()*/false){
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
                            <?php if(false/*$this->getShopItem()->getCountArticles()*/){
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
  $price   = $this->getShopItem()->getPerc();
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

<div class="description p-3 "  >
            <ul class="accordion">
                <?php if ($this->getShopItem()->getLongText() or $this->getShopItem()->getLongTextUk() ) {?>
			<li class="nav-item"><a class="nav-link " href="#tabs-1" data-toggle="collapse" ><?=$this->trans->get('Описание').' '.$this->getShopItem()->model.' '.$this->getShopItem()->brand?></a>
		<div id="tabs-1"  class="collapse show"  data-parent=".description" >
                    <table class="table table-hover" itemprop="description">
                        <tr>
                            <td>Сезон</td>
                            <td><b><a href="<?=$this->getCategory()->getPath()?>sezons-<?=$this->getShopItem()->getNameSezon()->translate?>"><?=mb_strtolower($this->getShopItem()->getNameSezon()->getName())?></a></b></td>
                        </tr>
                        <?php
 
                        $long = explode("<br>", $this->getShopItem()->getLongText());
                        if(count($long) > 0){
                         foreach ($long as $value) { 
                             $s = explode(": ", $value);
                             if(count($s) == 2){
                             ?>
                        <tr>
                            <td><?=$s[0]?></td>
                            <td><b><?=str_replace(".","", str_replace(";","",$s[1]))?></b></td>
                        </tr>
                        <?php
                             }
                         }
                        }
                    ?>
                    </table>
                </div>
                        </li> 
			<?php } ?>
                        <?php if ($this->getShopItem()->getSostav() or $this->getShopItem()->getSostavUk()) { ?>
                <li class="nav-item"><a class="nav-link collapsed" href="#tabs-2" data-toggle="collapse" ><?=$this->trans->get('Cостав')?></a>
                 <div id="tabs-2" class="collapse" data-parent=".description" >
                     <table class="table table-hover">
                         <?php
                         $sos =  explode(";", $this->getShopItem()->getSostav());
                         if(count($sos) > 0){
                             foreach ($sos as $s){
                                 $ss = explode("% ", $s);
                                 if(count($ss) == 2){ ?>
                                      <tr> 
                             <td><?=ucfirst(str_replace(".","", str_replace(";","",$ss[1])))?></td>
                             <td><?=$ss[0]?>%</td>
                         </tr>
                                 <?php }else{
                                    $ss = explode(": ", $s);
                                if(count($ss) == 2){     ?>
                                    <td><?=ucfirst(str_replace(".","", str_replace(";","",$ss[0])))?></td>
                             <td><b><?=$ss[1]?></b></td>
                                     
                                 <?php  } }
                             } 
                         }
                     ?>
                        
                     </table>
                 </div>
                </li>
                        <?php } ?>
                <?php if ($this->getShopItem()->getSootRozmer()) { ?>
                            <li class="nav-item"><a class="nav-link collapsed" href="#tabs-3" data-toggle="collapse"><?=$this->trans->get('Соответствие')?></a>
                                <div id="tabs-3" class="collapse" data-parent=".description"><p><?=$this->getShopItem()->getSootRozmer()?></p></div>
                            </li>
                      <?php  } ?>
                            <li class="nav-item"><a class="nav-link collapsed" href="#tabs-4" data-toggle="collapse"><?=$this->trans->get('Способ доставки')?></a>
                            <div id="tabs-4" class="collapse" data-parent=".description">
	<?php foreach(DeliveryType::find('DeliveryType', ['active_user'=> 1, 'id != 16'],['sort'=>'ASC']) as $dely){ ?>
			<a target="_blank" href="/pays/#<?=$dely->id?>"><p><?=strip_tags($dely->name)?></p></a> 
					<?php } ?>
	</div>
                            </li>
			<li class="nav-item"><a class="nav-link collapsed" href="#tabs-5" data-toggle="collapse"><?=$this->trans->get('Способ оплаты')?></a>
                        <div id="tabs-5" class="collapse" data-parent=".description">
	<?php foreach(PaymentMethod::find('PaymentMethod',['active'=> 1]) as $pay){ ?>
					<p><?=strip_tags($pay->name)?></p>
					<?php } ?>
	</div>
                        </li>
            </ul>
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
                 $("#image-gallery").lightGallery(); 
                 $( '.photos' ).each( function() {
            var $sync1 = $(this).children('.thumbnails-single');
            $sync1.owlCarousel({
                animateOut: 'fadeOut',
                loop:true,
                autoplay: false,
                //autoplayTimeout: 7000,
               // autoplayHoverPause: true,
                items: 1,
                margin: 0,
                dots: true,
                nav: false,
               // rtl: true,
                autoHeight: true,
                responsive:{
                    0:{
                        items:1
                    },
                    480:{
                        items:1
                    },
                    768:{
                        items:1
                    }
                }
            });  
        });
	//$(".phone_form").mask("38(999)999-99-99");
	/*$('a.cloud-zoom').lightBox({
				fixedNavigation: true,
				overlayOpacity: 0.6
			});*/
			//$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
		//$('a.cloud-zoom').lightBox({fixedNavigation: true,overlayOpacity: 0.6});
	
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