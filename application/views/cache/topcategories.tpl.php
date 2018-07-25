<div class="row logo">  
	<div class="col-xs-12 col-sm-10 col-md-3 d-none d-md-block pt-3 pl-3 pr-0 text-center">
        <form method="get" class="form-inline" action="/search/">
		<div class="w-100">
		<input type="text" class="form-control" style="max-width: 200px;background: url(/img/top_menu/img_search.png) no-repeat top 5px right 4px #fff;background-size: 22px;display:inline-block;" data-provide="typeahead"  maxlength="30" name="s" placeholder="<?=$this->trans->get('поиск');?>" pattern="^[A-Za-zА-Яа-яЁё ,.-_&amp;іїІЇь]+$" >
		<input type="submit" class="btn" value="Искать" style="display:none;" >
		</div>
		</form>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 px-0 py-2">
	<div class="vc_separator    vc_sep_pos_align_center vc_sep_color_black double-bordered-thick " >
	<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line" style="border-color: #838282;"></span></span>
	<a href="/">
	<div  class="center_logo_red" style="margin: 0 20px;background: url(<?=Config::findByCode('logotype')->getValue()?>);background-size: cover;"></div></a>
	<span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line" style="border-color: #838282;"></span></span>
	</div>
	</div>
<div class="col-xs-12 col-sm-10 col-md-3 d-none d-md-block pt-3 pl-3 pr-0 text-center" >
<div class="phone w-100">
<a href="/contact/"><span data-placement="bottom"  data-tooltip="tooltip" title="Наши контакты"><?=Config::findByCode('phones')->getValue()?></span>
<?php if(Config::findByCode('new_grafik')->getValue()) { ?>
<span  data-placement="bottom"  data-tooltip="tooltip" title="<?=Config::findByCode('new_grafik')->getValue()?>" style="color:#e30613;font-size:10px;    font-weight: bold;"><img  src="/img/top_menu/phone_new.png"><br><?=$this->trans->get('Изменения в режиме работы')?>!</span>
<?php }else{ ?><img alt="Наши контакты"  src="/img/top_menu/phone.png"><?php } ?>
</a>
</div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="/css/menu_category.css?v=1.2" />
<script>
var e,i=320;
e = !!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
$(document).ready(function(){
var n = window.innerWidth,
o=$(".menu-item-has-children").not(".mega-menu-col");

o.children(".sub-menu, .mega-menu").css("margin-left",""),
n>i&&o.removeClass("sub-menu-open"),
n>i&&e!==!0?(
o.on({mouseenter:function(){
$(this).addClass("sub-menu-open")}
,mouseleave:function(){
$(this).removeClass("sub-menu-open")}
})):(o.unbind("mouseenter mouseleave"),
o.children("a").unbind("click").click(function(o){
o.preventDefault(),$(this).parent().toggleClass("sub-menu-open"),n>i&&1==e&&($(this).parent().siblings().removeClass("sub-menu-open"),
$(this).parent().siblings().find(".sub-menu-open").removeClass("sub-menu-open"))})),
n>=i&&o.children(".sub-menu, .mega-menu").each(function(){
var e=$(this).offset(),
i=$(this).width()+e.left,
o=n-(i+30);
i+30>n?$(this).css("margin-left",o):$(this).css("margin-left","")})

});

</script>
<div class=" menu-2-box">
	<div class="inner-nav">
		<ul>	
