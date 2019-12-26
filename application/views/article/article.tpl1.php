<?php
$count_article = $this->getShopItem()->getCountArticles();
if($this->user->id == 8005){ ?>
<!-- AstraFit.Loader -->
<script> 
(function(d, s, id, host, ver, shopID, locale){ 
    var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;
    d.astrafitSettings={host:host,ver:ver,shopID:shopID,locale:locale};
    js=d.createElement(s);js.id=id;js.async=true;js.src=host+"/js/loader."+ver+".min.js";
    fjs.parentNode.insertBefore(js,fjs);
}(document, "script", "astrafit-loader", "https://widget.astrafit.com", "latest", 464, "ru"));
</script>
<!-- /AstraFit.Loader -->
       <?php // echo $this->render('poll/index.tpl.php');
     // l($this->get);
  } ?>
<?php
$remind = $this->getShopItem()->getRemind();
if($remind){ // сообщить о наличии
echo $this->render('/article/file/remind.php');
 } ?>
<?php if($count_article) { 
		echo $this->render('/article/file/quik_order.php');
 } ?>
 <div id="rozmernasetka" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$this->trans->get('соответствие размеров')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>   
<div class="container article-index">
<div class="row m-auto">
    <div class="col-sm-12  col-lg-8 px-0 ">
        <div class="row m-auto images">
            <div class="col-sm-12 col-lg-1 px-0  thumbnails-all columns-4 owl-carousel ">
            <a href="<?=$this->getShopItem()->getImagePath('listing')?>" class="first" title="">
              
			<img src="<?=$this->getShopItem()->getImagePath('listing')?>"  class="wp-post-image " alt="" >
		</a>
            <?php if (count($this->getShopItem()->getImages()) > 0) {
                foreach ($this->getShopItem()->getImages() as $image) {?>
             <a href="<?=$image->getImagePath('listing')?>" class="last"  title="">
			<img src="<?=$image->getImagePath('listing')?>" class="wp-post-image" >
		</a>
            <?php } } ?>
        </div>
            
           <div class="photos col-sm-12 col-lg-11  thumbnails-single owl-carousel carousel-inner gallery list-unstyled" id="image-gallery">
              <?php
              $sale_icon ='';
             // $akciya = '';
              if($this->getShopItem()->label_id && $this->getShopItem()->label->name){
                    $sale_icon = '<span class="sale-icon">'.$this->getShopItem()->label->name.'</span>'; ?>
    <?php } ?> 
            <a href="<?=$this->getShopItem()->getImagePath()?>" class="zoom carousel-item1 " title=""  data-rel="prettyPhoto[product-gallery]">
                <?=$sale_icon?>
	<img itemprop="image" src="<?=$this->getShopItem()->getImagePath()?>"  class="jetzoom gimg" alt=""  data-jetzoom = "zoomImage: 'https://www.red.ua<?=$this->getShopItem()->getImagePath()?>'">
		</a>
            <?php if (count($this->getShopItem()->getImages()) > 0) { 
                foreach ($this->getShopItem()->getImages() as $image) { ?>
             <a href="<?=$image->getImagePath()?>" class="zoom carousel-item1 " title=""  data-rel="prettyPhoto[product-gallery]">
                 <?=$sale_icon?>
                <?=$akciya?>
			<img itemprop="image" src="<?=$image->getImagePath()?>"   alt="" class="jetzoom " alt=""  data-jetzoom = "zoomImage: 'https://www.red.ua<?=$image->getImagePath()?>'" >
		</a>
            <?php }} ?>
        </div>
       
        </div>
        
    </div>
    <div class="col-sm-12  col-lg-4 texts px-1">
         <?php if ($count_article) { ?>
        <?php  $option =  $this->getShopItem()->getOptions();
    if($option->value){ ?>
        <div class="card  border-danger mb-3">
         
            <div class="card-body text-danger">
                 <h5 class=" card-body-title  text-center"><?=$option->option_text?></h5>
                <div class="text-center">
                        <p class="p-0 m-0 d_end text-uppercase">До завершения:</p>
                        <div class="timer d-inline-block pt-2 btn-group" id="<?=$option->id?>"></div>
                    </div>
        <script>initializeClock('<?=$option->id?>', new Date("<?=$option->end?> 23:59:59"));</script>
  </div>
        </div>
   <?php } ?>
        <div class="card">
             <div class="card-body">
                <h5 class="card-title model"><?=$this->getShopItem()->getModel()?></h5>
                <p class="shop_brand"  itemprop="brand" itemscope itemtype="http://schema.org/Brand">
                    <a href="<?=$this->getShopItem()->brands->getPath()?>">
                        <span itemprop="name"><?=$this->getShopItem()->getBrand()?></span>
                    </a>
                </p>      
<p class="article-category">
    <a  href="<?=$this->getShopItem()->getCategory()->getPath()?>"><?=$this->getShopItem()->getCategory()->getH1()?></a>
</p>

	<?php if($option->value and $option->type == 'final'){
            $price   = $this->getShopItem()->getPerc(100, 1);
  $pric = explode(',', trim(Number::formatFloat($price['price'], 2)));
            $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getFirstPrice(), 2));
 }else{
             $price['price'] = $this->getShopItem()->getPriceSkidka();
             $pric = explode(',', Number::formatFloat($this->getShopItem()->getPriceSkidka(), 2));
             $pric_sk = false;
            if ($this->getShopItem()->getDiscount()) {
                $pric_sk = explode(',', Number::formatFloat($this->getShopItem()->getOldPrice(), 2));
            }
    }?>
	<p class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
            <meta itemprop="priceCurrency" content="UAH" />
            <span class="price"> 
                <link itemprop="availability" href="http://schema.org/InStock" />
                <input type="text" hidden value="<?=$price['price']?>" itemprop="price" content="<?=$price['price']?>" name="price" id="price">
                <span><?=$pric[0]?></span>
            <?=(int)$pric[1] ? '<span style="font-size:11px; vertical-align: text-bottom; margin-left: -4px;">,'.$pric[1].'</span>' : ''; //копейки?> грн
	</span>
            <?php if($pric_sk){ ?>
			<span class="old-price">
			(<?php echo $pric_sk[0];
					echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
			</span>
            <?php } ?>
	</p>
        <hr>
        <div class="form-group">
           <label class="col-form-label"><?=$this->trans->get('Цвет')?>:</label>
           <div class=" d-inline-block "><?=$this->getShopItem()->color_name->getName()?></div>
        </div>
               
<form action="<?=$this->getShopItem()->getPath()?>" method="post" id="article" class="form was-validated">
                    <div class="form-group">
                    <input type="text" hidden value="<?=$this->getShopItem()->color_id?>" id="color"  name="color">
    <label for="size_sel" class="col-form-label"><?=$this->trans->get('Размер')?>:</label>
    <div class="d-inline-block">
        <?php $mas = [];
        $one_s = 0;
        $one_c = 0;
        $cod = '';
 foreach ($this->getShopItem()->sizes as $k => $size) {
     if ($size->getCount() > 0) {
     $mas[$size->size->id]['size'] = $size->size->size;
     $mas[$size->size->id]['color'] = $size->id_color;
      $mas[$size->size->id]['code'] = $size->code;
     }
 }
 foreach (array_unique($mas) as $k => $value) {
     if(count($mas) == 1){
         $one_s = $k;
        $one_c = $value['color'];
        $cod = $value['code'];
     }
 }
        ?>
    <select class="form-control " name="size"  required id="size">
        <option  label="<?=$this->trans->get('Выберите размер')?>"></option>
        <?php  foreach ($mas as $k => $size) { ?>
                        <option value="<?=$k?>" <?php if(count($mas) == 1){ echo 'selected';}?>  ><?=$size['size']?></option>
        <?php }
        ?>
    </select>
         <span class="error size"><span><?=$this->trans->get('Выберите размер'); ?></span></span>
        </div>
    <div class="d-inline-block"> <a href="/ajax/sitka/id/<?=$this->getShopItem()->category->getSizeType()?>" class="setka btn-link" data-toggle="modal"><?=$this->trans->get('соответствие размеров')?></a></div>
  </div>

    <?php if($one_s and $one_c and $cod){ // $cod = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article'=>$this->getShopItem()->getId(),'id_size'=>$one_s, 'id_color'=>$one_c))->code;
        ?>
        <div class="form-group  art "  >
      <label class="sarticle col-form-label py-0"><?=$this->trans->get('Артикул')?>:</label>
      <span class=" red d-inline-block  "><?=$cod?></span>
      <input style="display: none;"  type="text" name="artikul" id="artikul" value="<?=$cod?>"/>  
    </div>
        <?php }else{ ?>
    <div class="form-group  art "  >
      <label class="sarticle col-form-label py-0"><?=$this->trans->get('Артикул')?>:</label>
      <span class=" red d-inline-block  "></span>
      <input style="display: none;"  type="text" name="artikul" id="artikul" value=""/>  
    </div>
        <?php } ?>
    
    <div class="buttom_click" style="margin: 5px;" >			

    <span class="error error_add"><i class="arrow-left"></i><span class="mes"></span></span>
    <span class="error ok_add"><i class="arrow-left"></i><span class="mes"></span></span>
</div>
       <div class="text-center"> 
    <div class="btn-group  btn-sm btn-group-lg m-auto" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-danger"  id="sub_bascet"  onclick="getQuickCartNew(<?=$this->getShopItem()->getId()?>); return false;" data-placement="bottom"  data-tooltip="tooltip"  title="<?=$this->trans->get('Выбраный Вами товар будет добавлен в корзину'); ?>" >
      <span><?=$this->trans->get('Добавить в корзину')?></span>
  </button>
  <button type="button" class="btn btn-secondary " onclick="getQuickOrderNew(); return false;" data-target="#comment-modal_b_ord" data-toggle=""  data-placement="bottom"  data-tooltip="tooltip" id="quick_order"  title="<?=$this->trans->get('Вам потребуется ввести только имя, телефон и email. Всю дополнительную информацию узнает менеджер по телефону')?>">
  <i class="icon ion-ios-clock-outline" style="font-size:25px"></i>
  </button>
  <button type="button" class="btn btn-secondary">
      <?php $chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }?>
     <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox" class="chek_des"  <?=$chek?>   >
      <label class="leeb des" style="" for="d_chek-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip"></label>
  </button>
