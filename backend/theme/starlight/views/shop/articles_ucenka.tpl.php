<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle(); echo $this->cur_category ? '( '.$this->cur_category->getName().' )' : '';?></h3></div>
<div class="panel-body">
<form method="get" action="/admin/ucenka/">
    <p>Поиск:</p>
    <p>
        Дата: <input type="date" class="form-control input"
					value="<?php if (isset($_GET['from'])) echo @$_GET['from']; else echo date('Y-m-d'); ?>"
					name="from"/>
		<br/>
        <input type="radio" name="proc" class="" value="1" <?php if (@$_GET['proc'] == 1 or !isset($_GET['proc'])) { ?>checked="checked" <?php } ?> /> Все
		<!--<input type="radio" name="proc" value="5" <?php if (@$_GET['proc'] == 5) { ?>checked="checked" <?php } ?>/> 5%
		<input type="radio" name="proc" value="10" <?php if (@$_GET['proc'] == 10) { ?>checked="checked" <?php } ?>/> 10%
		<input type="radio" name="proc" value="15" <?php if (@$_GET['proc'] == 15) { ?>checked="checked" <?php } ?>/> 15%-->
		<input type="radio" name="proc" value="20" <?php if (@$_GET['proc'] == 20) { ?>checked="checked" <?php } ?>/> 20%
		<!--<input type="radio" name="proc" value="25" <?php if (@$_GET['proc'] == 25) { ?>checked="checked" <?php } ?>/> 25%-->
        <input type="radio" name="proc" value="30" <?php if (@$_GET['proc'] == 30) { ?>checked="checked" <?php } ?>/> 30%
		<!--<input type="radio" name="proc" value="35" <?php if (@$_GET['proc'] == 35) { ?>checked="checked" <?php } ?>/> 35%-->
		<input type="radio" name="proc" value="40" <?php if (@$_GET['proc'] == 40) { ?>checked="checked" <?php } ?>/> 40%
		<!--<input type="radio" name="proc" value="45" <?php if (@$_GET['proc'] == 45) { ?>checked="checked" <?php } ?>/> 45%-->
        <input type="radio" name="proc" value="50" <?php if (@$_GET['proc'] == 50) { ?>checked="checked" <?php } ?>/> 50%
        <input type="radio" name="proc" value="60" <?php if (@$_GET['proc'] == 60) { ?>checked="checked" <?php } ?>/> 60%
       <!-- <input type="radio" name="proc" value="90" <?php if (@$_GET['proc'] == 90) { ?>checked="checked" <?php } ?>/> 90%-->
        <br/>
        Текст: <input type="text" value="<?= @$_GET['search'] ?>" name="search" class="form-control input" /><br/>
     <!--   Бренд: <select name="brand" class="form-control input">
					<option value="">Виберите бренд</option>
<?php
			/*foreach (wsActiveRecord::useStatic('Brand')->findAll() as $b) {
                if ($b->getName() != '') {
?>
                    <option value="<?php echo $b->getName() ?>" <?php if (@$_GET['brand'] == $b->getName()) echo 'selected="selected"';?>>
						<?php echo $b->getName()?>
					</option>
<?php
                }
            }*/
?>
        </select><br/>-->
		
