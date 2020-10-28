<div class="card pd-20 mb-3">
  <h6 class="card-body-title">Форма поиска</h6>
 <div class="card-body">
<form action="<?=$this->path?>tovar/" method="get">
    <div class="form-layout">
            <div class="row mg-b-25">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Номер товара: </label>
                  <input class="form-control" type="text" value="<?=$_GET['id']?>" name="id" placeholder="142682">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Актикул модели: </label>
                  <input class="form-control" type="text" name="artikul" value="<?=$_GET['artikul']?>" placeholder="bm005">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Актикул размера: </label>
                  <input class="form-control" type="text" name="cod_size" value="<?=$_GET['cod_size']?>" placeholder="0815591-200220">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Грейд: </label>
                 <select name="greyd" class="form-control select2-show-search"   data-placeholder="Виберите грейд бренда">
                     <option label="Виберите грейд бренда"></option>
			<?php foreach (wsActiveRecord::useStatic('BrandGryde')->findAll() as $g) { ?>
			<option value="<?=$g->id?>" <?php if ($_GET['greyd'] == $g->id) echo 'selected="selected"';?>><?=$g->getName()?></option>
			<?php  } ?>
		</select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Бреннд: </label>
                 <select name="brand_id" class="form-control select2-show-search"  data-palceholder="Виберите бренд" >
                     <option label="Виберите бренд"></option>
			<?php
                        foreach (wsActiveRecord::useStatic('Brand')->findAll(['hide'=>1]) as $b) {
				if ($b->getName() != '') { ?>
			<option value="<?=$b->id?>" <?php if ($_GET['brand_id'] == $b->id) echo 'selected="selected"';?>><?=$b->getName()?></option>
			<?php } } ?>
		</select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Категория: </label>
                 <select name="category_id" id="category_id" class="form-control select2-show-search"  data-palceholder="Выберите категорию"  >
                     <option label="Выберите категорию"></option>
			<?php
                        $mas = [];
		foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(['active'=>1, 'id not in(85,106,267)', 'parent_id not in(85,106,267)']) as $cat) { $mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			foreach ($mas as $kay => $value) {
			?>
                        <option value="<?=$kay?>"<?php if ($_GET['category_id'] == $kay){ echo "selected";}?>><?=$value?></option>
			<?php  } ?>
		</select>
                </div>
              </div><!-- col-3 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Сезон: </label>
                  <select class="form-control select2" name="sezon"  data-palceholder="Сезон"  >
                      <option label="Сезон"></option>
                                <?php foreach (wsActiveRecord::useStatic('Shoparticlessezon')->findAll() as $s) { ?>
                                <option <?=($_GET['sezon'] == $s->id)?'selected':''?> value="<?=$s->id?>"><?=$s->name?></option>
                         <?php   } ?>
				</select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Статус: </label>
                  <select class="form-control select2" name="status"  data-palceholder="Статус"  >
                      <option label="Статус"></option>
                               <?php foreach(wsActiveRecord::useStatic('Shoparticlesstatus')->findAll() as $s){ ?>
               <option <?=(@$_GET['status'] == $s->id)?'selected':''?> value="<?=$s->id?>"><?=$s->name?></option>
					<?php } ?>
				</select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Накладная: </label>
                   <input class="form-control" type="text" value="<?=$_GET['code']?>" name="code" placeholder="142682">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                     <label class="form-control-label">Наличие: </label>
                     <label class="rdiobox">
                <input name="nalich" type="radio" value="1" <?php if (!isset($_GET['nalich']) or $_GET['nalich'] == 1) { ?>checked<?php } ?> >
                <span>В наличии</span>
              </label>
                    <label class="rdiobox">
                <input name="nalich" type="radio" value="0"  <?php if(@$_GET['nalich'] == 0) { ?>checked<?php } ?> >
                <span>Все</span>
              </label>
                    
                    <label class="rdiobox">
                <input name="nalich" type="radio" value="2" <?php if($_GET['nalich'] == 2) { ?>checked<?php } ?>>
                <span>Нет в наличии</span>
              </label>
                </div>
              </div><!-- col-4 -->
              <div class="col-sm-12 text-center">
                  <div class="form-group mt-4 pt-1">
                      <button class="btn btn-info btn-lg mg-r-5" type="submit">Искать</button>
                     <!-- <button class="btn btn-secondary" type="reset">Очистить</button>-->
                  </div>
                  </div>
              </div><!-- row -->
          </div>
</form>
</div>
</div>
