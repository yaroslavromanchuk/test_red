<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?php if ($this->errors) { ?>
    <div id="errormessage">
	<img src="<?=SITE_URL?>/img/icons/error.png" class="page-img">
        <h2>Найдены ошибки:</h2>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
<?php } if ($this->saved) { ?>
    <div id="pagesaved">
        <img src="<?=SITE_URL?>/img/icons/accept.png"  class="page-img">
        <h2>Данные успешно сохранены</h2>
    </div>
<?php } ?>
<div id="sostav_f" class="modal fade" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Состав</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
			<h4 class="lh-3 mg-b-20"><a href="" class="tx-inverse hover-primary">Заполнение состава</a></h4>
            <p class="mg-b-5">Кликните в поле которое нужно добавить и введите содержимое. После заполения нужных полей, нажмите "Добавить".</p>
          </div>
          <div class="modal-body pd-25 text_sostav">
            <?php
			if(@$this->sostav){
			foreach($this->sostav as $s){ ?>
			<div class="input-group w300 mg-b-5" style="float:left;margin-right: 5px;">
			<span class="input-group-addon bg-transparent">
			<label class="ckbox wd-16"><input type="checkbox" id="n_<?=$s->id?>" class="sostav_checkbox"><span></span></label>
			</span>
			<?=$s->text?>
			<span class="input-group-addon tx-size-sm lh-2"><?=$s->name?></span>
			</div>
			<?php } 
			
			}?>
			
          </div>
          <div class="modal-footer">
            <button type="button" onclick="Add_Sostav();return false;" class="btn btn-info pd-x-20" data-dismiss="modal">Добавить</button>
            <button type="button" class="btn btn-secondary pd-x-20" data-dismiss="modal">Закрыть</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#product-edit">Редактирование</a></li>
  <li><a data-toggle="tab" href="#opisanie">Описание</a></li>
