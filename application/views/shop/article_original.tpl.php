<script type="text/javascript">
$(document).ready(function(){
	if($("#telephone").attr('value') == '') {
		$("#telephone").mskd("099 999-99-99");
	}
	<?php
		$find_sf = wsActiveRecord::useStatic('Event')->findFirst('sumforgift > 0 AND publick > 0', array('sumforgift' => 'ASC'));
		if (isset($find_sf)) {
			if (@$find_sf->getCustomersCount() > 20 ){
				/*$find_sf->setPublick(0);
				$find_sf->save();*/
			}
		}
	?>
});

function getQuikBrand(id) {
	$.post('/product/id/' + id + '/metod/getbrand/', function (data) {
		$('#quik_brand .quik_frame').html(data);
	});

	return false;
}

$(function () {
	$('a.cloud-zoom').lightBox({fixedNavigation: true});

});
function getArticle(sizeid, colorid) {
	if (sizeid > 0) {
		if (colorid > 0) {
		
			//console.log(sizeid+' '+colorid);
			
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: '/page/getarticle/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				beforeSend: function(){
					$('#article').css('opacity', '0.1');
					$('#wait_circle').show();
				},
				data: "color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>,
				success: function (result) {
					if (result.type == 'error') {
						$('#article').css('opacity', '1');
						$('#wait_circle').hide();
						$('.sarticle span').html('соответствия размер - цвет не найдено');
						$('.sarticle').show();
					}
					else {
						$('.sarticle span').html(result.code);
						$('.sarticle').show();
					}
				},
				error:function(e){
					$('.sarticle span').html('error_ajax');
					$('.sarticle').show();
				},
				complete: function(){
				}
			});
		}
	}
}

$(document).ready(function () {

	$('#size').on('change', 'input[name="size"]', function() {

		$( ".error.size" ).fadeOut();
		var size_id = $('input[name="size"]:checked').val() || 0;
		var color_id = $('input[name="color"]:checked').val() || 0;
		if (color_id > 0 && size_id > 0) {
			getArticle(size_id, color_id);
		}
		if (size_id == '0') {
			window.location.reload(true);
			return(false);
		}

		$('#color').html('загрузка...');
		$('#article').css('opacity', '0.5');
		$('#wait_circle').show();
		var url = '/page/getcolor/&'+"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>;
		$.get(
			url,
			"size_id=" + size_id + '&article_id=' + <?=$this->getShopItem()->getId()?>,
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '';
					var checked = '';
					$(result.color).each(function () {
						checked = '';
						if (color_id == $(this).attr('id')) checked = 'checked="checked"';
						options +=	'<input type="radio" value="' + $(this).attr('id') + '" name="color" id="' + $(this).attr('id') + 'color" '+ checked +'>'+
									'<label for="' + $(this).attr('id') + 'color"><i>' + $(this).attr('title') + '</i></label>';
					});
					$('#color').html(options);
					$('#article').css('opacity', '1');
					$('#wait_circle').hide();
				}
			},
			"json"
		);

		//console.log($('input[name="color"][value="'+color_id+'"]').val()+color_id);
	});

	$('#color').on('change', 'input[name="color"]', function() {

		$( ".error.color" ).fadeOut();
		var size_id = $('input[name="size"]:checked').val() || 0;
		var color_id = $('input[name="color"]:checked').val() || 0;
		$('.color_click_' + color_id).click();
		if (color_id > 0 && size_id > 0) {
			getArticle(size_id, color_id);
		}
		if (color_id == '0') {
			window.location.reload(true);
			return(false);
		}

		$('#article').css('opacity', '0.5');
		$('#size').html('загрузка...');
		$('#wait_circle').show();
		var url = '/page/getsize/&'+"color_id=" + color_id + '&article_id=' + <?php echo $this->getShopItem()->getId(); ?>;
		$.get(
			url,
			"color_id=" + color_id + '&article_id=' + <?php echo $this->getShopItem()->getId(); ?>,
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '';
					var checked = '';
					$(result.size).each(function () {
						checked = '';
						if (size_id == $(this).attr('id')) checked = 'checked="checked"';
						options +=	'<input type="radio" value="' + $(this).attr('id') + '" name="size" id="' + $(this).attr('id') + 'size" '+ checked +'>'+
									'<label for="' + $(this).attr('id') + 'size"><i>' + $(this).attr('title') + '</i></label>';
					});
					$('#size').html(options);
					$('#article').css('opacity', '1');
					$('#wait_circle').hide();
				}
			},
			"json"
		);

	});
});