<?php if($remind){ ?>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-placement="bottom"  data-tooltip="tooltip" id="quick_order_r" href="#comment-modal_b_ord1" title="<?=$this->trans->get('Вам придет email сообщение, когда выбранный Вами товар появится в наличии')?>.">
                            <i class="icon ion-ios-alarm-outline" style="font-size:25px"></i>
                        </button>                                           
<?php } ?>

</div>
</div>
                </form>
       <hr>
        <?php if($model = $this->getShopItem()->getModels()){ 
       $par = explode(',', $this->trans->get('rost,grud,taliya,bedra')); ?>
<p class="p_model">
        <b><?=$this->trans->get('param_model')?>(см.):</b><br>
               <?=$par[0].':'.$model->rost?>, <?=$par[1].':'.$model->grud?>, <?=$par[2].':'.$model->taliya?>, <?=$par[3].':'.$model->bedra?>
</p> 
<?php } ?>
<div class="description p-3 ">
            <ul class="accordion">
                <?php if ($this->getShopItem()->getLongText() or $this->getShopItem()->getLongTextUk() ) {?>
			<li class="nav-item"><a class="nav-link " href="#tabs-1" data-toggle="collapse" ><?=$this->trans->get('Описание')?></a>
		<div id="tabs-1"  class="collapse show"  data-parent=".description" >
		<p style="line-height: 2.5;">
                    Сезон: <a href="<?=$this->getCategory()->getPath()?>sezons-<?=$this->getShopItem()->getNameSezon()->translate?>"><?=$this->getShopItem()->getNameSezon()->getName()?></a>
		<br><?=$this->getShopItem()->getLongText()?>
		</p></div>
		
                        </li> 
			<?php } ?>
                        
                        <?php if ($this->getShopItem()->getSostav() or $this->getShopItem()->getSostavUk()) { ?>
                <li class="nav-item"><a class="nav-link collapsed" href="#tabs-2" data-toggle="collapse" ><?=$this->trans->get('Cостав')?></a>
                 <div id="tabs-2" class="collapse" data-parent=".description" ><p><?=$this->getShopItem()->getSostav()?></p></div>
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
					<p><?=strip_tags($dely->name)?></p>
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
                   
            </div>
        </div>
    </div>
     <?php  }else{
        
         ?>
           <div class="card">
             <div class="card-body">
                <h5 class="card-title model"><?=$this->getShopItem()->getModel()?></h5>
                <p class="shop_brand"  itemprop="brand" itemscope itemtype="http://schema.org/Brand">
                    <a href="<?=$this->getShopItem()->brands->getPath()?>">
                        <span itemprop="name"><?=$this->getShopItem()->getBrand()?></span>
                    </a>
                </p>      
<p class="article-category">
    <a  href="<?=$this->getShopItem()->getCategory()->getPath()?>"><?=$this->getShopItem()->getCategory()->getH1()?></a>
</p>
	<p class="div_price"  itemprop="offers" itemscope itemtype="http://schema.org/Offer" >
            <meta itemprop="priceCurrency" content="UAH" />
            <span class="price"> 
                <link itemprop="availability" href="http://schema.org/InStock" />
                <input type="text" hidden value="<?=$this->getShopItem()->price?>" itemprop="price" content="<?=$this->getShopItem()->price?>" name="price" id="price">
                <span><?=$this->getShopItem()->price?></span>
            грн
	</span>
            <?php if($this->getShopItem()->old_price){ ?>
			<span class="old-price">
			(<?php echo $this->getShopItem()->old_price;?> грн)
			</span>
            <?php } ?>
	</p>
        <hr>
        <div class="form-group">
           <label class="col-form-label"><?=$this->trans->get('Цвет')?>:</label>
           <div class=" d-inline-block "><?=$this->getShopItem()->color_name->getName()?></div>
        </div>

       <div class="text-center"> 
            <?=$this->trans->get('ТОВАРА НЕТ НА СКЛАДЕ')?>
    <div class="btn-group  btn-sm btn-group-lg m-auto" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-secondary">
      <?php $chek = '';
$title = $this->trans->get('Добавить в избранное');
if($this->ws->getCustomer()->getIsLoggedIn() and wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId(), 'id_articles'=>$this->getShopItem()->getId())) > 0 or $this->getCurMenu()->getPath()=='/desires/'){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    
}else if($_SESSION['desires'][$this->getShopItem()->getId()]){
    $chek = 'checked'; 
    $title = $this->trans->get('Удалить c избранного');
    }?>
     <input hidden id="d_chek-<?=$this->getShopItem()->getId()?>" type="checkbox" class="chek_des" <?=$chek?>   >
      <label class="leeb des" style="" for="d_chek-<?=$this->getShopItem()->getId()?>" title="<?=$title?>" data-placement="bottom"  data-tooltip="tooltip"></label>
  </button>
