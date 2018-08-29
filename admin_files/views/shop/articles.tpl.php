<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>"  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?> <?php echo $this->cur_category ? '('.$this->cur_category->getName().')' : '';?></h1>
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
		</select><br><input type="checkbox" name="whith_kids" id="whith_kids" value="1" <?php if ($_GET['whith_kids'] == 1) { ?>checked="checked"<?php } ?> />
		<label for="whith_kids">С подкатегорями</label>
		</td>
		<td>Сезон</td>
		<td><select class="form-control select2" name="sezon">
				<option value=""></option>
				<option <?=(@$_GET['sezon'] == 1)?'selected':''?> value="1">Лето</option>
				<option <?=(@$_GET['sezon'] == 2)?'selected':''?> value="2">Осень-Весна</option>
				<option <?=(@$_GET['sezon'] == 3)?'selected':''?> value="3">Зима</option>
				<option <?=(@$_GET['sezon'] == 4)?'selected':''?> value="4">Всесезон</option>
				<option <?=(@$_GET['sezon'] == 5)?'selected':''?> value="5">Демисезон</option>
				</select></td>
	</tr>
	<tr>
	<td>Уценка:</td>
	<td><input type="checkbox" id='ucenalos' value="1" <?php if (@$_GET['ucenalos'] == 1) { ?>checked="checked"<?php } ?>
					   name="ucenalos"/>
				<label for="ucenalos">Уценялось</label></td>
				<td>Процент %</td>
				<td><select class="form-control select2" name="proc">
				<option value="">%</option>
				<option <?=(@$_GET['proc'] == 20)?'selected':''?> value="20">20%</option>
				<option <?=(@$_GET['proc'] == 30)?'selected':''?> value="30">30%</option>
				<option <?=(@$_GET['proc'] == 40)?'selected':''?> value="40">40%</option>
				<option <?=(@$_GET['proc'] == 50)?'selected':''?> value="50">50%</option>
				<option <?=(@$_GET['proc'] == 60)?'selected':''?> value="60">60%</option>
				</select></td>
				
	</tr>
	<tr>
	<td>Активность:</td>
	<td>
				<!--<input type="checkbox" id='nughoucenyat'
					   value="1" <?php //if ($_GET['ucenka'] == 1) { ?>checked="checked"<?php //} ?> name="ucenka"/>
				<label for="nughoucenyat">Нужно уценять</label>
				<input type="checkbox" id='oneekzemplar'
					   value="1" <?php //if ($_GET['issetone'] == 1) { ?>checked="checked"<?php //} ?> name="issetone"/>
				<label for="oneekzemplar">1 в наличии</label>-->
				
				<input type="checkbox" id='active'
					   value="1" <?php if (@$_GET['active'] == 1) { ?>checked="checked"<?php } ?> name="active"/>
				<label for="active">неактивные</label>
		</td>
		<td>Статус:</td>
		<td>
		<select class="form-control select2" name="status" data-placeholder="Статус товара">
				  <option value="">Статус</option>
		<?php foreach(wsActiveRecord::useStatic('Shoparticlesstatus')->findAll() as $s){ ?>
               <option <?=(@$_GET['status'] == $s->id)?'selected':''?> value="<?=$s->id?>"><?=$s->name?></option>
					<?php } ?>
		</select>
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
		<td><input type="text" class="form-control input" value="<?=@$_GET['price']?>" name="price"></td>
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
        <?php foreach ($mas as $kay => $value) { ?>
        <option value="<?=$kay?>"><?=$value?></option>
		<?php } ?>
    </select> 
	<button class=" add_dop_cat btn btn-small btn-default"><i class="glyphicon glyphicon-copy" aria-hidden="true"></i> Добавить доп.кат</button>
