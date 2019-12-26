 <a href="#"  class="rozmerSetka1" onclick="
     $('.popap_blok').css('width',$(document).width()).css('height',$(document).height()).show();
	$('.mask').css('width',$(document).width()).css('height',$(document).height()).css({'opacity':'0.7'}).show();
	$('#rozmerSetka1').css('left',($(document).width()-$('#rozmerSetka1').width())/2);
	$('#rozmerSetka1').toggle('slow', function() {});
        return false;">
     (<?=$this->trans->get('размерная сетка')?>)
 </a>
<div class="popap_blok">
	<div class="mask" onclick="$(this).hide(); $('#rozmerSetka1').toggle('slow', function() {}); $('.popap_blok').hide();"></div>
	<div class="simple_overlay" style="width: auto; z-index:99999; left: 5%;border-radius: 5px;background: #d4d4d4;" id="rozmerSetka1">
            <a class="close" onclick="$('.mask').hide(); $('#rozmerSetka1').toggle('slow', function() {});  $('.popap_blok').hide(); return false;"></a>
            <div class="quik_frame" style="padding: 10px; height: auto;">
		<div class="sizebloc">
                    <div class="size_tabs">
<?php
		if ($this->getShopItem()->category->getSizeType() > 3 and $this->getShopItem()->category->getSizeType() < 7) {
?>
			<div style="float: left; margin: 6px 6px 15px;">Женское:</div>
			<a href="#" id="tab_5" class="<?php if ($this->getShopItem()->category->getSizeType() == 5) echo 'selected'; ?>"
			onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
			>Верх</a>
			<a href="#" id="tab_6" class="<?php if ($this->getShopItem()->category->getSizeType() == 6) echo 'selected'; ?>"
			onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
			>Низ</a>
			<a href="#" id="tab_4" class="<?php if ($this->getShopItem()->category->getSizeType() == 4) echo 'selected'; ?>"
			onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
                    >Обувь</a>
		<div style="clear: both;"></div>
<?php
}elseif($this->getShopItem()->category->getSizeType() <= 3) {
?>
	<div style="float: left; margin: 6px 6px 15px;">
														Мужское:
													</div>
													<a href="#" id="tab_1"
														class="<?php if ($this->getShopItem()->category->getSizeType() == 1) echo 'selected'; ?>"
														onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
													>
														Верх
													</a>
													<a href="#" id="tab_2"
														class="<?php if ($this->getShopItem()->category->getSizeType() == 2) echo 'selected'; ?>"
														onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
													>
														Низ
													</a>
													<a href="#" id="tab_3"
														class="<?php if ($this->getShopItem()->category->getSizeType() == 3) echo 'selected'; ?>"
														onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
													>
														Обувь
													</a>
													<div style="clear: both;"></div>
<?php
												}elseif($this->getShopItem()->category->getSizeType() > 6){
												?>
												<div style="float: left; margin: 6px 6px 15px;">
														Детское:
													</div>
													<a href="#" id="tab_8"
														class="<?php if ($this->getShopItem()->category->getSizeType() == 8) echo 'selected'; ?>"
														onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
													>
														Одежда
													</a>
													<a href="#" id="tab_7"
														class="<?php if ($this->getShopItem()->category->getSizeType() == 7) echo 'selected'; ?>"
														onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false"
													>
														Обувь
													</a>
													<div style="clear: both;"></div>
												
												<?php
												}
?>
											</div>
<?php
											if ($this->getShopItem()->category->getSizeType() > 3 and $this->getShopItem()->category->getSizeType() < 7) {
?>
												<img style="max-width: 900px;"
													class="tab_4 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 4) echo 'selected'; ?>"
													src="/img/size/size4.png" alt="size"/>
												<img style="max-width: 900px;"
													class="tab_5 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 5) echo 'selected'; ?>"
													src="/img/size/size5.png" alt="size"/>
												<img style="max-width: 900px;"
													class="tab_6 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 6) echo 'selected'; ?>"
													src="/img/size/size6.png" alt="size"/>

<?php
											}
											elseif($this->getShopItem()->category->getSizeType() <=3) {
?>
												<img style="max-width: 900px;"
													class="tab_1 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 1) echo 'selected'; ?>"
													src="/img/size/size1.png" alt="size"/>
												<img style="max-width: 900px;"
													class="tab_2 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 2) echo 'selected'; ?>"
													src="/img/size/size2.png" alt="size"/>
												<img style="max-width: 900px;"
													class="tab_3 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 3) echo 'selected'; ?>"
													src="/img/size/size3.png" alt="size"/>
<?php
											}elseif($this->getShopItem()->category->getSizeType() > 6){
											?>
											<img style="max-width: 900px;     max-height: 600px;"
													class="tab_8 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 8) echo 'selected'; ?>"
													src="/img/size/baby.png" alt="size"/>
											<img style="max-width: 900px;     max-height: 600px;"
													class="tab_7 tabs_img <?php if ($this->getShopItem()->category->getSizeType() == 7) echo 'selected'; ?>"
													src="/img/size/baby_ob.png" alt="size"/>
													
													<?php
											}
?>
										</div>
									</div>
								</div>
</div>