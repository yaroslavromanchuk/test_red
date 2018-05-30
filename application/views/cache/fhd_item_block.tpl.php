<div class="article-item-fhd <?php echo $this->article->getId(); ?>" >
    <?php if ($this->label){?>
    <div class="article_label_container">
        <div class="article_label">
            <img src="<?php echo $this->label?>" alt="" >
        </div>
    </div> 
    <?php } ?>
    <a href="<?php echo $this->article->getPath();?>" class="img">
	<img src="<?php echo $this->article->getImagePath('detail');?>" style="width: 100%;" alt="<?php echo htmlspecialchars($this->article->getTitle());?>">
	</a>
    <!--<p class="brand"><?php echo $this->article->getBrand();?></p>-->
    <p><span  class="name" ><?php echo $this->article->getModel();?></span> - <span class="brand" ><?php echo $this->article->getBrand();?></span></p>
    <p class="price"><?php $pric = Number::formatFloat($this->article->getPriceSkidka(), 2); $pric = explode(',', $pric); echo $pric[0];?>
		<span style="font-size:11px;vertical-align: text-top;margin-left: -4px;"><?php echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span>&nbsp;грн
	<?php if((float)$this->article->getOldPrice()){ ?>
	<span class="price-old" style="text-decoration: line-through;"><?php echo $this->article->showPrice($this->article->getOldPrice());?>грн</span>
	<?php } ?>
	
	</p>
   <!-- <div>
		<div class="name_box"><?=$this->trans->get('Цвета')?>:</div><?php
		foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$this->article->getId().' AND count > 0 GROUP BY id_color') as $color){
            if($color and $color->color){
			if($color->color->getColor()){?>
                <div class="color_box" style="background: <?php echo $color->color->getColor();?>"></div>
                <?php } else {?>
                <div class="no_color_box"><?php echo $color->color->getName();?></div>
                <?php } ?>
            <?php } } ?>
        <div style="clear: both;"></div>
    </div>
    <div class="article-size">
		<div class="name_box"><?=$this->trans->get('Размеры')?>:</div><?php
		foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$this->article->getId().'  AND count > 0 GROUP BY id_size') as $size){
		if(@$size and @$size->color){
			if(@$size->size->getSize() and $size->count > 0){
		?>
                <div class="no_color_box"><?php echo $size->size->getSize();?></div>
            <?php }}} ?>
        <div style="clear: both;"></div>
    </div>-->
</div>