</ul>
<div class="tab-content">
<form action="" method="post" enctype="multipart/form-data" id="form1" class="form-horizontal">
<div  id="product-edit" class="tab-pane none fade in active" >
<div   class="col-xs-6">
<div class="form-group">
    <label for="get_now" class="col-xs-4 control-label">Сразу на сайт:</label>
    <div class="col-xs-8">
	 <input type="checkbox" value="1"  <?php if ($this->article->getGetNow()) echo 'checked="checked"'; ?> name="get_now" id="get_now">
    </div>
  </div>
  <div class="form-group">
    <label for="ontop" class="col-xs-4 control-label">На главную:</label>
    <div class="col-xs-8">
	<?php if ($this->article->getId() != null) {
	$top = wsActiveRecord::useStatic('Shoparticlestop')->findFirst(array('article_id' => $this->article->getId()));
	}else {
	$top = false;
	} ?>
	 <input type="checkbox" <?php if ($top) echo 'checked="checked"'; ?>   name="ontop" id="ontop">
    </div>
  </div>
  <div class="form-group">
    <label for="category_id" class="col-xs-4 control-label">Категория *:</label>
    <div class="col-xs-8">
	<?php  $mas = array();
			foreach ($this->categories as $cat) {$mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			?>
			<select name="category_id" class="form-control " id="category_id" onChange="SelectCatType.call(this)">
			 <option value="" selected>Выберите категорию...</option>
			 <?php foreach ($mas as $kay => $value) {
			if(strripos($value, 'SALE') === FALSE){ ?>
             <option value="<?=$kay?>" <?php if ((!$this->article && $kay == $this->cur_category->getId()) || ($this->article && $kay == $this->article->getCategoryId())) echo "selected"; ?>><?=$value?></option>
			 <?php } } ?>
			</select>
    </div>
  </div>
      <div class="form-group">
    <label for="size_type" class="col-xs-4 control-label">Пол:</label>
    <div class="col-xs-8">
	        <select name="size_type" class="form-control input" id="size_type">
		 <option value="">Выбрать...</option>
            <option value="1" <?php if ($this->article->getSizeType() == 1) { ?>selected="selected"<?php } ?> > Мужское</option>
            <option value="2" <?php if ($this->article->getSizeType() == 2) { ?>selected="selected"<?php } ?> > Женское</option>
			<option value="3"  <?php if ($this->article->getSizeType() == 3 or !$this->article->getSizeType()) { ?>selected="selected"<?php } ?> >Унисекс</option>
        </select>
		</div>
  </div>
   <div class="form-group">
    <label for="sezon" class="col-xs-4 control-label">Сезон:</label>
    <div class="col-xs-8">
	<select name="sezon" class="form-control input" id="sezon">
		<option value="" >Выбрать...</option>
		<option value="1" <?php if ($this->article->getSezon() == 1) { ?>selected="selected"<?php } ?> >Лето</option>
		<option value="2" <?php if ($this->article->getSezon() == 2) { ?>selected="selected"<?php } ?> >Осень-Весна</option>
		<option value="3" <?php if ($this->article->getSezon() == 3) { ?>selected="selected"<?php } ?> >Зима</option>
		<option value="4" <?php if ($this->article->getSezon() == 4) { ?>selected="selected"<?php } ?> >Всезезон</option>
                <option value="4" <?php if ($this->article->getSezon() == 5) { ?>selected="selected"<?php } ?> >Демисезон</option>
	</select>
	</div>
  </div>

<div class="form-group">
    <label for="brands" class="col-xs-4 control-label">Брэнд *:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input" name="brand" id="brands" value="<?=htmlspecialchars($this->article->getBrand())?>"/>
			</div>
  </div>

  
    <div class="form-group" >
    <label for="code" class="col-xs-4 control-label">№ накладной:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" name="code" id="code" value="<?php echo htmlspecialchars($this->article->getCode()); ?>"/>
			</div>
  </div>


<div class="form-group"  >
    <label for="img" class="col-xs-4 control-label">Рисунок*:</label>
    <div class="col-xs-8">
	<?php $i = 0; ?>
        <input name="image_file" type="file" onChange="handleFileSelect(this);return false;" class="form-control input" id="img"  style="float: left;" title="Главное изображение товара"/><span class="image_file v_img" style="display: inline-block;z-index:100"></span>
		 <?php if ($this->article->getImage()) { ?>
            <img class="prev" src="<?=$this->article->getImagePath('small_basket'); ?>" alt="" rel="#miesim<?=$i;?>"/>
            <div class="simple_overlay" id="miesim<?=$i; ?>" style="position: absolute; margin-left: 320px; margin-top: -150px;z-index:100">
                <img src="<?php echo $this->article->getImagePath('detail'); ?>" alt=""/>
            </div>
            <?php $i++; ?>
            <input type="hidden" name="image" value="<?php echo $this->article->getImage(); ?>"/><?php } ?>
	</div>
  </div>

  
  <?php if($this->article->getId() == null) { ?>
  <div class="form-group"  >
    <label for="img2" class="col-xs-4 control-label">Рисунок 2:</label>
    <div class="col-xs-8">
        <input name="image_file_2" type="file" onChange="handleFileSelect(this);return false;" class="form-control input" id="img2" style="float: left;" title="Главное изображение товара 2"/><span class="image_file_2 v_img" style="display: inline-block;z-index:100"></span>
	</div>
  </div>
  <?php }?>
    <script>
function handleFileSelect(evt) {
	var file = evt.files;
    var f = file[0];
	//console.log(file);
    // Only process image files.
    if (!f.type.match('image.*')) {
        alert("Вы выбрали не картинку!!! Попробуйте еще раз.");
		return false;
    }
    var reader = new FileReader();
    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" title="', escape(theFile.name), '" src="', e.target.result, '" />'].join('');
			$('.'+evt.name).html('');
			$('.'+evt.name).html(span);
        };
    })(f);
    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
}
/*
document.getElementById('img').addEventListener('change', handleFileSelect, false);
document.getElementById('img2').addEventListener('change', handleFileSelect, false);
*/
</script>
  <div class="form-group"  >
    <label for="" class="col-xs-4 control-label">Доп.Рисунки:</label>
    <div class="col-xs-8">
	 <?php
        $q = 'Select DISTINCT ws_articles_sizes.id_color, ws_articles_colors.name from ws_articles_sizes
        JOIN ws_articles_colors on ws_articles_colors.id= ws_articles_sizes.id_color
        where ws_articles_sizes.id_article =' . $this->article->getId();
        if ($this->article->getId() != null) {
		$clr = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q);
		$j=0;
        foreach ($this->article->getImages() as $image) {
            ?>
			<div class="size_article" style="margin-top: 2px;">
            <input name="images_file<?=$image->getId()?>" type="file" style="float: left;" class="form-control input"/>
				   <?php if ($image->getImage()) { ?>
        <img class="prev" rel="#miesim<?=$j?>" src="<?=$image->getImagePath('small_basket'); ?>" alt=""/>
          <div class="simple_overlay" id="miesim<?=$j; ?>" style="position: absolute; margin-left: 320px; margin-top: -150px;;z-index:100;">
                <img src="<?php echo $image->getImagePath('detail'); ?>" alt=""/>
            </div>
            <?php $j++; ?>
        <?php } ?>
        <input type="hidden" name="images<?= $image->getId() ?>" value="<?php echo $image->getImage(); ?>">
        
            <select name="imcl_<?= $image->getId() ?>" class="form-control input w150">
                <option value="0">Без цвета</option>
                <?php foreach ($clr as $im_col) { ?>
                    <option value="<?php echo $im_col->getIdColor() ?>"
                            <?php if ($image->getColorId() == $im_col->getIdColor()){ ?>selected="selected"<?php } ?>><?php echo $im_col->getName() ?></option>
                <?php } ?>
            </select><input type="submit" name="delete_images<?= $image->getId() ?>" class="btn btn-small btn-default" value="Удалить"></div>
        <?php } ?>
            <br/><input type="button" name="addimage" id="addimage" class="btn btn-small btn-default" value="Добавить изображение">
            <script type="text/javascript">
                $(document).ready(function () {
				//$('#img').change(function(){handleFileSelect();return false;});
				//$('#img2').change(function(){handleFileSelect();return false;});
                    $('#addimage').click(function () {
                        var url = '/admin/shop-articles/edit/id/<?=$this->article->getId();?>';
                        $.post(
                            url,
                            "addimage=on",
                            function (data) {
                                var color = '<?php foreach (wsActiveRecord::useStatic('Shoparticlescolor')->findAll() as $color) {
                                        echo '<option value="' . $color->getId() . '">' . $color->getName() . '</option>';
                                    } ?>';
                                var content = '<input name="images_file' + data.id + '" type="file" class="form-control input"><input type="hidden" name="images' + data.id + '" ><input type="submit" name="delete_images' + data.id + '" value="Удалить"><br><select name="imcl_' + data.id + '"><option value="0">Без цвета</option>' + color + '</select><br/>';
                                $('#addimage').before(content);
                                console.log(data);
                                console.log(data.id);

                            },
                            "json"
                        ).fail(function () {
                                console.log("error");
                            });
                    });
                });
            </script>
        <?php

        } else echo 'Создайте товар, после чего станет доступно добавление дополнительных фотографий.';
        ?>
		</div>
  </div>
    <div class="form-group"  >
    <label for="excel_file" class="col-xs-4 control-label">Excel:</label>
    <div class="col-xs-8">
	 <input name="excel_file" type="file" class="form-control input" id="excel_file">
			</div>
  </div>