</script>

<div class="simple_overlay" id="quik">
    <a class="close"></a>
    <div id='quik_frame' style="text-align: center;"></div>
</div>

<div class="simple_overlay" id="quik_brand">
	<a class="close"></a>
	<div class='quik_frame' style="height: auto;width: 534px;"></div>
</div>

<div class="simple_overlay" id="quik_order" style="display:none;border-radius: 5px;border-color: #DB5705;">
	<a class="close"></a>
	<div class='quik_frame'>
	<form id="qo" action="" method="">
		<input type="hidden" name="id" id="quik_order-id" value="<?php echo $this->getShopItem()->getId(); ?>" />
		<div id="qo-first_step">
		<table class="quik_order_table" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td colspan="2" align="center"><h1>Быстрый заказ</h1></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><span class="red">*</span> - Поля, обязательные для заполнения</td>
			</tr>
			<tr>
				<td><label for="quik_order-name" class="label_fill">Имя, Фамилия <span class="red">*</span> </label></td>
				<td><input name="name" id="quik_order-name" required
				value="<?php if (isset($this->basket_contacts['name']) and $this->basket_contacts['name']) echo htmlspecialchars($this->basket_contacts['name']);
				elseif ($this->ws->getCustomer()->getIsLoggedIn()) echo $this->ws->getCustomer()->getFirstName();
				?>" /></td>
			</tr>
			<tr>
				<td><label for="quik_order-email" class="label_fill">Email <span class="red">*</span> </label></td>
				<td>
				<?php if (!$this->ws->getCustomer()->getIsLoggedIn()) { ?>
					<input type="email" name="email" id="quik_order-email" placeholder="sample@domen.com" required
					value="<?php if (isset($this->basket_contacts['email']) and $this->basket_contacts['email']) echo htmlspecialchars($this->basket_contacts['email']);
					elseif ($this->ws->getCustomer()->getIsLoggedIn()) echo $this->ws->getCustomer()->getEmail();
					?>" />
				<?php
				} else {
					echo $this->ws->getCustomer()->getEmail();
					echo '<input type="hidden" name="email" id="quik_order-email" value="' . $this->ws->getCustomer()->getEmail() . '"/> ';
				} ?>
				</td>
			</tr>
			<tr>
				<td><label for="telephone" class="label_fill">Телефон <span class="red">*</span> </label></td>
				<td><label for="telephone">+38</label><input type="tel" name="telephone" id="telephone" placeholder="012 345-67-89" maxlength="16" size="14" required
				value="<?php if (isset($this->basket_contacts['telephone']) and $this->basket_contacts['telephone']) echo htmlspecialchars($this->basket_contacts['telephone']);
				elseif ($this->ws->getCustomer()->getIsLoggedIn()) echo $this->ws->getCustomer()->getPhone1();
				?>" /></td>
			</tr>
			<tr>
				<td><label for="quik_order-comment" class="label_fill">Комментарий к заказу </label></td>
				<td><textarea name="comment" id="quik_order-comment" rows="5" cols="16"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Заказать" style="padding: 0px 25px; margin-top: 10px; height: 28px; cursor: pointer;" /></td>
			</tr>
		</table>
		</div>
		<div id="qo-load" style="display:none;">
			<table>
				<tr>
					<td colspan="2" align="center" valign="middle" height="200" width="300"><img src="/images/loader_big.gif" alt="Идёт загрузка..." /></td>
				</tr>
			</table>
		</div>
		<div id="qo-result" style="display:none;"></div>
	</form>
	</div>
</div>


<?php
$label = false;
if (wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $this->getShopItem()->getLabelId()))) {
    $label = wsActiveRecord::useStatic('Shoparticleslabel')->findFirst(array('id' => $this->getShopItem()->getLabelId()))->getImage();
}
/*if (wsActiveRecord::useStatic('Shoparticlestop')->count(array('article_id'=>$this->getShopItem()->getId()))){
        $label = wsActiveRecord::useStatic('Shoparticleslabel')->findFirst()->getImage();
    }*/
//	$similar = Shoparticles::getSimilar($this->getShopItem()->getId());