<?php if($remind){ ?>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-placement="bottom"  data-tooltip="tooltip" id="quick_order_r" href="#comment-modal_b_ord1" title="<?=$this->trans->get('Вам придет email сообщение, когда выбранный Вами товар появится в наличии')?>.">
                            <i class="icon ion-ios-alarm-outline" style="font-size:25px"></i>
                        </button>                                           
<?php } ?>

</div>
</div>
       <hr>
        <?php if($model = $this->getShopItem()->getModels()){ 
       $par = explode(',', $this->trans->get('rost,grud,taliya,bedra')); ?>
<p class="p_model">
        <b><?=$this->trans->get('param_model')?>(см.):</b><br>
               <?=$par[0].':'.$model->rost?>, <?=$par[1].':'.$model->grud?>, <?=$par[2].':'.$model->taliya?>, <?=$par[3].':'.$model->bedra?>
</p> 
<?php } ?>
<div class="description p-3 ">
            <ul class="accordion">
                <?php if ($this->getShopItem()->getLongText() or $this->getShopItem()->getLongTextUk() ) {?>
			<li class="nav-item"><a class="nav-link " href="#tabs-1" data-toggle="collapse" ><?=$this->trans->get('Описание')?></a>
		<div id="tabs-1"  class="collapse show"  data-parent=".description" >
		<p style="line-height: 2.5;">
                    Сезон: <a href="<?=$this->getCategory()->getPath()?>sezons-<?=$this->getShopItem()->getNameSezon()->translate?>"><?=$this->getShopItem()->getNameSezon()->getName()?></a>
		<br><?=$this->getShopItem()->getLongText()?>
		</p></div>
		
                        </li> 
			<?php } ?>
                        
                        <?php if ($this->getShopItem()->getSostav() or $this->getShopItem()->getSostavUk()) { ?>
                <li class="nav-item"><a class="nav-link collapsed" href="#tabs-2" data-toggle="collapse" ><?=$this->trans->get('Cостав')?></a>
                 <div id="tabs-2" class="collapse" data-parent=".description" ><p><?=$this->getShopItem()->getSostav()?></p></div>
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
					<p><?=strip_tags($dely->name)?></p>
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
                   
            </div>
        </div>
   <?php  } ?>
    
