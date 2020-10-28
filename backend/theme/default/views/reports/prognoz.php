<div class="row row-sm mg-x-0 mg-t-20">
              <div class="col-12 my-1  text-center">
                  <legend>ПРОДАЖИ</legend>
              </div>
          <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Продажи по месяцам</h6>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
                <canvas id="realization"></canvas>
              </div><!-- list-group -->
              <div class="card-footer">
                  <form class="form-horizontal" name="realization" id="realization_form" >
<fieldset>
    <div class="form-row">
            <legend>Интервалы:</legend>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_from_r">От</label>  
  <div class="col-md-9">
      <input id="one_date_from_r" name="one_date_from_r" type="month"  placeholder="2019-04" class="form-control  form-control-sm" value="<?=date("Y-m", strtotime('-1 month'))?>" min="2018-01" max="<?=date("Y-m")?>" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_to_r">До</label>   
  <div class="col-md-9">
      <input id="one_date_to_r" name="one_date_to_r" type="month" placeholder="2019-05"   value="<?=date("Y-m")?>" min="2018-01" max="<?=date("Y-m")?>" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите вторую дату</span>  
  </div>
</div>
    </div>
    <div class="form-row">
    <input type="text" class="form-control form-control-sm" hidden  name="method"  value="realization">
    <!-- Button -->
<div class="form-group col-md-12 text-center">
    <button id="realization-send" type="submit" name="realization-send" class="btn btn-outline-primary">Построить</button>
</div>
</div>
</fieldset>
                  </form>
                  </div>
              </div>
              </div>
          <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Продажи по грейдам за период</h6>
                  <p>Выберите период, автоматически сравниться с предидущим годом.</p>
                  </div>
                    </div>
              <div class="card-body">
               <!-- <canvas id="order_gryde"></canvas>-->
                 <div id="order_gryde"></div>
              </div><!-- list-group -->
              <div class="card-footer">
                  <form class="form-horizontal" name="order_gryde_form" id="order_gryde_form_id" >
<fieldset>
<!-- Form Name -->
<!-- Text input-->
<div class="form-row">
    <div class="form-group col-md-6">
        <select name="gryde" id="gryde" class="form-control form-control-sm select2" data-placeholder="Выберите грейд">
            <option label="Грейд"></option>
            <?php foreach (wsActiveRecord::useStatic('BrandGryde')->findAll() as $b ) {
                ?>
            <option value="<?=$b->greyd_id?>"><?=$b->name?></option>
                <?php  } ?>
        </select>
    </div>
<div class="form-group col-md-6">
        <select name="type" class="form-control form-control-sm select2" data-placeholder="Выберите тип">
            <option value="1">Продажи в грн.</option>
            <option value="2">Продажи в шт.</option>
        </select>
    </div>
</div>
<div class="form-row">
    <legend>Интервалы:</legend>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_from_gryde">От</label>  
  <div class="col-md-9">
      <input id="one_date_from_gryde" name="one_date_from_gryde" type="month" onchange="$('#two_date_from_gryde').val(this.value)" placeholder="2019-04" class="form-control  form-control-sm" value="<?=date("Y-m", strtotime('-1 month'))?>" min="2018-01" max="<?=date("Y-m")?>" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_to_gryde">До</label>   
  <div class="col-md-9">
      <input id="one_date_to_gryde" name="one_date_to_gryde" type="month" placeholder="2019-05" onchange="$('#two_date_to_gryde').val(this.value)"  value="<?=date("Y-m")?>" min="2018-04" max="<?=date("Y-m")?>" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите вторую дату</span>  
  </div>
</div>
</div>
<!--
<div class="form-row">
    <legend>Сравнить с предыдущим периодом:</legend>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_from_gryde">От</label>  
  <div class="col-md-9">
      <input id="two_date_from_gryde" name="two_date_from_gryde" type="month" placeholder="2019-04" value="<?=date("Y-m", strtotime('-1 month'))?>" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one_date_to_gryde">До</label>  
  <div class="col-md-9">
      <input id="two_date_to_gryde" name="two_date_to_gryde" type="month" placeholder="2019-05-05" value="<?=date("Y-m")?>" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите вторую дату</span>  
  </div>
</div>
</div>-->
<div>
    <input type="text" class="form-control form-control-sm" hidden  name="method"  value="order_gryde_period">
    <!-- Button -->
<div class="form-group col-md-12 text-center">
    <button  type="submit" name="order_gryde_send" class="btn btn-outline-primary">Построить</button>
</div>
</div>
</fieldset>
                  </form>
                  </div>
              </div>
              </div>
           <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Сравнение остатков товара за период по категориям</h6>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
		<div id="ostatki2"  ></div>
                <div id="ostatki2_table"></div>
              </div><!-- list-group -->
             <div class="card-footer">
               <form class="form-horizontal" name="procent-ostatka"  id="procent-ostatka">
                   