<div class="form-group"  >
    <label for="soot_rozmer" class="col-xs-4 control-label">Размеры:</label>
    <div class="col-xs-8">
		<select name="soot_rozmer" class="form-control input" id="soot_rozmer">
	<option value="">Укажите соответствие!</option>
	<option value="Европейские размеры." <?php if ($this->article->getSootRozmer() == 'Европейские размеры.') { ?>selected="selected"<?php } ?>>Европейские размеры.</option>
	<option value="Наши размеры." <?php if ($this->article->getSootRozmer() == 'Наши размеры.') { ?>selected="selected"<?php } ?> >Наши размеры.</option>
	<option value="Французские размеры." <?php if ($this->article->getSootRozmer() == 'Французские размеры.') { ?>selected="selected"<?php } ?> >Французские размеры.</option>
	<option value="Размеры США." <?php if ($this->article->getSootRozmer() == 'Размеры США.') { ?>selected="selected"<?php } ?> >Размеры США.</option>
		 </select>
			</div>
  </div>
      <div class="form-group"  >
    <label for="sostav" class="col-xs-4 control-label">Состав:</label>
    <div class="col-xs-8">
	<button class="btn btn-small btn-default mg-b-5" type="button"  data-toggle="modal" data-target="#sostav_f"><i class="icon ion-compose tx-20 mr-5" aria-hidden="true"></i>Указать состав</button>
	<?php if($this->user->id == 8005){ ?>
	<button class="btn btn-small btn-default mg-b-5" type="button"  data-toggle="modal" data-target="#opis_mod"><i class="icon ion-compose tx-20 mr-5" aria-hidden="true"></i>Заполнить описание</button>
	<?php } ?>
	<textarea rows="4" cols="50" class="form-control input" id="sostav" name="sostav"><?php echo $this->article->getSostav(); ?></textarea>
			</div>
  </div>
      <div class="form-group"  >
    <label for="skidka_block" class="col-xs-4 control-label">Блок скидок:</label>
    <div class="col-xs-8">
	 <input name="skidka_block" type="checkbox" value="1" id="skidka_block" <?php if ($this->article->getSkidkaBlock()) echo 'checked="checked"'; ?> />
			</div>
  </div>
  
  </div>
<div class="col-xs-6" >
      <div class="form-group" >
    <label for="new" class="col-xs-4 control-label">Новый товар:</label>
    <div class="col-xs-8">
	 <input name="new" type="checkbox" id="new" <?php if ($this->article->getNew() == 1 or $this->article->getId() == null) echo 'checked="checked"'; ?> />
			</div>
  </div>
  <div class="form-group">
    <label for="dop_cat_id" class="col-xs-4 control-label">Доп. Категория:</label>
    <div class="col-xs-8">
	<select name="dop_cat_id" class="form-control" id="dop_cat_id">
            <option value="0" selected>Выберите категорию...</option>
            <?php foreach ($mas as $kay => $value) { ?>
            <option value="<?=$kay?>" <?php if ($this->article && $kay == $this->article->getDopCatId()) echo "selected"; ?>><?=$value?></option>
			<?php } ?>
        </select>
	    </div>
  </div>
   <div class="form-group">
    <label for="status" class="col-xs-4 control-label">Статус:</label>
    <div class="col-xs-8">
	<select name="status" class="form-control" id="status">
            <?php foreach (wsActiveRecord::useStatic('Shoparticlesstatus')->findAll() as $kay => $value) { ?>
            <option value="<?=$value->id?>" <?php if ($this->article->getStatus() && $this->article->getStatus() == $value->id){echo "selected";}elseif($value->id == 2){echo "selected";}?> ><?=$value->name?></option>
			<?php } ?>
        </select>
	    </div>
  </div>

  <div class="form-group">
    <label for="model" class="col-xs-4 control-label">Название *:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input model"  name="model" id="model" value="<?php echo htmlspecialchars($this->article->getModel()); ?>"/>
			</div>
  </div>
    <div class="form-group" >
    <label for="model_uk" class="col-xs-4 control-label">Название Укр:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input" name="model_uk" id="model_uk" value="<?php echo htmlspecialchars($this->article->getModelUk()); ?>"/>
			</div>
  </div>
  <div class="form-group" >
    <label for="price" class="col-xs-4 control-label">Цена*:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input" name="price" id="price" value="<?php echo $this->article->getPrice(); ?>"/>
	</div>
  </div>