?>
<div class="article-detail-box">
<div class="photos">
    <?php if ($label) { ?>
        <div class="article_label_container_2">
            <div class="article_label_2">
                <img src="<?php echo $label ?>" alt=""/>
            </div>
        </div>
    <?php } ?>
    <div style="display:none" id="cloud_big_src"><?php echo $this->getShopItem()->getImagePath();?></div>
    <a onclick="$('a.cloud-zoom').css('z-index',1);"
       href='<?php echo $this->getShopItem()->getImagePath('small_preview'); ?>' class='cloud-zoom' id='zoom1'
       rel="adjustX: 10, adjustY:-4">
        <img class="photo-big" rel='.photo-big' src="<?php echo $this->getShopItem()->getImagePath('detail'); ?>"
             alt='<?php echo htmlspecialchars($this->getShopItem()->getTitle()); ?>'
             title="<?php echo htmlspecialchars($this->getShopItem()->getTitle()); ?>"/>

    </a>

    <?php
    if (count($this->getShopItem()->getImages()) > 0) {
        ?>
        <a href='<?php echo $this->getShopItem()->getImagePath(); ?>' class='cloud-zoom-gallery'
           title='<?php echo htmlspecialchars($this->getShopItem()->getTitle()); ?>'
           rel="useZoom: 'zoom1', smallImage: '<?php echo $this->getShopItem()->getImagePath('detail'); ?>' ">
            <img src="<?php echo $this->getShopItem()->getImagePath('detail'); ?>"
                 alt="<?php echo htmlspecialchars($this->getShopItem()->getTitle()); ?>" class="photo-small"
                 onmouseover="$(this).parent().click()"/></a>
        <?php foreach ($this->getShopItem()->getImages() as $image) { ?>
            <a href='<?= $image->getImagePath(); ?>'
               class='cloud-zoom-gallery <?php if ((int)$image->getColorId() > 0) { ?>color_click_<?php echo $image->getColorId() ?><?php } ?>'
               title='<?= $image->getTitle(); ?>'
               rel="useZoom: 'zoom1', smallImage: '<?= $image->getImagePath('detail'); ?>' ">
                <div class="for_cloud_big_src"
                     style="display:none"><?php echo $image->getImagePath('small_preview');?></div>
                <img src="<?= $image->getImagePath('detail'); ?>" alt="<?= $image->getTitle(); ?>" class="photo-small"
                     onmouseover="$(this).parent().click()"/>

            </a>

        <?php } ?>
    <?php } ?>

</div>
<div class="texts">
<div class="shop_brand">
    <?php
    if (@$this->getShopItem()->article_brand->getText()) {
        ?>
        <p><a href="#" rel="#quik_brand" onclick="getQuikBrand(<?php echo $this->getShopItem()->getId() ?>)"><?php echo $this->getShopItem()->getBrand();?></a>
        </p>
    <?php } else { ?>
        <p><?php echo $this->getShopItem()->getBrand();?></p>
    <?php } ?>
</div>
<h1><?php echo $this->getShopItem()->getModel();?></h1>


<?php if (strlen($this->getShopItem()->getCode()) > 0) { ?>
    <!--<p>Артикул: <?php /*echo $this->getShopItem()->getCode();*/?></p>-->
<?php } ?>
<div class="line-1"></div>
<form action="<?php echo $this->getShopItem()->getPath(); ?>" method="post" id="article">