</span>
</td>
<td  class="td_ss">
<span class="ss">
<form action="/admin/otchets/type/5/" method="post">
<button id='article_exel11' type="submit" class=" btn btn-small btn-default"><i class="glyphicon glyphicon-floppy-save" aria-hidden="true"></i> Экспорт артикулов Excel</button>
</form>
</span>
</td>
<td  class="td_ss">
<?php if($this->admin_rights['318']['right']){ ?>
<span class="ss">
<label>Активация товара</label><br>
<input type="text" id="activ_article" name="act" class="form-control" style="width:100px;    display: inline-block;" >
<button  class="btn btn-small btn-default" onclick="Activ($('#activ_article').val());" ><i class="glyphicon glyphicon-flash" aria-hidden="true"></i></button>
</span>
<?php } ?>
</td>
<tr>
</table>
<script type="text/javascript">
    $(document).ready(function () {
        $('#article_exel').click(function () {
           /* if ($('.article_check_box:checked').val()) {
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
				}*/
					var url = '/admin/otchets/type/5/';
					//var new_data = '&id='+id;
					$.ajax({
                type: "POST",
                url: url,
				dataType: 'json',
                //data: new_data,
                success: function (data) {
				console.log(data);
//fopen('Товар с накладной активирован', data);
                },
				error: function(data){
				console.log(data);
				
				}
            });
			
              //  window.location = '/admin/otchets/type/5/id/' + id;

            
        });
		
    });
</script>
<?php $count = $this->articles->count(); ?>
<form method="post" action="/admin/shop-articles/changeinfo/">
<table id="products1" cellpadding="4" cellspacing="0" class="table">
    <tr>
		<th>
		<label class="ckbox" data-tooltip="tooltip" title="Выделить все товары"><input onchange="chekAll();return false;" class="chekAll" type="checkbox"/><span></span></label>
		</th>
        <th>Действие</th>
		<th>Покупки</th>
		<th>Статус</th>
		<th>Накладная</th>
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
            <td>
			<a href="<?=$article->getPath();?>" target="_blank" style="display: inline-block;">
			<i class="icon ion-monitor bleak tx-30 pd-5" alt="Просмотр" data-id="<?=$article->getId();?>" data-placement="left" title="Смотреть на сайте"  data-tooltip="tooltip" class="img_return view_article"></i>
			</a>
			<a href="<?=$this->path?>shop-articles/edit/id/<?=$article->getId()?>/"  style="display: inline-block;">
			<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="Редактировать"  data-tooltip="tooltip"></i></a></td>
            <td>
                <?php if ($article->ArtycleBuyCount() == 0) { ?>
                <a href="<?=$this->path;?>shop-articles/delete/id/<?=$article->getId();?>/"  style="display: inline-block;"
                   onclick="return confirm('Удалить товар?')">
				   <i class="icon ion-close-circled red tx-30 pd-5" alt="Удалить" data-placement="left" title="Удалить"  data-tooltip="tooltip"></i>
						</a>
                <?php } else { ?>
				<i class="icon ion-android-cart green tx-30 pd-5 shoping" data-id="<?=$article->getId()?>"   data-tooltip="tooltip"  title="Товар покупался <?=$article->ArtycleBuyCount()?> раз"></i>
					<?php } ?>
            </td>
            <td>
			<?=$article->name_status->name?>
			</td>
			<td>
			<?=$article->code?>
			</td>
            <td>
			<i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$article->getId()?>" data-placement="left" title="Смотреть историю"  data-tooltip="tooltip" ></i>
            </td>
            <td>
