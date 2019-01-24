<?php if(true){ ?>
 <div class="row row-sm mg-x-0">
     <div class="col-sm-12">
     <div class="card p-3">
          <h6 class="card-body-title mb-2">Оформленные заказы</h6>
          <div class="row">
 <?php if($this->orders_days){ ?>
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

if($this->orders_week){	?>
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
        if($this->orders_month){ ?>
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
if($this->orders_year){ ?>
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
 <?php if($this->orders_days_op){ ?>
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

if($this->orders_week_op){	?>
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
        if($this->orders_month_op){ ?>
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
if($this->orders_year_op){ ?>
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
          <div class="col-xl-4 p-2">
            <div class="card p-2">
              <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">СТАТИСТИКА ОФОРМЛЕНИЯ ЗАКАЗОВ</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
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
			 <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
			  <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">Оборот товаров</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
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
              <div class="col-xl-6 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Остатки товара</h6>
                  <span class="mg-b-20 mg-sm-b-30">Отображается остатки за последние 30 дней  в 03:00</span>
                  </div>
                    </div>
              <div class="card-body diagram_ostatki">
                  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>
		<canvas id="ostatki"  ></canvas>
              </div><!-- list-group -->
            <!--  <div class="card-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
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
          <div class="col-xl-12 mg-t-20 mg-xl-t-0">
            <div class="card overflow-hidden mg-t-20">
                <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                    <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-body-title">Прогнозы</h6>
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
        // $cat =  ;
         $mas = array();
		foreach (Shopcategories::find('Shopcategories', ['active'=> 1, 'id not in (106, 267)']) as $cat) {
                    $mas[$cat->getId()] = $cat->getRoutez();
                }
                
			asort($mas);
			
         
       // Menu::find('Menu',['active'=>1, 'type_id'=>2, 'section'=>$s->id], ['sequence'=>'ASC']);
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
    <button type="submit" class="btn btn-primary btn-sm">Построить</button>
</form>
                        </div>
                    </div>
              <div class="card-body diagram_prognoz">
                <!--  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>-->
                <div id="prognoz"></div>
              </div><!-- list-group -->
            <!--  <div class="card-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
		  
		
          </div>
        <div class="row row-sm mg-x-0 mg-t-20">
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
          <div class="col-xl-6 mg-t-25 mg-xl-t-0 d-none">
            <div class="card pd-20 pd-sm-40">
              <h6 class="card-body-title">Просмотры страниц</h6>
              <p class="mg-b-20 mg-sm-b-30">Просмотры страниц за последние 30 дней.</p>
              <div  class=" views">
                  
                  <canvas id="views" ></canvas>
              </div>
            </div><!-- card -->
          </div><!-- col-6 -->
        </div><!-- row -->
  <script src="<?=$this->files?>views/template/js/ResizeSensor.js"></script>
	   
	   
    <script src="<?=$this->files?>views/template/lib/jquery.sparkline.bower/jquery.sparkline.min.js"></script>
   <script src="<?=$this->files?>views/template/lib/d3/d3.js"></script>
   <script src="<?=$this->files?>views/template/lib/chart.js/Chart.js"></script>
   <script src="<?=$this->files?>views/template/js/dashboard.js?v=1"></script>
    <script src="<?=$this->files?>views/template/lib/Flot/jquery.flot.js"></script>
   <script src="<?=$this->files?>views/template/lib/Flot/jquery.flot.pie.js"></script>	
    <script src="<?=$this->files?>views/template/lib/raphael/raphael.min.js"></script>
 <script src="<?=$this->files?>views/template/lib/morris.js/morris.js"></script>   
<script>
    var updateInterval = 60000;
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

$(document).ready(function(){

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

//$('#h').addClass("active");
//$('#h_a').addClass("active");

//rickshaw2('h');//1 zakazi

//chartLine('h_a');//tovari

//Ucenka();//ucenka po datam
//Ucenka_2(); // ucenka na segodna

////chartBar4('dely');//zakazi po sposobam dostavki
//flotPie2('status');
//flotPie3('quick');

///ostatki();//ostatki tovara
//chek(); //sredniy chek
////visit();//visity
/////konversiya();//visity
/*
var date = new Date();
var fuldate = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
date.setDate(date.getDate() -7);
var fuldate7 = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();*/
///analityks('', '');

});
/**
 * 
 * @type type
 */
$("#form_prognoz").submit(function(e){
    console.log($(this).serialize());
    prognoz($(this).serialize());
            // analityks($('#from_analitic').val(), $('#to_analitic').val())
    return false;
});


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
function analityks(from, to){
      var url = '/admin/home/';
		var new_data = '&method=konversiya&from='+from+'&to='+to;
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
                        for(var key in res){
                            $('.'+key).html(res[key]); 
                            if(key != 'otkaz' && key != 'konvers' && key != 'pageviewsPerSession'){
                                 $('.'+key+'_s').html(parseInt(res[key]/res['dney']));
                            }else{
                             //    $('.'+key+'_s').html(res[key]);
                            }
                           
                        }
                },
				error: function (res) {
				console.log(res);
				}
            });
    return false;
}


function Ucenka_2(){
var url = '/admin/home/';
		var new_data = '&method=ucenka_2';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {

				$("#koll_ucenka").html(res['sum']);
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function(res) {
			console.log(res);
			new Morris.Donut({
    element: 'ucenka_2',
    data: [
      {label: "0%", value: res[0]},
      {label: "10%", value: res[10]},
      {label: "20%", value: res[20]},
      {label: "30%", value: res[30]},
	  {label: "40%", value: res[40]},
	  {label: "50%", value: res[50]},
	  {label: "60%", value: res[60]}
    ],
    colors: ['#0c8e22','#0c8e17','#98eacc','#4a63e0','#ffccce','#ff6870', '#e40613'],
    resize: true
  });
			});



}
function rickshaw2(e){
    
  var label = [];
  var date =[];
 var i;
 var max = 0;
 var sum = 0;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(e);
				var l = res.length;
			for (i = 0; i < l; i++) {
			if(res[i]['y'] > max) max = res[i]['y'];
			label.push(res[i]['x']); 
			date.push(res[i]['y']); 
			sum +=res[i]['y']; 
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			$('#col_order_5').html(sum);
			$("canvas#rickshaw2").remove();
			$("div.diagram_5").append('<canvas id="rickshaw2" height="200"></canvas>');
			var ctx = document.getElementById("rickshaw2").getContext('2d');

new Chart(ctx, {
type: 'line',
data: {
  labels: label,
  datasets: [{
    data: date,
  fill: true,
    backgroundColor: '#73a9e7'
  }]
},
options: {
  legend: {
    display: false,
      labels: {
        display: false
      }
  },
  scales: {
    yAxes: [{gridLines: {
        display: false,
        color: "black"
      },
      ticks: {
        beginAtZero:true,
        fontSize: 10,
        max: max
      }
    }],
    xAxes: [{
	gridLines: {
        display: false,
        color: "black"
      },
      ticks: {
        beginAtZero:true,
        fontSize: 10
      }
    }]
  }
}
});
});
}

function delivery_time(e, from, to){
console.log(e);
console.log(from);
console.log(to);
    
  var label = [];
  var m =[];
  var up =[];
  var np =[];
  var k =[];
  
 var sum = 0;
 var ctn = 0;

		$.ajax({
                url: '/admin/home/',
                type: 'POST',
                dataType: 'json',
                data: { method: 'delivery', type: e, from : from, to: to},
                success: function (res) {
                    console.log(res);
                    if(true){
				
                                for(var key in res){
                                    label.push(res[key]['date']+' : '+res[key]['ctn']);
                                    m.push(parseInt(res[key]['time'])); 
                                    sum += parseInt(res[key]['time']);
                                }
                                $('#col_dely_5').html(parseInt(sum/res.length));
                            }
                            if(false){
                                for(var key in res.m){
                                    label.push(res.m[key]['date']+':'+res.m[key]['ctn']);
                                    m.push(parseInt(res.m[key]['time'])); 
                                   // sum += parseInt(res.m[key]['time']);
                                }
                               // sr = parseInt(sum/res.m.length);
                               // sum = 0;
                                for(var key in res.up){
                                   // label.push(res.up[key]['date']+':'+res.up[key]['ctn']);
                                    up.push(parseInt(res.up[key]['time'])); 
                                  //  sum += parseInt(res.up[key]['time']);
                                }
                               // sr = parseInt(sum/res.up.length);
                               // sum = 0;
                                for(var key in res.np){
                                   // label.push(res.np[key]['date']+':'+res.np[key]['ctn']);
                                    np.push(parseInt(res.np[key]['time'])); 
                                   // sum += parseInt(res.np[key]['time']);
                                }
                              //  sum = 0;
                                for(var key in res.k){
                                   // label.push(res.k[key]['date']+':'+res.k[key]['ctn']);
                                    k.push(parseInt(res.k[key]['time'])); 
                                    //sum += parseInt(res.k[key]['time']);
                                }
                              //  sum = 0;
                                
                            }

                    },
                    error: function (res) {
				console.log(res);
				}
            }).done(function() {
			
			$("#dely_shop_3").remove();
			$(".diagram_8_dely").append('<canvas id="dely_shop_3"></canvas>');
			var ctx4 = document.getElementById('dely_shop_3');
  var myChart8 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Дней',
        data: m,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }/*,{
	  label: 'Укр.Почта',
        data: up,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: 'НоваПочта',
        data: np,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Курьер',
        data: k,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      }*/]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: false
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
           // max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
});
}

function prognoz(form){
  var label = [];
  var date =[];
 var i;
  var cats = [];
 var max = 0;
 var min = 30000;
  var url = '/admin/home/';
		var new_data = '&method=prognoz&'+form;
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
                                date = res;
                      /*  for(var key in res){
                        if(res[key]['y'] > max){ max = res[key]['y'];}
                        if(res[key]['y'] < min){ min = res[key]['y'];}
                        
			label.push(res[key]['x']); 
			date.push(res[key]['y']); 
                        }*/
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
                console.log(date);
                
                         chart.xAxis[0].setCategories(date.x);
                        // chart.yAxis[0].setCategories(date.ost);
   chart.series[0].setData(date.n_0);  
   chart.series[1].setData(date.n_1);
   chart.series[2].setData(date.n_2);
   chart.series[3].setData(date.ost);
   chart.series[4].setData(date.prod);
   chart.series[5].setData(date.add);
   chart.series[6].setData(date.sr_pr);
   chart.series[7].setData(date.sr_ost);
               
          });
var chart = new  Highcharts.Chart({
title: {
        text: 'DDMRP'
            },
  chart: {
    renderTo: 'prognoz',
   // zoomType: 'xy'
  },

    xAxis: {
   // categories: date.x,
    
    labels: {
      rotation: 90
    }, 
    title:{
         text: 'Недели'
    }
            },
    yAxis: {
                title: {
                    text: 'Остаток товара'
                },
             //   opposite: true
            },
  /*  plotOptions: {
        series: {
            stacking: 'normal'
        }
    },*/
    tooltip: {
        shared: true
    },
  series: [
  {
  
   type: 'area',
   name:'Ниже нормы',
  // yAxis: 1,
   
   color: '#bc0000',
   zIndex: 2,
   marker: {
            enabled: false
        },
    data: [0]
  },
  {
   type: 'area',
   name: 'Норма',
   //yAxis: 1,
   color: '#fcff5e',
   zIndex: 1,
   marker: {
            enabled: false
        },
    data: [1]
  },
  {
   type: 'area',
   name: 'Выше нормы',
  // yAxis: 1,
   color: '#03a842',
   zIndex: 0,
   marker: {
            enabled: false
        },
    data: [2]
  },
  {
   type: 'spline',
   name: 'Остатки',
   color: '#000000',
   zIndex: 3,
    data: [3]
  },
  {
   type: 'spline',
   name: 'Продажи',
   color: '#005cf2',
   zIndex: 4,
    data: [4]
  },
  {
   type: 'spline',
   name: 'Добавлено',
   color: '#ad06a7',
   zIndex: 5,
    data: [5]
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Мах.Продажи',
   color: Highcharts.getOptions().colors[2],
   zIndex: 5,
    data: [6]
  }
  ,
  {
   type: 'spline',
   dashStyle: 'Dot',
   name: 'Мах.Остатки',
   color: Highcharts.getOptions().colors[1],
   zIndex: 5,
    data: [7]
  }
  ]
});

                
          /*      $("#prognoz_div").remove();
			$("#prognoz").append('<div id="prognoz_div"></div>');
                
               new Morris.Line({
                   element: 'prognoz_div',
                   data: date,
     xkey: 'x',
    ykeys: ['o', 'z', 'a'],
    labels: ['Остаток', 'Купили', 'Добавили'],
    lineColors: ['#000000','#0000FF', '#578c0c'],
    lineWidth: 1,
    ymax: 'auto 100',
    smooth: false,
   // fillOpacity: 0.5,
    gridTextSize: 10,
    hideHover: 'auto',
    parseTime : false,
    xLabels: "week",
    resize: true
                   
               }); */
return false;

}

function ostatki(){
  var label = [];
  var date =[];
 var i;
 var max = 0;
 var min = 30000;
  var url = '/admin/home/';
		var new_data = '&method=ostatki';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
                        for(var key in res){
                        if(res[key]['y'] > max){ max = res[key]['y'];}
                        if(res[key]['y'] < min){ min = res[key]['y'];}
                        
			label.push(res[key]['x']); 
			date.push(res[key]['y']); 
                        }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
		var ctx4 = $('#ostatki');
                    ctx4.height= 300;
   new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	 // label: '20%',
        data: date,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });
});
}