<?php
		$mas = array();
		foreach ($this->categories as $cat) {$mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			?>
			
        Категория: <select name="id" id="select" class="form-control input">
            <option value="">Выберите категорию</option>
						<?php
			foreach ($mas as $kay => $value) {
if(strripos($value, 'SALE') === FALSE and strripos($value, 'NEW') === FALSE){
			?>
			<option value="<?=$kay?>"<?php if ($this->cur_category and $kay == $this->cur_category->getId()) echo "selected";?>><?=$value?></option>
			<?php } } ?>
        </select>
    </p>
    <p>
        <input type="checkbox" name="whith_kids" id="whith_kids" value="1"
<?php if (@$_GET['whith_kids'] == 1) { ?> checked="checked"<?php }?>/>
		<label for="whith_kids">С подкатегорями</label>
    </p>


    <p>Сортировать
        <select name="sort" class="form-control input">
            <option value="dateplus" <?php if (@$_GET['sort'] == 'dateplus') echo 'selected="selected"';?>>По дате
                спадание
            </option>
            <option value="dateminus" selected <?php if (@$_GET['sort'] == 'dateminus') echo 'selected="selected"';?> >По дате
                возростание
            </option>
            <option value="priceminus" <?php if (@$_GET['sort'] == 'priceminus') echo 'selected="selected"';?>>По цене
                возростание
            </option>
            <option value="priceplus" <?php if (@$_GET['sort'] == 'priceplus') echo 'selected="selected"';?>>По цене
                спадание
            </option>
            <option value="viewsminus" <?php if (@$_GET['sort'] == 'viewsminus') echo 'selected="selected"';?>>По
                просмотрам
                возростание
            </option>
            <option value="viewsplus" <?php if ($_GET['sort'] == 'viewsplus') echo 'selected="selected"';?>>По
                просмотрам
                спадание
            </option>
            <option value="category" <?php if ($_GET['sort'] == 'category') echo 'selected="selected"';?>>По
                категории
            </option>
            <option value="brandaz" <?php if ($_GET['sort'] == 'brandaz') echo 'selected="selected"';?>>По
                бренду A-Z
            </option>
            <option value="brandza" <?php if ($_GET['sort'] == 'brandza') echo 'selected="selected"';?>>По
                бренду Z-A
            </option>
            <option value="ucenkazr" <?php if ($_GET['sort'] == 'ucenkazr') echo 'selected="selected"';?>>Уценка возростание</option>
            <option value="ucenkasp" <?php if ($_GET['sort'] == 'ucenkasp') echo 'selected="selected"';?>>Уценка спадание</option>
        </select>
    </p>
	<p>
	<input type="checkbox" name="block_ucenka" id="block_ucenka" value="1"
<?php if (@$_GET['block_ucenka'] == 1) { ?> checked="checked"<?php }?>/>
		<label for="block_ucenka">Выбрать товар который попал в блок уценки</label>
	</p>
    <input type="submit" class="btn btn-small btn-default"  value="Искать">
    <input type="button"  class="btn btn-small btn-default" value="Очистить" onclick="location.replace('/admin/ucenka/')"><br/>
    <br/>
</form>
  </div>
  </div>

</div>

<script>
    $(document).ready(function () {
      		 $('img.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
    });
</script>


<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title"> Уценить отмеченный товар</h3></div>
<div class="panel-body">
<p style="width:200px;">
<label class="ckbox" data-tooltip="tooltip" title="Блокировать от дополнительных скидок"><input type="checkbox" name="skidka_block" id="skidka_block" value="1"><span>Блокировать от скидок</span></label></p>
<p style="width:200px;">
<label class="ckbox" data-tooltip="tooltip" title="Снять блокировку от уценки"><input type="checkbox" name="block_ucenka_end" id="block_ucenka_end" value="1"><span>Снять блокировку от уценки</span></label></p>

<p>
    <select name="ucenka_to_otm" id='ucenka_to_otm' class="form-control input">
        <option value="0">Не уценять</option>
			<!--<option value="5" <?php if (@$_GET['proc'] == 5) { ?>selected="selected" <?php } ?>>5%</option>
			<option value="10" <?php if (@$_GET['proc'] == 10) { ?>selected="selected" <?php } ?>>10%</option>
			<option value="15" <?php if (@$_GET['proc'] == 15) { ?>selected="selected" <?php } ?>>15%</option>-->
			<option value="20" <?php if (@$_GET['proc'] == 20) { ?>selected="selected" <?php } ?>>20%</option>
			<!--<option value="25" <?php if (@$_GET['proc'] == 25) { ?>selected="selected" <?php } ?>>25%</option>-->
            <option value="30" <?php if (@$_GET['proc'] == 30) { ?>selected="selected" <?php } ?>>30%</option>
			<!--<option value="35" <?php if (@$_GET['proc'] == 35) { ?>selected="selected" <?php } ?>>35%</option>-->
			<option value="40" <?php if (@$_GET['proc'] == 40) { ?>selected="selected" <?php } ?>>40%</option>
			<!--<option value="45" <?php if (@$_GET['proc'] == 45) { ?>selected="selected" <?php } ?>>45%</option>-->
            <option value="50" <?php if (@$_GET['proc'] == 50) { ?>selected="selected" <?php } ?>>50%</option>
            <option value="60" <?php if (@$_GET['proc'] == 60) { ?>selected="selected" <?php } ?>>60%</option>
           <!-- <option value="90" <?php if (@$_GET['proc'] == 90) { ?>selected="selected" <?php } ?>>90%</option>-->
    </select>
    <input type="button" id='ucenka_otm' class="btn btn-small btn-default" onclick="return false;" value="Уценить"/>
</p>
</div>
<div class="panel-footer">
<?php if ($this->ucenka_ok) { ?><h2>Уценено <?=$this->ucenka_ok?> товаров</h2><?php } ?>
</div>
</div>
</div>
<script>
    $('document').ready(function () {
        $('#ucenka_otm').click(function () {
            if (confirm('Уценить все отмеченные товары ?')) {
                if ($('#ucenka_to_otm option:selected').val() != 0) {
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
						block = 0;
						proc = $('#ucenka_to_otm option:selected').val();
				//	url = 'ucenka_id=' + id + '&usenka_id_proc=' + $('#ucenka_to_otm option:selected').val();
					if($('#skidka_block').is(":checked")) block = 1; //url+='&skidka_block='+$('#skidka_block').val();
                 //   alert(url);
					sendUcenka(id, proc, block);
					
					
					//    window.location = url;

                    }
                }
            }
        });
    });

	
function sendUcenka(id = '', proc = 0, block = 0){
var b_uc = 0;
if($('#block_ucenka_end').prop("checked")){
b_uc =1;
}
var url = '';

if(id != '') url+='ucenka_id=' + id + '&usenka_id_proc=' + proc;

if(block !=0) url+='&skidka_block='+block;
if(b_uc !=0) url+='&block_ucenka_end=1';

	if(url != ''){
	$.ajax({
                type: "POST",
                url: "/admin/ucenka/",
				dataType: 'json',
                data: url,
                success: function (data) {
				if(data.uceneno > 0){
				for(var key in data.id){
				$('.'+data.id[key]).hide();
				}
				}
					console.log(data);
                },
				error:function(e){
				console.log(e);
						//$('.sarticle_return span').html('error_ajax');
						//$('.sarticle_return').show();
					}
            });
			}
			return false;
	
	}
</script>

<!--<a onclick="chekAll(); return false;" href="#">Отметить/Снять все</a>-->
<script type="text/javascript">
 function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.article_check_box').prop('checked', true);
		}else{
		$('.article_check_box').prop('checked', false);
		}
            return false;
        }
