<span ><img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" width="32" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> <?php echo $this->cur_category ? '('.$this->cur_category->getName().')' : '';?></h1></span>
<form method="get" action="/admin/shop-articles/">
<style type="text/css">#search {border: 1px solid #EEE;} #search td {vertical-align: middle; } #search tr:nth-child(even) { background: #F8F8F8; }</style>

<table border="0" bordercolor="#ABADB3" style="background-color:#EEE; border-collapse: collapse;width:800px;" class="table"  align="center" cellpadding="3" cellspacing="0" id="search">
	<tr>
		<td colspan="4" align="center"><strong>Поиск:</strong></td>
	</tr>
	<tr>
		<td>Артикул:</td>
		<td><input type="text" class="form-control input" value="<?= @$_GET['search_artikul'] ?>" name="search_artikul"></td>
		<td>Текст:</td>
		<td><input type="text" class="form-control input" value="<?= @$_GET['search'] ?>" name="search"></td>
	</tr>
	<tr>
		<td>Бренд:</td>
		<td>
		<select name="brand" class="selectpicker show-tick form-control input" data-live-search="true" style="max-width: 180px;">
			<option value="">Виберите бренд</option>
			<?php foreach (wsActiveRecord::useStatic('Brand')->findAll() as $b) {
				if ($b->getName() != '') { ?>
			<option value="<?=$b->getName()?>" <?php if ($_GET['brand'] == $b->getName()) echo 'selected="selected"';?>><?=$b->getName()?></option>
			<?php } } ?>
		</select>
		</td>
		<td>Цвет</td>
		<td>
		<select name="color" class="selectpicker show-tick form-control input" data-live-search="true" style="max-width: 180px;">
		<option value="">Виберите цвет</option>
		<?php foreach (wsActiveRecord::useStatic('Shoparticlescolor')->findAll() as $color) { ?>
		<option value="<?=$color->getId()?>" <?php if ($_GET['color'] == $color->getId()) echo "selected"; ?>><?php echo $color->getName(); ?></option>
		<?php } ?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Дата добавления:</td>
		<td><input type="date" class="form-control input"  name="from"/></td>
		<td colspan="2"><input type="date" class="form-control input"  name="to"/></td>
	</tr>
	<tr>
		<td>Категория:</td>
		<td>
		<?php
		$mas = array();
		foreach ($this->categories as $cat) {$mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			?>
	<select name="id" id="select" class="form-control input" style="max-width: 180px;">
			<option value="">Выберите категорию</option>
			<?php
			foreach ($mas as $kay => $value) {
if(strripos($value, 'SALE') === FALSE){
			?>
			<option value="<?=$kay?>"<?php if ($this->cur_category and $kay == $this->cur_category->getId()) echo "selected";?>><?=$value?></option>
			<?php } } ?>
		</select>
		</td>
		<td colspan="2" ><input type="checkbox" name="whith_kids" id="whith_kids" value="1" <?php if ($_GET['whith_kids'] == 1) { ?>checked="checked"<?php } ?> />
		<label for="whith_kids">С подкатегорями</label></td>
	</tr>
	<tr>
	<td>Доп.параметры:</td>
		<td colspan="3">
			<p>
				<input type="checkbox" id='nughoucenyat'
					   value="1" <?php if ($_GET['ucenka'] == 1) { ?>checked="checked"<?php } ?> name="ucenka"/>
				<label for="nughoucenyat">Нужно уценять</label>
				<input type="checkbox" id='oneekzemplar'
					   value="1" <?php if ($_GET['issetone'] == 1) { ?>checked="checked"<?php } ?> name="issetone"/>
				<label for="oneekzemplar">1 в наличии</label>
				<input type="checkbox" id='ucenalos' value="1" <?php if (@$_GET['ucenalos'] == 1) { ?>checked="checked"<?php } ?>
					   name="ucenalos"/>
				<label for="ucenalos">Уценялось</label>
				<input type="checkbox" id='active'
					   value="1" <?php if (@$_GET['active'] == 1) { ?>checked="checked"<?php } ?> name="active"/>
				<label for="active">неактивные</label>
				
			</p>
		</td>

	</tr>
	<tr>
	<td>Наличие:</td>
	<td><p><input type="radio" name="nalich" value="0" <?php if (@$_GET['nalich'] == 0) { ?>checked="checked"<?php } ?>
					   id='allnalich'> <label for="allnalich">Все</label>
				<input type="radio" name="nalich" value="1" <?php if (!isset($_GET['nalich']) or @$_GET['nalich'] == 1) { ?>checked="checked"<?php } ?>
					   id='nalich'> <label for="nalich">В наличии</label>
				<input type="radio" name="nalich" value="2" <?php if (@$_GET['nalich'] == 2) { ?>checked="checked"<?php } ?>
					   id='nonalich'> <label for="nonalich">Нет в наличии</label>
					   </p>
					   </td>
					   		<td>Накладная</td>
		<td><input type="text" class="form-control input" value="<?=@$_GET['code'] ?>" name="code"></td>
	</tr>
	<tr>
		<td>Сортировать:</td>
		<td>
			<select name="sort" class="form-control input" style="max-width: 180px;">
				<option value="dateplus" <?php if (@$_GET['sort'] == 'dateplus') echo 'selected="selected"';?>>Сначала новые</option>
				<option value="dateminus" <?php if (@$_GET['sort'] == 'dateminus') echo 'selected="selected"';?> >Сначала старые</option>
				<option value="priceminus" <?php if (@$_GET['sort'] == 'priceminus') echo 'selected="selected"';?>>По цене возростание</option>
				<option value="priceplus" <?php if (@$_GET['sort'] == 'priceplus') echo 'selected="selected"';?>>По цене спадание</option>
				<option value="viewsminus" <?php if (@$_GET['sort'] == 'viewsminus') echo 'selected="selected"';?>>По просмотрам возростание</option>
				<option value="viewsplus" <?php if (@$_GET['sort'] == 'viewsplus') echo 'selected="selected"';?>>По просмотрам спадание</option>
				<option value="category" <?php if (@$_GET['sort'] == 'category') echo 'selected="selected"';?>>По категории</option>
				<option value="name" <?php if (@$_GET['sort'] == 'name') echo 'selected="selected"';?>>По названию</option>
			</select>
		</td>
		<td>Цена:</td>
		<td><input type="text" class="form-control input" value="<?= @$_GET['price'] ?>" name="price"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="padding:10px 0px;">
			<input type="submit" class="btn btn-small btn-default" name="go" value="Искать">
		</td>
		<td colspan="2" align="center" style="padding:10px 0px;">
		<input type="reset" class="btn btn-small btn-default" value="Очистить">
		</td>
	</tr>
</table>

</form>
<?php if(false){?>
<p>
    Все товары в Excel
    <?php $count = wsActiveRecord::useStatic('Shoparticles')->count();
    $parts = $count / 1000;
    for ($ip = 1; $ip <= $parts + 1; $ip++) {
        ?>
        <a href="/admin/articleexcel?part=<?php echo $ip ?>">часть <?php echo $ip?></a>,
    <?php
    }
    ?>
</p>
<?php }?>

<table align="center" style="padding-bottom: 10px;">
<tr>
<td class="td_ss">
<span class="ss">
<button class="btn btn-small btn-default" onclick="window.open('<?=$this->path;?>shop-articles/edit/id_cat/');"><i class="icon ion-compose tx-20 mr-5" aria-hidden="true"></i> Добавить товар</button>
</span>
</td>
<td  class="td_ss">
   <span class="ss">
    <select name="id" class="form-control input" id="select_dop_cat" style="max-width: 165px;">
        <option value="">Выберите категорию</option>
        <?php 
        foreach ($mas as $kay => $value) {
        ?>
        <option value="<?=$kay?>"><?=$value?></option>
		<?php } ?>
    </select> 
	<button class=" add_dop_cat btn btn-small btn-default"><i class="glyphicon glyphicon-copy" aria-hidden="true"></i> Добавить доп.кат</button>
</span>
</td>
<td  class="td_ss">
<span class="ss">
<button id='article_exel' class=" add_dop_cat btn btn-small btn-default"><i class="glyphicon glyphicon-floppy-save" aria-hidden="true"></i> Экспорт артикулов Excel</button>
</span>
</td>
<td  class="td_ss">
<span class="ss">
<input type="text" id="activ_article" name="act" class="form-control" style="width:100px;    display: inline-block;" >
<button  class="btn btn-small btn-default" onclick="Activ($('#activ_article').val());" ><i class="glyphicon glyphicon-flash" aria-hidden="true"></i></button>
</span>
</td>
<tr>
</table>
<script type="text/javascript">

function Activ(e){
var code = e;
                    if (code > 0 && code != '') {
					var url = '/admin/activearticle/';
					var new_data = '&code='+code;
					console.log(new_data);
					$.ajax({
                type: "POST",
                url: url,
				dataType: 'json',
                data: new_data,
                success: function (data) {
				console.log(data);
fopen('Товар с накладной активирован', data);
                },
				error: function(data){
				console.log(data);
				
				}
            });
			 return true;
					}
}
    $(document).ready(function () {
		 $('img.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
        $('#article_exel').click(function () {
            if ($('.article_check_box:checked').val()) {
                var id = '';
                var i = 0;
                jQuery.each($('.article_check_box:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(28);
                    } else {
                        id += $(this).attr('name').substr(28);
                    }
                    i++;
                });
                window.location = '/admin/otchets/type/5/id/' + id;

            }
        });
$('.add_dop_cat').click(function () {
                if ($('.article_check_box:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.article_check_box:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(28);
                        } else {
                            id += $(this).attr('name').substr(28);
                        }
                        i++;
                    });
                    cat = parseInt($('#select_dop_cat').val());
                    if (cat > 0) {
                        window.location.href = '/admin/adddopcat?cat=' + cat + '&ids=' + id;
                    } else {
                        alert('Выбирите категорию');
                    }
                } else {
                    alert('Выбирите товары');
                }
            });
    });
	
    function chekAll() {
	if($('.chekAll').is(":checked")){
		$('.article_check_box').prop('checked', true);
		}else{
		$('.article_check_box').prop('checked', false);
		}
        return false;
    }
