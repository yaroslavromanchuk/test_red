<script type="text/javascript">
    function getQuikArticle(id){
        $('#quik_frame').html('');
        $.post('/product/id/'+id+'/metod/frame/',function(data){
             $('#quik_frame').html(data);

                            $('a.cloud-zoom').lightBox({fixedNavigation:true});
            $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();

                
        });
    }
    $(document).ready(function(){
         $('#quik').css('left',($(document).width()-$('#quik').width())/2);
          $("a[rel]").overlay({mask:{
                color: '#ebecff',
                loadSpeed: 200,
                opacity: 0.7
            }});

    });
</script>
      <div class="simple_overlay" id="quik">
           <a class="close"></a>
<div id='quik_frame' name="quik_frame">

</div>
      </div>
<h1><?php echo $this->getCategory()->getName();?></h1>
    <div style="padding: 10px"><?=$this->getCategory()->getDescription()?></div>

    <div class="shop_sort">
        <form action="" method="get" id='form_sort'>
        Сортировать :
    <select name="sort" onchange="$('form#form_sort').submit();">
        <option value="dateplus" <?php if(@$_GET['sort'] == 'dateplus'){?>selected="selected"<?php } ?>>От нового к старому</option>
        <option value="dateminus" <?php if(@$_GET['sort'] == 'dateminus'){?>selected="selected"<?php } ?>>От старого к новому</option>
        <option value="priceplus" <?php if(@$_GET['sort'] == 'priceplus'){?>selected="selected"<?php } ?>>От дорогого к дешевому</option>
        <option value="priceminus" <?php if(@$_GET['sort'] == 'priceminus'){?>selected="selected"<?php } ?>>От дешевого к дорогому</option>
        <option value="views" <?php if(@$_GET['sort'] == 'views'){?>selected="selected"<?php } ?>>По популярности</option>
        <option value="brandaz" <?php if(@$_GET['sort'] == 'brandaz'){?>selected="selected"<?php } ?>>Бренд: A-Z</option>
        <option value="brandza" <?php if(@$_GET['sort'] == 'brandza'){?>selected="selected"<?php } ?>>Бренд: Z-A</option>
    </select>
            </form>

    </div>

					<div class="articles-list">
                        <div class="art-navi">
                            <!--div class="sort">
                                <a href="#" class="sort sort-asc"></a>
                                <span class="sep"></span>
                                <a href="#" class="sort sort-desc"></a>
                                <span class="label">Сортировка по дате</span>
                            </div-->
<?php ob_start (  ); ?>
                            <!--div class="sort">
                                <a href="#" class="sort sort-asc"></a>
                                <span class="sep"></span>
                                <a href="#" class="sort sort-desc"></a>
                                <span class="label">Сортировка по дате</span>
                            </div-->
                            <div class="summary">Товары с <?php echo $this->cur_page*$this->per_page+1;?> по <?php echo $this->cur_page*$this->per_page+$this->articles->count();?>, всего <?php echo $this->items_count; ?></div>

							<div class="paginator">
									<span class="label">Страница</span>

									<?php if ($this->cur_page > 0) { ?>
										<a href="<?php echo $this->page_url . "page/0"; ?>" class="cur-2">&lt;&lt;</a>
									<?php } else { ?>
										<span class="cur-2">&lt;&lt;</span>
									<?php } ?>

									<?php if ($this->cur_page > 0) { ?>
										<a href="<?php echo $this->page_url . "page/".($this->cur_page - 1); ?>" class="cur">&lt;</a>
									<?php } else { ?>
										<span class="cur">&lt;</span>
									<?php } ?>

									<?php
										$page_start = $this->cur_page - 1;
										if ($page_start < 0)
											$page_start = 0;
										$page_finish = $this->cur_page + 1;
										if ($page_finish > $this->page_count)
											$page_finish = $this->page_count;
										if ($this->page_count >= 2) {
											while ($page_finish - $page_start < 2) {
												if ($page_start > 0)
													$page_start--;
												elseif ($page_finish < $this->page_count)
													$page_finish++;
											}
										}

										for($i=$page_start; $i<=$page_finish; $i++) {
											if ($i == $this->cur_page) { ?>
												<span class="page"><?php echo ($i+1); ?></span>
											<?php } else { ?>
												<a href="<?php echo $this->page_url . "page/".$i; ?>"><?php echo ($i+1); ?></a>
											<?php }
											if ($i < $page_finish)
												echo '<span class="cur">|</span>';
										}
									?>

									<?php if ($this->cur_page < $this->page_count) { ?>
										<a href="<?php echo $this->page_url . "page/".($this->cur_page + 1); ?>" class="cur">&gt;</a>
									<?php } else { ?>
										<span class="cur">&gt;</span>
									<?php } ?>

									<?php if ($this->page_count > $this->cur_page) { ?>
										<a href="<?php echo $this->page_url . "page/{$this->page_count}"; ?>" class="cur-2">&gt;&gt;</a>
									<?php } else { ?>
										<span class="cur-2">&gt;&gt;</span>
									<?php } ?>
                                <?php if(!$this->view_all){?>
                                 | <a href="<?php echo $this->page_url?>?view=all">ПРОСМОТРЕТЬ ВСЕ</a>
                                <?php } else {?>
                                     | <a href="<?php echo $this->page_url . "page/0"; ?>">ПО СТРАНИЦАМ</a>
                                <?php } ?>
								</div>
								<div class="clear"></div>
