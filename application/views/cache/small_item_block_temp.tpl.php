<?php
$c = false;
$param = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT DISTINCT id_size FROM ws_articles_sizes WHERE id_article='.$this->article->getId().' AND count > 0');
$option =  $this->article->getOptions();

if(isset($option->value) && $option->value and $option->type == 'final'){
$price = $this->article->getPerc();

$pr_real = explode(',', trim(Number::formatFloat($price['price'], 2)))[0];
$t_price = $this->article->temp_price;
if($t_price){
    $old = trim($this->article->showPrice($t_price));
    $procent = ($t_price - $price['price'])/$t_price*100;
  //  $this->article->label_id = 16;
}else{
    $procent = ($this->article->getFirstPrice() - $price['price'])/$this->article->getFirstPrice()*100;
   $old = trim($this->article->showPrice($this->article->getFirstPrice())); 
}
//$old = trim($this->article->showPrice($this->article->getFirstPrice()));
}else{
    $old_price = $this->article->old_price?$this->article->old_price:$this->article->temp_price?$this->article->temp_price:0;
   $procent = $this->article->getUcenka()?$this->article->getUcenka():0;
   if($this->article->temp_price){
        $procent = ($this->article->temp_price - $this->article->price)/$this->article->temp_price*100;
   }
   $pr_real = explode(',', trim(Number::formatFloat($this->article->getPriceSkidka(), 2)))[0];
   $old = $old_price>0?trim($this->article->showPrice($old_price)):0;
}
?>
 <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-3  p-1 px-md-3 d-flex" id="<?=$this->article->id?>">
            <div class="product-grid">
                <div class="product-image">
                    <a href="<?=$this->article->getPath()?>">
                        <?php if($this->article->getImages()->count() > 0){
                          /*   $Headers = @get_headers('https://www.red.ua'.$this->article->getImagePath('card_product'));

if(stripos($Headers[0], '200') !== false) {
$siz_photo = 'card_product';
}else{
$siz_photo = 'detail';
}*/
                            ?>
                        <img class="pic-1" alt="<?=htmlspecialchars($this->article->getTitle())?>"  src="<?=$this->article->getImagePath('detail')?>">
                           <?php $i = 2; 
                            foreach ($this->article->getImages() as $image) {
                            if($image->image){ ?>
                                 <img class="pic-2 catalog_img"  alt="<?=$image->title?>" data-src="<?=$image->getImagePath('detail')?>">
                            <?php 
                            break;
                            }
                           // if($i==3){break;}
                                }
                                }else{ ?>
                         <img class="pic-1 " alt="<?=htmlspecialchars($this->article->getTitle())?>" src="<?=$this->article->getImagePath('card_product')?>">
                        <?php } ?>
                    </a>
                    <ul class="social list-group-horizontal">
                         <?php if($this->getCurMenu()->getUrl() == 'desires'){ ?>
                        <li>   <input hidden id="d_chek-<?=$this->article->getId()?>" type="checkbox" onchange=" $('#'+$(this).attr('id').substr(7)).hide(); setDesires($(this).attr('id').substr(7));" class="chek_des" checked  >
      <label class="leeb des" style="" for="d_chek-<?=$this->article->getId()?>" title="<?=$this->trans->get('Удалить c избранного')?>" data-placement="bottom"  data-tooltip="tooltip"></label>
              </li>
                 <?php } ?>
                         <li><a   href="#comment-modal_article"  onclick="getQuikArticle(<?=$this->article->id?>);  return false;" data-toggle="modal" data-original-title="<?=$this->text_trans[1]?>" ><i class="fa fa-eye ion-ios-eye"></i></a></li>
                        <!--<li><a href="#" data-tooltip="tooltip" data-original-title="Quick View"><i class="fa fa-shopping-bag ion-ios-briefcase"></i></a></li>-->
                        <li><a href="<?=$this->article->getPath()?>"  ><i class="fa fa-shopping-cart ion-ios-cart"></i></a></li>
                    </ul>
                      
                </div>
                <?php if ($this->article->label_id or $procent > 0){ ?>
                <span class="sale-icon">
                    <?=$this->article->label->name?$this->article->label->name:''?>
                        <?=($procent > 0)?'- '.ceil($procent).'%':''?>
                </span> 
                        <?php } ?>
                <?php if(isset($option->value) && $option->value){ ?>
                <span class="sale-icon-right" data-tooltip="tooltip"  data-original-title="<?=$this->article->getOptions()->option_text?>">
                    Акция
                </span> 
       <?php }elseif($this->article->getCashback()){ ?>
                <span class="sale-icon-right" data-tooltip="tooltip"  data-original-title="Возвращаем <?=$this->article->getCashback()?>%  на бонусный счёт">
                Cashback <?=$this->article->getCashback()?>%
                </span>
     <?php  } ?>
               
                
                <div class="product-content">
                    <h3 class="title"><?=$this->article->getModel()?><a href="<?=$this->article->category->getPath().'brands-'.$this->article->brands->getToUrl().'/'?>" class="brand"> <?=$this->article->brands->name?></a></h3>
                    
                        
 <div class="price">
     <?=$pr_real?> <span class="grn">грн.</span>
 <?=$old>0?'<div>'.$old.' <span class="grn">грн.</span></div>':''?>
</div>

                    <div class="size-color">
                    <p>
                        <a href="<?=$this->article->category->getPath()?>"><?=$this->article->category->getH1()?></a>
                    </p>
                    <p>
                    <?=$this->article->color_id?'<span class="color">'.$this->text_trans[2].':<a href="'.$this->article->category->getPath().'colors-'.$this->article->color_id.'/">'.$this->article->color_name->name.'</a></span>,':''?>
                        
                    <?php
                    if($param ){ 
                        $mas = [];
                        echo '<span class="size">'.$this->text_trans[3].': ';
		foreach($param as $size){
		 $mas[] = '<a href="'.$this->article->category->getPath().'sizes-'.$size->size->id.'/">'.$size->size->getSize().'</a>';
                    }
                    echo implode(', ', $mas);
                    echo '</span>';
                    
                } ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
