<?php 
$golovni_category = Shopcategories::find('Shopcategories', ['active'=> 1, 'parent_id'=>0, 'id not in (106, 85, 267, 146)']);
$graid = wsActiveRecord::useStatic('BrandGryde')->findAll();

?>  
 <div class="row row-sm mg-x-0 mg-t-20">
    <div class="col-sm-12">
                <div class="card  px-4  mb-4">
                    <div class="card-body">
                         <h6 class="card-body-title">Интервалы:</h6>
                          <span class="mg-b-20 mg-sm-b-30">...</span>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                 <div class="input-group">
    <span class="input-group-addon" id="">От</span>          
    <input type="date"  class="form-control form-control-sm" name="from_prognoz" id="from_oborot" value="<?=date("Y-m-d", strtotime('-60 days'))?>"  max="<?=date("Y-m-d")?>" >
    </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                            <div class="input-group">
                            <span class="input-group-addon" id="">До</span>
    <input type="date" class="form-control form-control-sm" id="to_oborot" name="to_prognoz"  value="<?=date("Y-m-d")?>"  max="<?=date("Y-m-d")?>">
    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>
<div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  text-center">
                  <legend>МАРЖА</legend>
                  <div class="card  px-4  mb-4">
                      <div class="card-body">
                          <div class="row">
                               <div class="col-sm-12 col-md-6">
                                 <div class="input-group">
    <span class="input-group-addon" >Групировать по:</span>    
    <select name="group_by" id="group_by" class="form-control form-control-sm select2">
        <option value="1">Товар</option>
        <option value="2">Бренд</option>
        <option value="3">Модель</option>
        <option value="4">Бренд+Модель</option>
    </select>
 </div>
</div>
                              <div class="col-sm-12 col-md-2">
                                  <div class="form-group">
                                  <label class="ckbox">
  <input type="checkbox" id="ostatok">
  <span>Включить остаток</span>
</label>
                                      </div>
                              </div>
                              <div class="col-sm-12 col-md-2">
                          
                          <span class="input-group-append" >
          <button class="btn btn-outline-primary" onclick="return TopExcelMarga();" type="button">Скачать маржу</button>
    </span>
                                  </div>
                              </div>
                      </div>
              </div>
</div>
</div>
<div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  ">
                  <legend class="text-center">"ABC" Анализ</legend>
                  <div class="card  px-4  mb-4">
                      <div class="card-body">
                        <h6 class="card-title">По остаткам:</h6>
                                    <span class="mg-b-20 mg-sm-b-30">Товары в остатке на текущий момент. Разделение на "ABC" зависит от колличества дней на сайте. А < 30, B 30 < 60, C 60 <  </span>
                                    <br><br>
                          <div class="row">
                              <div class="col-sm-12 col-lg-3">
                                  <div class="input-group">
                                  <span class="input-group-addon" >Группа:</span>    
    <select name="abc-group-ostatok" id="abc-group-ostatok" class="form-control form-control-sm select2">
         <option value="0-10000">Все</option>
        <option value="0-30">A</option>
        <option value="30-60">B</option>
        <option value="60-10000">C</option>
    </select>
                                  </div>
                              </div>
                               <div class="col-sm-12 col-lg-9">
                                   
                                 <div class="input-group">
                                      <span class="input-group-addon" >Группировать:</span> 
    <select name="abc-group_by-ostatok" id="abc-group_by-ostatok" class="form-control form-control-sm select2">
        <option value="tovar">Товар</option>
        <option value="model">Модель</option>
        <option value="category">Категория</option>
        <option value="brand">Бренд</option>
        <option value="graid">Грейд</option>
        
    </select>
    
     <div class="input-group-append" >
          <button class="btn btn-outline-primary" onclick="return ABCostatok($('#abc-group-ostatok'), $('#abc-group_by-ostatok'), $('#result_abc_ostatok'), 'view');" type="button">Смотреть</button><button class="btn btn-outline-success" onclick="return ABCostatok($('#abc-group-ostatok'),  $('#abc-group_by-ostatok'), $('#result_abc_ostatok'), 'download');" type="button">Скачать</button>
    </div>
 </div>
                                         
</div>
                              
<div class="col-sm-12 p-3" id="result_abc_ostatok"></div>
       
                              
                              </div>
                      </div>
              </div>
<div class="card  px-4  mt-4 mb-4">
                      <div class="card-body">
                        <h6 class="card-title">По продажам:</h6>
                                    <span class="mg-b-20 mg-sm-b-30">Продажи за интервал времени. Разделение на "ABC" зависит от % маржи. А > 60%, 40% < B < 60%, C < 40%  </span>
                                     <br><br>
                          <div class="row">
                             
                               <div class="col-sm-12 col-md-3 col-lg-3">
                                 
                                 <div class="input-group">
    <span class="input-group-addon" >Группа:</span>    
    <select name="abc-group-order" id="abc-group-order" class="form-control form-control-sm select2">
        <option value="all">Все</option>
        <option value="a">A</option>
        <option value="b">B</option>
        <option value="c">C</option>
    </select>
    
 </div>
                                       