</script>
<?php $count = $this->articles->count(); ?>
<form method="post" action="/admin/shop-articles/changeinfo/">
<table id="products1" cellpadding="4" cellspacing="0" class="table">
    <tr>
	<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все товары"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
        <th></th>
		<th>Покупки</th>
		<th colspan="2">Позиция</th>
        <th>История</th>
        <th>Товар</th>
        <th>Цена</th>
        <th>Просмотры</th>
        <th>Добавлен</th>
        <th>Наличие</th>
        <th>Активность</th>
        <th>Категория</th>
    </tr>
    <?php
    $row = 'row1';
    $cur = -1;
    $articles = $this->articles;

    foreach ($articles as $article) {

        $row = ($row == 'row2') ? 'row1' : 'row2';
        $cur++;
        $is_first = (0 == $cur);
        $is_last = ($count == $cur + 1);
        ?>
        <tr class="<?=$row;?>">
            <td>
                <label class="ckbox"><input type="checkbox" class="article_check_box"
                       name="articel_for_change_category_<?=$article->getId();?>"/><span></span></label>
            </td>
            <td class="kolomicon">
			<a href="<?=$article->getPath();?>" target="_blank" style="display: inline-block;">
			<i class="icon ion-monitor bleak tx-30 pd-5" alt="Просмотр" data-id="<?=$article->getId();?>" data-placement="left" title="Смотреть на сайте"  data-tooltip="tooltip" class="img_return view_article"></i>
			</a>
			<a href="<?=$this->path?>shop-articles/edit/id/<?=$article->getId()?>/"  style="display: inline-block;">
			<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="Редактировать"  data-tooltip="tooltip"></i></a></td>
            <td class="kolomicon">
                <?php if ($article->ArtycleBuyCount() == 0) { ?>
                <a href="<?=$this->path;?>shop-articles/delete/id/<?=$article->getId();?>/"  style="display: inline-block;"
                   onclick="return confirm('Удалить товар?')">
				   <i class="icon ion-close-circled red tx-30 pd-5" alt="Удалить" data-placement="left" title="Удалить"  data-tooltip="tooltip"></i>
						</a>
                <?php } else { ?>
				<i class="icon ion-android-cart green tx-30 pd-5 shoping" data-id="<?=$article->getId()?>"   data-tooltip="tooltip"  title="Товар покупался <?=$article->ArtycleBuyCount()?> раз"></i>
					<?php } ?>
            </td>
            <?php
            if ($count > 1) {
            if (!$is_first) {
            ?>
            <td class="kolomicon">
			<a href="<?php echo $this->path; ?>shop-articles/moveup/id/<?php echo $article->getId(); ?>/">
			<i class="icon ion-chevron-up green tx-30 pd-5" alt="Вверх" data-placement="bottom" title="Вверх"  data-tooltip="tooltip"></i>
			</a>
			</td>
            <?php

            } else {
            ?>
            <td class="kolomicon">&nbsp;</td>
            <?php

            }
            if (!$is_last) {
            ?>
            <td class="kolomicon">
			<a href="<?=$this->path?>shop-articles/movedown/id/<?=$article->getId()?>/">
				<i class="icon ion-chevron-down green tx-30 pd-5" alt="Вниз" data-placement="top" title="Вниз"  data-tooltip="tooltip"></i>
				</a>
				</td>
            <?php

            } else {
            ?>
            <td class="kolomicon">&nbsp;</td><?php

            }
            } else { ?>
                <td class="kolomicon">&nbsp;</td>
                <td class="kolomicon">&nbsp;</td>
            <?php } ?>
            <td  class="kolomicon" >
			<i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$article->getId()?>" data-placement="left" title="Смотреть историю"  data-tooltip="tooltip" ></i>
            </td>
            <td>
<img class="img_pre" rel="#imgiyem<?=$article->getId(); ?>"src="<?=$article->getImagePath('small_basket'); ?>" alt="<?=htmlspecialchars($article->getTitle()); ?>"/>
                <div class="simple_overlay" id="imgiyem<?=$article->getId(); ?>" style="position: fixed;top: 20%;left: 45%;z-index:100">
                    <img src="<?=$article->getImagePath('detail'); ?>" alt="<?=htmlspecialchars($article->getTitle()); ?>"/>
                </div><br>
                <?=$article->getTitle();?>
            </td>
            <td style="width: 75px;"><?php echo $article->getPrice();
			if($article->getOldPrice() > 0){ echo '<br><span style="color: #af241b;">'.$article->getOldPrice().'</span>';}?>
			<?php if($this->user->getId() == 8005 or $this->user->getId() == 1 or $this->user->getId() == 34608){
			if($article->getOldPrice() > 0){
			echo '<br><span style="color: #af241b; font-size:10px;">- '.round((1 - ($article->getPrice() /$article->getOldPrice())) * 100, 0).' %</span>';
			}
			echo '<br><span style="color: #af241b; font-size:10px;font-family: monospace;">max-'.$article->getMaxSkidka().'%</span>';
			echo '<br><span style="color: #af241b; font-size:10px;font-family: monospace;">min-'.$article->getMinPrice().'грн</span>';
			} ?>
			<!--
                <a href="#" class="real_price" onclick="return showPriceEditor(<?php //echo $article->getId(); ?>)"
                   id="link_edit_<?php //echo $article->getId(); ?>">
                    <?php //echo $article->getPrice();?>
                </a>

                <div class="for_hide_<?php //echo $article->getId(); ?>" style="display: none;">
                    Новая:<input type="text" class="art_price" id="art_price_<?php //echo $article->getId(); ?>"
                                 value="<?php //echo $article->getPrice(); ?>"/><br/>
                    Старая:<input type="text" class="art_price_old"
                                  id="art_price_old_<?php //echo $article->getId(); ?>"
                                  value="<?php //echo $article->getOldPrice(); ?>"/>
                </div>
                <input type="button" id="btn_edit_<?php //echo $article->getId(); ?>" value="Сохранить"
                       onclick="update_price(<?php //echo $article->getId(); ?>)" class="for_hide"
                       style="display: none"/>
                <br/>
                <a href="" class="priceProc_30" onclick=" return showPriceEditor(<?php //echo $article->getId(); ?>);">-30%</a>
                <a href="" class="priceProc_50" onclick=" return showPriceEditor(<?php //echo $article->getId(); ?>);">-50%</a>
                <a href="" class="priceProc_70" onclick=" return showPriceEditor(<?php //echo $article->getId(); ?>);">-70%</a>
                <a href="" class="priceProc_90" onclick=" return showPriceEditor(<?php //echo $article->getId(); ?>);">-90%</a>

-->
            </td>
            <td><?=$article->getViews();?></td>
            <td><?=strftime('%d.%m.%Y %H:%M', strtotime($article->getCtime()))?></td>
            <td><?php
                foreach ($article->sizes as $sizes) {
                    if ($sizes) {
                        echo '<p>' . @$sizes->color->getName() . '-' . @$sizes->size->getSize() . ": " . @$sizes->getCount() . '</p>';
                    }
                } ?></td>
            <td><?php if ($article->getActive() == 'n') { ?>
                <a href="javascript:void(0);" class="active" id='a_<?= $article->getId(); ?>'>
				<i class="icon ion-close-circled red tx-30 pd-5" alt="No active"></i>
							</a><?php } else { ?>
                    <a href="javascript:void(0);" class="active" id='d_<?= $article->getId(); ?>'>
					<i class="icon ion-checkmark-circled green tx-30 pd-5" alt="Active"></i>
							</a>
                <?php } ?></td>
            <td>
                <?=$mas[$article->category_id]?>
				<?php if($this->user->getId() == 8005 or $this->user->getId() == 1 or $this->user->getId() == 34608){ 
				 if($article->dop_cat_id) { echo '<br><span style="font-size:9px;color:#af241b;">'.$mas[$article->dop_cat_id].'</span>';}
				 } ?>
            </td>
        </tr>
    <?php

    }
    ?>