<div class="form-group" >
    <label for="old_price" class="col-xs-4 control-label">Старая цена:</label>
    <div class="col-xs-8">
	  <input type="text" class="form-control input"  name="old_price" id="old_price" value="<?=$this->article->getOldPrice()?>"/>
	</div>
  </div>
<div class="form-group" >
    <label for="min_price" class="col-xs-4 control-label">Себестоимость:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input"  name="min_price" id="min_price" value="<?=$this->article->getMinPrice()?>"/>
	</div>
  </div>
<div class="form-group"  >
    <label for="max_skidka" class="col-xs-4 control-label">MAX скидка:</label>
    <div class="col-xs-8">
	 <input type="text" class="form-control input"  name="max_skidka" id="max_skidka" value="<?=$this->article->getMaxSkidka()?>"/>
	</div>
  </div>
         <div class="form-group" >
    <label for="long_text" class="col-xs-4 control-label">Полное описание*:</label>
    <div class="col-xs-8">
	 <textarea  name="long_text" class="form-control " id="long_text"  rows="10"><?=$this->article->getLongText()?></textarea>
			</div>
  </div>

    <div class="form-group"  >
    <label for="" class="col-xs-4 control-label">Всего на складе:</label>
    <div class="col-xs-8">
	<?=$this->article->stock?> шт.
	</div>
  </div>
    <div class="form-group"  >
    <label for="active" class="col-xs-4 control-label">В наличии:</label>
    <div class="col-xs-8">
	<input name="active" type="checkbox"
                   id="active" <?php if (strcasecmp($this->article->active, 'y') == 0) echo 'checked="checked"'; ?> />
		</div>
  </div>
    <div class="form-group"  >
    <label for="label_id" class="col-xs-4 control-label">Этикетка:</label>
    <div class="col-xs-8">
	<select name="label_id" class="form-control input" id="label_id">
            <option value="" selected>Сделать выбор...</option>

            <?php foreach (wsActiveRecord::useStatic('Shoparticleslabel')->findAll() as $item) { ?>
                <option
                    value="<?=$item->getId(); ?>" <?php if ($item->getId() == $this->article->label_id) echo "selected"; ?>><?=$item->getName(); ?></option>
            <?php

            }
            ?>
        </select>
		</div>
  </div>
      <div class="form-group"  >
    <label for="" class="col-xs-4 control-label">Размеры\Цвет*:</label>
    <div class="col-xs-8">
	 <?php
        if ($this->article->getId() != null) {

        foreach ($this->article->sizes as $sizes) {
            echo "<p class='size_article'>";
            echo '<label>Цвет:</label><select name="color' . $sizes->getId() . '"  class="selectpicker show-tick form-control input w150" data-live-search="true">';
            ?>
            <option value="" selected>Выберите цвет...</option>

            <?php foreach (wsActiveRecord::useStatic('Shoparticlescolor')->findAll() as $color) {
            ?><option
            value="<?php echo $color->getId(); ?>" <?php if ($color->getId() == $sizes->getIdColor()) echo "selected"; ?>><?php echo $color->getName(); ?></option>
        <?php

        }
            echo '</select><br>';
            echo '<label>Размер:</label><select name="size' . $sizes->getId() . '" class="selectpicker show-tick form-control input w150" data-live-search="true">';
            ?>
            <option value="" selected>Выберите размер...</option>

            <?php
            $group = '';
        foreach (wsActiveRecord::useStatic('Size')->findAll() as $siz) {
            $new_group = $siz->getCategoryId();
            if ($new_group != $group) {
                $group = $new_group;
                if ($group != 0) {
                    echo '</optgroup>';
                    echo ' <optgroup label="' . $siz->category->getName() . '">';
                } else {
                    echo ' <optgroup label=" ">';
                }
            }
            ?><option
            value="<?php echo $siz->getId(); ?>" <?php if ($siz->getId() == $sizes->getIdSize()) echo "selected"; ?>><?php echo $siz->getSize(); ?></option>
        <?php

        }
            echo '</optgroup>';
            echo '</select><br>';
            ?>
         <label>Артикул:</label><input type="text" class="form-control input" style="width: 120px;padding: 5px 0px;font-size: 13px;" maxlength="16" name="sarticle<?= $sizes->getId() ?>" value="<?= $sizes->getCode() ?>"><br>
         <label>На складе:</label><input type="text" class="form-control input w50" style="text-align: center;" size="3" name="count<?=$sizes->getId()?>" value="<?=$sizes->getCount()?>">
        <!--<br/>Расположение (ряд-стеллаж-полка-ячейка): <input type="text" class="form-control input" size="10" name="location<?= $sizes->getId() ?>"
                                                             value="<?= $sizes->getLocation() ?>" placeholder="1-2-3-4">-->
          <input type="submit" class="btn btn-sm btn-default" style="margin-left: 10px;" name="delete<?= $sizes->getId() ?>" value="Удалить">
        <?php
        echo "</p>";
        }


        ?>

            <input type="button" name="addsize" id='addsize' class="btn btn-sm btn-default" value="Добавить размер">
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#addsize').click(function () {
                        var url = '/admin/shop-articles/edit/id/<?=$this->article->getId();?>';
                        $.post(
                            url,
                            "addsize=on",
                            function (data) {
                                var color = '<?php foreach (wsActiveRecord::useStatic('Shoparticlescolor')->findAll() as $color) {
                                        echo '<option value="' . $color->getId() . '">' . $color->getName() . '</option>';
                                    } ?>';
                                var size = '<?php  $group = '';
                                        foreach (wsActiveRecord::useStatic('Size')->findAll() as $siz) {
                                            $new_group = $siz->getCategoryId();
                                            if ($new_group != $group) {
                                                $group = $new_group;
                                                if ($group != 0) {
                                                    echo '</optgroup>';
                                                    echo ' <optgroup label="' . $siz->category->getName() . '">';
                                                }
                                                else {
                                                    echo ' <optgroup label=" ">';
                                                }

                                            }
                                            echo '<option value="' . $siz->getId() . '">' . $siz->getSize() . '</option>';
                                        } ?>';
                                var content = '<p class="size_article"><select name="color' + data.id + '" class="form-control input"><option value="" selected>Выберите цвет...</option>' + color + '</select><select name="size' + data.id + '" class="form-control input"><option value="" selected>Выберите размер...</option>' + size +
                                    '</optgroup></select><br/> Артикул: <input type="text" class="form-control input" value="" name="sarticle' + data.id + '"><br/>На складе: <input type="text" class="form-control input" name="count' + data.id + '" value="0"><input type="submit" name="delete' + data.id + '" value="Удалить"></p>';
                                $('#addsize').before(content);
                                console.log(data);
                                console.log(data.id);

                            },
                            "json"
                        );
                    });
                });
            </script>
        <?php

        } else echo 'Создайте товар, после чего станет доступен выбор цвета, размера и количества.';?>
				</div>
  </div>
    
  </div>
  </div>
  
