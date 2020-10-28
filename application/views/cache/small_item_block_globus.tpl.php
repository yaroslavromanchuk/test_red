<?php
$option =  $this->article->getOptions();

if($option->value and $option->type == 'final'){
$price = $this->article->getPerc();
$procent = ($this->article->getFirstPrice() - $price['price'])/$this->article->getFirstPrice()*100;
$pr_real = explode(',', trim(Number::formatFloat($price['price'], 2)))[0];
$old = trim($this->article->showPrice($this->article->getFirstPrice()));
}else{
    $old_price = $this->article->getOldPrice();
   $procent = $this->article->getUcenka()?$this->article->getUcenka():0;
   $pr_real = explode(',', trim(Number::formatFloat($this->article->getPriceSkidka(), 2)))[0];
   $old = $old_price>0?trim($this->article->showPrice($old_price)):0;
}
?>
 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 px-3 float-left">
            <div class="product-grid">
                <div class="product-image">
                    <a href="<?=$this->article->getPath()?>">
                        <?php if($this->article->getImages()->count() > 0){ ?>
                         <img class="pic-1" alt="<?=htmlspecialchars($this->article->getTitle())?>" src="<?=$this->article->getImagePath('detail')?>">
                           <?php $i = 2; 
                            foreach ($this->article->getImages() as $image) {
                            if($image->image){ ?>
                                 <img class="pic-2" alt="<?=$image->title?>" src="<?=$image->getImagePath('detail')?>">
                            <?php 
                            break;
                            }
                                }
                                }else{ ?>
                         <img class="pic-1" alt="<?=htmlspecialchars($this->article->getTitle())?>" src="<?=$this->article->getImagePath('detail')?>">
                        <?php } ?>
                    </a>
                </div>
                <?php if ($this->article->label_id or $procent > 0){ ?>
                <span class="sale-icon">
                    <?=$this->article->label->name?$this->article->label->name:''?>
                        <?=($procent > 0)?'- '.ceil($procent).'%':''?>
                </span> 
                        <?php } ?>
                <?php if($option->value){ ?>
                <span class="sale-icon-right" data-tooltip="tooltip"  data-original-title="<?=$this->article->getOptions()->option_text?>">
                    Акция
                </span> 
       <?php } ?>
                
                <div class="product-content">
                    <h3 class="title"><?=$this->article->getModel()?><a href="<?=$this->article->brands->getPathFind()?>" class="brand"> <?=$this->article->brands->name?></a></h3>
 <div class="price">
<?=$pr_real?> <span class="grn">грн.</span>
<?=$old>0?'<div>'.$old.' <span class="grn">грн.</span></div>':''?>
</div>
                </div>
            </div>
        </div>