<?php
$count = wsActiveRecord::useStatic('Shoparticlessize')->findAll(array('id_article' => $this->getShopItem()->getId()));
$ms = 0;
foreach ($count as $count) {
    $ms = $ms + $count->getCount();
}
if ($ms > 0) {
    ?>






    <!--<p>Выбор размер</p>-->

    <div class="buy-form">
    <?php
    // var_dump(d($this->getShopItem()->getSizes()));
    ?>
    <div style="min-height: 48px;">
	<p>Размер:  <a href="#" rel='#rozmerSetka1' class="rozmerSetka1"
	onclick="$('.popap_blok').css('width',$(document).width()).css('height',$(document).height()).show();
	$('.mask').css('width',$(document).width()).css('height',$(document).height()).show();
	$('#rozmerSetka1').css('left',($(document).width()-$('#rozmerSetka1').width())/2);
	$('#rozmerSetka1').toggle('slow', function() {}); return false;">(размерная сетка)</a></p>

	<span id="size">
    <!-- <select class="size" style="width: 232px;" name="size" id="size"> -->
        <!-- <option value="0">Выбeрите размер</option> -->
        <?php
        $one_z = 0;
        $one_c = 0;
        $mas = array();
        foreach ($this->getShopItem()->getSizes() as $size) {
            if ($size->getCount() > 0)
                $mas [$size->getSize()->getId()] = $size->getSize()->getSize();
        }
        foreach (array_unique($mas) as $kay => $value) {
            if (count($mas) == 1) {
                $one_z = $kay;
                echo '<input type="radio" value="' . $kay . '" name="size" id="'. $kay . 'size" checked="checked">';
				echo '<label for="'. $kay . 'size">' . $value . '</label>';
                //echo '<option value="' . $kay . '" selected="selected">' . $value . '</option>';
            } else {
				echo '<input type="radio" value="' . $kay . '" name="size" id="'. $kay . 'size">';
				echo '<label for="'. $kay . 'size">' . $value . '</label>';
                //echo '<option value="' . $kay . '">' . $value . '</option>';
            }
        } ?>
    <!-- </select> -->
	</span>
	<span class="error size"><i class="arrow-left"></i><h2>Выберите размер</h2></span>

    <?php
    if ($this->getShopItem()->getSizeType() and $this->getShopItem()->getSizeType() != 100) {
        ?>


        <div class="popap_blok">
            <div class="mask"
                 onclick="$(this).hide(); $('#rozmerSetka1').toggle('slow', function() {}); $('.popap_blok').hide();"></div>
            <div class="simple_overlay" style="width: auto; z-index:99999" id="rozmerSetka1">
                <a class="close"
                   onclick="$('.mask').hide(); $('#rozmerSetka1').toggle('slow', function() {});  $('.popap_blok').hide(); return false;"></a>

                <div class="quik_frame" style="auto; padding: 15px; height: auto;">
                    <div class="sizebloc">
                        <div class="size_tabs">
                            <?php
                            if ($this->getShopItem()->getSizeType() > 3) {
                                ?>
                                <div style="float: left; margin: 6px 6px 15px;">
                                    Женское:
                                </div>
                                <a href="#" id="tab_5"
                                   class="<?php if ($this->getShopItem()->getSizeType() == 5) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Верх</a>
                                <a href="#" id="tab_6"
                                   class="<?php if ($this->getShopItem()->getSizeType() == 6) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Низ</a>
                                <a href="#" id="tab_4"
                                   class="<?php if ($this->getShopItem()->getSizeType() == 4) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Обувь</a>
                                <div style="clear: both;"></div>
                            <?php } else { ?>
                                <div style="float: left; margin: 6px 6px 15px;">
                                    Мужское:
                                </div>
                                <a href="#" id="tab_1"
                                   class="<?php if ($this->getShopItem()->category->getSizeType() == 1) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Верх</a>
                                <a href="#" id="tab_2"
                                   class="<?php if ($this->getShopItem()->category->getSizeType() == 2) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Низ</a>
                                <a href="#" id="tab_3"
                                   class="<?php if ($this->getShopItem()->category->getSizeType() == 3) echo 'selected' ?>"
                                   onclick="$('.tabs_img').hide(); $('.'+$(this).attr('id')).show(); $('.size_tabs a').removeClass('selected'); $(this).addClass('selected'); return false">Обувь</a>

                                <div style="clear: both;"></div>
                            <?php } ?>
                        </div>
                        <?php
                        if ($this->getShopItem()->getSizeType() > 3) {
                            ?>
                            <img style="max-width: 900px;"
                                 class="tab_4 tabs_img <?php if ($this->getShopItem()->getSizeType() == 4) echo 'selected' ?>"
                                 src="/img/size4.jpg" alt="size"/>
                            <img style="max-width: 900px;"
                                 class="tab_5 tabs_img <?php if ($this->getShopItem()->getSizeType() == 5) echo 'selected' ?>"
                                 src="/img/size5.jpg" alt="size"/>
                            <img style="max-width: 900px;"
                                 class="tab_6 tabs_img <?php if ($this->getShopItem()->getSizeType() == 6) echo 'selected' ?>"
                                 src="/img/size6.jpg" alt="size"/>

                        <?php } else { ?>
                            <img style="max-width: 900px;"
                                 class="tab_1 tabs_img <?php if ($this->getShopItem()->getSizeType() == 1) echo 'selected' ?>"
                                 src="/img/size1.jpg" alt="size"/>
                            <img style="max-width: 900px;"
                                 class="tab_2 tabs_img <?php if ($this->getShopItem()->getSizeType() == 2) echo 'selected' ?>"
                                 src="/img/size2.jpg" alt="size"/>
                            <img style="max-width: 900px;"
                                 class="tab_3 tabs_img <?php if ($this->getShopItem()->getSizeType() == 3) echo 'selected' ?>"
                                 src="/img/size3.jpg" alt="size"/>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

	
	<div class="clear"></div>
	</div>

	<div>
    <p>Цвет:</p>
	
	<span id="color">
    <!-- <select class="color" style="width: 232px;" name="color" id="color"> -->
        <!-- <option value="0">Выберите цвет</option> -->
        <?php $mass = array();
        foreach ($this->getShopItem()->getSizes() as $color) {
            if ($color->getCount() > 0)
                $mass [$color->getColor()->getId()] = $color->getColor()->getName();
        }
        foreach (array_unique($mass) as $kay => $value) {
            if (count($mass) == 1) {
                $one_z = $kay;
                echo '<input type="radio" value="' . $kay . '" name="color" id="'. $kay . 'color" checked="checked">';
				echo '<label for="'. $kay . 'color"><i>' . $value . '</i></label>';
                //echo '<option value="' . $kay . '" selected="selected">' . $value . '</option>';
            } else {
				echo '<input type="radio" value="' . $kay . '" name="color" id="'. $kay . 'color">';
				echo '<label for="'. $kay . 'color"><i>' . $value . '</i></label>';
                //echo '<option value="' . $kay . '">' . $value . '</option>';
            }
        } ?>
    <!-- </select> -->
	</span>

	<span class="error color"><i class="arrow-left"></i><h2>Выберите цвет</h2></span>

<?php if(!$one_c or !$one_z ){?>
    <p class="sarticle" style="display: none; margin-top: 10px; ">Артикул: <span></span></p>
<?php } else {?>
        <p class="sarticle" style="margin-top: 10px; ">Артикул: <span><?php echo wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article'=>$this->getShopItem()->getId(),'id_size'=>$one_z,'id_color'=>$one_c))->getCode();?></span></p>
    <?php } ?>

    </div>
    <?php if (false) { ?>
        <div class="to_basket" style="float: right; margin-top: 0;">


            <div class="soc-center">


                <div id="vk_like"></div>
                <br/>
                <!--Facebook-->
                <!-- <div id="fb-root"></div>-->


                <iframe
                    src="http://www.facebook.com/plugins/like.php?href=http://<?php echo $_SERVER['HTTP_HOST'] . '/product/id/' . $this->getShopItem()->getId() ?>&layout=button_count&show_faces=true&width=450&action=like&colorscheme=light&height=35" scrolling="no" frameborder="0"
                    style="border:none; overflow:hidden; width:450px; height:35px;"
                    allowTransparency="true"></iframe>
                <!--Facebook-->
                <br/>
                Подписаться: <a href="http://vkontakte.ru/club21090760" target="_blank"><img height="15" src="/img/vkontakte.png" alt="vkontakte"/></a>
                <a href="http://www.facebook.com/pages/RED-Ukraine/148503625241218?sk=wall" target="_blank"><img
                        height="15" src="/img/Facebook.png" alt="facebook"/></a>
                <a href="http://twitter.com/#!/red_ukraine" target="_blank"><img height="15" src="/img/twitter.png" alt="twitter"/></a>



            </div>

        </div>
    <?php } ?>
    <div class="clear"></div>
    </div>
<?php
} else {
    ?>
    <div class="tnx">
        ТОВАРА НЕТ НА СКЛАДЕ.
    </div>

    <?php if (false) { ?>
        <div class="to_basket">
            <div class="soc-center">

                <br/><br/>
                <!--  <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="vkontakte,facebook"></div>-->
                <!-- Put this script tag to the <head> of your page -->
                <!--VK-->


                <!-- Put this div tag to the place, where the Like block will be -->
                <div id="vk_like"></div>

                <!--VK-->
                <br/>
                <!--Facebook-->
                <iframe
                    src="http://www.facebook.com/plugins/like.php?href=http://<?php echo $_SERVER['HTTP_HOST'] . '/product/id/' . $this->getShopItem()->getId() ?>&layout=button_count&show_faces
=true&width=450&action=like&colorscheme=light&height=35" scrolling="no" frameborder="0"
                    style="border:none; overflow:hidden; width:450px; height:35px;"
                    allowTransparency="true"></iframe>
                <!--Facebook-->
                <br/>
                Подписаться: <a href="http://vkontakte.ru/club21090760" target="_blank"><img height="15" src="/img/vkontakte.png" alt="vkontakte"/></a>
                <a href="http://www.facebook.com/pages/RED-Ukraine/148503625241218?sk=wall" target="_blank"><img height="15" src="/img/Facebook.png" alt="facebook"/></a>
                <a href="http://twitter.com/#!/red_ukraine" target="_blank"><img height="15" src="/img/twitter.png" alt="twitter"/></a>

            </div>
        </div>
    <?php } ?>
<?php } ?>

<?php
if (isset($this->error)) {
    echo "<div>";
    foreach ($this->error as $error) {
        ?>
        <h2 style="color: red;"><?=$error?></h2>
    <?php

    }
    echo "</div>";
}
?>


<div class="line-1"></div>

<span class="price">Цена <?php $pric = Number::formatFloat($this->getShopItem()->getPriceSkidka(), 2); $pric = explode(',', $pric); echo $pric[0];?>
	<span style="font-size:11px;vertical-align: text-bottom;margin-left: -4px;"><?php echo (int)$pric[1] ? ','.$pric[1] : ''; //копейки ?></span> грн
</span>

<?php if ($this->getShopItem()->getDiscount()) { ?>
<span class="old-price">(<?php $pric_sk = Number::formatFloat($this->getShopItem()->getOldPrice(), 2); $pric_sk = explode(',', $pric_sk);
	echo $pric_sk[0];
	echo (int)$pric_sk[1] ? ','.$pric_sk[1] : '';?> грн)
</span>
<?php } ?>

<?php
if ($this->get->metod != 'frame') {?>
	<span onclick="getQuickCart('<?php echo $this->getShopItem()->getPath(); ?>'); return false;" rel="#quik" class="submit article_submit_new" style="float:right;"><i class="icon-shopping-cart icon-white"></i> В КОРЗИНУ</span>
	<!--  style="float:right; margin:10px; padding: 5px; border: 1px dashed; border-radius: 5px; clear: both;" -->
	<?php if(true /*LOCAL*/){?>
	<span onclick="getQuickOrder(); return false;" rel="#quik_order" class="submit article_submit_new tooltip" id="quick_order" style="float:right; margin-right: 10px;" original-title="Вам потребуется ввести только имя, телефон и email. Всю дополнительную информацию узнает менеджер по телефону"><i class="icon-briefcase icon-white"></i> Быстрый заказ</span>
	<?php } ?>
<?php
} else {?>
	<input type="submit" onclick="submitCartValidator(); return false;" class="submit article_submit" value="В КОРЗИНУ" style="float: right; margin-top: 6px;"/>
<?php } ?>
<img id="wait_circle" src="/images/loader.gif" alt="Идёт загрузка..." style="display: none; float: right; padding: 8px;" >
<div class="clear"></div>
<div class="line-1"></div>


<script type="text/javascript" src="//vk.com/js/api/openapi.js?87"></script>

<script type="text/javascript">
    VK.init({apiId: 2633530, onlyWidgets: true});
</script>

<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


<script type="text/javascript">
    $(document).ready(function () {
        /*VK.Widgets.Like("vk_like", { pageUrl: "http://
        <?php echo $_SERVER['HTTP_HOST'] . '/product/id/' . $this->getShopItem()->getId()?>"});*/

    });
</script>

<?php if (!LOCAL) { ?>
<div class="likes">
    <ul>
        <li style="width: 85px;">
            <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
            <g:plusone></g:plusone>
        </li>
        <li style="width: 90px;">
            <div id="vk_like"></div>
            <script type="text/javascript">
                VK.Widgets.Like("vk_like", {type: "mini"});
            </script>
        </li>
        <li style="width: 115px;">
            <div class="fb-like"
                 data-href="http://<?php echo $_SERVER['HTTP_HOST'] . '/product/id/' . $this->getShopItem()->getId() ?>"
                 data-send="false" data-layout="button_count" data-width="100" data-show-faces="true"
                 data-font="arial"></div>

        </li>
        <li style="width: 90px;">
            <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
            <script>!function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, "script", "twitter-wjs");</script>

        </li>
        <li>

            <div id="ok_shareWidget"></div>
            <script>
            !function (d, id, did, st) {
              var js = d.createElement("script");
              js.src = "http://connect.ok.ru/connect.js";
              js.onload = js.onreadystatechange = function () {
              if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                if (!this.executed) {
                  this.executed = true;
                  setTimeout(function () {
                    OK.CONNECT.insertShareWidget(id,did,st);
                  }, 0);
                }
              }};
              d.documentElement.appendChild(js);
            }(document,"ok_shareWidget","http://<?php echo $_SERVER['HTTP_HOST'] . '/product/id/' . $this->getShopItem()->getId() ?>","{width:145,height:25,st:'rounded',sz:12,ck:3}");
            </script>

        </li>
    </ul>
    <div class="clear"></div>
</div>
<?php } ?>
</form>


