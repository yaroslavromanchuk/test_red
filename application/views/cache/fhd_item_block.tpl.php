<div class="article-item-fhd <?=$this->article->id?>" >
    <a href="<?=$this->article->getPath()?>" class="img">
	<img src="<?=$this->article->getImagePath('detail')?>" style="width: 100%;" alt="<?=htmlspecialchars($this->article->getTitle())?>">
	</a>
    <p><span  class="name" ><?php echo $this->article->getModel();?></span> - <span class="brand" ><?php echo $this->article->getBrand();?></span></p>
    <p class="price"><?php $pric = Number::formatFloat($this->article->getPriceSkidka(), 2); $pric = explode(',', $pric); echo $pric[0];?>
	<span style="font-size:11px;vertical-align: text-top;margin-left: -4px;"><?php echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span>&nbsp;грн
	<?php if((float)$this->article->getOldPrice()){ ?>
	<span class="price-old" style="text-decoration: line-through;"><?php echo $this->article->showPrice($this->article->getOldPrice());?>грн</span>
	<?php } ?>
	</p>
</div>
