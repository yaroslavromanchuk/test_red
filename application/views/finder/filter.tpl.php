<!--<link type="text/css" href="/js/bs_slider/css/bootstrap-slider.min.css" rel="stylesheet"/>
<script src="/js/bs_slider/bootstrap-slider.min.js"></script>-->
<?php $text = explode(',', $this->trans->get('Очистить фильтры,Применить фильтры,Категория,Размеры,Цвета,Цена,История просмотров'));
$text_result_trans = explode(',', $this->trans->get('Вы искали,Всего найдено,Сортировать по,цена по возрастанию,цена по убыванию,популярность по возрастанию,популярность по спаданию,дате поступления по возрастанию,дате поступления по спаданию'));
 ?>  
  <!-- Категория --> 
 <?php if ($this->search_word) { echo $text_result_trans[0].':'.$this->search_word.'<br>';} ?>			
<div  class="form-group" id="sersh_r">
<?=$text_result_trans[1];?>: <span id="total_founded"><?=$this->result_count?></span><span class="res_loader" style="display: none;"><img alt="Loading" src="/img/loader.gif"/></span>
</div>
<div class="form-group">
<select name="order_by" id="order_by" onchange="changeSortOrder()" class="form-control select2" data-placeholder="<?=$text_result_trans[2];?>:">
							<option value="" selected><?=$text_result_trans[2];?>:</option>
                            <option value="1"><?=$text_result_trans[3];?></option>
                            <option value="2"><?=$text_result_trans[4];?></option>
                            <option value="3"><?=$text_result_trans[5];?></option>
                            <option value="4"><?=$text_result_trans[6];?></option>
                            <option value="5"><?=$text_result_trans[7];?></option>
                            <option value="6"><?=$text_result_trans[8];?></option>
                        </select>
</div>
			 <a href="#" class="clean_filter" onclick="return clearsearchfilters()"><?=$text[0]?></a>
            <input type="hidden" id="selected_root_category" name="selected_root_category" value="<?=$this->finder_category ?>">