<div  id="opisanie" class="tab-pane none fade">
<h3>Описание</h3>
<div   class="col-xs-12">
<p class="z 70 75 69 30 77 142 147 148 32 39 40 78 149 110 150 158 137 251 275 274 276 278 277 283 273 263">
<input type="checkbox"  class="checking" value="1">
<label class="leb1"  for="1">Длина рукава: </label>
<select class="form-control input" id="1">
<option value="короткий">короткий</option>
<option value="длинный">длинный</option>
<option value="без рукавов">без рукавов</option>
<option value="3/4">3/4</option>
</select>
</p>
<p class="z 70 75 69 30 77 142 147 148 32 39 40 78 149 110 150 158 137 275 274 276 278 277 283 273 263 ">
<input type="checkbox"  class="checking" value="2">
<label class="leb2"  for="2">Покрой: </label>
<select class="form-control input" id="2">
<option value="приталенный">приталенный</option>
<option value="свободный">свободный</option>
<option value="прямой">прямой</option>
<option value="удлиненный">удлиненный</option>
</select>
</p>
<p class="z 15 73 149 78 107 84 40 158 263 39 32 141 14 148 80 142 113 255 249 140 147 69 74 77  13 7 70 65 75 30 143 144 59 150 163 110 151 66 37 57 61 67 35 60 62 56 58 36 68 53 71 117 158 137 251 275 274 276 278 279 282 277 280 281 283 273 297 296">
<input type="checkbox"  class="checking" value="3">
<label class="leb3"  for="3">Вид застежки: </label>
<select multiple class="form-control input" id="3" size="3">
<option value="без застежки">без застежки</option>
<option value="молния">молния</option>
<option value="пуговицы">пуговицы</option>
<option value="липучка">липучка</option>
<option value="кнопки">кнопки</option>
<option value="крючки">крючки</option>
<option value="пряжка">пряжка</option>
<option value="шнуровка">шнуровка</option>
<option value="магнитная кнопка">магнитная кнопка</option>
<option value="эластичный пояс">эластичный пояс</option>
<option value="магнитная кнопка">магнитная кнопка</option>
<option value="завязки">завязки</option>
</select>
</p>
<p class="z 150 110 30 69 70 75 77 142 147 148 32 39 40 78 84 249 263 149 158 137 275 274 276 278 277 283 273 297 296 ">
<input type="checkbox"  class="checking" value="5">
<label class="leb5"  for="5">Вырез горловины: </label>
<select class="form-control input" id="5">
<option value="округлый">округлый</option>
<option value="v-образный">v-образный</option>
<option value="лодочка">лодочка</option>
<option value="отложной воротник">отложной воротник</option>
<option value="воротник-стойка">воротник-стойка</option>
</select>
</p>
<p class="z 70 75 30 147 148 32 82 249 150 110 158 137 84 74 140 275 276 283 273 163 297 296 263">
<input type="checkbox"  class="checking" value="8">
<label class="leb8"  for="8">Вид бретелек: </label>
<select class="form-control input" id="8">
<option value="тонкие">тонкие</option>
<option value="тонкие (регулируются по длине)">тонкие (регулируются по длине)</option>
<option value="широкие">широкие</option>
<option value="без бретелек">без бретелек</option>
</select>
</p>
<p class="z 15 73 149 78 107 84 40 158 263 39 32 141 14 148 80 142 113 255 249 140 147 69 74 77 13 7 70 65 75 30 143 144 59 150 163 110 151 66 158 137 251 275 274 276 278 279 282 277 280 281 283 273">
<input type="checkbox"  class="checking" value="9">
<label class="leb9"  for="9">Тип карманов: </label>
<select multiple class="form-control input" id="9" size="3">
<option value="накладные">накладные</option>
<option value="прорезные">прорезные</option>
<option value="втачные">втачные</option>
</select>
</p>
<p class="z 73 75 80 107 113 158 137 151 279 282 263 283 147 40">
<input type="checkbox"  class="checking" value="10">
<label class="leb10"  for="10">Крой брюк: </label>
<select class="form-control input"  id="10">
<option value="зауженный">зауженный</option>
<option value="прямой">прямой</option>
<option value="клеш">клеш</option>
</select>
</p>
<p class="z 73 75 80 141 143 107 113 158 137 84 249 74 140 151 110 279 282 280 163 297 296 283 147 40">
<input type="checkbox"  class="checking" value="11">
<label class="leb11"  for="11">Тип посадки: </label>
<select class="form-control input"  id="11">
<option value="низкая">низкая</option>
<option value="средняя">средняя</option>
<option value="высокая">высокая</option>
</select>
</p>
<p class="z 73 80 141 143 107 113 70 144 158 137 75 263 276 279 282 280 281">
<input type="checkbox"  class="checking" value="13">
<label class="leb13"  for="13">Ширина пояса: </label> 
<input type="text" class="form-control input" id="13"  value="" />
</p>
<p class="z 150 142 149 78 69 274 277 75 263">
<input type="checkbox"  class="checking" value="14">
<label class="leb14"  for="14">Опции капюшона: </label>
<select  class="form-control input" id="14">
<option value="съемный">съемный</option>
<option value="не съемный">не съемный</option>
<option value="скрытый капюшон">скрытый капюшон</option>