<fieldset>
<!-- Form Name -->
<!-- Text input-->
<div class="form-row">
<select name="cat_prognoz" id="cat_ostatoc_category" class="form-control form-control-sm select2" data-placeholder="Выберите категорию товара">
            <option label="Категория"></option>
            <option value="999">Главные категории</option>
            <option value="888">Товары по гендеру</option>
            <option value="777">Товары по грейду</option>
            <?php foreach (Shopcategories::find('Shopcategories', ['active'=> 1, 'parent_id'=>0, 'id not in (106, 85, 267, 146)']) as $cat ) {
                ?>
            <option value="<?=$cat->id?>"><?=$cat->getRoutez()?></option>
                <?php  } ?>
        </select>
</div>
<div class="form-row">
    <legend>Интервалы:</legend>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one-date-from">От</label>  
  <div class="col-md-9">
      <input id="one-date-from" name="one_date_from" type="date" onchange="$('#two-date-from').val(this.value)" placeholder="2019-04-05" class="form-control  form-control-sm" value="<?=date("Y-m-d", strtotime('-30 days'))?>" min="2018-04-04" max="<?=date("Y-m-d", strtotime('-2 days'))?>" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one-date-to">До</label>   
  <div class="col-md-9">
      <input id="one-date-to" name="one_date_to" type="date" placeholder="2019-05-05" onchange="$('#two-date-to').val(this.value)"  value="<?=date("Y-m-d")?>" min="2018-04-04" max="<?=date("Y-m-d")?>" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите вторую дату</span>  
  </div>
</div>
</div>
<div class="form-row">
    <legend>Сравнить с:</legend>
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one-date-from">От</label>  
  <div class="col-md-9">
      <input id="two-date-from" name="two_date_from" type="date" placeholder="2019-04-05" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one-date-to">До</label>  
  <div class="col-md-9">
      <input id="two-date-to" name="two_date_to" type="date" placeholder="2019-05-05" class="form-control form-control-sm" required="">
  <span class="help-block">Выберите вторую дату</span>  
  </div>
</div>
</div>
<div>
    <input type="text" class="form-control form-control-sm" hidden  name="method"  value="sredniy_ostatok">
    <!-- Button -->
<div class="form-group col-md-12 text-center">
    <button id="ostatoc-send" type="submit" name="ostatoc-send" class="btn btn-outline-primary">Построить</button>
</div>
</div>
</fieldset>
</form>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
          
          </div>
<div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  text-center">
                  <legend>ПРОГНОЗИРОВАНИЕ</legend>
              </div>
          <div class="col-xl-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Прогнозы по категориям</h6>
                  </div>
                    <div>
<form class="form-inline" name="form_prognoz" id="form_prognoz">
  <div class="form-group">
    <input type="date"  class="form-control form-control-sm" name="from_prognoz" id="from_prognoz" value="<?=date("Y-m-d", strtotime('-60 days'))?>" min="<?=date("Y-m-d", strtotime('-2 Year'))?>" max="<?=date("Y-m-d")?>" >
    
  </div>
    
  <div class="form-group">
   <input type="date" class="form-control form-control-sm" id="to_prognoz" name="to_prognoz"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-2 Year'))?>" max="<?=date("Y-m-d")?>">
  </div>
    <div class="form-group">
        <?php
         $mas = [];
		foreach (Shopcategories::find('Shopcategories', ['active'=> 1, 'id not in (106)']) as $cat) {
                    $mas[$cat->getId()] = $cat->getRoutez();
                }
                asort($mas);
?> 
        <select name="cat_prognoz" id="cat_prognoz" class="form-control form-control-sm select2" data-placeholder="Выберите категорию товара">
            <option label="Категория"></option>
            <?php foreach ($mas as $key => $value) {
                if(strripos($value, 'SALE') === FALSE){
                ?>
            <option value="<?=$key?>"><?=$value?></option>
                <?php } } ?>
        </select>
    </div>
    <div class="form-group">
        <select name="interval_prognoz" id="interval_prognoz" class="form-control form-control-sm select2" data-placeholder="Выберите интервал">
            <option label="Интервал"></option>
            <option value="1" selected>Недели</option>
            <option value="2">Дни</option>
            <option value="3">Месяцы</option>
        </select>
    </div>
    <div class="form-group">
    <button type="submit" name="toChart" class="btn btn-primary ">Построить</button>
    
    </div>
</form>
                        </div>
                    </div>
              <div class="card-body diagram_prognoz">
                <!--  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>-->
                <div id="prognoz"></div>
              </div><!-- list-group -->
             <div class="card-footer text-right">
                 <button type="button" onclick="ToExcel($('#form_prognoz').serialize());" name="toExcel" class="btn btn-primary btn-sm">Скачать категории по грейдам</button>
    <button type="button" onclick="testik_dey($('#form_prognoz').serialize());" name="toExcelDey" class="btn btn-primary btn-sm">Скачать прогноз категорий по дням</button>
    <button type="button" onclick="balance_brand_in_category_to_excel($('#form_prognoz').serialize());" name="brandexcel" class="btn btn-primary btn-sm">Скачать буфер по брендам</button>
    <button type="button" onclick="prognoz_category_to_excel($('#form_prognoz').serialize());" name="brandexcel_cat" class="btn btn-success btn-sm">Прогноз по категориям за период в таблицу</button>
             
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
          </div>
        <div class="row row-sm mg-x-0 mg-t-20">
          <div class="col-xl-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Прогнозы по брендам</h6>
                  </div>
                    <div>