</script>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Товар</h3></div>
<div class="panel-body">
<form method="post" action="/admin/shop-articles/changeinfo/">
    <table id="products1" cellpadding="4" cellspacing="0" style="" class="table table-bordered table-hover table-striped">
        <tr>
		<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
            <th>Действия</th>
			<th>Куплено</th>
            <th>Товар</th>
            <th>Цена</th>
			<th>Уценен</th>
			<th>СБ</th>
			<th>Скидка</br>(max)</th>
            <th>История</th>
            <th>Просмотров</th>
            <th>Дата создания</th>
            <th>Наличие</th>
            <th>Активность</th>
            <th>Категория</th>
			
        </tr>
        <?php
        $row = 'row1';
        $cur = -1;
        $articles_a = $this->articles_a;
		$count = count($articles_a);
		$j=0;
		$pr = round((100 - $_GET['proc'])/100, 2);
		//echo $pr;
       foreach ($articles_a as $article) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $cur++;
            $is_first = (0 == $cur);
            $is_last = ($count == $cur + 1);
            ?>
            <tr class="<?=$article->id?>">
			<td><label class="ckbox"><input type="checkbox" class="article_check_box cheker" name="articel_for_change_category_<?=$article->id?>"/><span></span></label></td>
                <td width="50px" class="kolomicon" valign="top">
               <a href="<?=$article->getPath()?>" target="_blank">
			<img src="<?=SITE_URL?>/img/icons/view-small.png" alt="Просмотр" data-placement="left" title="Смотреть на сайте"  data-tooltip="tooltip" class="img_return view_article"/>
					</a>
			<a href="<?=$this->path?>shop-articles/edit/id/<?=$article->getId()?>/"  style="display: inline-block;">
			<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="Редактировать"  data-tooltip="tooltip"></i></a>
			
					<i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$article->getId()?>" data-placement="left" title="Смотреть историю"  data-tooltip="tooltip" ></i>
                </td>
                <td class="kolomicon">
                    <?php if ($article->ArtycleBuyCount() == 0) { ?>
                   <a href="<?=$this->path;?>shop-articles/delete/id/<?=$article->getId();?>/"  style="display: inline-block;"
                   onclick="return confirm('Удалить товар?')">
				   <i class="icon ion-close-circled red tx-30 pd-5" alt="Удалить" data-placement="left" title="Удалить"  data-tooltip="tooltip"></i>
						</a>
                    <?php } else { ?><i class="icon ion-android-cart green tx-30 pd-5 shoping" data-id="<?=$article->getId()?>"   data-tooltip="tooltip"  title="Товар покупался <?=$article->ArtycleBuyCount()?> раз"></i>
						<?php } ?>
                </td>
                <td>