</div>
<?php
$code = array(14101,14106,14110,14112,14114,14116,14117,14118,14120,14121,14123,14125,14127,14129,14131,14133,14134,14137,14140,14141,14142,14145,14148,14150,14156);
if(/*in_array($this->getShopItem()->getCode(), $code)*/false){
echo $this->render('/article/file/komplekt.php');
} ?>

</div>
<div class="row m-auto"><?=$this->render('/pages/sliders/articles.php')?></div>
<script>
    $('#size').on('change',  function() {
                    
			$( ".error.size" ).fadeOut();
			var size_id = $('#size').val() || 0;
			var color_id = $('#color').val() || 0;
			if (color_id > 0 && size_id > 0) {
				getArticle(size_id, color_id);
			}
			if (size_id == '0') {
                            $('.sarticle').next("span").html('');
                            $('.art').hide();
			}
			
			
			
		});
	function getArticle(sizeid, colorid) {
		$.ajax({
					type: 'GET',
					dataType: 'json',
					url: '/page/getarticle/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/',
					beforeSend: function(){
					$("#message").fadeOut(300);
						$('#article').css('opacity', '0.1');
					},
					success: function (result) {
						if (result.type === 'error') {
							$('#article').css('opacity', '1');
							$('.sarticle').next("span").html('соответствия размер - цвет не найдено');
							$('.art').show();
						}else {
							$('.sarticle').next("span").html(result.code);
							$('#artikul').val(result.code);
							$('.art').show();
						}
                                                $('#article').css('opacity', '1');
					},
					error:function(e){
						$('.sarticle').next("span").html('error_ajax');
						$('.art').show();
					},
					complete: function(){
					}
				});
		
	}
	     function getQuickCartNew(id) { //dobavlenie tovara v korzinu
	var size = $("#size").val();
	var color = $("#color").val();
	var art = $("#artikul").val();
	var price = $("#price").val();
	if (size > 0 && color > 0 && art != '') {
		$.ajax({
			beforeSend: function () {
				
			},
			type: "POST",
			url: '/addtocard/id/'+id+'/',
			data: '&size=' + size + '&color=' + color + '&artikul=' + art,
			dataType: 'json',
			success: function (data) {
                           // console.log(data);
				if (data.error != 1) {
				
				$('.gimg').clone()
				.appendTo('.article-index')
				.css({
					'position': 'absolute',
					'z-index': '9999',
					top: $('.gimg').offset().top,
    left: $('.gimg').offset().left,
    width: '400px',
    'border-radius': '100px'
				})
				.animate({
					//opacity: 'toggle',
					top: $('.img_bag').offset().top-25,
					left: $('.img_bag').offset().left+5,
					width: '30px'
				}, {
    duration: 1000,
	//queue: false,
    specialEasing: {
      opacity: 'linear',
      height: 'swing'
    } }, function () {
					$(this).remove();
				}).animate({
				 top: $('.img_bag').offset().top+10,
				 left: $('.img_bag').offset().left+10,
				 opacity: 'toggle',
				 width: '10px'
				}, {
    duration: 500});
                                $('#sub_bascet').prop('disabled',true);
					$('#span_ok1').addClass("span_ok").html(data.count).show();
					$('#span_ok').addClass("span_ok").html(data.count).show();
					ga('send', 'add', '/virtual/tovartobacket/');
					dataLayer.push({'event' : 'articles', 'eventAction': 'add_backet', 'eventLabel' : $("#id_tovar").val(), 'eventValue' : price });
				} else {
                                    
                                $(".error.ok_add .mes").html('');
                                $(".error.error_add .mes").html(data.message);
				$(".error.error_add").fadeIn();
				}

			},
			error: function (data) {
				console.log('error = ' + data);
			},
			complete: function () {
				setTimeout(function () {
                                    $(".error.error_add").fadeOut(10);
				}, 4000);
			}
		});
	}
	if (color <= 0 || !color) {
		$(".error.color").fadeIn();
	} else {
		$(".error.color").html('');
	}
	if (size <= 0 || !size) {
            $('#size').focus();
		//$(".error.size").fadeIn();
	}
        if(art == ''){
            console.log('articul error');
        }
}
function getQuickOrderNew() {//bistriy zakaz open form
        var size = $("#size").val();
	var color = $("#color").val();

	if (size == 0 || typeof size == "undefined"){ $(".error.size").fadeIn(); }else{ $(".error.size").html('');}
        
	if (size > 0 && color > 0) {
		$('#quick_order').attr('data-toggle', 'modal');
	}else{
            $(".error.size").fadeIn();
        }
}

	$(document).ready(function () {
            
            $('.setka[data-toggle="modal"]').click(function(e) {
  e.preventDefault();
  var url = $(this).attr('href');
  //console.log('tut');
  //var modal_id = $(this).attr('data-target');
   $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function(res) {
          $('#rozmernasetka .modal-body').html(res);
            $('#rozmernasetka').modal('show');
            
        },
        error:function(request, status, error) {
            console.log("ajax call went wrong:" + request.responseText);
        }
    });
  return false;
});


    $('.chek_des').change(function(event) {
            setDesires($(this).attr('id').substr(7));
    });
	$(".phone_form").mask("38(999)999-99-99");
	
	$('a.cloud-zoom').lightBox({fixedNavigation: true,overlayOpacity: 0.6});
	      
        /*===================================================================================*/
        /*  Electro Product Gallery Carousel
        /*===================================================================================*/
         $("#image-gallery").lightGallery(); 
        $( '.images' ).each( function() {
            var $sync1 = $(this).children('.thumbnails-single');
            var $sync2 = $(this).children('.thumbnails-all');

            var flag = false;
            var duration = 100;

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

            $sync1.on('changed.owl.carousel', function (e) {
                if (!flag) {
                    flag = true;
                    $sync2.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
                $sync2.find(".owl-item").removeClass("synced").eq(e.item.index).addClass("synced");
            });

            $sync2.on('initialized.owl.carousel',function (e) {
                $sync2.find(".owl-item").eq(0).addClass("synced");
            });

            var thumbnail_column_class = $sync2.attr( 'class' );
            var cols = parseInt( thumbnail_column_class.replace( 'thumbnails-all columns-', '' ) );

            $sync2.owlCarousel( {
              //  loop:true,
              
                items: cols,
                margin: 1,
                dots: false,
                nav: false,
                grab: true,
                //rtl: true,
                autoHeight: false,
                responsive:{
                    0:{
                        items: 3
                    },
                    480:{
                        items:3
                    },
                    768:{
                        items:cols
                    },
                }
            });

            $sync2.on('click', 'a', function (e) {
                e.preventDefault();
            });

            $sync2.on('click', '.owl-item', function () {
                $sync1.trigger('to.owl.carousel', [$(this).index(), duration, true]);
            });

            $sync2.on('changed.owl.carousel', function (e) {
                if (!flag) {
                    flag = true;
                    $sync1.trigger('to.owl.carousel', [e.item.index, duration, true]);
                    flag = false;
                }
            });
        });
   

$("#qo1").submit(function () { //bistriy zakaz
	
		var f = $('#telephone').val();
		f = f.replace(/[^0123456789]/g, '');

		if (f.length != 12) {
			var x = 12 - f.length;
			var t = ' В номере телефона не хватает ' + x + ' цыфр.';
			$("#leb").css({
				'color': 'red'
			});
			$("#leb").text(t);
			$('#telephone').css({
				'border-color': '#d8512d'
			});
			setTimeout(function () {
				$('#telephone').removeAttr('style');
			}, 600);
		} else {
			$.ajax({
			beforeSend: function () {
			$('#hide .modal-body #qo-result').html('<div style="text-align:center;"><img src="/img/loading_trac.png"></div>');
			$('#qo-result').show();
			$('#hide .modal-footer').hide();
				},
				type: 'POST',
				url: '/quick-order/',
				data: $("form#qo1").serialize() + '&size=' + $("#size").val() + '&color=' + $("#color").val()+'&artikul='+$('#artikul').val(),
				dataType: 'json',
				success: function (data) {
				
				if(data.result == 'send'){
                                  dataLayer.push({'event' : 'quick', 'eventAction' : 'add_quick'});
				$('#qo-result').hide();
				$('#hide .modal-body').html(data.message);
				$('#hide .modal-footer').hide();
				ga('send', 'quick', '/virtual/quick/');
				ga('send', {hitType: 'event', eventCategory: 'quick',  eventAction: 'add_quick' });
				}else{
				var er = data.message.error;
				t = '';
				for(var key in er){
				$('#'+key).addClass('is-invalid');
				t+=er[key];
				
				}
				$('#hide .modal-body #qo-result').html('<div class="alert alert-danger" role="alert">'+t+'</div>');
					$('#qo-result').fadeIn(300);
					$('#hide .modal-footer').show();
				}	
				},
				error: function (e) {
                                    console.log(e);
					$('#qo-result').html('Извините, но при отправке заказа произошла ошибка, попробуйте позже');
					$('#qo-result').show();
					$('#hide .modal-footer').hide();
				}
			});
		}
		return false;
	});
        
        
        if(window.innerWidth > 992) {
        JetZoom.quickStart();
        }
                
	});
</script>