function chartLine(e){
var label = [];
  var date1 =[];
  var date2 =[];
  var date3 =[];
 var i;
 var max = 0;
 var sum = 0;
  var url = '/admin/home/';
		var new_data = '&method=shop&type='+e;
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(e);
				var l = res.length;
					for (i = 0; i < l; i++) {
			if(res[i]['y'] > max) max = res[i]['y'];
			label.push(res[i]['x']); 
			date1.push(res[i]['y']); 
			date2.push(res[i]['pay']); 
			date3.push(res[i]['ret']);
			sum +=res[i]['y']; 
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			$('#col_articles_5').html(sum);

//return false;
  var ctx4 = document.getElementById('articles_shop_3');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Заказали',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Купили',
        data: date2,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Вернули',
        data: date3,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
           // max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}
function visit(){
var label = [];
  var date1 =[];
  var date2 =[];
  var date3 =[];
  var date4 =[];
  var date5 =[];
 var min = 0;
 var max= 1;
  var url = '/admin/home/';
		var new_data = '&method=visit';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(e);
                                for(var key in res){
                                  label.push(key); 
                                  date1.push(res[key]['visit']);
                                  date2.push(res[key]['page']);
                                  date3.push(res[key]['glubina']);
                                  date4.push(res[key]['otkaz']);
                                  date5.push(res[key]['konvers']);
                                    
                                }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
//return false;
  var ctx4 = $('#visit');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Сеансы',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Просмотры страниц',
        data: date2,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Глубина',
        data: date3,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Отказы %',
        data: date4,
        borderColor: '#5B0000',
        borderWidth: 1,
        fill: false
      },{
	  label: 'Конверсия %',
        data: date5,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
           // max: max,
          //  min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}
function konversiya(){
var label = [];
  var date1 =[];
  //var date2 =[];
 //var min = 0;
 //var max= 1;
  var url = '/admin/home/';
		var new_data = '&method=konversiya';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(e);
                                for(var key in res){
                                  label.push(key); 
                                  date1.push(res[key]['new']);
                                 // date2.push(res[key]['st']);
                                    
                                }
   // min = Math.min.apply(null, date2);
    // max = Math.max.apply(null, date1);

                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
//return false;
  var ctx4 = $('#views');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Конверсия',
        data: date1,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
                    // max: max,
           // min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
  });
  return false;
}


function Ucenka(){
var label = [];
var date10 =[];
  var date20 =[];
  var date30 =[];
  var date40 =[];
  var date50 =[];
  var date60 =[];
var url = '/admin/home/';
		var new_data = '&method=ucenka';
		//console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
console.log(res);
var l = res.length;
					for (i = 0; i < l; i++) {
			//if(res[i]['y'] > max) max = res[i]['y'];
			label.push(res[i]['x']); 
                        date10.push(res[i]['10']); 
			date20.push(res[i]['20']); 
			date30.push(res[i]['30']); 
			date40.push(res[i]['40']);
			date50.push(res[i]['50']);
			date60.push(res[i]['60']);
			//sum +=res[i]['y']; 
  }
				//$("#koll_ucenka").html(res['sum']);
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function(res) {

			  var ctx4 = document.getElementById('ucenka');
  var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: '10%',
        data: date10,
        borderColor: '#320063',
        borderWidth: 1,
        fill: false
      },{
	  label: '20%',
        data: date20,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      },{
	  label: '30%',
        data: date30,
        borderColor: '#5B93D3',
        borderWidth: 1,
        fill: false
      },{
	  label: '40%',
        data: date40,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: '50%',
        data: date50,
        borderColor: '#5B9300',
        borderWidth: 1,
        fill: false
      },{
	  label: '60%',
        data: date60,
        borderColor: '#5B9311',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: true,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10//,
           // max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11
          }
        }]
      }
    }
  });
			
			});



}
function chartBar4(e){
var label = [];
  var date =[];
 var i;
 var max = 0;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				var l = res.koll.length;
				label = res.name;
				date = res.koll;
					for (i = 0; i < l; i++) {
			if(res.koll[i] > max) max = res.koll[i];
			//label.push(res[i]['x']); 
			//date.push(res[i]['y']); 
  }
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
			 var ctb4 = document.getElementById('chartBar4').getContext('2d');
  new Chart(ctb4, {
    type: 'bar',
    data: {
      labels: label,
      datasets: [{
        label: 'Заказов: ',
        data: date,
        backgroundColor: [
          '#324463',
          '#5B93D3',
          '#7CBDDF',
          '#5B93D3',
          '#324463'
        ]
      }]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: false
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
			 max: max
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 11,
           
          }
        }]
      }
    }
  });
			
			});

};
function flotPie2(e){
  var piedata =[];
 var i;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				piedata = res;
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
  $.plot('#flotPie2', piedata, {
    series: {
      pie: {
        show: true,
        radius: 1,
        innerRadius: 0.4,
        label: {
          show: true,
          radius: 2/3,
          formatter: labelFormatter,
         threshold: 0.1
        }
      }
    },
    grid: {
      hoverable: true,
      clickable: true
    },
    legend: { show: true
	}
  });

  function labelFormatter(label, series) {
  //console.log(series);
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + series.data[0][1] + "</div>";//Math.round(series.percent)
  }
  });
}
function flotPie3(e){
  var piedata =[];
 var i;
  var url = '/admin/home/';
		var new_data = '&method=order&type='+e;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				piedata = res;
                },
				error: function (res) {
				console.log(res);
				}
            }).done(function() {
  $.plot('#flotPie3', piedata, {
    series: {
      pie: {
        show: true,
        radius: 1,
        innerRadius: 0.4,
        label: {
          show: true,
          radius: 2/3,
          formatter: labelFormatter,
         threshold: 0.1
        }
      }
    },
    grid: {
      hoverable: true,
      clickable: true
    },
    legend: { show: true
	}
  });

  function labelFormatter(label, series) {
 // console.log(series);
    return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + series.data[0][1] + "</div>";//Math.round(series.percent)
  }
  });
}
function  chek(){
    var date = $('.chek .label').text().split(',');// [1,2,3,4,5,6,7,8,9];
    var label = $('.chek .date').text().split(',');
    var min = Math.min.apply(null, date);
    var max = Math.max.apply(null, date);
    console.log(date);
    var sum = 0;
for(var i = 0; i < date.length; i++){
    sum += parseInt(date[i]);
    }
    $('.sr_chek').html(parseInt(sum/date.length));
    //var min = 0;
   // var max = 10;
    var ctx4 = $('.chek');
            ctx4.height= 300;
   new Chart(ctx4, {
    type: 'line',
    data: {
      labels: label,
      datasets: [{
	  label: 'Ср.чек',
        data: date,
        borderColor: '#324463',
        borderWidth: 1,
        fill: false
      }
	  ]
    },
    options: {
      legend: {
        display: false,
          labels: {
            display: true
          }
      },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10,
            max: max,
            min: min
          }
        }],
        xAxes: [{
          ticks: {
            beginAtZero:true,
            fontSize: 10
          }
        }]
      }
    }
  });
    
}

</script>		