<img class="img_pre" rel="#imgiyem<?=$article->getId(); ?>"src="<?=$article->getImagePath('small_basket'); ?>" alt="<?=htmlspecialchars($article->getTitle()); ?>"/>
                <div class="simple_overlay" id="imgiyem<?=$article->getId(); ?>" style="position: fixed;top: 20%;left: 45%;z-index:100">
                    <img src="<?=$article->getImagePath('detail'); ?>" alt="<?=htmlspecialchars($article->getTitle()); ?>"/>
                </div><br>
               <span class=""><?=$article->getTitle();?></span>
            </td>
            <td style="width: 75px;"><?php echo $article->getPrice();
			if($article->getOldPrice() > 0){ echo '<br><span style="color: #af241b;">'.$article->getOldPrice().'</span>';}?>
			<?php if($this->user->getId() == 8005 or $this->user->getId() == 1 or $this->user->getId() == 34608 or $this->user->getId() == 36431){
			if($article->getOldPrice() > 0){
			echo '<br><span style="color: #af241b; font-size:10px;">- '.round((1 - ($article->getPrice() /$article->getOldPrice())) * 100, 0).' %</span>';
			}
			echo '<br><span style="color: #af241b; font-size:10px;font-family: monospace;">max-'.$article->getMaxSkidka().'%</span>';
			echo '<br><span style="color: #af241b; font-size:10px;font-family: monospace;">min-'.$article->getMinPrice().'грн</span>';
			} ?>
            </td>
            <td><?=$article->getViews();?></td>
            <td><?=strftime('%d.%m.%Y %H:%M', strtotime($article->getCtime()))?></td>
            <td><?php foreach ($article->sizes as $sizes) {
                    if ($sizes){ echo '<p>' . @$sizes->color->getName() . '-' . @$sizes->size->getSize() . ": " . @$sizes->getCount() . '</p>';}
					} ?>
			</td>
            <td><?php if($this->admin_rights['318']['right']){
			if ($article->getActive() == 'n') { ?>
                <a href="javascript:void(0);" class="active" id="a_<?=$article->getId();?>">
				<i class="icon ion-close-circled red tx-30 pd-5 " alt="No active"></i>
							</a><?php } else { ?>
                    <a href="javascript:void(0);" class="active" id='d_<?=$article->getId();?>'>
					<i class="icon ion-checkmark-circled green tx-30 pd-5" alt="Active"></i>
							</a>
                <?php }
				}elseif($this->user->getTelegram()){ ?>
				<i class="icon ion-flag text-primary tx-30 pd-5"  id="reminder" name="reminder" onclick="return add_reminder(<?=$article->getId()?>);" data-tooltip="tooltip" data-original-title="Уведомить о активации"></i>
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
<script type="text/javascript">
$(document).ready(function(){
		 $('img.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
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
function add_reminder(e){
var code = e;
                    if (code > 0 && code != '') {
					var url = '/admin/activearticle/';
					var new_data = '&add_reminder='+code;
					console.log(new_data);
					$.ajax({
                type: "POST",
                url: url,
				dataType: 'json',
                data: new_data,
                success: function (data) {
				console.log(data);
fopen(data.title, data.message);
                },
				error: function(data){
				console.log(data);
				
				}
            });
			 return true;
					}
}

	

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
$('.history').click(function (e) {
//console.log(e);

var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/articlehistory/id/'+id+'/m/1',function (data) {fopen('История изменения товара', data);});	

});
$('.shoping').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/ordersbyartycle/id/'+id+'/m/1',function (data) {fopen('История покупок товара', data);});	
});

$('a.active').click(function () {
		console.log($(this).attr('id'));
		
            var element = $(this);
            var id = $(this).attr('id');
			var type = id.charAt(0);
			id=id.substring(2);
			console.log(type);
            $.get('/admin/activearticle/id/'+id+'/type/'+type,function (result) {
			result = JSON.parse(result);
			console.log(result);
                    if (result.type == 'error') {
                        return(false);
                    }else{
					element.attr('id', result.id);
                        if (result.func == 'd') {
                            element.html('<i class="icon ion-checkmark-circled green tx-30 pd-5" alt="Active"></i>');
                        }else {
                            element.html('<i class="icon ion-close-circled red tx-30 pd-5" alt="No active"></i>');
                        }

                    }
			});
        });

</script>
Перенести в категорию:
<select name="category_id" id="category_id" class="form-control input">
    <?php foreach ($mas as $kay => $value) { ?>
    <option value="<?=$kay?>"><?=$value?></option>
	<?php } ?>
</select>
<input type="submit" value="Перенести" onclick="return confirm('Вы действительно хотите перенести выбранные товары в категорию ?')" class="btn btn-small btn-default" />
</form>