<div class="line-1"></div>

<div class="description">
	<?php echo $this->getShopItem()->getLongText(); ?>
</div>

<div class="line-1"></div>

<?php
if ($this->getShopItem()->getSootRozmer()) {
    ?>


    <p class="strong">Соответствие размеров:</p>
    <?php echo $this->getShopItem()->getSootRozmer() ?>
    <div class="line-1"></div>

<?php } ?>
<?php
if ($this->getShopItem()->getSostav()) {
    ?>


    <p class="strong">Состав:</p>
    <?php echo $this->getShopItem()->getSostav() ?>
    <div class="line-1"></div>

<?php } ?>




<?php
if (isset($this->ok)) {
    ?>
    <div class="tnx">
        СПАСИБО!<br/>
        ВАШ ТОВАР ДОБАВЛЕН В КОРЗИНУ.
    </div>
<?php } ?>
</div>
<div class="clear"></div>
</div>

<?php
/*
	if ($similar and $similar->count() > 0) {
		echo '<h2>С этим товаром также продаются</h2><div class="articles-row" style="background: none;">';
        $i = 0;
        foreach ($similar as $article) {
            $i++;
            if ($this->get->metod and $this->get->metod == 'frame') {
                if ($i > 4) break;
            }
            if ($i > 5) break;
*/
/*
            <div class="article-item">
                <a href="<?php echo $article->getPath(); ?>" id="red_item_<?php echo $article->getId() ?>"
                   class="img for_track"><img
                        src="<?php echo $article->getImagePath('listing'); ?>"
                        alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/></a>

                <p class="brand"><?php echo $article->getBrand();?>&nbsp;</p>

                <p class="name"><?php echo $article->getModel();?></p>

                <p class="price"><?php echo $article->showPrice($article->getPriceSkidka());?>грн</p>
*/
/*
			if ($article->getDiscount()) {
				echo '<p class="price-old" style="text-decoration: line-through;">'.$article->showPrice($article->getOldPrice()).'грн</p>';
			}
			echo '</div>';
        }
		echo '</div>';
	}
*/
?>