<?php $paginator = ob_get_contents ( );
ob_end_clean (  );
if ($this->articles->count()) echo $paginator;
?>
             </div>

                        <?php
						$global_images = array();
                        	$cnt = 0;
                        	foreach($this->getArticles() as $article)
							{
								if(!($cnt % 3)) echo '<div class="articles-row">';
								//if ($_SERVER['REMOTE_ADDR'] == '93.72.133.153'){
									$label = false;
									if (wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id'=>$article->getLabelId()))){
										$label = wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id'=>$article->getLabelId()))->getImage();
										
									}

								//}

									$global_images[$article->getId()][] = $article->getImagePath('listing');
									if(count($article->getImages())>0){
									 foreach($article->getImages() as $image){
										 $global_images[$article->getId()][] = $image->getImagePath('listing');
										}
									}

						?>
                            <div class="article-item">

                                <a href="#" onclick="getQuikArticle(<?php echo $article->getId()?>);" rel="#quik" class="quik_look">Быстрый просмотр</a>
                               
                         
	                            <?php if ($label){?>
		                            <div class="article_label_container">
											<div class="article_label">
												   <img src="<?php echo $label?>" alt="" />
											</div>
								</div>
								<?php } ?>
                                <a href="<?php echo $article->getPath();?>" class="img"><img src="<?php echo $article->getImagePath('listing');?>" alt="<?php echo htmlspecialchars($article->getTitle());?>"/></a>
                                <p class="brand"><?php echo $article->getBrand();?>&nbsp;</p>
                                <p class="name"><?php echo $article->getModel();?></p>
                                <p class="price">Цена <?php echo $article->showPrice($article->getPriceSkidka());?>грн</p>
                                <?php if((int)$article->getOldPrice()){?><p class="price-old" style="text-decoration: line-through;"><?php echo $article->showPrice($article->getOldPrice());?>грн</p><?php } ?>
                                <div><?php foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$article->getId().' GROUP BY id_color') as $color){
                                     if($color and $color->color){
                                    ?>

                                    <?php if($color->color->getColor()){?>
                                    <div class="color_box" style="background: <?php echo $color->color->getColor();?>"></div>
                                    <?php } else {?>
                                             <div class="no_color_box">
                                                 <?php echo $color->color->getName();?>
                                             </div>
                                        <?php } ?>
                                    <?php } } ?>
                                    <div style="clear: both;"></div>
                                </div>
                            </div>
						<?php
							$cnt++;
								if(!($cnt % 3)) echo '</div>';
							}
							if($cnt % 3)
								echo '</div>';
						?>
                        <div class="art-navi">
                            <?php if ($this->articles->count()) echo $paginator; ?>
                        </div>
                    </div>
<script type="text/javascript"><!--
var tmb_i = 0;
var tmb_obj = null;
var tmb_timer = null;
<?php
	$thumbs = 'var thmbs={';
	if (count($global_images)>0){
		foreach ($global_images as $img_id=>$picture){
			$pictures = '';
			for ($i = 0, $c = count($picture); $i<$c; $i++){
				$pictures .= '"'.$picture[$i].'",';
			}
			$thumbs .= '"'.$img_id.'":['.rtrim($pictures,',').'],';
		}

		
	}
echo rtrim($thumbs, ',').'};';
?>
//--></script>

<script type="text/javascript" language="javascript">
$(document).ready(function () {


ob = $('.articles-row .article-item');
	if(ob.size() > 0){
		ob.bind("mouseenter",function(){
			var img_obj = $(this).children("a").children("img");
			if(thmbs[tmbGetPrId(img_obj)]){
				if(tmb_timer != null){
					clearInterval(tmb_timer);
				}
				tmb_i = 0;
				tmb_obj = img_obj;
				tmbStep();
				tmb_timer = setInterval(tmbStep, 700);
			}
		}).bind("mouseleave",function(){
			var img_obj = $(this).children("a").children("img");
			var prod_id = tmbGetPrId(img_obj);
			if(thmbs[prod_id] && tmb_timer != null){
				clearInterval(tmb_timer);
				tmb_timer = null;
				img_obj.attr("src",thmbs[prod_id][0]);
			}
		});
	}
});
	
function tmbGetPrId(img){
	var prod_id = img.parent("a").attr("href");
	//prod_id = prod_id.substr(prod_id.lastIndexOf("/")-1);
	//prod_id = prod_id.substr(12,prod_id.lastIndexOf("/"));
	prod_id = prod_id.split('/product/id/');
	prod_id = prod_id[1].split('/');
	//console.log(prod_id);
	return prod_id[0];
}
function tmbStep(){
	tmb_i++;
	var prod_id = tmbGetPrId(tmb_obj);
	
	if((tmb_i+1) > thmbs[prod_id].length){
		tmb_i = 0;
	}
	tmb_obj.attr("src", thmbs[prod_id][tmb_i]);
}


</script>