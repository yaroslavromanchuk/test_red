<?php $text = explode(',', $this->trans->get('Очистить фильтры,Применить фильтры,Категория,Размеры,Цвета,Цена,История просмотров'));
$text_result_trans = explode(',', $this->trans->get('Вы искали,Всего найдено,Сортировать по,цена по возрастанию,цена по убыванию,популярность по возрастанию,популярность по спаданию,дате поступления по возрастанию,дате поступления по спаданию'));
 ?>  
 <!--
<link type="text/css" href="/js/bs_slider/css/bootstrap-slider.min.css?v=1.2" rel="stylesheet"/>
 <script src="/js/bs_slider/bootstrap-slider.min.js"></script>-->
 <!--<link type="text/css" href="/js/priceslider/jquery-ui.css?v=1.2" rel="stylesheet"/>-->
  <script src="/js/priceslider/jquery-ui.min.js"></script>
<span class="search_result" ><?=@$this->search_word?$text_result_trans[0].' : <span class="rez_s">'.$this->search_word.'</span><br>':''?></span>			
<div  class="form-group" id="sersh_r"><?=$text_result_trans[1];?>: <span id="total_founded"><?=$this->result_count?></span></div>
<div class="form-group">
<select name="order_by" id="order_by" onchange="return sorter(0)" class="form-control select2" data-placeholder="<?=$text_result_trans[2]?>:">
							<option value=""  <?php if(!isset($_GET['order_by'])) echo 'selected'; ?> ><?=$text_result_trans[2]?>:</option>
                            <option value="1" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 1) echo 'selected'; ?> ><?=$text_result_trans[3];?></option>
                            <option value="2" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 2) echo 'selected'; ?> ><?=$text_result_trans[4];?></option>
                            <option value="3" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 3) echo 'selected'; ?> ><?=$text_result_trans[5];?></option>
                            <option value="4" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 4) echo 'selected'; ?> ><?=$text_result_trans[6];?></option>
                            <option value="5" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 5) echo 'selected'; ?> ><?=$text_result_trans[7];?></option>
                            <option value="6" <?php if(isset($_GET['order_by']) and $_GET['order_by'] == 6) echo 'selected'; ?> ><?=$text_result_trans[8];?></option>
                        </select>
</div>
			 <a href="" class="clean_filter" onclick="return clearallfilters();"><?=$text[0]?></a>
<div class="form-group" id="list_f">

</div>	
			 
            <input type="hidden" id="selected_root_category" name="selected_root_category" value="<?=$this->finder_category?>">
<?php  if(count($this->filters['categories']) > 1){ ?>
            <div class="list_wrapper sub-title-click text-center">
                <p class="sub-title"><span><?=$text[2]?></span></p>
                <div style="clear: both;"></div>
			
                <div class="list_list drop_list" >
                    <?php
					$asc = array();
					$asc = explode(',' , $this->get->categories);
                        foreach ($this->filters['categories'] as $cat) { ?>
                                    <label for="c_category_<?=$cat['id']?>"  class="ckbox" >
									<input type="checkbox" class="c_category ck" name="categories"
                                           id="c_category_<?=$cat['id']; ?>" value="<?=$cat['id']?>"  <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?> />
                                         <span><?=@$cat['parent']?$cat['parent'].':'.$cat['name']:$cat['name']?><!--<span class="badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
										 
                                    </label>
                            <?php } ?>
	
				
            </div>
			<button type="button"  class="btn btn-secondary btn-sm goFilter drop_list"><?=$text[1]?></button>
			</div>
			<?php }?>
            <!-- Бренды -->
            <?php if (count($this->filters['brands'])) { ?>
                <div class="list_wrapper sub-title-click text-center">
                    <p class="sub-title"><span>Brands</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" >
                        <?php
						$asc = array();
					$asc = explode(',' , $this->get->brands);
					//echo print_r($asc);
						ksort($this->filters['brands']);
                        foreach ($this->filters['brands'] as $cat) { ?>
                            <div>
                                <label for="c_brand<?=$cat['id']?>" class="ckbox">
								<input type="checkbox" class="c_brand ck" id="c_brand<?=$cat['id']?>"
<?php if ($this->get->brands == $cat['id']){ ?> checked="checked" <?php }else if(in_array($cat['id'], $asc)){ ?> checked="checked" <?php } ?>  value="<?=$cat['id']?>"/>
                                    <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
									
                                </label>
                            </div>
                        <?php } ?>
						 
                    </div>
				<button type="button"  class="btn btn-secondary btn-sm goFilter drop_list"><?=$text[1]?></button>
                </div>
            <?php } ?>
			<!-- Сезон -->
			<?php if (count($this->filters['sezons'])) { ?>
			  <div class="list_wrapper  text-center">
                    <p class="sub-title"><span>Сезон</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;" >
                        <?php
					$asc = array();
					$asc = explode(',' , $this->get->sezons);
						foreach ($this->filters['sezons'] as $cat) { ?>
                            <div>
                                <label for="c_sezon<?=$cat['id']?>" class="ckbox">
								<input type="checkbox" class="c_sezon ck" id="c_sezon<?=$cat['id']?>" <?php if ($this->get->sezons == $cat['id']){ ?> checked="checked" <?php }else if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>  value="<?=$cat['id']?>"/>
                                   <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
									
                                </label>
                            </div>
                        <?php } ?>
						
                    </div>
					 <button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;"><?=$text[1]?></button>
                </div>
			<?php }	?>
            <!-- Размеры -->
            <?php if (count($this->filters['sizes'])) { ?>
                <div class="list_wrapper  text-center">
                    <p class="sub-title"><span><?=$text[3]?></span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
                        <?php 
						$asc = array();
					$asc = explode(',' , $this->get->sizes);
						foreach ($this->filters['sizes'] as $cat) { ?>
                                    <label for="s_size<?=$cat['id']?>" class="ckbox">
									 <input type="checkbox" class="s_size ck"  id="s_size<?=$cat['id']?>" <?php if ($this->get->sizes == $cat['id']){ ?> checked="checked" <?php }else if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?> value="<?=$cat['id']?>"/>
                                         <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
                                    </label>
                        <?php } ?>
						
                    </div>
					<button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;"><?=$text[1]?></button>
                </div>
            <?php } ?>
            <!-- Цвета -->
            <?php if (count($this->filters['colors'])) { ?>
                <div class="list_wrapper   text-center" >
                    <p class="sub-title"><span><?=$text[4]?></span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;" >
                        <?php
$asc = array();
					$asc = explode(',' , $this->get->colors);
						foreach ($this->filters['colors'] as $cat) { ?>
                                <label for="c_color<?=$cat['id']?>" class="ckbox">
								 <input type="checkbox" class="c_color ck"  id="c_color<?=$cat['id']?>" value="<?=$cat['id']?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>>
                                      <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
                                </label>
                        <?php } ?>
						 
                    </div>
				 <button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;"><?=$text[1]?></button>
                </div>
            <?php } ?>
            <!-- LABELS -->
            <?php if (count($this->filters['labels'])) { ?>
                <div class="list_wrapper   text-center">
                    <p class="sub-title"><span>Labels</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
					
                        <?php
$asc = array();
					$asc = explode(',' , $this->get->labels);
						foreach ($this->filters['labels'] as $cat) { ?>
                                <label for="c_label<?=$cat['id']?>" class="ckbox">
								<input type="checkbox" class="c_label ck" id="c_label<?=$cat['id']?>" value="<?=$cat['id']?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?>/>
                                      <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
                                </label>
                        <?php } ?>
						
                    </div>
				 <button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;"><?=$text[1]?></button>
                </div>
            <?php } ?>
			<!-- skidka -->
            <?php if (count($this->filters['skidka'])) { ?>
                <div class="list_wrapper  text-center">
                    <p class="sub-title"><span>Скидки</span></p>
                    <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
                        <?php
						$asc = array();
					$asc = explode(',' , $this->get->skidka);
                        foreach ($this->filters['skidka'] as $cat) { ?>
                                <label for="c_skidka<?=$cat['id']?>" class="ckbox">
								<input type="checkbox" class="c_skidka ck" id="c_skidka<?=$cat['id']?>" value="<?=$cat['id']?>" <?php if(in_array($cat['id'], $asc, true)){ ?>checked="checked" <?php } ?> />
								  <span><?=$cat['name']?><!--<span class="badge badge-secondary badge-pill ml-1"><?=$cat['count']?></span>--></span>
                                </label>
                        <?php } ?>
                    </div>
					 <button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;"><?=$text[1]?></button>
                </div>
            <?php } ?>
			<div class="list_wrapper  text-center">
			<p class="sub-title"><span><?=$text[5]?></span></p>
			 <div style="clear: both;"></div>
                    <div class="list_list drop_list" style="display:none;">