<img class="img_pre" rel="#imgiyem<?=$article->getId(); ?>"src="<?=$article->getImagePath('small_basket'); ?>" alt="<?=htmlspecialchars($article->getTitle()); ?>"/>
                <div class="simple_overlay" id="imgiyem<?=$article->getId(); ?>" style="position: fixed;top: 20%;left: 45%">
                    <img src="<?=$article->getImagePath('detail'); ?>"
                         alt="<?=htmlspecialchars($article->getTitle()); ?>"/>

                </div><br>
                <?php echo $article->getTitle();?>
                </td>
                <td>

                   <!-- <a href="#" class="real_price" onclick="return showPriceEditor(<?=$article->id?>)"
                       id="link_edit_<?=$article->id?>">-->
                      <p onclick="return showPriceEditor(<?=$article->id?>)" id="link_edit_<?=$article->id?>" style="cursor: pointer;font-size: 100%;"><?=$article->price?></p>
                   <!-- </a>-->

                    <div class="for_hide_<?=$article->id?>" style="display: none;">
<div class="list-group">
<?php
for($i=20; $i<=60; $i+=10){ if($article->ucenka >= $i or $article->max_skidka <=$i) continue; ?>
  <a href="#" onclick="return sendUcenka(<?=$article->id?>,<?=$i?>, 1);"  class="list-group-item list-group-item-action"><?=$i?></a>
  <?php } ?>
</div>


                      <!--  Новая:<input type="text" class="art_price" id="art_price_<?=$article->id?>" value="<?=$article->price?>"/><br/>
                        Старая:<input type="text" class="art_price_old" id="art_price_old_<?=$article->id?>" value="<?=$article->old_price?>"/>-->
                    </div>
					
                   <!-- <input type="button" id="btn_edit_<?=$article->id?>" value="Уценить" onclick="update_price(<?=$article->id?>)" class="for_hide" style="display: none"/>
					
					<input type="button" id="btn_edit_<?=$article->id?>" value="Сохранить" onclick="update_price(<?=$article->id?>)" class="for_hide" style="display: none"/>-->
      <!--              <br/>
					<a href="" class="priceProc_5"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-5%</a>
					   <a href="" class="priceProc_10"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-10%</a>
					   <a href="" class="priceProc_15"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-15%</a>
					   <a href="" class="priceProc_20"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-20%</a>
					   <a href="" class="priceProc_25"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-25%</a> 
                    <a href="" class="priceProc_30"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-30%</a>
					   <a href="" class="priceProc_35"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-35%</a>
					   <a href="" class="priceProc_40"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-40%</a>
					   <a href="" class="priceProc_45"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-45%</a>
                    <a href="" class="priceProc_50"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-50%</a>
                    <a href="" class="priceProc_70"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-70%</a>
                    <a href="" class="priceProc_90"
                       onclick=" return showPriceEditor(<?php echo $article->id; ?>);">-90%</a>
	-->

                </td>
				 <td>
                    <?php if ((int)$article->old_price and $article->old_price != $article->price) {
                        echo round((1 - $article->price / $article->old_price) * 100, 0)?> %
                    <?php } else { ?>
                        Не уценен.
                    <?php } ?>
                </td>
				<td><?=$article->min_price?></td>  
				<td><?=$article->max_skidka?> %</td>
               
                <td>