</select>
</p>
<p class="z 150 142 149 277">
<input type="checkbox"  class="checking" value="15">
<label class="leb15"  for="15">Опции опушки: </label>
<select class="form-control input"  id="15">
<option value="съемная">съемная</option>
<option value="не съемная">не съемная</option>
<option value="без опушки">без опушки</option>
</select>
</p>
<!--
<p class="z 35 37 57 62 58 36 56 61 60 68 67 142 149 115 163 249 140 84 55 79 146 150 277 297 296">
<input type="checkbox"  class="checking" value="16">
<label class="leb16"  for="16">Сезон: </label>
<select multiple class="form-control input" id="16" size="4">
<option value="лето">лето</option>
<option value="зима">зима</option>
<option value="демисезон">демисезон</option>
<option value="круглогодичный">круглогодичный</option>
</select>
</p>-->
<p class="z 249 140 74 137 84 55 146 163 297 296">
<input type="checkbox"  class="checking" value="18">
<label class="leb18"  for="18">Назначение: </label>
<select multiple class="form-control input" id="18" size="3">
<option value="пляж">пляж</option>
<option value="спорт">спорт</option>
<option value="ежедневный">ежедневный</option>
<option value="термобелье">термобелье</option>
<option value="для кормления">для кормления</option>
</select>
</p>
<p class="z 249 140 74 137 163 297 296">
<input type="checkbox"  class="checking" value="19">
<label class="leb19"  for="19">Особенности белья: </label>
<select class="form-control input"  id="19">
<option value="бретели съемные">бретели съемные</option>
<option value="бретели не съемные">бретели не съемные</option>
<option value="бретели регулируются по длине">бретели регулируются по длине</option>
</select>
</p>
<p class="z 249 140 74 163 297 296">
<input type="checkbox"  class="checking" value="20">
<label class="leb20"  for="20">Вид чашки: </label>
<select  class="form-control input"  id="20">
<option value="пуш-ап">пуш-ап</option>
<option value="пуш-ап съемный">пуш-ап съемный</option>
<option value="с мягкой чашкой">с мягкой чашкой</option>
<option value="тонкий поролон">тонкий поролон</option>
</select>
</p>
<p class="z 70 144  276 281">
<input type="checkbox"  class="checking" value="21">
<label class="leb21"  for="21">Длина изделия: </label>
<select class="form-control input"  id="21">
<option value="мини">мини</option>
<option value="миди">миди</option>
<option value="макси">макси</option>
</select>
</p><p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="24">
<label class="leb24"  for="24">Вид мыска: </label>
<select class="form-control input"   id="24">
<option value="открытый">открытый</option>
<option value="закрытый">закрытый</option>
</select>
</p>
<p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="23">
<label class="leb23"  for="23">Форма мыска: </label>
<select class="form-control input"   id="23">
<option value="округлый">округлый</option>
<option value="острый">острый</option>
</select>
</p>
<p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="25">
<label class="leb25"  for="25">Высота платформы: </label>
<input type="text" class="form-control input" id="25"  value="" />
</p>
<p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="26">
<label class="leb26"  for="26">Высота каблука: </label>
<input type="text" class="form-control input" id="26"  value="" />
</p>
<p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="27">
<label class="leb27"  for="27">Высота танкетки: </label>
<input type="text" class="form-control input" id="27"  value="" />
</p>
<p class="z 35 37 57 62 58 36 56 61 60 68 67">
<input type="checkbox"  class="checking" value="28">
<label class="leb28"  for="28">Тип подошвы: </label>
<select class="form-control input"  id="28">
<option value="рифленная">рифленная</option>
<option value="протекторная">протекторная</option>
</select>
</p>
<p class="z 65">
<input type="checkbox"  class="checking" value="32">
<label class="leb32"  for="32">Длина пряжки: </label>
<input type="text" class="form-control input" id="32"  value="" />
</p>
<p class="z 65">
<input type="checkbox"  class="checking" value="33">
<label class="leb33"  for="33">Ширина пряжки: </label>
<input type="text" class="form-control input" id="33"  value="" />
</p>
<p class="z 65 53 79 71 114 115 117 152 253 55">
<input type="checkbox"  class="checking" value="35">
<label class="leb35"  for="35">Цвет фурнитуры: </label>
<input type="text" class="form-control input" id="35"  value="" />
</p>
<p class="z 53 117">
<input type="checkbox"  class="checking" value="37">
<label class="leb37"  for="37">Длина: </label>
<input type="text" class="form-control input" id="37"  value="" />
</p>
<p class="z 53 117 268">
<input type="checkbox"  class="checking" value="38">
<label class="leb38"  for="38">Ширина: </label>
<input type="text" class="form-control input" id="38"  value="" />
</p>
<p class="z 268">
<input type="checkbox"  class="checking" value="53">
<label class="leb53"  for="53">Высота: </label>
<input type="text" class="form-control input" id="53"  value="" />
</p>
<p class="z 53 117">
<input type="checkbox"  class="checking" value="39">
<label class="leb39"  for="39">Глубина: </label>
<input type="text" class="form-control input" id="39"  value="" />
</p>
<p class="z 117 ">
<input type="checkbox"  class="checking" value="41">
<label class="leb41"  for="41">Отсеки: </label>
<select multiple class="form-control input" id="41" size="2">
<option value="для монет">для монет</option>
<option value="для купюр">для купюр</option>
<option value="для карточек">для какточек</option>
</select>
</p>
<p class="z 53">
<input type="checkbox"  class="checking" value="43">
<label class="leb43"  for="43">Количество отделений: </label>
<input type="text" class="form-control input" id="43"  value="" />
</p>
<p class="z 79">
<input type="checkbox"  class="checking" value="44">
<label class="leb44"  for="44">Габариты товара: </label>
<input type="text" class="form-control input" id="44"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="52">
<label class="leb52"  for="52">Модель: </label>
<input type="text" class="form-control input" id="52"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="45">
<label class="leb45"  for="45">Механизм: </label>
<input type="text" class="form-control input" id="45"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="46">
<label class="leb46"  for="46">Материал браслета: </label>
<input type="text" class="form-control input" id="46"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="47">
<label class="leb47"  for="47">Материал корпуса: </label>
<input type="text" class="form-control input" id="47"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="48">
<label class="leb48"  for="48">Форма корпуса: </label>
<input type="text" class="form-control input" id="48"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="49">
<label class="leb49"  for="49">Ширина циферблата: </label>
<input type="text" class="form-control input" id="49"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="50">
<label class="leb50"  for="50">Ширина ремешка: </label>
<input type="text" class="form-control input" id="50"  value="" />
</p>
<p class="z 155 154">
<input type="checkbox"  class="checking" value="51">
<label class="leb51"  for="51">Толщина корпуса: </label>
<input type="text" class="form-control input" id="51"  value="" />
</p>
<p class="z 253">
<input type="checkbox"  class="checking" value="54">
<label class="leb54"  for="54">Материал линзы: </label>
<input type="text" class="form-control input" id="54"  value="" />
</p>
<p class="z 253">
<input type="checkbox"  class="checking" value="55">
<label class="leb55"  for="55">Материал оправы: </label>
<input type="text" class="form-control input" id="55"  value="" />
</p>
<p class="z 253">
<input type="checkbox"  class="checking" value="56">
<label class="leb56"  for="56">Материал дужек: </label>
<input type="text" class="form-control input" id="56"  value="" />
</p>
<p class="z 115 ">
<input type="checkbox"  class="checking" value="58">
<label class="leb58"  for="58">Ширина полей: </label>
<input type="text" class="form-control input" id="58"  value="" />
</p>
<p class="z 253">
<input type="checkbox"  class="checking" value="57">
<label class="leb57"  for="57">Степень защиты: </label>
<input type="text" class="form-control input" id="57"  value="" />
</p>
<p class="z a ">
<input type="checkbox"  class="checking" value="4">
<label class="leb4"  for="4">Комплектация: </label>
<input type="text" class="form-control input" id="4"  value="" />
</p>
<p class="z a ">
<input type="checkbox"  class="checking" value="6">
<label class="leb6"  for="6">Размер на фото: </label>
<input type="text" class="form-control input w100"  id="6"  value="" />
</p>
<p class="z a ">
<input type="checkbox"  class="checking" value="7">
<label class="leb7"  for="7">Страна бренда: </label>
<input type="text" class="form-control input" id="7"  value="" />
</p>
<p class="z a ">
<input type="checkbox"  class="checking" value="59">
<label class="leb59"  for="59">Примечание: </label>
<input type="text" class="form-control input" id="59"  value="" />
</p>
</div>
</div>
  <p style="text-align: center;">
