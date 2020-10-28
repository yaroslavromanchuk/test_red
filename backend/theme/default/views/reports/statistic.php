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
          <h6 class="card-body-title mb-2">Активные заказы</h6>
          <div class="row">
 <?php if(count($this->orders_days_active['koll'])){ ?>
          <div class="col-sm-6 col-xl-3">
            <div class="card pd-20 bg-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за сегодня</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#days"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_days_active['koll'])?></span>
              </div><!-- card-body -->
	<h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
                                $bon = 0;
				foreach($this->orders_days_active['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_days_active['dep'] as $k => $koll){
				$dep += $koll;
				}
                                foreach($this->orders_days_active['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
				 <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                                     <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }

if(count($this->orders_week_active['koll'])){	?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card pd-20 bg-info">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за неделю</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#week"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_week_active['koll'])?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
                                $bon = 0;
				foreach($this->orders_week_active['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_week_active['dep'] as $k => $koll){
				$dep += $koll;
				}
                                foreach($this->orders_week_active['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                                    <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
        if(count($this->orders_month_active['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-purple">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за месяц</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#month"  ><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2" ><?=implode(',', $this->orders_month_active['koll'])?></span>
              </div><!-- card-body -->
			   <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
                                $bon = 0;
				foreach($this->orders_month_active['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_month_active['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_month_active['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                           <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
                </div>
                <div>
                  <span class="tx-11 tx-white-6">Денежные средства</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($am,2)?> грн.</h6>
                </div>
              </div><!-- -->
            </div><!-- card -->
          </div><!-- col-3 -->
<?php }
if(count($this->orders_year_active['koll'])){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-sl-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">за год</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#year"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?=implode(',', $this->orders_year_active['koll'])?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
                                  $bon = 0;
				foreach($this->orders_year_active['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_year_active['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_year_active['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div> 
                    <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
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
                                 $bon = 0;
				foreach($this->orders_days_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_days_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_days_op['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
				 <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                                     <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
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
                                $bon = 0;
				foreach($this->orders_week_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_week_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_week_op['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                             <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
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
                                $bon = 0;
				foreach($this->orders_month_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_month_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_month_op['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                     <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
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
                                  $bon = 0;
				foreach($this->orders_year_op['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_year_op['dep'] as $k => $koll){
				$dep += $koll;
				}
				foreach($this->orders_year_op['bon'] as $k => $koll){
				$bon += $koll;
				}
				echo Number::formatFloat($am+$dep+$bon, 2).' грн.';
				?></h5>
              <div class="d-flex align-items-center justify-content-between mg-t-15 bd-t bd-white-2 pd-t-10">
                <div>
                  <span class="tx-11 tx-white-6">Депозит</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($dep,2)?> грн.</h6>
                </div>
                  <div>
                  <span class="tx-11 tx-white-6">Бонусы</span>
                  <h6 class="tx-white mg-b-0"><?=Number::formatFloat($bon,2)?> грн.</h6>
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
                            <p class="mg-b-20 mg-sm-b-30">Отображается средний чек (в грн.) за последние 30 дней.<br> (деньги+депозит)</p>
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
		<p>Остатки в единицах:</p>
                  <canvas id="ostatki"  ></canvas>
                <p>Остатки в грн.:</p>
                <canvas id="ostatki_grn"  ></canvas>
                
              </div><!-- list-group -->
             <div class="card-footer">
                 
                 
                  <button type="button" onclick=" $(this).hide('slow'); ostatki()" class="btn btn-outline-primary">Отобразить</button>
                <!--<a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>-->
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
  <script src="<?=$this->files?>views/template/js/home.js?v=4.6.4"></script>   
<script>
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
</script>		