</div>
<div class="col-sm-12 col-md-9 col-lg-9">
                                 
                                 <div class="input-group">
    <span class="input-group-addon" >Группировать:</span> 
    <select name="abc-group_by-order" id="abc-group_by-order" class="form-control form-control-sm select2">
        <option value="tovar">Товар</option>
        <option value="model">Модель</option>
        <option value="category">Категория</option>
        <option value="brand">Бренд</option>
        <option value="graid">Грейд</option>
        
    </select>
     <span class="input-group-append" >
          <button class="btn btn-outline-primary" onclick="return ABCorder($('#abc-group-order'),$('#abc-group_by-order'), $('#result_abc_order'), 'view');" type="button">Смотреть</button><button class="btn btn-outline-success" onclick="return ABCorder($('#abc-group-order'), $('#abc-group_by-order'), $('#result_abc_order'), 'download');" type="button">Скачать</button>
    </span>
                                 </div>
                                    </div>

                              
<div class="col-sm-12 p-3" id="result_abc_order"></div>
       
                              
                              </div>
                      </div>
              </div>
</div>
</div>
        <div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  text-center">
                  <legend>ТОП</legend>
              </div>
            <div class="col-sm-12">
                <div class="card  px-4  mb-4">
                    <div class="card-body">
                        <div class="row">
                         <!--   <div class="col-sm-12 col-md-6">
                  <div class="btn-group" role="group" aria-label="Basic example">
                  <button id="top_dey" class="btn btn-sm btn-secondary tx-12"  onclick="return Top('dey');">День</button>
                  <button id="top_ned" class="btn btn-sm btn-secondary tx-12" onclick="return Top('ned');">Неделя</button>
                  <button id="top_mes" class="btn btn-sm btn-secondary tx-12" onclick="return Top('mes');">Месяц</button>
                </div>
                            </div>-->
                            <div class="col-sm-12 col-md-2">
                                 <div class="input-group">
    <span class="input-group-addon" >Топ:</span>    
    <select name="limit" id="top_limit" class="form-control form-control-sm select2">
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
 </div>
</div>
                         <div class="col-sm-12 col-md-3">
                               <div class="input-group">
    <span class="input-group-addon" >ТИП:</span>    
    <select name="top_type" id="top_type" class="form-control form-control-sm select2">
      <option value="1">Категории</option>
        <option value="2">Бренды</option>
    </select>
                               </div>
                         </div>                     
<div class="col-sm-12 col-md-6">
    <div class="input-group">
    <span class="input-group-addon" >Единицы:</span>    
    <select name="ed" id="top_ed" class="form-control form-control-sm select2">
      <option value="1">шт.</option>
        <option value="2">грн.</option>
        <option value="3">маржа</option>
    </select>
     <span class="input-group-append" >
         <button class="btn btn-outline-secondary" onclick="return Top();" type="button">Отобразить</button>
        
     </span> 
    <span class="input-group-append" >
          <button class="btn btn-outline-primary" onclick="return TopExcel();" type="button">Скачать</button>
    </span>
 </div>
</div>
                        </div>
                    </div>
                    <div class="card-body">
                         <h6 class="card-body-title">Результат:</h6>
                         <div class="top_res">
                              <canvas id="res_top"></canvas>
                         </div>
                        
                    </div>
                </div>
            </div>
        </div>
<!--Оборачиваемость-->
        <div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  text-center">
                  <legend>Оборачиваемость</legend>
              </div>
        <div class="col-sm-12">
        <div class="card  p-4 ">
            <div class="card-body diagram_ostatki">
                <h6 class="card-body-title">Общая</h6>
                  <span class="mg-b-20 mg-sm-b-30">Вся товарная группа</span>
		<div id="oborot"></div>
                <div id="oborot_grn"></div>
            </div>
                <div class="card-footer">
                    <form class="form-inline" name="form_oborot_all" id="form_oborot_all" style="display:inline-block; margin-right: 15px;">
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_all">
    <div class="form-group">
    <button type="submit" name="oborot_go" class="btn btn-primary ">Построить понедельно</button> </div>
</form>
                     <form class="form-inline" name="oborot_all_monch" id="form_oborot_all_grn" style="display:inline-block; margin-right: 15px;">
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_all_monch">
    <div class="form-group">
    <button type="submit" name="oborot_go_grn" class="btn btn-primary ">Построить помесячно</button> </div>