<button class="btn btn-small btn-default " type="submit" name="button" id="button" class="btn btn-small btn-default" ><i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Сохранить</button>
</p>
</form>
<p id="er" style="color: red;text-align: center;font-size: 20px;margin-top: -10px;"></p>
</div>
<script>
var arr = [];
var i = 0;
$(".checking").change(function(){
    if(this.checked){
	console.log($(this).val());
	//console.log($(".leb"+$(this).val()));
	console.log($("#"+$(this).val()).val());
        arr[i] = "<p class='opis'><span class='span_opis'>"+$(".leb"+$(this).val()).html()+"</span> "+ $("#"+$(this).val()).val()+" </p>";
        i++;
    }else{
        var val = $(this).val();
        var index = arr.indexOf(val);
        arr.splice(index, 1);
        i--;
    }
console.log(arr);	
     long_text.innerHTML = arr.join('');
    //console.log(arr);
});


$( ".s_s" ).click(function() {
var c = $(this).parent("div").find("input.sostav_checkbox")[0];
console.log(this);
$("#"+c.id).prop('checked', true);
});
function Add_Sostav(){
var arr_s = [];
var j = 0;
$('input:checked.sostav_checkbox').each(function(){
var input = $("#i"+this.id);
console.log(input);

if(input.prop("tagName") == 'SELECT'){
arr_s.push(input.attr("name")+' '+input.val());
}else{
arr_s.push(input.val()+input.attr("name"));
}

j++;
});
console.log(arr_s.sort().reverse());
  sostav.innerHTML = arr_s.join('; \n')+'.';
}