<form class="form-inline" name="form_prognoz_brand" id="form_prognoz_brand">
  <div class="form-group">
    <input type="date"  class="form-control form-control-sm" onchange="DistinctBrand();" name="from_prognoz_brand" id="from_prognoz_brand" value="<?=date("Y-m-d", strtotime('-60 days'))?>" min="<?=date("Y-m-d", strtotime('-1 Year'))?>" max="<?=date("Y-m-d")?>" >
    
  </div>
    
  <div class="form-group">
      <input type="date" class="form-control form-control-sm" onchange="DistinctBrand();" id="to_prognoz_brand" name="to_prognoz_brand"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>">
  </div>
    <div class="form-group">
        <select name="brand_prognoz" id="brand_prognoz" class="form-control form-control-sm " data-placeholder="Выберите категорию товара">
            <option label="Бренд"></option>
        </select>
    </div>
    <div class="form-group">
        <select name="interval_prognoz_brand" id="interval_prognoz_brand" class="form-control form-control-sm select2" data-placeholder="Выберите интервал">
            <option label="Интервал"></option>
            <option value="1" selected>Недели</option>
            <option value="2">Дни</option>
        </select>
    </div>
    <div class="form-group">
    <button type="submit" name="toChartBrand" class="btn btn-primary">Построить</button>
   <!-- <button type="button" onclick="ToExcel($('#form_prognoz').serialize());" name="toExcel" class="btn btn-primary btn-sm">Скачать</button>
    <button type="button" onclick="testik_dey($('#form_prognoz').serialize());" name="toExcelDey" class="btn btn-primary btn-sm">Скачать по дням</button>-->
    
    </div>
</form>
                        </div>
                    </div>
              <div class="card-body diagram_prognoz">
                <!--  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>-->
                <div id="prognoz_brand"></div>
              </div><!-- list-group -->
              <div class="card-footer text-right">
                <button type="button" onclick="balance_brand_all_to_excel($('#form_prognoz_brand').serialize());" name="brandallexcel" class="btn btn-primary btn-sm">Скачать прогноз</button>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
          </div>
         <!--Проценти-->
<div class="row row-sm mg-x-0 mg-t-20">
    <div class="col-sm-12">
        <div class="card  p-4 ">
            <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                <div class="mg-b-20 mg-sm-b-0">
          <h6 class="card-body-title mb-2">Продажи по процентам</h6>
          </div>
            <div class="input-group  ">
            <div class="input-group-prepend">
    <span class="input-group-text" id="">От</span>
  </div>
    <input type="date"  class="form-control form-control-sm" name="from_procent" id="from_procent" value="<?=date("Y-m-d", strtotime('-60 days'))?>" min="<?=date("Y-m-d", strtotime('-1 Year'))?>" max="<?=date("Y-m-d")?>" >
   <input type="date" class="form-control form-control-sm" id="to_procent" name="to_procent"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-6 month'))?>" max="<?=date("Y-m-d")?>">
  <div class="input-group-append">
    <span class="input-group-text" id="">До</span>
  </div>
    </div>
          </div>
            <div class="card-body">
        <div class="row row-sm ">
             <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Общая</h6>
                  <span class="mg-b-20 mg-sm-b-30">Вся товарная группа</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki111">
		<div id="oborot111"></div>
              </div>
                <div class="card-footer">
                    <button type="button" name="procent" onclick="ProcentToExcel(this);" class="btn btn-primary btn-sm">Скачать</button>
                </div>
            </div><!-- card -->
          </div><!-- col-3 -->
          </div>
                </div>
        </div>
    </div>
</div>
    <script src="<?=$this->files?>views/template/js/resize_sensor.js"></script>   
    <script src="<?=$this->files?>views/template/lib/jquery.sparkline.bower/jquery.sparkline.min.js"></script>
   <script src="<?=$this->files?>views/template/lib/d3/d3.js"></script>
   <script src="<?=$this->files?>views/template/lib/chart.js/chart.js"></script>
   <script src="<?=$this->files?>views/template/js/dashboard.js?v=1"></script>
    <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.js"></script>
   <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.pie.js"></script>	
    <script src="<?=$this->files?>views/template/lib/raphael/raphael.min.js"></script>
 <script src="<?=$this->files?>views/template/lib/morris.js/morris.js"></script>   
  <script src="<?=$this->files?>views/template/js/home.js?v=4.5.26"></script> 
  <script src="<?=$this->files?>views/template/js/prognoz.js?v=1.2.27"></script>