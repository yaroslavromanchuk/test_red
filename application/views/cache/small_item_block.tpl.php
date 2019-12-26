<?php
$c = false;
$param = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT DISTINCT id_size FROM ws_articles_sizes WHERE id_article='.$this->article->getId().' AND count > 0');
?>
<li class="article-item <?=$this->article->getId()?> col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-1">
<div class="articles">
  <div  class="img_art">
    <?php
	//$label = ;
        
	if ($this->label){
            ?> <div class="article_label_container_left">
                <div class="article_label">
                    <img src="<?=$this->label?>" alt="" >
                </div>
            </div> 
                <?php } ?>
        
    <?php
   $option =  $this->article->getOptions();
    if($option->value){
        
        ?>
           <div class="article_label_container_right">
               <div class="article_label">
                   <img src="/storage/label/promotion.png" alt="promotion"  data-tooltip="tooltip"  data-original-title="<?=$this->article->getOptions()->option_text?>" >
               </div>
           </div> 
       <?php } ?>
	
    
	<?php if($c){
	$pr = $this->article->getPrice();
	if((float)$this->article->getOldPrice()) {$pr  = $this->article->getOldPrice();}
	$skid = (int)(1-($this->article->getPriceSkidka()/$pr))*100;?>
	<p class="event_label" ><span><?='-'.round($skid).'%'?></span></p>
	<?php } ?>
        
	<?php if($this->article->getImages()->count() > 0){ ?>
	<div id="myCarousel_<?=$this->article->getId()?>" class="carousel slide"  data-interval="false">
	<div class="carousel-inner">
	<a href="<?=$this->article->getPath()?>" class="img">
            <div class="carousel-item active">
                <img src="<?=$this->article->getImagePath('detail')?>" alt="<?=htmlspecialchars($this->article->getTitle())?>">
            </div>
        <?php foreach($this->article->getImages() as $image){
            if($image->image){  ?>
            <div class="carousel-item">
                <img alt="<?=$image->title?>" class="catalog_img" title="<?=$image->title?>" data-src="<?=$image->getImagePath('detail')?>">
            </div><?php } } ?>
</a>
	</div>
	<a class="carousel-control-prev" href="#myCarousel_<?=$this->article->getId()?>" role="button" data-slide="prev">
	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
	<span class="sr-only">Previous</span>
	</a>
  <a class="carousel-control-next" href="#myCarousel_<?=$this->article->getId()?>" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
	</div>
	<?php }else{ ?>
	<a href="<?=$this->article->getPath()?>" class="img" >
	<img src="<?=$this->article->getImagePath('detail')?>" alt="<?=htmlspecialchars($this->article->getTitle())?>">
	</a>
	<?php } ?>
<p class="name"><span><?=$this->article->getModel()?></span></p>
<p class="brand"><span><?=$this->article->getBrand()?></span></p>
<hr style="margin-bottom:  5px;">

	<?php if($option->value and $option->type == 'final'){
$price = $this->article->getPerc(100, 1);
$procent = (int)($this->article->getFirstPrice() - $price['price'])/$this->article->getFirstPrice()*100;

	?>
	<p class="price">
	<?php if($this->article->getOldPrice() > 0){ ?>
	<span style="    padding: 0px 2px;
    background: #e40413;
    color: white;
    font-size: 12px;
    display: inline-block;
    font-weight: normal;">- <?=(int)$procent?> %</span><br>
	<?php } ?>
	<span class="price-old"><?=trim($this->article->showPrice($this->article->getFirstPrice()));?>&nbsp;<span style="font-size:14px;">грн</span></span>
        
	<?php 
        $pric = trim(Number::formatFloat($price['price'], 2));
        $pric = explode(',', $pric);
        echo $pric[0];
        ?>
		<span style="font-size:11px;vertical-align: text-top;margin-left: -4px;"><?=(int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span>&nbsp;<span style="font-size:14px;">грн</span>
	</p>
	<?php }else{ ?>
	<p class="price">
		<?php
$old_price = $this->article->getOldPrice();
if($old_price > 0){ ?>
<span style="    padding: 0px 2px;
    background: #e40413;
    color: white;
    font-size: 12px;
    display: inline-block;
    font-weight: normal;">- <?=$this->article->getUcenka()?$this->article->getUcenka():(int)((1-($this->article->price/$old_price))*100)?> %</span><br>
	<span class="price-old"><?=trim($this->article->showPrice($old_price));?>&nbsp;<span style="font-size:14px;">грн</span>
	</span>
	<?php } ?>
	
	<?php $pric = trim(Number::formatFloat($this->article->getPriceSkidka(), 2)); $pric = explode(',', $pric); echo $pric[0];?>
		<span style="font-size:11px;vertical-align: text-top;margin-left: -4px;"><?=(int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span>&nbsp;<span style="font-size:14px;">грн</span>

	</p>
	<?php } ?>
	
	<div  class="size-color-box p-2">
        <div class="color">
	<div class="name_box"><?=$this->text_trans[2]?>:</div>
	<?=$this->article->color_id?'<div class="no_color_box">'.$this->article->color_name->name.'</div>':''?>
    </div>
	<div style="clear: both;"></div>
    <div class="article-size">
		<div class="name_box"><?=$this->text_trans[3]?>:</div>
		<?php
		foreach($param as $size){
		 echo '<div class="no_color_box">'.$size->size->getSize().'</div>';
		} ?>
    </div>
	<div style="clear: both;"></div>
	<div class="quik_look">
<a href="#comment-modal_article" onclick="getQuikArticle(<?=$this->article->getId()?>);  return false;" data-toggle="modal" ><span class="glaz_quik"></span><?=$this->text_trans[1]?></a>
	  </div>
	  </div>
	  </div>
	  </div>
</li>