<?php  if(count($this->filters['categories'])> 1){ ?>
            <div class="list_wrapper sub-title-click">
                <p class="sub-title"><span><?=$text[2]?></span></p>
                <div style="clear: both;"></div>
                <div class="list_list drop_list" >
                    <?php
					//echo print_r($this->filters['categories']);
                  //  $groups = array();
                 //   foreach ($this->filters['categories'] as $cat) {
                  //      if ($cat['id'] != $this->finder_category) {
                  //          $groups[@$cat['parent']][] = $cat;
                   //     }
                  //  }
                  //  ksort($groups);
					$asc = array();
					$asc = explode(',' , $this->get->categories);
                   // foreach ($groups as $k => $g) {
					//if(@$k) echo '<h4 class="fh4">'.$k.'</h4>';
                        foreach ($this->filters['categories'] as $cat) {
						
?>
                         
                                <div>
                                    <label for="c_category_<?=$cat['id'];?>"  class="ckbox" >
									<input type="checkbox" class="c_category"
                                           id="c_category_<?=$cat['id']; ?>" value="<?=$cat['id']; ?>"  <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?> />
                                         <span><?php if($cat['parent']) echo $cat['parent'].':'; ?><?=$cat['name']?><span class="co"><?=$cat['count']?></span></span>
                                    </label>
                                </div>
                            <?php
                            
                        }
                   // }
                   ?>
				           
                </div>
		 <a href="#"  onclick="return gatheringSelected('categories', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
            </div>
			<?php }?>
            <!-- Бренды -->
            <?php if (count($this->filters['brands'])) { ?>
                <div class="list_wrapper sub-title-click">
                    <p class="sub-title"><span>Brands</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" >
                        <?php
						$asc = array();
					$asc = explode(',' , $this->get->brands);
						ksort($this->filters['brands']);
                        foreach ($this->filters['brands'] as $cat) { ?>
                            <div>
                                <label for="c_brand<?=$cat['id']; ?>" class="ckbox">
								<input type="checkbox" class="c_brand" id="c_brand<?=$cat['id']; ?>"
<?php if ($this->get->brand == $cat['id']){ ?>checked="checked" <?php }else if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>  value="<?=$cat['id']; ?>"/>
                                    <span><?=$cat['name'].' <span class="co">  '.$cat['count'].'</span>'; ?></span>
                                </label>
                            </div>
                        <?php } ?>
						
                    </div>
					<a href="#"  onclick="return gatheringSelected('brands', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
            <?php } ?>
			<!-- Сезон -->
			<?php if (count($this->filters['sezons'])) { ?>
			  <div class="list_wrapper">
                    <p class="sub-title"><span>Сезон</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
                        <?php foreach ($this->filters['sezons'] as $cat) { ?>
                            <div>
                                <label for="c_sezon<?=$cat['id'];?>" class="ckbox">
								<input type="checkbox" class="c_sezon" id="c_sezon<?=$cat['id']; ?>" value="<?=$cat['id']; ?>"/>
                                 <span><?=$cat['name'].' <span class="co">  '.$cat['count'].'</span>'; ?></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
					<a href="#" style="display:none;" onclick="return gatheringSelected('sezons', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
			<?php }	?>
            <!-- Размеры -->
            <?php if (count($this->filters['sizes'])) { ?>
                <div class="list_wrapper">
                    <p class="sub-title"><span><?=$text[3]?></span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
                        <?php foreach ($this->filters['sizes'] as $cat) { ?>
                              <div>
                                    <label for="s_size<?=$cat['id']; ?>" class="ckbox">
									 <input type="checkbox" class="s_size"  id="s_size<?=$cat['id']; ?>" value="<?=$cat['id']; ?>"/>
                                       <span><?=$cat['name'].' <span class="co">  '.$cat['count'].'</span>'; ?></span>
                                    </label>
                                </div>
                        <?php } ?>
                    </div>
					<a href="#" style="display:none;" onclick="return gatheringSelected('sizes', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
            <?php } ?>
            <!-- Цвета -->
            <?php if (count($this->filters['colors'])) { ?>
                <div class="list_wrapper" >
                    <p class="sub-title"><span><?=$text[4]?></span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
                        <?php
$asc = array();
					$asc = explode(',' , $this->get->colors);
						foreach ($this->filters['colors'] as $cat) { ?>
                            <div>
                                <label for="c_color<?=$cat['id']; ?>" class="ckbox">
								 <input type="checkbox" class="c_color"  id="c_color<?=$cat['id']; ?>" value="<?=$cat['id']; ?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>>
                                    <span> <?=$cat['name'].' <span class="co">  '.$cat['count'].'</span>'; ?></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
					<a href="#" style="display:none;" onclick="return gatheringSelected('colors', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
            <?php } ?>
            <!-- ЦЕНА -->
			<?php if(/*@$this->price_min and @$this->price_max*/false){ ?>
           <div class="list_wrapper d-none"  >
                <p class="sub-title"><span><?=$text[5]?></span></p>
                <div style="clear: both;"></div>
				<div class="price_list drop_list" style="display:none;">
				<input id="ex2" type="text" class="span2" value="" data-slider-min="<?=$this->price_min?>" data-slider-max="<?=$this->price_max?>" data-slider-step="5" data-slider-value="[<?=$this->price_min?>,<?=$this->price_max?>]"/>
				</div>
               <!-- <div class="price_list drop_list" style="display:none;" id="slider">-->
                    <div class="real_price_min"   style="display:none;"><?=$this->price_min ?></div>
                    <div class="real_price_max"  style="display:none;" ><?=$this->price_max ?></div>
                   <div class="formCost" >
					<input type="hidden"  value="<?=$this->price_min.';'.$this->price_max ?>"/>
                        <input type="hidden" id="minCost" value="<?=$this->price_min ?>"/>
                        <input type="hidden" id="maxCost" value="<?=$this->price_max ?>"/>
                   </div>
               <!-- </div>-->
				<a href="#" style="display:none;" onclick="return gatheringSelected('price', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
				</div>
				<?php } ?>
            <!-- LABELS -->
            <?php if (count($this->filters['labels'])) { ?>
                <div class="list_wrapper">
                    <p class="sub-title"><span>Labels</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list">
					
                        <?php
$asc = array();
					$asc = explode(',' , $this->get->labels);
						foreach ($this->filters['labels'] as $cat) { ?>
                            <div>
                                <label for="c_label<?=$cat['id']; ?>" class="ckbox">
								<input type="checkbox" class="c_label" id="c_label<?=$cat['id']; ?>" value="<?=$cat['id']; ?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>/>
                                    <span><?=$cat['name'].' <span>  '.$cat['count'].'</span>'; ?></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
					<a href="#" style="display:none;" onclick="return gatheringSelected('labels', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
            <?php } ?>
			<!-- skidka -->
            <?php if (count($this->filters['skidka'])) { ?>
                <div class="list_wrapper">
                    <p class="sub-title"><span>Скидки</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list">
                        <?php
						$asc = array();
					$asc = explode(',' , $this->get->skidka);
                        foreach ($this->filters['skidka'] as $cat) { ?>
                            <div>
                                <label for="c_skidka<?=$cat['id']; ?>" class="ckbox">
								<input type="checkbox" class="c_skidka" id="c_skidka<?=$cat['id']; ?>" value="<?=$cat['id']; ?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?> />
                                    <span><?=$cat['name'].' <span>  '.$cat['count'].'</span>'; ?></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
					<a href="#" style="display:none;" onclick="return gatheringSelected('skidka', 0, 4)" class="openFilter drop_list"><?=$text[1]?></a>
                </div>
            <?php } ?>
            <input type="hidden" id="search_word" value="<?=$this->search_word ?>">
			<div>
			<?php				
			
								$flag = 0;
								$mass = array();			
								if ((isset($_SESSION['hist']) or $this->history)) {
								if($this->history){
								$flag = 1;
								}else if(count($_SESSION['hist']) > 0){
								$flag = 2; 
								$mass = array_unique($_SESSION['hist']);
								krsort($mass);
								}
                        if ($flag == 2) { ?>
					<div class="list_wrapper">
                    <p class="sub-title">
					<span><?=$text[6];?></span>
					</p>
					<div style="clear: both;"></div>
                    <div class="list_list drop_list" style="max-height:100%;display:none;" >
                        <?php $i = 0; foreach ($mass as $v) {
$history_products = wsActiverecord::useStatic('Shoparticles')->findFirst(array('id'=>$v, 'stock > 0')); ?>
	<div class="top_articles_item " >
         <a name="<?=$history_products->getId(); ?>" href="<?=$history_products->getPath();?>" style="    text-align: center;">
        <img  src="<?=$history_products->getImagePath('detail'); ?>" alt="<?=$history_products->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$history_products->getPath();?>"><?=$history_products->getModel();?></a></h3>
				<h4 style="text-align:left;"><a href="<?=$history_products->getPath();?>"><?=$history_products->getBrand();?></a></h4>
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
				<div class="list_wrapper">
                    <p class="sub-title"><span><?=$text[6];?></span></p>
					<div style="clear: both;"></div>
                    <div class="list_list drop_list" style="max-height:100%;display:none;" >
                        <?php foreach ($this->history as $v) { ?>
	<div class="top_articles_item " >
         <a name="<?=$v->getId(); ?>" href="<?=$v->getPath();?>" style="    text-align: center;">
        <img  src="<?=$v->getImagePath('detail'); ?>" alt="<?=$v->getBrand();?>" style="max-width:100%;"  >  
		</a>
				<div class="post-name" >
				<h3><a href="<?=$v->getPath();?>"><?=$v->getModel();?></a></h3>
				<h4 style="text-align:left;"><a href="<?=$v->getPath();?>"><?=$v->getBrand();?></a></h4>
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
				//$("#ex2").slider();
				</script>