</table>
<?php
$limitLeft = 2;
$limitRight = 2;
$url = explode('?', $_SERVER['REQUEST_URI']);
if (count($url) == 2) {
    $ur = $url[0];
    $get = '?' . $url[1];
} else {
    $ur = $_SERVER['REQUEST_URI'];
    $get = '';
}
$pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
$paginator = '&nbsp;&nbsp;';
if ($this->page > 1) {
    $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
} else {
    $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
}
$start = 1;
$end = $this->totalPages;
if ($this->page > $limitLeft) {
    $paginator .= '...&nbsp;';
    $start = $this->page - $limitLeft;
}
if (($this->page + $limitRight) < $this->totalPages) {
    $end = $this->page + $limitRight;
}
for ($i = $start; $i <= $end; $i++) {
    if ($i == $this->page) {
        $paginator .= '<span>' . $i . '</span>';
    } else {
        $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
    }
    if ($i <= $end - 1) {
        $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
    }
}
if ($this->page == $this->totalPages) {
    $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
} else {
    $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
}
echo $paginator;
?><br/>
Всего страниц: <?=$this->totalPages?>, записей: <?=$this->count?>
<br/><br/>
Перенести в категорию:
<select name="category_id" id="category_id" class="form-control input">
    <?php foreach ($mas as $kay => $value) { ?>
    <option value="<?=$kay?>"><?=$value?></option>
	<?php } ?>