</form>
                </div>
            </div><!-- card -->
          </div><!-- col-3 -->
             <div class="col-sm-12">
            <div class="card overflow-hidden mg-t-20 p-4">
              <div class="card-body diagram_ostatki">
                   <h6 class="card-body-title">Главные категории</h6>
                  <span class="mg-b-20 mg-sm-b-30">Товары по категориям</span>
		<div id="oborot_root"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_root" id="form_oborot_root">
  <div class="form-group">
       <div class="input-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
           <span class="input-group-addon p-0">
               <button type="submit" name="oborot_go_root" class="btn btn-primary">Построить</button>
           </span>    
       </div>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_root_category">
   <input type="text" class="form-control form-control-sm" hidden  name="cat_prognoz"  value="0">
  </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
          <div class="col-xl-12">
            <div class="card overflow-hidden mg-t-20 p-4">
              <div class="card-body diagram_ostatki">
                   <h6 class="card-body-title">Подкатегории</h6>
                  <span class="mg-b-20 mg-sm-b-30">Товары по подкатегориям</span>
		<div id="oborot_category"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_category" id="form_oborot_category">
  <div class="form-group">
        <select name="cat_prognoz" id="cat_oborot_category" class="form-control  select2" data-placeholder="Выберите категорию">
            <option label="Категория"></option>
            <?php foreach ($golovni_category as $cat){ ?>
            <option value="<?=$cat->id?>"><?=$cat->getRoutez()?></option>
                <?php } ?>
        </select>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_category">
  </div>
    <div class="form-group" >
        <div class="input-group">
         <select name="zone"  data-placeholder="Зона" class="form-control  select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
        <span class="input-group-addon p-0">
             <button type="submit" name="oborot_go_category" class="btn btn-primary ">Построить</button>
           </span> 
        </div>
        </div>

</form>

              </div><!-- card-footer -->
          </div>
        </div>
         <div class="col-sm-12">
            <div class="card overflow-hidden mg-t-20 p-4">
              <div class="card-body diagram_ostatki_brand">
                  <h6 class="card-body-title">Бренды</h6> 
                  <span class="mg-b-20 mg-sm-b-30">Товары по брендам</span>
		<div id="oborot_brand"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_brand" id="form_oborot_brand">
  <div class="form-group">
      <div class="input-group">
          <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
          <span class="input-group-addon p-0">
              <button type="submit" name="oborot_go_brand" class="btn btn-primary">Построить</button>
          </span>
      </div>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_brand">
  </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
          <div class="col-sm-12">
            <div class="card overflow-hidden mg-t-20 p-4">
              <div class="card-body diagram_ostatki_brand">
                  <h6 class="card-body-title">Грейды</h6> 
                  <span class="mg-b-20 mg-sm-b-30">Товары по грейдам</span>
		<div id="oborot_graid"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_graid" id="form_oborot_graid">
  <div class="form-group">
      <div class="input-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
          <span class="input-group-addon p-0">
              <button type="submit" name="oborot_go_brand" class="btn btn-primary">Построить</button>
          </span>
      </div>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_graid">
  </div>
</form>
              </div><!-- card-footer -->
          </div>
        </div>
           <div class="col-sm-12">
            <div class="card overflow-hidden mg-t-20 p-4">
              <div class="card-body diagram_ostatki_brand">
                  <h6 class="card-body-title">Грейды с категориями</h6> 
                  <span class="mg-b-20 mg-sm-b-30">Товары по грейдам с разворотом на категории</span>
		<div id="oborot_graid"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_graid_category" id="form_oborot_graid_category">
                     <div class="form-group">
        <select name="graid"  class="form-control  select2" data-placeholder="Выберите грейд">
            <option label="Грейд"></option>
            <?php foreach ($graid as $b){ ?>
            <option value="<?=$b->greyd_id?>"><?=$b->name?></option>
                <?php } ?>
        </select>
  </div>
                  <div class="form-group">
        <select name="category"  class="form-control  select2" data-placeholder="Выберите категорию">
            <option label="Категория"></option>
            <?php foreach ($golovni_category as $cat){ ?>
            <option value="<?=$cat->id?>"><?=$cat->getRoutez()?></option>
                <?php } ?>
        </select>
  </div>
  <div class="form-group">
      <div class="input-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
          <span class="input-group-addon p-0">
              <button type="submit" name="oborot_go_brand_category" class="btn btn-primary">Построить</button>
          </span>
      </div>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_graid_category">
  </div>
</form>
              </div><!-- card-footer -->
          </div>
        </div>
                </div>
          </div><!--row-->
         <!--/Оборачиваемость-->
    <script src="<?=$this->files?>views/template/js/resize_sensor.js"></script>   
    <script src="<?=$this->files?>views/template/lib/jquery.sparkline.bower/jquery.sparkline.min.js"></script>
   <script src="<?=$this->files?>views/template/lib/d3/d3.js"></script>
   <script src="<?=$this->files?>views/template/lib/chart.js/chart.js"></script>
   <script src="<?=$this->files?>views/template/js/dashboard.js?v=1"></script>
    <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.js"></script>
   <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.pie.js"></script>	
    <script src="<?=$this->files?>views/template/lib/raphael/raphael.min.js"></script>
 <script src="<?=$this->files?>views/template/lib/morris.js/morris.js"></script>   
  <script src="<?=$this->files?>views/template/js/home.js?v=4.5.7"></script> 
   <script src="<?=$this->files?>views/template/js/oborot.js?v=2.3"></script> 

 