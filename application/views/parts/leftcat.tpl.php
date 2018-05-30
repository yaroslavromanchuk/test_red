<div class="menu-left">

<?php
	if ($this->getCategory() && $this->getCategory()->getId() != 106){

	function cmp($a, $b) {
		//d($a);
		if ($a->getOrderz() ==$b->getOrderz()) {
			return 0;
		}
		return ($a->getOrderz() > $b->getOrderz()) ? -1 : 1;//DESC, for ASC <
	}

    function cmpname($a, $b) {
        //d($a);
        if ($a->getBrand() ==$b->getBrand()) {
            return 0;
        }
        return ($a->getBrand() > $b->getBrand()) ? -1 : 1;//DESC, for ASC <
    }



	$itemz = 7;
	$parent = ($this->getCategory() && $this->getCategory()->getKids()->count()) ? $this->getCategory() : null;
	if(!$parent && $this->getCategory() && $this->getCategory()->getParentId())
		$parent = $this->getCategory()->getParent();
	if($parent) { ?>
	<h1><?php echo $parent->getName();?></h1>
		<?php
		$i = 0; $count = $parent->getKids()->count();
		$arratorr = array();
		foreach($parent->getKids() as $category){
			if ($this->getSearch() &&  $this->getCategory()->getId()==$category->getId()){
				$arratorr[$i] = $category;
				$arratorr[$i]['orderz'] = 99999;
			}else{
				$arratorr[$i] = $category;
				$arratorr[$i]['orderz'] = $category->getActiveProductCount();
			}
			$i++;
		}
		usort($arratorr, "cmp");
		$i = 0;
		foreach($arratorr as $category)
		{
			if($category->getActive()==1){
				if ($i == $itemz){
					echo '<div id="kids_razvernut_for_toggle" style="display:none">';
				}
				$selected = ($this->getCategory() && $this->getCategory()->getId()==$category->getId()) ? ' class="selected"' : '';
				echo '<a href="' . $category->getPath() . '"' . $selected . '>' . $category->getName() . ' (' . $category->getActiveProductCount() . ')</a>';

				$i++;


			} else {
                $i++;
            }
            if ($i == $count && $count > $itemz){

					echo '</div>
					<div>
						<div class="razvorachivatel" id="kids_razvernut" onclick="return toggleCategory(\'kids_razvernut\')">Развернуть</div>
						<div style="clear:both"></div>
					</div>
					';
				}
		}
	}
	?>

<h1>Бренды</h1>
	<?php
	$i = 0; $count = Shoparticles::getListBrands($this->getCategory())->count();

	$arratorr = array();
	foreach(Shoparticles::getListBrands($this->getCategory(),1) as $brand){
		if ($this->getSearch() && $this->getSearch()->getBrand()==$brand->getBrand()){
			$arratorr[$i] = $brand;
			$arratorr[$i]['orderz'] = 99999;
		}else{
			$arratorr[$i] = $brand;
			$arratorr[$i]['orderz'] = $brand->getCnt();
		}
		$i++;
	}
	/*usort($arratorr, "cmpname");*/
	$i = 0;
	foreach($arratorr as $brand)
	{
		if ($i == $itemz){
			echo '<div id="brands_razvernut_for_toggle" style="display:none">';
		}
		$selected = ($this->getSearch() && $this->getSearch()->getBrand()==$brand->getBrand()) ? ' class="selected"' : '';
		echo '<a href="' . Shoparticles::getSearchPath(array('brand'=>$brand->getBrand(), 'category'=>$this->getCategory())) . '"' . $selected . '>'.
		     $brand->getBrand() . ' (' . $brand->getCnt() .')</a>';

		$i++;
		if ($i == $count && $count > $itemz){
			echo '</div>
					<div>
						<div class="razvorachivatel" id="brands_razvernut" onclick="return toggleCategory(\'brands_razvernut\')">Развернуть</div>
						<div style="clear:both"></div>
					</div>
					';
		}
	}
	?>

<h1>Цены</h1>
	<?php
	$old_price = 1;
	$i = 0; $count = Shoparticles::getListPrices($this->getCategory())->count();
	$arratorr = array();
	foreach(Shoparticles::getListPrices($this->getCategory()) as $price){
		if ($this->getSearch() && $this->getSearch()->getPrice() == $price->getRoundPrice()){
			$arratorr[$i] = $price;
			$arratorr[$i]['orderz'] = 99999;

		}else{
			$arratorr[$i] = $price;
			$arratorr[$i]['orderz'] = $price->getCnt();
		}
		$arratorr[$i]['old_price'] = $old_price;
		$arratorr[$i]['cntz'] = $price->getCnt();
		$old_price = $price->getRoundPrice()+1;

		$i++;
	}

	usort($arratorr, "cmp");
	$i = 0;
	foreach($arratorr as $price){
		if ($i == $itemz){
			echo '<div id="prices_razvernut_for_toggle" style="display:none">';
		}
		$old_price = $price->getOldPrice();
		$selected = ($this->getSearch() && $this->getSearch()->getPrice() == $price->getRoundPrice()) ? ' class="selected"' : '';
		echo '<a href="' . Shoparticles::getSearchPath(array('price'=>$price->getRoundPrice(), 'category'=>$this->getCategory())) . '"' . $selected . '>'.
		     $old_price . ' - ' . (int)$price->getRoundPrice() . ' (' . $price->getCntz() .')</a>';
		//$old_price = $price->getRoundPrice()+1;


		$i++;
		if ($i == $count && $count > $itemz){
			echo '</div>
					<div>
						<div class="razvorachivatel" id="prices_razvernut" onclick="return toggleCategory(\'prices_razvernut\')">Развернуть</div>
						<div style="clear:both"></div>
					</div>
					';
		}
	};
	?>

<h1>Цвета</h1>
	<?php
	$i = 0; $count = Shoparticles::getListColors($this->getCategory())->count();
	$arratorr = array();
	foreach(Shoparticles::getListColors($this->getCategory()) as $color){
		if ($this->getSearch() && $this->getSearch()->getColor()==$color->getColorId()){
			$arratorr[$i] = $color;
			$arratorr[$i]['orderz'] = 99999;
		}else{
			$arratorr[$i] = $color;
			$arratorr[$i]['orderz'] = $color->getCnt();
		}
		$i++;
	}
	usort($arratorr, "cmp");
	$i = 0;
	foreach($arratorr as $color)
	{
		if ($i == $itemz){
			echo '<div id="colors_razvernut_for_toggle" style="display:none">';
		}
		$selected = ($this->getSearch() && $this->getSearch()->getColor()==$color->getColorId()) ? ' class="selected"' : '';
		echo '<a href="' . Shoparticles::getSearchPath(array('color'=>$color->getColorId(), 'category'=>$this->getCategory())) . '"' . $selected . '>'.
		     $color->getColor() . ' (' . $color->getCnt() .')</a>';
		$i++;
		if ($i == $count && $count > $itemz){
			echo '</div>
					<div>
						<div class="razvorachivatel" id="colors_razvernut" onclick="return toggleCategory(\'colors_razvernut\')">Развернуть</div>
						<div style="clear:both"></div>
					</div>
					';
		}
	}
	?>

<h1>Размеры</h1>
	<?php
	$i = 0;
	$count = Shoparticles::getListSizes($this->getCategory())->count();
	$arratorr = array();
	foreach(Shoparticles::getListSizes($this->getCategory()) as $size){
		if ($this->getSearch() && $this->getSearch()->getSize()==$size->getSizeId()){
			$arratorr[$i] = $size;
			$arratorr[$i]['orderz'] = 99999;
		}else{
			$arratorr[$i] = $size;
			$arratorr[$i]['orderz'] = $size->getCnt();
		}
		$i++;
	}
	usort($arratorr, "cmp");
	$i = 0;
	foreach($arratorr as $size)
	{
		if ($i == $itemz){
			echo '<div id="sizes_razvernut_for_toggle" style="display:none">';
		}
		$selected = ($this->getSearch() && $this->getSearch()->getSize()==$size->getSizeId()) ? ' class="selected"' : '';
		echo '<a href="' . Shoparticles::getSearchPath(array('size'=>$size->getSizeId(), 'category'=>$this->getCategory())) . '"' . $selected . '>'.
		     $size->getName() . ' (' . $size->getCnt() .')</a>';
		$i++;
		if ($i == $count && $count > $itemz){
			echo '</div>
					<div>
						<div class="razvorachivatel" id="sizes_razvernut" onclick="return toggleCategory(\'sizes_razvernut\')">Развернуть</div>
						<div style="clear:both"></div>
					</div>
					';
		}
	}


}
?>
</div>
<!--<div style="text-align: center; position: relative; z-index: 20;">
<object style="position: relative; z-index: 20;" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" id="obj1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="400">
    <param name="movie"
value="/img/red_image_240x400_3.swf">
    <param name="quality" value="High">

    <embed src="/img/red_image_240x400_3.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj1" width="240" height="400"></object>
    </div>-->