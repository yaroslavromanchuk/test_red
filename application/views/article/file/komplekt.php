<?php
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