<div id="slider"></div> 
<div class="input-group input-group-sm mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" style="font-size: 0.7rem;" id="inputGroup_min">От:</span>
  </div>
  <input type="text" class="form-control" style="font-size: 0.7rem;" aria-label="Small" id="minCost" value="<?=$this->get->price_min?$this->get->price_min:$this->price_min?>"  aria-describedby="inputGroup_min">
   <input type="text" class="form-control" aria-label="Small" style="font-size: 0.7rem;" id="maxCost" value="<?=$this->get->price_max?$this->get->price_max:$this->price_max?>" aria-describedby="inputGroup_max">
  <div class="input-group-append">
    <span class="input-group-text" style="font-size: 0.7rem;" id="inputGroup_max">:До</span>
	
  </div>
</div>

  </div>
  <button type="button"  class="btn btn-secondary btn-sm goFilter drop_list" style="display:none;" ><?=$text[1]?></button>
  </div>
			
            <input type="hidden" id="search_word" value="<?=$this->search_word?>">
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
				<div class="list_wrapper">
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
			
			$("#slider").slider({
	min: <?=$this->price_min?>,
	max: <?=$this->price_max?>,
	values: [<?=$this->get->price_min?$this->get->price_min:$this->price_min?>,<?=$this->get->price_max?$this->get->price_max:$this->price_max?>],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCost").val(jQuery("#slider").slider("values",0));
		jQuery("input#maxCost").val(jQuery("#slider").slider("values",1));
    },
    slide: function(event, ui){
		jQuery("input#minCost").val(jQuery("#slider").slider("values",0));
		jQuery("input#maxCost").val(jQuery("#slider").slider("values",1));
    }
});
$("input#minCost").change(function(){
	var value1=$("input#minCost").val();
	var value2=$("input#maxCost").val();

    if(parseInt(value1) > parseInt(value2)){
		value1 = value2;
		$("input#minCost").val(value1);
	}
	$("#slider").slider("values",0,value1);	
});

	
$("input#maxCost").change(function(){
	var value1=$("input#minCost").val();
	var value2=$("input#maxCost").val();
	
	if (value2 > 1000) { value2 = 1000; $("input#maxCost").val(1000)}

	if(parseInt(value1) > parseInt(value2)){
		value2 = value1;
		$("input#maxCost").val(value2);
	}
	$("#slider").slider("values",1,value2);
});

				$('.goFilter').click(function() {
				//if ($(this).is(':checked')) {
				
				//$(this).appendTo('#list_f');

 // }else{
 // $(this).detach('#list_f');
				
			//	}
				
				$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
				return gatfilterSelected();
				});
				//$("#ex2").slider();
				</script>