<?php

if ($this->getShopItem()->article_brand->getText() and false) {
    ?>
    <div class="brand_info">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <?php if ($this->getShopItem()->article_brand->getImage()) { ?>
                    <img src="<?php echo $this->getShopItem()->article_brand->getImage() ?>"
                         alt="<?php echo $this->getShopItem()->article_brand->getName() ?>"></td>
                <?php } ?>
                <td>
                    <p class="strong"><?php echo $this->getShopItem()->article_brand->getName()?></p>
                    <?php echo $this->getShopItem()->article_brand->getText()?>
                </td>
            </tr>
        </table>
    </div>
<?php } ?>


<?php
/*
if (!$this->ws->getCustomer()->isAdmin()) {
    <script type="text/javascript">
        var _vc = _vc || {};
        _vc['_sid'] = '<?php echo session_id()?>';
        _vc['_uid'] = '<?php echo (int)$this->ws->getCustomer()->getId()?>';
        _vc['_oid'] = '<?php echo $this->getShopItem()->getId()?>';
        _vc['_title'] = '<?php echo urlencode(str_replace(array("'", '"'), '', $this->getShopItem()->getModel() . $this->getShopItem()->getBrand()))?>';
        _vc['track_class'] = 'for_track';
        _vc['_dsid'] = 'red_views';
    </script>
    <script type="text/javascript" src="http://shop.webunion.kiev.ua/js/vcounter.js"></script>
}
*/
?>