<?php foreach (wsActiveRecord::useStatic('UcenkaHistory')->findAll(array('article_id' => $article->id)) as $h) { 
 echo date('d-m-Y', strtotime($h->getCtime())).' - '.$h->getProc().'% ';
 } ?>
                </td>
                <td><?=$article->views?></td>
                <td><?=$article->ctime?></td>
                <td>
					<?php
                foreach ($article->sizes as $sizes) {
                    if ($sizes and $sizes->getCount() > 0) {
                        echo '<p>' . @$sizes->color->getName() . '-' . @$sizes->size->getSize() . ": " . @$sizes->getCount() . '</p>';
                    }
                } ?>
					
					</td>
                <td><?php if ($article->active == 'n') { ?>
                    <a href="javascript:void(0);" class="active" id='a_<?= $article->id; ?>'><img
                                src="/img/icons/error.png" alt="No active"></a><?php } else { ?>
                        <a href="javascript:void(0);" class="active" id='d_<?= $article->id; ?>'><img
                                src="/img/icons/accept.png" alt="Active"></a>
                    <?php } ?></td>
                <td>
                    <?php echo $mas[$article->category_id] ?>
                </td>
				
            </tr>
        <?php
     $j++;   }
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
    $pager = preg_replace('/\/page\/\d*/', '', $ur);
    $paginator = '&nbsp;&nbsp;';
	if (strlen($get)>0) {
		$get .= '&page=';
	}
	else {
		$get = '?page=';
	}
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . $get . '1"><<</a>&nbsp;<a href="' . $pager . $get . ($this->page - 1).'"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><<</span>&nbsp;<span class="grey"><</span>&nbsp;';
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
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $get . $i.'">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . $get . ($this->page + 1) . '">></a>&nbsp;<a href="' . $pager . $get . $this->totalPages . '">>></a>';
    }
    echo $paginator;

    ?><br/>
    Всего страниц: <?php echo $this->totalPages ?>, записей: <?php  echo $j."/".$this->count; ?>
    <br/>

</form>
</div>
</div>
</div>

<script >
$('.history').click(function (e) {
//console.log(e);

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
		console.log("update_price"+id);
        var price = $('#art_price_' + id).val().replace(',', '.');
        var old_price = $('#art_price_old_' + id).val();
        $('#link_edit_' + id).show();
        $('#btn_edit_' + id).hide();
        $('.for_hide_' + id).hide();
		console.log(price);
		console.log(old_price);
        if (price != '') {
            $('#link_edit_' + id).text(price);
			console.log("/admin/shop-articles/changeinfo");
			console.log("update_price: 1, id: "+id+", price: "+price+", old_price: "+old_price);
            $.ajax({
                type: "POST",
                url: "/admin/shop-articles/",
                data: {update_price: 1, id: id, price: price, old_price: old_price},
                success: function (msg) {
					console.log(msg);
                }
            });

        }

    }

    function showPriceEditor(id) {
       // $('#link_edit_' + id).hide();
       // $('#btn_edit_' + id).show();
        $('.for_hide_' + id).show();
        return false;
    }
    $(document).ready(function () {
	$('.priceProc_5').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.95));
        });
		$('.priceProc_10').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.9));
        });
		$('.priceProc_15').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.85));
        });
		$('.priceProc_20').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.8));
        });
		$('.priceProc_25').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.75));
        });
		
        $('.priceProc_30').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.7));
        });
		
		$('.priceProc_35').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.65));
        });
		$('.priceProc_40').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.6));
        });
		$('.priceProc_45').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.55));
        });
		
        $('.priceProc_50').click(function () {
            price = parseInt($(this).parent().find('.art_price_old').val());
            if (price == 0) {
                price = parseInt($(this).parent().find('.art_price').val());
                $(this).parent().find('.art_price_old').val(price)
            }
            $(this).parent().find('.art_price').val(Math.ceil(price * 0.5));
        });
        $('.priceProc_60').click(function () {
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
                            element.html('<img src="/img/icons/accept.png" alt="Active">');
                        }
                        else {
                            element.html('<img src="/img/icons/error.png" alt="No active">');
                        }

                    }
                },
                "json"
            );
        });


    });

</script>