<?php
$t_f = date("Y-m-d"); 
$cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => 0, 'active' => 1));
	foreach ($cats as $category) {
		$color='';
			//if($this->get->id == $category->getId()) $color='style="color:#e40413;"';
$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => $category->getId(), 'active' => 1), array('name'=>'ASC'));
	if($category->getId() == 267){
echo '<li class=" menu-item-has-children menu-item-has-mega-menu" ><a href="/category/id/" '.$color.' class="menu-item-span"><span class="menu-item-span">'.$category->getName().'</span></a>';
			}else if($category->getId() == 11){ // vse tovary
			$color = 'style="color:red;"';
			echo '<li class=" menu-item-has-children menu-item-has-mega-menu"><a href="'.$category->getPath().'" '.$color.' class="menu-item-span"><span class="menu-item-span">'.$category->getName().'</span></a>';
			}else{
			echo '<li class=" menu-item-has-children menu-item-has-mega-menu"><a href="'.$category->getPath().'" '.$color.' class="menu-item-span"><span class="menu-item-span">'.$category->getName().'</span></a>';
			}
			if ($sub_cats->count() > 1) { ?>
			<ul class="sub-menu cat_<?=$category->getId()?>">
			<?php 	foreach ($sub_cats as $sub_category) { 
				
		$sub_sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => $sub_category->getId(), 'active' => 1), array('name'=>'ASC'));
		if($sub_sub_cats->count() > 0 and $category->getId() != 85){ ?>
		 <li class="menu-item-has-children"><a href="<?=$sub_category->getPath()?>"><?=$sub_category->getName()?></a>
		 <ul class="sub-menu cat_<?=$sub_category->getId()?>">
		 <?php  foreach ($sub_sub_cats as $sub_sub_category) {
if ($sub_sub_category->getId() != 11 and $sub_sub_category->getId() != 12 and $sub_sub_category->getId() !=299) {
						$arr = $sub_sub_category->getKidsIds();
						$arr[] = $sub_sub_category->getId();
		$articles = wsActiveRecord::useStatic('Shoparticles')->count(array('category_id in('.implode(",", $arr).')',' stock > 0', 'data_new < "'.$t_f.'" '));
						if ($articles == 0) continue;
					} ?>
		 <li><a href="<?=$sub_sub_category->getPath()?>" ><?=$sub_sub_category->getName()?></a></li>
					  
										<?php }  ?>
		</ul>
		</li>
			<?php 	}else{
			if ($category->getId() != 85 and $category->getId() != 11 and $category->getId() != 12 and $category->getId() !=299) {
						$arr = $sub_category->getKidsIds();
						$arr[] = $sub_category->getId();
$articles = wsActiveRecord::useStatic('Shoparticles')->count(array('category_id in('.implode(",", $arr).')',' stock > 0', 'data_new < "'.$t_f.'" '));
						if ($articles == 0) continue;
					}
					//$color='';
					//if($this->get->id == $sub_category->getId()){ $color='style="color:#e40413;"';}
					echo ' <li><a href="' . $sub_category->getPath() . '" '.$color.'>' . $sub_category->getName().'</a></li>';
	} ?>
		
					<?php	} ?>
		</ul>
						
	<?php		}else if ($category->getId() == '106') { //show articles from last 10 days
	$sub_cats = wsActiveRecord::useStatic('Shopcategories')->findAll(array('id in(14,15,33,54,59)'));
	$t_t = date("Y-m-d", strtotime("-10 day"));?>
<div class="mega-menu cat_106">
	<ul class="sub-menu mega-menu-row">
			<?php foreach ($sub_cats as $sub_category) {
$sql = 'SELECT * from ws_articles where category_id in('.implode(",", $sub_category->getKidsIds()).') and  stock not like "0" and active = "y"  GROUP BY  `model`  order by `ws_articles`.`id` DESC  LIMIT 0, 5';
$t = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
if($t->count() == 0) continue;?>
<li class="menu-item-has-children mega-menu-col">
<a href="<?=$sub_category->getPath()?>"><?=$sub_category->getName()?></a>
<ul class="sub-menu"><?php foreach($t as $ar){?><li><a href="<?=$ar->getPath()?>"><?=$ar->getTitle()?></a></li><?php } ?></ul>
</li>
<?php } ?>
	</ul>
</div>
<?php
			}

		}

			if (count($this->cached_brands)) {
?>
<li class="menu-item-has-children menu-item-has-mega-menu"><a href="/brands/" class="menu-item-span"><span class="menu-item-span"><?=$this->trans->get('Бренды')?></span></a>
<ul class="sub-menu">
<?php
					$j = 0;
					foreach ($this->cached_brands as $brand) {
						if ((int)$brand['brand_id'] and !in_array(@$brand['brand'], array('<>', '1', 'Italia', 'Made in Germany'))) {
							$j++;
?>
<li><a href="/category/brands/<?=(int)$brand['brand_id']?>" <?php if ($j > 12){ echo 'class="brand_hide'; } ?> ><?=$brand['brand']?></a></li>
<?php if ($j == 12) { ?><li><a href="/brands/" class="brand_show_all"><b><?=$this->trans->get('Показать все');?></b></a></li><?php break;} ?>
<?php } } ?>
</ul>
<?php
	}?>
                        </ul>
                    </div>
					</div>