</select>
<input type="submit" value="Перенести" onclick="return confirm('Вы действительно хотите перенести выбранные товары в категорию ?')" class="btn btn-small btn-default" />
</form>
<script type="text/javascript">
$('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/articlehistory/id/'+id+'/m/1',function (data) {fopen('История изменения товара', data);});	
});
$('.shoping').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/ordersbyartycle/id/'+id+'/m/1',function (data) {fopen('История покупок товара', data);});	
});

    function delTovar(object, id) {
        var url = '/admin/shop-articles/delete/id/';
        if (confirm('Удалить товар?')) {
            $(object).parent().parent().hide();
            $.get(
                url + id,
                function (result) {
                },
                "json"
            );
        }
        return true;
    }

    function update_price(id) {
        var price = $('#art_price_' + id).val().replace(',', '.');
        var old_price = $('#art_price_old_' + id).val();
        $('#link_edit_' + id).show();
        $('#btn_edit_' + id).hide();
        $('.for_hide_' + id).hide();
        if (price != '') {
            $('#link_edit_' + id).text(price);
            $.ajax({
                type: "POST",
                url: "/admin/shop-articles/changeinfo",
                data: {update_price: 1, id: id, price: price, old_price: old_price},
                success: function (msg) {
			
                }
            });
        }
    }

    function showPriceEditor(id) {
        $('#link_edit_' + id).hide();
        $('#btn_edit_' + id).show();
        $('.for_hide_' + id).show();


        return false;
    }
    $(document).ready(function () {
        $('.priceProc_30').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.7));
        });
        $('.priceProc_50').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.5));
        });
        $('.priceProc_70').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.3));
        });
        $('.priceProc_90').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.1));
        });


        $('a.active').click(function () {
            var element = $(this);
            var id = $(this).attr('id');
            var url = '/admin/activearticle';
            $.get(
                url,
                "id=" + id,
                function (result) {
                    if (result.type == 'error') {
                        return(false);
                    }
                    else {
                        element.attr('id', result.id);
                        if (result.func == 'd') {
                            element.html('<i class="icon ion-checkmark-circled green tx-30 pd-5" alt="Active"></i>');
                        }
                        else {
                            element.html('<i class="icon ion-close-circled red tx-30 pd-5" alt="No active"></i>');
                        }

                    }
                },
                "json"
            );
        });
    });
</script>