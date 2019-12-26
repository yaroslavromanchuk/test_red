<?php if(true){ ?>
 <div class="row row-sm mg-x-0">
     <div class="col-sm-12">
     <div class="card p-3">
          <h6 class="card-body-title mb-2">Оформленные заказы</h6>
          <div class="row">
 <?php if(count($this->orders_days['koll'])){ ?>
          <div class="col-sm-6 col-xl-3">
            <div class="card pd-20 bg-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">За сегодня</h6>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_days['koll'])?></span>
              </div><!-- card-body -->
	<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?=Number::formatFloat($this->orders_days['summa'], 2)?></h5>
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }

if(count($this->orders_week['koll'])){	?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card pd-20 bg-info">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за неделю</h6>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_week['koll'])?></span>
              </div><!-- card-body -->
		<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?=Number::formatFloat($this->orders_week['summa'], 2)?></h5>
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
        if(count($this->orders_month['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-purple">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за месяц</h6>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2" ><?=implode(',', $this->orders_month['koll'])?></span>
              </div><!-- card-body -->
		<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?=Number::formatFloat($this->orders_month['summa'], 2)?></h5>
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
if(count($this->orders_year['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-sl-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за год</h6>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_year['koll'])?></span>
              </div><!-- card-body -->
		<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?=Number::formatFloat($this->orders_year['summa'], 2)?></h5>
            </div><!-- card -->
          </div><!-- col-3 -->
		   <?php }  ?>
          </div>
         </div>
          </div>
        </div><!-- row -->
<?php }
if(true){ ?>
    <div class="row row-sm mg-x-0">
     <div class="col-sm-12">
     <div class="card p-3">
          <h6 class="card-body-title mb-2">Оплаченные заказы</h6>
          <div class="row">
 <?php if(count($this->orders_days_op['koll'])){ ?>
          <div class="col-sm-6 col-xl-3">
            <div class="card pd-20 bg-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за сегодня</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#days"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_days_op['koll'])?></span>
              </div><!-- card-body -->
	<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_days_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_days_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				echo Number::formatFloat($am+$dep, 2).' грн.';
				?></h5>
				 <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }

if(count($this->orders_week_op['koll'])){	?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card pd-20 bg-info">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за неделю</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#week"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_week_op['koll'])?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_week_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_week_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				echo Number::formatFloat($am+$dep, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
        if(count($this->orders_month_op['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-purple">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за месяц</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#month"  ><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2" ><?=implode(',', $this->orders_month_op['koll'])?></span>
              </div><!-- card-body -->
			   <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_month_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_month_op['dep'] as $k => $koll){
				$dep += $koll;
				}
					echo Number::formatFloat($am+$dep, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
if(count($this->orders_year_op['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-sl-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за год</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#year"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_year_op['koll'])?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_year_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_year_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				echo Number::formatFloat($am+$dep, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
		   <?php }  ?>
		   </div>
		   </div>
		   </div>
		   </div>
    
<?php } ?>
        <div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1  text-center">
                  <legend>ЗАКАЗЫ</legend>
              </div>
          <div class="col-xl-4 p-2">
            <div class="card p-2">
              <div class="card-header bg-transparent pd-y-20 pd-x-5 d-sm-flex align-items-center justify-content-between">
                <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">ОФОРМЛЕНИ ЗАКАЗОВ</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
                  <div class="d-block">
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button  id="h" class="btn btn-sm btn-secondary tx-12 order active">День</button>
                  <button   class="btn btn-sm btn-secondary tx-12" onClick="nTime();">Неделя</button>
                  <button  id="m" class="btn btn-sm btn-secondary tx-12" onClick="mTime();" >Месяц</button>
                </div>
				<div  class="btn-group n_time" style="display:none;" role="group" aria-label="Basic example">
				<button  id="n_h" class="btn btn-sm btn-secondary tx-12 order ">Часы</button>
                                <button  id="n_d" class="btn btn-sm btn-secondary tx-12 order ">Дни</button>
				</div>
				<div  class="btn-group m_time" role="group" style="display:none;" aria-label="Basic example">
				<button  id="m_h" class="btn btn-sm btn-secondary tx-12 order ">Часы</button>
                                <button  id="m_d" class="btn btn-sm btn-secondary tx-12 order ">Дни</button>
				</div>
                  </div>
              </div><!-- card-header -->
              <div class="card-body bd-color-gray-lighter">
                <div class="row no-gutters tx-center">
                  <div class="col-12 col-sm-10  tx-left">
                    <p class=" tx-12 lh-8 mg-b-5">Диаграмма отображает количество заказов за период. Исключены(Отменён, Возврат).</p>
                  </div><!-- col-4 -->
                  <div class="col-6 col-sm-2 ">
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="col_order_5" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase">Заказов</p>
                  </div>
                </div><!-- row -->
              </div><!-- card-body -->
              <div class="card-body pd-10 diagram_5">
                  <button type="button" onclick=" $(this).hide('slow'); rickshaw2('h')" class="btn btn-outline-primary">Отобразить</button>
				<canvas id="rickshaw2" > </canvas>
              </div><!-- card-body -->
            </div><!-- card -->
		</div>
            <div class="col-xl-4 p-2">
            <div class="card pd-20">
              <h6 class="card-body-title">Средний чек</h6>
              <div class="card-body p-0">
                  <div class="row row no-gutters tx-center">
                      <div class="col-12 col-sm-10  tx-left">
                            <p class="mg-b-20 mg-sm-b-30">Отображается средний чек (в грн.) за последние 30 дней.</p>
                      </div>
                   <div class="col-6 col-sm-2">
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5 sr_chek"></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase">Среднее</p>
                     </div>
                  </div>
              </div>
              <div  class="card-body">
                  <button type="button" onclick=" $(this).hide('slow'); chek()" class="btn btn-outline-primary">Отобразить</button>
                  <canvas class="chek" >
                      <span class="label"><?=implode(',', $this->chek['label'])?></span>
                      <span class="date"><?=implode(',', $this->chek['date'])?></span>
                  </canvas>
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
	<div class="col-xl-4 p-2">
            <div class="card p-2">
			 <div class="card-header bg-transparent pd-y-20 pd-x-5 d-sm-flex align-items-center justify-content-between">
			  <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">Оборот товаров</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
                             <div class="d-block">
				 <div class="btn-group" role="group" aria-label="Basic example">
                  <button  id="h_a" class="btn  btn-sm btn-secondary tx-12 articles active">День</button>
                  <button   class="btn btn-sm btn-secondary tx-12" onClick="naTime();">Неделя</button>
                  <button   class="btn btn-sm btn-secondary tx-12" onClick="maTime();" >Месяц</button>
                </div>
				<div  class="btn-group n_a_time" style="display:none;" role="group" aria-label="Basic example">
				<button  id="n_h_a" class="btn btn-sm btn-secondary tx-12 articles ">Часы</button>
                                <button  id="n_d_a" class="btn btn-sm btn-secondary tx-12 articles ">Дни</button>
				</div>
				<div  class="btn-group m_a_time" role="group" style="display:none;" aria-label="Basic example">
				<button  id="m_h_a" class="btn btn-sm btn-secondary tx-12 articles ">Часы</button>
                                <button  id="m_d_a" class="btn btn-sm btn-secondary tx-12 articles ">Дни</button>
				</div>
                             </div>
			  </div>
			  <div class="card-body pd-0 bd-color-gray-lighter">
                <div class="row no-gutters tx-center">
                 <!-- <div class="col-12 col-sm-10  tx-left">
                    <p class="pd-l-20 tx-12 lh-8 mg-b-5">Диаграмма отображает движение товара по пунктам выдачи</p>
                  </div>--><!-- col-4 -->
				 <div class="col-12 col-sm-11 tx-right">
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="col_articles_5" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase">Товаров</p>
                  </div>
                </div><!-- row -->
                <div class=" diagram_8 ">
                    <button type="button" onclick=" $(this).hide('slow'); chartLine('h_a')" class="btn btn-outline-primary">Отобразить</button>
               <canvas id="articles_shop_3" ></canvas>
		</div>
              </div>
		
            </div><!-- card -->

          </div><!-- col-8 -->
          <div class="col-xl-4 p-2 d-none">
            <div class="card p-2">
                <div class="m-2">
                 <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">Жизненный цикл заказа</h6>
                 </div>
		<div class="card-header bg-transparent p-2 d-sm-flex align-items-center justify-content-between">
			
<div class="btn-group-sm" role="group" aria-label="Basic example">

<input type="date"  class="form-control-sm" name="from_dely" id="from_dely" value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>" >

<input type="date" class="form-control-sm" name="to_dely" id="to_dely"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>">
</div>
<div class="btn-group-sm" role="group" aria-label="Basic example">
<!--<button type="button" onclick=" delivery_time(350, $('#from_dely').val(), $('#to_dely').val());" class="btn btn-outline-primary btn-sm">Отобразить</button>-->
                    
 <button type="button" onclick=" delivery_time(35, $('#from_dely').val(), $('#to_dely').val());" class="btn btn-outline-primary btn-sm">Магазины</button>
     <button type="button" onclick=" delivery_time(4, $('#from_dely').val(), $('#to_dely').val());" class="btn btn-outline-primary btn-sm">Укр.Почта</button>
     <button type="button" onclick=" delivery_time(816, $('#from_dely').val(), $('#to_dely').val());" class="btn btn-outline-primary btn-sm">Нова Почта</button>
     <button type="button" onclick=" delivery_time(9, $('#from_dely').val(), $('#to_dely').val());" class="btn btn-outline-primary btn-sm">Курьер</button>

                 <!-- <button  id="h_a" class="btn  btn-sm btn-secondary tx-12 articles active">День</button>
                  <button   class="btn btn-sm btn-secondary tx-12" onClick="naTime();">Неделя</button>
                  <button   class="btn btn-sm btn-secondary tx-12" onClick="maTime();" >Месяц</button>-->
</div>
				<!--<div  class="btn-group n_a_time" style="display:none;" role="group" aria-label="Basic example">
				<button  id="n_h_a" class="btn btn-sm btn-secondary tx-12 articles ">Часы</button>
                                <button  id="n_d_a" class="btn btn-sm btn-secondary tx-12 articles ">Дни</button>
				</div>
				<div  class="btn-group m_a_time" role="group" style="display:none;" aria-label="Basic example">
				<button  id="m_h_a" class="btn btn-sm btn-secondary tx-12 articles ">Часы</button>
                                <button  id="m_d_a" class="btn btn-sm btn-secondary tx-12 articles ">Дни</button>
				</div>-->
			  </div>
			  <div class="card-body pd-0 bd-color-gray-lighter">
                <div class="row no-gutters tx-center">
                  <div class="col-9 col-sm-10  tx-left">
                     <p class=" tx-12 lh-8 mg-b-5">Жизненный цикл заказов по способу доставки за выбраный интервал времени.</p>
                  </div><!-- col-4 -->
		<div class="col-3 col-sm-11 tx-right">
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="col_dely_5" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase">Дней</p>
                  </div>
                  <div class=" col-12 diagram_8_dely ">
               <canvas id="dely_shop_3"></canvas>
		</div>
                </div><!-- row -->
                
              </div>
		
            </div><!-- card -->

          </div><!-- col-8 -->
          </div>
          <div class="row row-sm mg-x-0 mg-t-20">
              <div class="col-12 my-1  text-center">
                  <legend>ОСТАТКИ ТОВАРА</legend>
              </div>
              <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Остатки товара</h6>
                  <span class="mg-b-20 mg-sm-b-30">Отображается остатки за последние 30 дней  в 03:00</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
		<canvas id="ostatki"  ></canvas>
              </div><!-- list-group -->
             <div class="card-footer">
                 
                 
                  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>
                <!--<a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>-->
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
           <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Сравнение остатков товара за период</h6>
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
      <input id="one-date-from" name="one_date_from" type="date" onchange="$('#two-date-from').val(this.value)" placeholder="2019-04-05" class="form-control  form-control-sm" value="<?=date("Y-m-d", strtotime('-30 days'))?>" min="<?=date("Y-m-d", strtotime('-61 days'))?>" max="<?=date("Y-m-d", strtotime('-2 days'))?>" required="">
  <span class="help-block">Выберите первую дата</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group col-md-6">
  <label class="col-md-2 col-form-label" for="one-date-to">До</label>  
  <div class="col-md-9">
      <input id="one-date-to" name="one_date_to" type="date" placeholder="2019-05-05" onchange="$('#two-date-to').val(this.value)"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>" class="form-control form-control-sm" required="">
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
        <div  class="row row-sm mg-x-0 mg-t-20">
            <div class="col-12 my-1 text-center">
                  <legend>УЦЕНКА</legend>
              </div>
            <div class="col-xl-6 mg-t-20">
            <div class="card pd-20 pd-sm-40">
              <h6 class="card-body-title">Статистика уценки</h6>
			  <div class="card-body pd-0 bd-color-gray-lighter">
			  <div class="row no-gutters tx-center">
                  <div class="col-12 col-sm-10  tx-left">
                    <p class="pd-l-20 tx-12 lh-8 mg-b-5">Отображаются история уценки товаров.</p>
                  </div><!-- col-4 -->
				 <div class="col-6 col-sm-2 ">
				 <!--<p class="tx-11 mg-b-5 tx-uppercase">Всего </p>
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="koll_ucenka" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase"> Товаров</p>-->
                  </div>
                </div><!-- row -->
                <div class=" pd-10 diagram_9">
                     <button type="button" onclick=" $(this).hide('slow'); Ucenka()" class="btn btn-outline-primary">Отобразить</button>
               <canvas id="ucenka"  ></canvas>
			  </div>
			  </div>
              
            </div><!-- card -->
          </div><!-- col-6 -->
          <div class="col-xl-3 mg-t-20">
            <div class="card pd-20 pd-sm-10">
                <div class="card-header bg-transparent pd-y-10 d-sm-flex align-items-center justify-content-between">
                   
                <div class="col-sm-10  col-lg-8 tx-left">
              <h6 class="card-body-title">Уценка</h6>
               <p class="tx-12 lh-8 mg-b-5">Отображены товары которые есть в наличии.</p>
                </div>
                   <div class="col-sm-2 col-lg-4 text-right">
				 <p class="tx-11 mg-b-5 tx-uppercase">Всего </p>
                    <p class="tx-inverse tx-lato tx-bold mg-b-5" id="koll_ucenka" ></p>
                    <p class="tx-11 mg-b-5 tx-uppercase"> Товаров</p>
                  </div>
           
              </div>
		<div class="card-body pd-0 bd-color-gray-lighter">
                    <button type="button" onclick=" $(this).hide('slow'); Ucenka_2()" class="btn btn-outline-primary">Отобразить</button>
		<div class="card pd-10 diagram_9 ht-200 ht-sm-250" id="ucenka_2"></div>
		</div>
              
            </div><!-- card -->
          </div><!-- col-6 -->
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
    <input type="date"  class="form-control form-control-sm" name="from_prognoz" id="from_prognoz" value="<?=date("Y-m-d", strtotime('-60 days'))?>" min="<?=date("Y-m-d", strtotime('-1 Year'))?>" max="<?=date("Y-m-d")?>" >
    
  </div>
    
  <div class="form-group">
   <input type="date" class="form-control form-control-sm" id="to_prognoz" name="to_prognoz"  value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>">
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
        <!--Оборачиваемость-->
        <div class="row row-sm mg-x-0 mg-t-20">
            <div class="col-sm-12">
        <div class="card  p-4 ">
            <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                <div class="mg-b-20 mg-sm-b-0">
          <h6 class="card-body-title mb-2">Оборачиваемость</h6>
          </div>
            <div class="input-group  ">
                   <div class="input-group-prepend">
    <span class="input-group-text" id="">От</span>
  </div>
    <input type="date"  class="form-control form-control-sm" name="from_prognoz" id="from_oborot" value="<?=date("Y-m-d", strtotime('-60 days'))?>"  max="<?=date("Y-m-d")?>" >
   <input type="date" class="form-control form-control-sm" id="to_oborot" name="to_prognoz"  value="<?=date("Y-m-d")?>"  max="<?=date("Y-m-d")?>">
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
              <div class="card-body diagram_ostatki">
		<div id="oborot"></div>
              </div>
                <div class="card-footer">
                    <form class="form-inline" name="form_oborot_all" id="form_oborot_all">
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_all">
    <div class="form-group">
    <button type="submit" name="oborot_go" class="btn btn-primary btn-sm">Построить</button> </div>
</form>
                </div>
            </div><!-- card -->
          </div><!-- col-3 -->
             <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Главные категории</h6>
                  <span class="mg-b-20 mg-sm-b-30">Товары по категориям</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
		<div id="oborot_root"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_root" id="form_oborot_root">
  <div class="form-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_root_category">
   <input type="text" class="form-control form-control-sm" hidden  name="cat_prognoz"  value="0">
  </div>
    <div class="form-group">
    <button type="submit" name="oborot_go_root" class="btn btn-primary btn-sm">Построить</button> </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
          <div class="col-xl-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Подкатегории</h6>
                  <span class="mg-b-20 mg-sm-b-30">Товары по подкатегориям</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
		<div id="oborot_category"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_category" id="form_oborot_category">
  <div class="form-group">
        <select name="cat_prognoz" id="cat_oborot_category" class="form-control form-control-sm select2" data-placeholder="Выберите категорию товара">
            <option label="Категория"></option>
            <?php foreach (Shopcategories::find('Shopcategories', ['active'=> 1, 'parent_id'=>0, 'id not in (106, 85, 267, 146)']) as $cat ) {
                ?>
            <option value="<?=$cat->id?>"><?=$cat->getRoutez()?></option>
                <?php  } ?>
        </select>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_category">
  </div>
    <div class="form-group" >
         <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
        </div>
    <div class="form-group">
    <button type="submit" name="oborot_go_category" class="btn btn-primary btn-sm">Построить</button> </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
         <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Бренды</h6> 
                  <span class="mg-b-20 mg-sm-b-30">Товары по брендам</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki_brand">
		<div id="oborot_brand"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_brand" id="form_oborot_brand">
  <div class="form-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_brand">
  </div>
    <div class="form-group">
    <button type="submit" name="oborot_go_brand" class="btn btn-primary btn-sm">Построить</button> </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
          <div class="col-sm-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Грейды</h6> 
                  <span class="mg-b-20 mg-sm-b-30">Товары по грейдам</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki_brand">
		<div id="oborot_graid"></div>
              </div>
                <div class="card-footer">
              <form class="form-inline" name="form_oborot_graid" id="form_oborot_graid">
  <div class="form-group">
      <select name="zone"  data-placeholder="Зона" class="form-control form-control-sm select2">
           <option label="Зона"></option>
           <option value="0">Все</option>
            <option value="28">Красная</option>
            <option value="21">Синяя</option>
            <option value="1">Зеленая</option>
      </select>
   <input type="text" class="form-control form-control-sm" hidden  name="method"  value="oborot_graid">
  </div>
    <div class="form-group">
    <button type="submit" name="oborot_go_brand" class="btn btn-primary btn-sm">Построить</button> </div>
</form>
                
                
              </div><!-- card-footer -->
          </div>
        </div>
            
              
            </div>
            </div>
          </div>
                </div>
          </div>
         <!--/Оборачиваемость-->
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
         
        <div class="row row-sm mg-x-0 mg-t-20">
              
<div class="col-xl-3 d-none">
            <div class="card overflow-hidden">
			 <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
			  <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">Заказы по способам доставки</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
			  </div>
			  <div class="card-body pd-0 bd-color-gray-lighter">
                <div class="row no-gutters tx-center">
                  <div class="col-12 col-sm-10  tx-left">
                    <p class="tx-12 lh-8 mg-b-5">Диаграмма отображает заказы в статусах: Новый, Собран, Собран 2, Собран 3.</p>
                  </div><!-- col-4 -->
                </div><!-- row -->
                <div class="pd-10 diagram_6">
                   
              <canvas id="chartBar4" style="height: 250px;"></canvas>
			  </div>
              </div>
		
            </div><!-- card -->

          </div><!-- col-8 -->
          <div class="col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 pd-sm-25 mg-t-20">
              <h6 class="card-body-title">Заказы</h6>
              <p class="mg-b-20 mg-sm-b-30">Заказы по статусам(внутренние)</p>
                <button type="button" onclick=" $(this).hide('slow'); flotPie2('status')" class="btn btn-outline-primary">Отобразить</button>
              <div id="flotPie2" class="ht-200 ht-sm-250"></div>
            </div><!-- card -->
			</div>
	<div class="col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 pd-sm-25 mg-t-20">
              <h6 class="card-body-title">Заявки</h6>
              <p class="mg-b-20 mg-sm-b-30">Заявки за последнюю неделю</p>
                <button type="button" onclick=" $(this).hide('slow'); flotPie3('quick')" class="btn btn-outline-primary">Отобразить</button>
              <div id="flotPie3" class="ht-200 ht-sm-250"></div>
            </div><!-- card -->
			</div>
          
          	<div class="col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card widget-messages mg-t-20">
              <div class="card-header">
                <span>Коментарии к заказам</span>
                <a href=""><i class="icon ion-more"></i></a>
              </div><!-- card-header -->
              <div class="list-group list-group-flush">
			  <?php if($this->orders_koment){
				foreach($this->orders_koment as $d){ ?>
				 <a href="<?=$this->path?>shop-orders/edit/id/<?=$d->id?>" class="list-group-item list-group-item-action media">
                  <div class="media-body">
                    <div class="msg-top">
                      <span><?=$d->id.' : '.$d->name?></span>
                      <span><?=$d->date_create?></span>
                    </div>
                    <p class="msg-summary"><?=$d->comments?></p>
                  </div><!-- media-body -->
                </a><!-- list-group-item -->
				
				<?php }
                                } ?>
              </div><!-- list-group -->
            <!--  <div class="card-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-xl-3 mg-t-20">
            <div class="card pd-20 pd-sm-10">
                <div class="card-header bg-transparent pd-y-10 d-sm-flex align-items-center justify-content-between">
                   
                <div class="col-sm-10  col-lg-8 tx-left">
              <h6 class="card-body-title">Заказы с бонусами</h6>
               <p class="tx-12 lh-8 mg-b-5">Отображены заказы с бонусом.</p>
                </div>
                  
              </div>
		<div class="card-body pd-0 bd-color-gray-lighter">
                    <button type="button" onclick=" $(this).hide('slow'); OrderBonus()" class="btn btn-outline-primary">Отобразить</button>
		<div class="card pd-10 diagram_9 ht-200 ht-sm-250" id="order_bonus"></div>
		</div>
              
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
         <div class="row row-sm mg-t-50"> 
          
          <div class="col-xl-6 mg-t-25 mg-xl-t-0">
            <div class="card pd-20 pd-sm-40">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="">
<h6 class="card-body-title">Посещаемость</h6>
              <p class="mg-b-20 ">Посещаемость за последние 30 дней.</p>
              </div>
                    <div>
<form class="form-inline" name="form_analitics" id="form_analitics">
  <div class="form-group">
    <input type="date"  class="form-control" name="from_analitic" id="from_analitic" value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>" >
    
  </div>
  <div class="form-group">
   <input type="date" class="form-control" id="to_analitic" placeholder="Password" value="<?=date("Y-m-d")?>" min="<?=date("Y-m-d", strtotime('-60 days'))?>" max="<?=date("Y-m-d")?>">
  </div>
    <button type="submit" class="btn btn-primary btn-sm">Отобразить</button>
</form>
                        </div>
              </div>
              
              <div  class="visit card-body">
                  <table class="table table-bordered">
                      <thead>
                      <tr>
                          <th>Показатель</th>
                          <th>Текущее значение</th>
                          <th>Среднее значение</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr><td >Сеансы</td><td class="sessions"></td><td class="sessions_s"></td></tr>
                      <tr><td>Пользователи</td><td class="users"></td><td class="users_s"></td></tr>
                      <tr><td>Новые Пользователи</td><td class="newUsers"></td><td class="newUsers_s"></td></tr>
                      <tr><td>Просмотры страниц</td><td class="pageviews"></td><td class="pageviews_s"></td></tr>
                      <tr><td>Страниц/Сеанс</td><td class="pageviewsPerSession"></td><td class="pageviewsPerSession_s"></td></tr>
                      <tr><td>Показатель отказов %</td><td class="otkaz"></td><td class="otkaz_s"></td></tr>
                      <tr><td>Конверсия %</td><td class="konvers"></td><td class="konvers_s"></td></tr>
                      </tbody>
                  </table>
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
          <div class="col-xl-6 mg-t-25 mg-xl-t-0 ">
            <div class="card pd-20 pd-sm-40">
              <h6 class="card-body-title">Подписка на бренд</h6>
              <p class="mg-b-20 mg-sm-b-30">Отображает количество подписчиков на бренды и заказы в ед..</p>
              <div  class="views" id="brand_sub">
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
  <script src="<?=$this->files?>views/template/js/resize_sensor.js"></script>
	   
	   
    <script src="<?=$this->files?>views/template/lib/jquery.sparkline.bower/jquery.sparkline.min.js"></script>
   <script src="<?=$this->files?>views/template/lib/d3/d3.js"></script>
   <script src="<?=$this->files?>views/template/lib/chart.js/chart.js"></script>
   <script src="<?=$this->files?>views/template/js/dashboard.js?v=1"></script>
    <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.js"></script>
   <script src="<?=$this->files?>views/template/lib/flot/jquery.flot.pie.js"></script>	
    <script src="<?=$this->files?>views/template/lib/raphael/raphael.min.js"></script>
 <script src="<?=$this->files?>views/template/lib/morris.js/morris.js"></script>   
  <script src="<?=$this->files?>views/template/js/home.js?v=4.3.6"></script>   
<script>
function nTime(){
$(".m_time").hide();
$(".n_time").show();
return false;
}
function mTime(){
$(".n_time").hide();
$(".m_time").show();
return false;
}
function naTime(){
$(".m_a_time").hide();
$(".n_a_time").show();
return false;
}
function maTime(){
$(".n_a_time").hide();
$(".m_a_time").show();
return false;
}

$(function(){

$('#days').on('shown.bs.modal', function () {
$('.sparkline_days').html($('.days').text());
  $('.sparkline_days').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#0083CD',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#week').on('shown.bs.modal', function () {
$('.sparkline_week').html($('.week').text());
  $('.sparkline_week').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#0083CD',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#month').on('shown.bs.modal', function () {
$('.sparkline_month').html($('.month').text());
  $('.sparkline_month').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#6e42c1',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
$('#year').on('shown.bs.modal', function () {
$('.sparkline_year').html($('.year').text());
  $('.sparkline_year').sparkline('html', {
    type: 'bar',
    barWidth: 30,
    height: 200,
    barColor: '#2b333e',
    lineColor: 'rgba(255,255,255,0.5)',
    chartRangeMin: 0,
    chartRangeMax: 10
  });
});
	new Morris.Bar({
    element: 'brand_sub',
    data: [
  { y: 'Подписались', a: <?=$this->res_brand_sub['sub']?>},
  { y: 'Заказали', a: <?=$this->res_brand_sub['order']?>}
],
xkey: 'y',
ykeys: ['a'],
labels: ['Колл. '],
        //barColors: ['#5058AB'],
gridTextSize: 14,
hideHover: 'auto',
resize: true
  });

});
/**
 * 
 * @type type
 */


$("#procent-ostatka").submit(function(){

    var f = $(this).serializeArray();
        console.log(f);
       // console.log(f.one-date-from);
   // alert(f);
    var f1 = new Date($('#one-date-from').val());
   var t1 = new Date($('#one-date-to').val());
   var f2 = new Date($('#two-date-from').val());
   var t2 = new Date($('#two-date-to').val());
   
   if(f1 > t1){
       alert('Не правильно указана дата');
       return false;
   }else if(f2 > t2){
        alert('Не правильно указана дата сравнения');
       return false;
   }else if(f2 >= f1  || t2 >= t1){
       alert('Дата сравнения должна быть меньше первой даты');
       return false;
   }

   procent_ostatka($(this).serialize());
    return false;
});


$("#form_prognoz").submit(function(e){
    console.log($(this).serialize());
    prognoz($(this).serialize());
    return false;
});
$("#form_prognoz_brand").submit(function(e){
    console.log($(this).serialize());
    prognozBrand($(this).serialize());
    return false;
});
$("#form_oborot_all").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&cat_prognoz=267&from_prognoz='+from+'&to_prognoz='+to;

    oborot_all(form);
    return false;
});

$("#form_oborot_root").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
               
  
  // var ar = [14,15,33,59,146,54];
  oborot_category(form, 'oborot_root');
   //   oborot_root(form, ar, 0); 
    return false;
});
$("#form_oborot_brand").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  oborot_brand(form, 'oborot_brand');
    return false;
});
$("#form_oborot_graid").submit(function(e){
    var form = $(this).serialize();
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
 console.log(form);
   //  $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
  oborot_graid(form, 'oborot_graid');
    return false;
});

$("#form_oborot_category").submit(function(e){
   // var form_array  = $(this).serializeArray();
    var form = $(this).serialize();
    //console.log(form_array);
    var from = $('#from_oborot').val();
    var to = $('#to_oborot').val();
    form+='&from_prognoz='+from+'&to_prognoz='+to;
    console.log(form);
    oborot_category(form, 'oborot_category');
    return false;
});
function oborot_category(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
    if(element == 'oborot_category'){
         var cat = $('#cat_oborot_category option:selected').text();
    }else{
        var cat = 'Главные категории';
    }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                 chart.addSeries({data: date[0].niz, name:'21', type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:'28', type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                 chart.addSeries({data: date[0].verch, name:'45', type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                for(var k in date){
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}
function oborot_brand(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
   // if(element == 'oborot_brand'){
       //  var cat = $('#cat_oborot_category option:selected').text();
    //}else{
        var cat = 'Бренды';
   // }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                    var l = date[0].verch.length;
                   
                 chart.addSeries({data: date[0].niz, name:date[0].niz_m, type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:date[0].norma_m, type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                // chart.addSeries({data: date[0].verch, name:date[0].verch_m, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                 var verch = 0;
                 for(var v in date){
                    if(date[v].verch_m > verch){
                      verch = date[v].verch_m;
                    }
                 }
                 var vv = [];
                  for(i=0;i<l;i++){
                      vv.push(verch);
                  }
                  chart.addSeries({data: vv, name: verch, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                  
                for(var k in date){
                    
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
                
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}
function oborot_graid(form, element){
  //  console.log(form);
   // console.log(element);
    //return false;
    
   // if(element == 'oborot_brand'){
       //  var cat = $('#cat_oborot_category option:selected').text();
    //}else{
        var cat = 'Грейды';
   // }
   
    console.log(cat);
            //var date =[];
		$.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: form,
                success: function (res) {
			console.log(res);
                        //  date = res;
                },
		error: function (res) {
			console.log(res);
		}
            }).done(function(date) {
                if(date.length > 0){
                    var l = date[0].verch.length;
                   
                 chart.addSeries({data: date[0].niz, name:date[0].niz_m, type: 'area', zIndex: 4,color: '#23bf08', marker: {enabled: false}});
                 chart.addSeries({data: date[0].norma, name:date[0].norma_m, type: 'area', zIndex: 3, color: '#17f2f4',  marker: {enabled: false}});
                // chart.addSeries({data: date[0].verch, name:date[0].verch_m, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                 chart.xAxis[0].setCategories(date[0].x);
                 var z = 5;
                 var verch = 0;
                 for(var v in date){
                    if(date[v].verch_m > verch){
                      verch = date[v].verch_m;
                    }
                 }
                 var vv = [];
                  for(i=0;i<l;i++){
                      vv.push(verch);
                  }
                  chart.addSeries({data: vv, name: verch, type: 'area', zIndex: 2,color: '#ff0018', marker: {enabled: false}});
                  
                for(var k in date){
                    
                    chart.addSeries({data: date[k].oborot, name:date[k].cat,  type: 'spline', zIndex: z});
                    z++;
                }
                
            }
                console.log(date);
                    $('.modal-backdrop').hide();
                    $('#foo').detach();
                      });
                      
     
var chart =  new  Highcharts.Chart({
title: {
        text: cat
            },
  chart: {
    renderTo: element
  },

    xAxis: {
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Дни'
                },
                 categories: [0]
            },
    tooltip: {
        shared: true
    }
});
     

return false;

}



$("#form_analitics").submit(function(e){
    analityks($('#from_analitic').val(), $('#to_analitic').val())
    return false;
});
$('.articles').click(function(e){
  if(e.target.id == 'h') {
  $(".n_a_time").hide();
$(".m_a_time").hide();
}
$('#'+e.target.id).addClass("active");
  //rickshaw2(e.target.id);
  console.log(e.target.id);
  chartLine(e.target.id);
});

$('.order').click(function(e){
$('#h').removeClass("active");
$('#n').removeClass("active");
$('#m').removeClass("active");
  console.log(e.target.id);
  if(e.target.id == 'h') {
  $(".n_time").hide();
$(".m_time").hide();
}
  $('#'+e.target.id).addClass("active");
  rickshaw2(e.target.id);
});

</script>		