function SelectCatType(){

var temp = this.options[this.selectedIndex].value;
console.log(temp);
			$('.z').hide();
           $('.'+temp).show();
		   $('.a').show();
}
</script>
<script type="text/javascript" src="/admin_files/scripts/jquery.autocomplete.js"></script>
<script type="text/javascript">
/*$('#button111').on('click', function(){
	if(validForm() != false) {
	$(".form1").submit();
	}else{ $( "form" ).text( "Вы не заполнили поля отмеченные красным!" ).show().fadeOut( 3000 );
  event.preventDefault();
  }
});*/

    $(document).ready(function () {
	//Add_sostav();
	
	
	$("#form1").submit(function() {
	if(validForm()) {
	return true;
	}else{
	return false;
  }
	
	});
	
        $('.prev').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
		
		$('.v_img').hover(function () {
		$(this).find('img').css('width','300px');
        }, function () {
		$(this).find('img').css('width','75px');
        });
        var ac = $('#model').autocomplete({
            serviceUrl: '/admin/getarticlename/', // Страница для обработки запросов автозаполнения
            minChars: 2, // Минимальная длина запроса для срабатывания автозаполнения
            maxHeight: 250, // Максимальная высота списка подсказок, в пикселях
            width: 200, // Ширина списка
            zIndex: 9999, // z-index списка

            deferRequestBy: 300, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
            onSelect: function (data, value) {

            } // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
        });
        ac.disable();
        ac.enable();
		/*
		 var ac = $('#model').autocomplete({
            serviceUrl: '/admin/getarticlename/', // Страница для обработки запросов автозаполнения
            minChars: 2, // Минимальная длина запроса для срабатывания автозаполнения
            maxHeight: 200, // Максимальная высота списка подсказок, в пикселях
            //width: 153, // Ширина списка
            zIndex: 9999, // z-index списка

            deferRequestBy: 0, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
            onSelect: function (data, value) {

            } // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
        });
        ac.disable();
        ac.enable();
		*/
        var bc = $('#brands').autocomplete({
            serviceUrl: '/admin/getarticlebrend/', // Страница для обработки запросов автозаполнения
            minChars: 2, // Минимальная длина запроса для срабатывания автозаполнения
            maxHeight: 250, // Максимальная высота списка подсказок, в пикселях
            width: 200, // Ширина списка
            zIndex: 9999, // z-index списка
            deferRequestBy: 300, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
            onSelect: function (data, value) {

            } // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
			//return false;
        });
        bc.disable();
        bc.enable();

        $('#add_tr').click(function(){
           var vls =$('#addSize input').serialize();
            $('#addSize input').val('');
            ajaxSizeTable(vls);
            console.log(vls);
        });
        ajaxSizeTable('');

    });
	function validForm(){
	console.log('+');
var valid = true;
if($('#sezon').val() == ''){ valid = false; $('#sezon').addClass("red"); $('#sezon').focus();}
if($('#size_type').val() == ''){ valid = false; $('#size_type').addClass("red"); $('#size_type').focus(); }
if($('#brands').val() == ''){ valid = false; $('#brands').addClass("red"); $('#brands').focus(); }
if(valid == false){ $( "#er" ).text( "Вы не заполнили поля отмеченные красным!" ).show().fadeOut( 3000 ); }
 return valid;
}

    function ajaxSizeTable(vls){
        $.ajax({
            url: '/admin/shop-articles/edit/act/tablesize/id/<?=$this->article->getId();?>',
            dataType: 'json',
            type: 'POST',// or GET
            data: vls,
            success:function(data){
                //$('#table_size tbody').html(data.txt);
            }
        });
    };

    function deleteSD(){
        $.ajax({
            url: '/admin/shop-articles/edit/act/tablesize/id/<?=$this->article->getId();?>',
            dataType: 'json',
            type: 'POST',// or GET
            data: vls,
            success:function(data){
                //$('#table_size tbody').html(data.txt);
            }
        });
    };
</script>