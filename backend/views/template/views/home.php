 <?php //foreach($this->orders_days['koll'] as $k => $koll){ echo $k.'-'.$koll.',';} ?>
  <?php //echo print_r($_SERVER);?>
  <?php //echo print_r($this->getCurMenu());?>
  <?php //echo print_r($this->post);?>
 

  

<?php if(true){ 
 if(@$this->orders_days){ ?>
  <span class="days d-none"><?php  foreach($this->orders_days['koll'] as $k => $koll){echo $k==0?$koll:','.$koll; }  ?></span>
  <div id="days" class="modal fade " style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Продажи за сегодня</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pd-25"><span  class="sparkline_days"></span></div>
        </div>
      </div><!-- modal-dialog -->
    </div>
 <?php }
 if(@$this->orders_week){ ?>
   <span class="week d-none"><?php  foreach($this->orders_week['koll'] as $k => $koll){echo $k==0?$koll:','.$koll; }  ?></span>
     <div id="week" class="modal fade " style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Продажи за неделю</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pd-25"><span  class="sparkline_week"></span></div>
        </div>
      </div><!-- modal-dialog -->
    </div>
 <?php }

 if(@$this->orders_month){ ?>
 <span class="month d-none"><?php  foreach($this->orders_month['koll'] as $k => $koll){echo $k==0?$koll:','.$koll; }?></span>
<div id="month" class="modal fade " style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Продажи за месяц</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pd-25"><span  class="sparkline_month"></span></div>
        </div>
      </div><!-- modal-dialog -->
    </div>

<?php }
if(@$this->orders_year){ ?>
   <span class="year d-none"><?php  foreach($this->orders_year['koll'] as $k => $koll){echo $k==0?$koll:','.$koll; }  ?></span>
   <div id="year" class="modal fade " style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14">
          <div class="modal-header pd-y-20 pd-x-25">
            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Продажи за год</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pd-25"><span  class="sparkline_year"></span></div>
        </div>
      </div><!-- modal-dialog -->
    </div>
<?php } ?>

 <div class="row row-sm mg-x-0 mg-t-20">
 <?php if(@$this->orders_days){ ?>
          <div class="col-sm-6 col-xl-3">
            <div class="card pd-20 bg-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">Продажи за сегодня</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#days"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2">
				<?php foreach($this->orders_days['koll'] as $k => $koll){ echo $k==0?$koll:','.$koll;} ?>
				</span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold">
				<?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_days['am'] as $k => $koll){
				$am += $koll;
				}
			foreach($this->orders_days['dep'] as $k => $koll){
				$dep += $koll;
				}
				echo Number::formatFloat($am+$dep, 2).' грн.';
				?>
				
				</h5>
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
		  <?php } if(@$this->orders_week){		  ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="card pd-20 bg-info">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">продажи за неделю</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#week"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?php foreach($this->orders_week['koll'] as $k => $koll){ echo $k==0?$koll:','.$koll;} ?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_week['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_week['dep'] as $k => $koll){
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
		  <?php } if(@$this->orders_month){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-purple">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">продажи за месяц</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#month"  ><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2" ><?php foreach($this->orders_month['koll'] as $k => $koll){ echo $k==0?$koll:','.$koll;} ?></span>
              </div><!-- card-body -->
			   <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_month['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_month['dep'] as $k => $koll){
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
		   <?php } if(@$this->orders_year){ ?>
          <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 bg-sl-primary">
              <div class="d-flex justify-content-between align-items-center mg-b-10">
                <h6 class="tx-11 tx-uppercase mg-b-0 tx-spacing-1 tx-white">продажи за год</h6>
                <a href="" class="tx-white-8 hover-white" data-toggle="modal" data-target="#year"><i class="icon ion-android-more-horizontal"></i></a>
              </div><!-- card-header -->
              <div class="d-flex align-items-center justify-content-between">
                <span class="sparkline2"><?php foreach($this->orders_year['koll'] as $k => $koll){ echo $k==0?$koll:','.$koll;} ?></span>
                
              </div><!-- card-body -->
			  <h5 class="mg-b-0 tx-white tx-lato tx-bold"><?php
				$am = 0;
				$dep = 0;
				$am_dep = 0;
				foreach($this->orders_year['am'] as $k => $koll){
				$am += $koll;
				}
				foreach($this->orders_year['dep'] as $k => $koll){
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
        </div><!-- row -->
<?php }?>
        <div class="row row-sm mg-x-0 mg-t-20">
          <div class="col-xl-6">
            <div class="card overflow-hidden">
              <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
                <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">СТАТИСТИКА ОФОРМЛЕНИЯ ЗАКАЗОВ</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button  id="h" class="btn btn-secondary tx-12 order ">День</button>
                  <button   class="btn btn-secondary tx-12" onClick="nTime();">Неделя</button>
                  <button  id="m" class="btn btn-secondary tx-12" onClick="mTime();" >Месяц</button>
                </div>
				<div  class="btn-group n_time" style="display:none;" role="group" aria-label="Basic example">
				 <button  id="n_h" class="btn btn-secondary tx-12 order ">Часы</button>
                  <button  id="n_d" class="btn btn-secondary tx-12 order ">Дни</button>
				</div>
				<div  class="btn-group m_time" role="group" style="display:none;" aria-label="Basic example">
				 <button  id="m_h" class="btn btn-secondary tx-12 order ">Часы</button>
                  <button  id="m_d" class="btn btn-secondary tx-12 order ">Дни</button>
				</div>
              </div><!-- card-header -->
              <div class="card-body pd-0 bd-color-gray-lighter">
                <div class="row no-gutters tx-center">
                  <div class="col-12 col-sm-10  tx-left">
                    <p class="pd-l-20 tx-12 lh-8 mg-b-5">Диаграмма отображает количество заказов за период. Исключены(Отменён, Возврат).</p>
                  </div><!-- col-4 -->
                  <div class="col-6 col-sm-2 ">
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="col_order_5" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase">Заказов</p>
                  </div>
                </div><!-- row -->
              </div><!-- card-body -->
              <div class="card pd-10 diagram_5">
				<canvas id="rickshaw2" height="250"></canvas>
              </div><!-- card-body -->
            </div><!-- card -->
			</div>
			<div class="col-xl-6">
            <div class="card overflow-hidden">
			 <div class="card-header bg-transparent pd-y-20 d-sm-flex align-items-center justify-content-between">
			  <div class="mg-b-20 mg-sm-b-0">
                  <h6 class="card-title mg-b-5 tx-13 tx-uppercase tx-bold tx-spacing-1">Оборот товаров</h6>
                  <span class="d-block tx-12"><?=$this->days[(date('D'))].date(" d.m.Y")?></span>
                </div>
				 <div class="btn-group" role="group" aria-label="Basic example">
                  <button  id="h_a" class="btn btn-secondary tx-12 articles ">День</button>
                  <button   class="btn btn-secondary tx-12" onClick="naTime();">Неделя</button>
                  <button   class="btn btn-secondary tx-12" onClick="maTime();" >Месяц</button>
                </div>
				<div  class="btn-group n_a_time" style="display:none;" role="group" aria-label="Basic example">
				 <button  id="n_h_a" class="btn btn-secondary tx-12 articles ">Часы</button>
                  <button  id="n_d_a" class="btn btn-secondary tx-12 articles ">Дни</button>
				</div>
				<div  class="btn-group m_a_time" role="group" style="display:none;" aria-label="Basic example">
				 <button  id="m_h_a" class="btn btn-secondary tx-12 articles ">Часы</button>
                  <button  id="m_d_a" class="btn btn-secondary tx-12 articles ">Дни</button>
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
              </div>
			   <div class="card pd-10 diagram_8 ">
               <canvas id="articles_shop_3"  ></canvas>
			  </div>
            </div><!-- card -->

          </div><!-- col-8 -->
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
			  </div>
              <div class="card pd-10 diagram_9">
               <canvas id="ucenka" ></canvas>
			  </div>
            </div><!-- card -->
          </div><!-- col-6 -->
		  <div class="col-xl-6 mg-t-20">
            <div class="card pd-20 pd-sm-40">
              <h6 class="card-body-title">Уценка</h6>
			  <div class="card-body pd-0 bd-color-gray-lighter">
			  <div class="row no-gutters tx-center">
                  <div class="col-12 col-sm-10  tx-left">
                    <p class="pd-l-20 tx-12 lh-8 mg-b-5">Отображены товары которые есть в наличии.</p>
                  </div><!-- col-4 -->
				 <div class="col-6 col-sm-2 ">
				 <p class="tx-11 mg-b-5 tx-uppercase">Всего </p>
                    <h4 class="tx-inverse tx-lato tx-bold mg-b-5" id="koll_ucenka" ></h4>
                    <p class="tx-11 mg-b-5 tx-uppercase"> Товаров</p>
                  </div>
                </div><!-- row -->
			  </div>
              <div class="card pd-10 diagram_9 ht-200 ht-sm-250" id="ucenka_2">
			  </div>
            </div><!-- card -->
          </div><!-- col-6 -->
			<div class="col-xl-6 d-none">
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
                    <p class="pd-l-20 tx-12 lh-8 mg-b-5">Диаграмма отображает заказы в статусах: Новый, Собран, Собран 2, Собран 3.</p>
                  </div><!-- col-4 -->
                </div><!-- row -->
              </div>
			   <div class="card pd-10 diagram_6">
              <canvas id="chartBar4" height="250"></canvas>
			  </div>
            </div><!-- card -->

          </div><!-- col-8 -->
          <div class="col-xl-6 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 pd-sm-25 mg-t-20">
              <h6 class="card-body-title">Заказы</h6>
              <p class="mg-b-20 mg-sm-b-30">Заказы по статусам(внутренние)</p>
              <div id="flotPie2" class="ht-200 ht-sm-250"></div>
            </div><!-- card -->
			</div>
			<div class="col-xl-6 mg-t-20 mg-xl-t-0">
            <div class="card pd-20 pd-sm-25 mg-t-20">
              <h6 class="card-body-title">Заявки</h6>
              <p class="mg-b-20 mg-sm-b-30">Заявки за последнюю неделю</p>
              <div id="flotPie3" class="ht-200 ht-sm-250"></div>
            </div><!-- card -->
			</div>
			<div class="col-xl-6 mg-t-20 mg-xl-t-0">
            <div class="card widget-messages mg-t-20">
              <div class="card-header">
                <span>Коментарии к заказам</span>
                <a href=""><i class="icon ion-more"></i></a>
              </div><!-- card-header -->
              <div class="list-group list-group-flush">
			  <?php if($this->orders_koment){
				foreach($this->orders_koment as $d){ ?>
				 <a href="<?=$this->path?>shop-orders/edit/id/<?=$d->id;?>" class="list-group-item list-group-item-action media">
                 <!-- <img src="<?=$this->files?>views/template/img/img10.jpg" alt="">-->
                  <div class="media-body">
                    <div class="msg-top">
                      <span><?=$d->id.' : '.$d->name?></span>
                      <span><?=$d->date_create?></span>
                    </div>
                    <p class="msg-summary"><?=$d->comments?></p>
                  </div><!-- media-body -->
                </a><!-- list-group-item -->
				
				<?php } } ?>
              </div><!-- list-group -->
            <!--  <div class="card-footer">
                <a href="" class="tx-12"><i class="fa fa-angle-down mg-r-3"></i> Load more messages</a>
              </div><!-- card-footer -->
            </div><!-- card -->
          </div><!-- col-3 -->
        </div><!-- row -->
  <script src="<?=$this->files?>views/template/js/ResizeSensor.js"></script>
	   
	   
    <script src="<?=$this->files?>views/template/lib/jquery.sparkline.bower/jquery.sparkline.min.js"></script>
   <script src="<?=$this->files?>views/template/lib/d3/d3.js"></script>
   <script src="<?=$this->files?>views/template/lib/chart.js/Chart.js"></script>
   <script src="<?=$this->files?>views/template/js/dashboard.js"></script>
    <script src="<?=$this->files?>views/template/lib/Flot/jquery.flot.js"></script>
   <script src="<?=$this->files?>views/template/lib/Flot/jquery.flot.pie.js"></script>	
    <script src="<?=$this->files?>views/template/lib/raphael/raphael.min.js"></script>
 <script src="<?=$this->files?>views/template/lib/morris.js/morris.js"></script>   
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

$('#h').addClass("active");
$('#h_a').addClass("active");

rickshaw2('h');
chartLine('h_a');

chartBar4('dely');
Ucenka();
Ucenka_2();
flotPie2('status');
flotPie3('quick');
});	
$('.articles').click(function(e){
  if(e.target.id == 'h') {
  $(".n_a_time").hide();
$(".m_a_time").hide();
}
$('#'+e.target.id).addClass("active");
  //rickshaw2(e.target.id);
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
      {label: "20%", value: res[20]},
      {label: "30%", value: res[30]},
	  {label: "40%", value: res[40]},
	  {label: "50%", value: res[50]},
	  {label: "60%", value: res[60]}
    ],
    colors: ['#0c8e17','#98eacc','#4a63e0','#ffccce','#ff6870', '#e40613'],
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

function Ucenka(){
var label = [];
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
        borderColor: '#5B9300',
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

</script>		