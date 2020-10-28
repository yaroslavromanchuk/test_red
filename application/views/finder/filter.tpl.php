<?php 
  $text = explode(',', $this->trans->get('Очистить фильтры,Применить фильтры,Категория,Размеры,Цвета,Цена,История просмотров'));
  $text_result_trans = explode(',', $this->trans->get('Вы искали,Всего найдено,Сортировать по,цена по возрастанию,цена по убыванию,популярность по возрастанию,популярность по спаданию,дате поступления по возрастанию,дате поступления по спаданию'));
?>
<?php if($this->filters){ ?>
 <form class="form-filter w-100" action="#" >
<div class="card">
<div class="row m-0 content">
<?php  if($this->search_word){ ?>
<div class="d-none">
   <span class="search_result" ><?=$this->search_word?$text_result_trans[0].' : <span class="rez_s">'.$this->search_word.'</span><br>':''?></span>
    <input type="hidden" id="search_word" value="<?=$this->search_word?>" >
</div>
<?php } ?> 
<div class="col-sm-12  p-1 text-center">
    <div class="form-group mb-0">
       <select name="order_by" id="order_by" onchange="return getOrdreBy(this,0)" class="form-control select2" data-placeholder="<?=$text_result_trans[2]?>:">
                            <option value=""><?=$text_result_trans[2]?>:</option>
                            <option value="cena_vozrastaniyu" <?php if($this->get->order_by and $this->get->order_by == 'cena_vozrastaniyu'){ echo 'selected';} ?> ><?=$text_result_trans[3]?></option>
                            <option value="cena_ubyvaniyu" <?php if($this->get->order_by and $this->get->order_by  == 'cena_ubyvaniyu') {echo 'selected';} ?> ><?=$text_result_trans[4]?></option>
                            <option value="populyarnost_vozrastaniyu" <?php if($this->get->order_by and $this->get->order_by  == 'populyarnost_vozrastaniyu'){ echo 'selected';} ?> ><?=$text_result_trans[5]?></option>
                            <option value="populyarnost_spadaniyu" <?php if($this->get->order_by and $this->get->order_by  == 'populyarnost_spadaniyu') {echo 'selected';} ?> ><?=$text_result_trans[6]?></option>
                            <option value="date_vozrastaniye" <?php if($this->get->order_by and $this->get->order_by == 'date_vozrastaniye') {echo 'selected';} ?> ><?=$text_result_trans[7]?></option>
                            <option value="date_spadaniye" <?php if($this->get->order_by and $this->get->order_by == 'date_spadaniye') {echo 'selected';} ?> ><?=$text_result_trans[8]?></option>
        </select> 
    </div>

</div>
		<!--	 <a href="" class="clean_filter" onclick="return getClearAllFilters();"><?=$text[0]?></a>-->
                     		 
<input type="hidden" id="selected_root_category" name="selected_root_category" value="<?=$this->finder_category?>">
            <!-- Бренды -->
            <?php if (count($this->filters['brands'])){ ?>
            <div class="col-sm-12  text-center align-middle p-1">
                <div class="form-group has-danger mb-0">
                <select class="form-control select2" data-placeholder="Brands" multiple name="brands" id="brands">
                    <?php
				$asc = explode(',' , $this->get->brands);
                             //   ksort($this->filters['brands']);
                        foreach ($this->filters['brands'] as $cat) { ?>
                    <option <?php if ($this->get->brands == urldecode($cat['name'])){ ?> selected <?php }elseif(in_array(urldecode($cat['name']), $asc)){ ?> selected <?php } ?>  value="<?=$cat['name']?>"><?=$cat['title']?></option>
                        <?php } ?>
                </select>
            </div>
                 </div>
            <?php } ?>
<!-- Сезон -->
	<?php if (count($this->filters['sezons'])){ ?>
	<div class="col-sm-12  text-center align-middle p-1">
            <div class="form-group has-danger mb-0">
                                <select class="form-control select2" data-placeholder="Сезон" multiple name="sezons" id="sezons">
                                    <?php
					//$asc = [];
					$asc = explode(',' , $this->get->sezons);
						foreach ($this->filters['sezons'] as $cat) { ?>
                                     <option <?php if ($this->get->sezons == $cat['translate']){ ?> selected <?php }else if(in_array($cat['translate'], $asc)){ ?> selected <?php } ?>  value="<?=$cat['translate']?>"><?=$cat['name']?></option>
                                                <?php } ?>
                                </select>
            </div>			
        </div>
			<?php }	?>
            <!-- Размеры -->
<?php if (count($this->filters['sizes'])){ ?>
        <div class="col-sm-12  text-center align-middle p-1">
                    <div class="form-group has-danger mb-0">
                        <select class="form-control select2" data-placeholder="<?=$text[3]?>" multiple name="sizes" id="sizes">
                          <?php 
			$asc = explode(',' , $this->get->sizes);
			foreach ($this->filters['sizes'] as $cat) { ?>
  <option <?php if ($this->get->sizes == $cat['id']){ ?> selected <?php }else if(in_array($cat['id'], $asc)){ ?> selected <?php } ?>  value="<?=$cat['id']?>"><?=$cat['name']?></option>                           
                        <?php } ?>  
                        </select>				
        </div>				
        </div>
            <?php } ?>
            <!-- Цвета -->
            <?php if (count($this->filters['colors'])){ ?>
                <div class="col-sm-12  text-center align-middle p-1" >
                     <div class="form-group has-danger mb-0">
                       <select class="form-control select2" data-placeholder="<?=$text[4]?>" multiple name="colors" id="colors">   
                        <?php
			$asc = explode(',' , $this->get->colors);
			foreach ($this->filters['colors'] as $cat) { ?>
<option <?php if ($this->get->colors == $cat['id']){ ?> selected <?php }else if(in_array($cat['id'], $asc)){ ?> selected <?php } ?>  value="<?=$cat['id']?>"><?=$cat['name']?></option>                                                    
                        <?php } ?>
			</select>			 
                    </div>
                </div>
            <?php } ?>
            <!-- LABELS -->
            <?php if (count($this->filters['labels'])){ ?>
                <div class="col-sm-12  text-center align-middle p-1">
                    <div class="form-group has-danger mb-0" >
                         <select class="form-control select2" data-placeholder="Labels" multiple name="labels" id="labels">   
                        <?php
			$asc = explode(',' , $this->get->labels);
			foreach ($this->filters['labels'] as $cat) { ?>
<option <?php if ($this->get->labels == $cat['id']){ ?> selected <?php }else if(in_array($cat['id'], $asc)){ ?> selected <?php } ?>  value="<?=$cat['id']?>"><?=$cat['name']?></option>
                        <?php } ?>
			</select>			
                    </div>
                </div>
            <?php } ?>
			<!-- skidka -->
            <?php if (count($this->filters['skidka'])){ ?>
                <div class="col-sm-12  text-center align-middle p-1">
                    <div class="form-group has-danger mb-0" >
                         <select class="form-control select2" data-placeholder="Скидки" multiple name="skidka" id="skidka">   
                        <?php
			$asc = explode(',' , $this->get->skidka);
                        foreach ($this->filters['skidka'] as $cat) { ?>
<option <?php if ($this->get->skidka == $cat['id']){ ?> selected <?php }else if(in_array($cat['id'], $asc)){ ?> selected <?php } ?>  value="<?=$cat['id']?>"><?=$cat['name']?></option>                        
                        <?php } ?>
</select>
                    </div>
                </div>
            <?php } ?>
    <?php if($this->filters['price_min'] || $this->filters['price_max']){ ?>
			<div class="col-sm-12   text-center align-middle p-1">
                    <div class="form-group mb-0" >
<div class="input-group">
  <span class="input-group-addon tx-size-sm lh-2"><?=$text[5]?></span>
  <input type="number" class="form-control price min"  name="price_min" min="<?=$this->filters['price_min']?>" max="<?=$this->filters['price_max']-1?>"  placeholder="от"  value="<?=$this->get->price_min?$this->get->price_min:''?>" >
  <input type="number" class="form-control price max"   placeholder="до" min="<?=$this->filters['price_min']+1?>" max="<?=$this->filters['price_max']?>"  name="price_max" value="<?=$this->get->price_max?$this->get->price_max:''?>" >
</div>
  </div>
  </div>
                        <?php  } ?>
                        <div class="col-sm-12  text-center ">
                            <i class="icon ion-ios-checkmark-circle-outline goFilter mr-2" data-tooltip="tooltip" data-original-title="<?=$text[1]?>" title="<?=$text[1]?>" style="font-size: xx-large; cursor: pointer"></i>
<i class="icon ion-ios-close-circle-outline" style="font-size: xx-large; cursor: pointer" data-tooltip="tooltip"  onclick="return getClearAllFilters();" data-original-title="<?=$text[0]?>" title="<?=$text[0]?>" ></i>                            
                        </div>
                            
                   
                        
                        
</div>
     </div>
 </form>  
<?php     }
                        ?>
			<div>
			<?php				
			
								$flag = 0;
								$mass = array();			
								if ((isset($_SESSION['hist']) or $this->history) and false) {
								if($this->history){
								$flag = 1;
								}else if(count($_SESSION['hist']) > 0){
								$flag = 2; 
								$mass = array_unique($_SESSION['hist']);
								krsort($mass);
								}
                        if ($flag == 2) { ?>
					<div class="col list_wrapper">
                    <p class="sub-title">
					<span><?=$text[6]?></span>
					</p>
					<div style="clear: both;"></div>
                    <div class="list_list drop_list" style="max-height:100%;display:none;" >
                        <?php $i = 0; foreach ($mass as $v) {
$history_products = wsActiverecord::useStatic('Shoparticles')->findFirst(array('id'=>$v, 'stock > 0')); ?>
	<div class="top_articles_item " >
         <a name="<?=$history_products->getId()?>" href="<?=$history_products->getPath()?>" style="text-align: center;">
        <img  src="<?=$history_products->getImagePath('detail')?>" alt="<?=$history_products->getBrand()?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name">
				<h3><a href="<?=$history_products->getPath()?>"><?=$history_products->getModel()?></a></h3>
				<h4 style="text-align:left;"><a href="<?=$history_products->getPath()?>"><?=$history_products->getBrand()?></a></h4>
				</div>
				<p style="font-size: 14px;"><?=$history_products->getPrice()?> грн</p>
				<hr>
     </div>
                      <?php
$i++;
if($i == 5) break;
					  } ?>
                    </div>
                </div>
                        <?php }else if($flag == 1){ ?>
				<div class="col list_wrapper">
                    <p class="sub-title"><span><?=$text[6]?></span></p>
					<div style="clear: both;"></div>
                    <div class="list_list drop_list" style="max-height:100%;display:none;" >
                        <?php foreach ($this->history as $v) { ?>
	<div class="top_articles_item">
         <a name="<?=$v->getId(); ?>" href="<?=$v->getPath()?>" style="text-align: center;">
        <img  src="<?=$v->getImagePath('detail')?>" alt="<?=$v->getBrand()?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$v->getPath()?>"><?=$v->getModel()?></a></h3>
				<h4 style="text-align:left;"><a href="<?=$v->getPath()?>"><?=$v->getBrand()?></a></h4>
				</div>
				<p style="font-size: 14px;"><?=$v->getPrice()?> грн</p>
				<hr>
     </div>
                      <?php } ?>
                    </div>
                </div>
						
						<?php } ?>
						
						<?php } ?>
			    </div>
                        
<script>
      $(function(){

        'use strict';

        $('.select2').select2({
          minimumResultsForSearch: Infinity
        });
    });

				$('.goFilter').click(function() {
				if (true) {
                               return gatFilters($("form.form-filter").serializeArray());

  }else{
              $(this).detach('#list_f');
  $('#list_f').html('Выберите параметр фильтрации');
				
				}
				
				
				});
				</script>