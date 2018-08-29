<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?=$this->getCurMenu()->getPageBody()?>
 <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div style="text-align: center;" id="d" >
<h2>Выберите интервал дат!</h2>
<p>от:<input class="form-control input" name="from"  type="date" id="from" > | до:<input class="form-control input" name="to"  type="date" id="to"></p>
</div>
 <div id="all_order" style="border: 1px dotted #aaa;padding: 10px;">
 <table align="center">
 <tr>
 <td>
	<div style="border: 1px dotted #aaa;padding: 10px;" ><h2>Общий график заказов</h2>
	<p><button class="knopka" name="send_ttn" id="send_ttn" onclick="go($('#from').val(), $('#to').val(), 1);">По дням</button> | <button class="knopka" name="send_ttn1" id="send_ttn1" onclick="go($('#from').val(), $('#to').val(), 2);">По неделям</button> | <button class="knopka" name="send_ttn2" id="send_ttn2" onclick="go($('#from').val(), $('#to').val(), 3);">По месяцам</button></p></div>
	</td>
	<td>
	<div style="border: 1px dotted #aaa;padding: 10px;"><h2>График заказов  по способу доставки</h2>
	<p><button class="knopka" name="send_ttn" id="send_ttn" onclick="godelivery($('#from').val(), $('#to').val(), 1);">По дням</button> | <button class="knopka" name="send_ttn1" id="send_ttn1" onclick="godelivery($('#from').val(), $('#to').val(), 2);">По неделям</button> | <button class="knopka" name="send_ttn2" id="send_ttn2" onclick="godelivery($('#from').val(), $('#to').val(), 3);">По месяцам</button></p></div>
	</td>
	</tr>
	</table>
	</div>
 <div id="curve_chart" ></div>
 <div class="row">
 <!--<select name="brand" id="brand">
 <option>Выберите бренд</option>
 <option value="0">Все</option>
 <?php
if(false){
 foreach(wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT `id_brand` FROM  `ws_balance_category`") as $b){ ?>
 <option value="<?=$b->id_brand?>"><?=$b->getArticleBrand()->getName()?></option>
 <?php } }?>
 </select>-->
 ot <input type="date" name="b_d_from" id="b_d_from">
 do <input type="date" name="b_d_to" id="b_d_to">
 <input type="button" name="go" onclick="Otchet(this);" value="Показать">

 <div class="mailing_start" style="display: none;">Формирование отчета, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
 </div>
 <div class="row1" id="return" style="text-align:center;">
 
 </div>
 <?php if($this->balance){ ?>
 <div class="row1">
 <table class="table table-responsive table-hover">
 <thead>
        <tr><th>Дата</th></tr>
    </thead>
	<?php
$i = 0;
	foreach($this->balance as $b){ ?>
	<tbody>
        <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-<?=$b->id.'_'.$i?>" aria-expanded="false" aria-controls="group-of-rows-<?=$b->id.'_'.$i?>">
            <td><i class="fa fa-plus" aria-hidden="true"></i> + <?=$b->date?></td>  
        </tr>
    </tbody>
	 <tbody id="group-of-rows-<?=$b->id.'_'.$i?>" class="collapse">
	<tr>
		<td>
		
	<table class="table table-responsive table-hover">
	 <tr><th>Бренд</th><th>Остаток</th></tr>
		
		<?php 
		foreach(wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT DISTINCT  `id_brand` 
FROM  `ws_balance_category` 
WHERE  `id_balance` =".$b->id." ORDER BY  `ws_balance_category`.`count` DESC") as $brand){
 ?><tbody>
        <tr class="clickable" data-toggle="collapse" data-target="#group-of-rows-<?=$b->id.'_'.$brand->id_brand?>" aria-expanded="false" aria-controls="group-of-rows-<?=$b->id.'_'.$brand->id_brand?>">
            <td><i class="fa fa-plus" aria-hidden="true"></i> -> + <?=$brand->getArticleBrand()->getName()?></td>
            <td><?=$brand->getCountBrand($b->id)?></td>
        </tr>
		    </tbody>
		<tbody id="group-of-rows-<?=$b->id.'_'.$brand->id_brand?>" class="collapse">
	<tr>
		<td colspan="3">
		
	<table class="table table-responsive table-hover">
	 <tr><th>Категория</th><th>Остаток</th></tr>
		<?php if(false){ foreach(wsActiveRecord::useStatic('BalanceCategory')->findByQuery("SELECT `id_category` ,  `count` 
FROM  `ws_balance_category` 
WHERE  `id_balance` = ".$b->id." and `id_brand` = ".$brand->id_brand." ORDER BY  `ws_balance_category`.`count` DESC ") as $cat){  ?>
			<tbody>
			<tr>
				<td><?=$cat->getCategoryName()->getRoutez()?></td>
				<td><?=$cat->count?></td>
			</tr>
			</tbody>
		
		<?php } }?>
		
	</table>	
		</td>
			</tr>
	</tbody>

<!-- tut-->
<?php 	}//brand ?>

	</table>
		
		</td>
	</tr>
 </tbody>
		
		
<?php	$i++;	}//balance

			?>

 </table>
 </div>
 <?php } ?>
 <script>
 function Otchet(e){
 //var brand  = $('#brand').val();
 //if(brand == 'Выберите бренд'){ brand = '';}
 var from = $('#b_d_from').val();
 var to = $('#b_d_to').val();
 
 var from_d = new Date(from);
  var to_d = new Date(to);
  
  //end.diff(start, "days")
  
 //var f1 = d.getDate() +1;
 //d.format("yyyy-mm-dd");
 var timeDiff = Math.abs(to_d.getTime() - from_d.getTime());
var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
//alert(diffDays+=1);



 //console.log(from);
 //console.log(to);
// console.log(to);
 //console.log(d.toString());
 
 start = 2;
 finish = 0;
 dney = diffDays+=1;
 //dney.toInteger();
 $('#return').html('<img src="/admin_files/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
 $('.mailing_start').show();
 send(start, from, to, finish, dney);
  
 return false;
 
 }
 function send(start, from, to, finish, dney){
 
 var url = '/admin/analitics/';
		var new_data = '&method=othot&from='+from+'&to='+to+'&start='+start+'&finish='+finish+'&dney='+dney;
		console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				if(!res.exit){
				var proc = (res.finish / res.dney) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
				//console.log(res);
				if(res.finish < res.dney){
				console.log(res);
				//console.log(res.mas);
				//var result = JSON.parse(res.mas);
				//console.log(result);
				//arr = res.mas;
				send(res.start, res.from, to, res.finish, res.dney);
				
				}
				}else{
				 $('.mailing_start').hide();
				 var text = '';
				text+= '<p style="font-size:16px;padding:15px;color:red;">'+res.exit+'</p>';
				text+= '<p style="padding: 10px;"><a href="'+res.src+'" style="font-size:14px;"> > СКАЧАТЬ ФАЙЛ < </a></p>';
				//console.log(text);
				//alert(res.exit);
				$('#return').html(text);
				//window.open(res.src);
				}
                },
				error: function(res){
				console.log(res);
					}
            });
 
 //return false;
 }
 
 function go(x,y,z) {
 if(x!='' && y!=''){
 var cats = [];
var dat = [];
var dat2 = [];
		var url = '/admin/analitics/';
		var new_data = '&from='+x+'&to='+y+'&metod=goo&interval='+z;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//alert(res.send2);
					//alert(res.year);
					for (i=0; i< res.send.length; i++) {
					
    cats.push(res.year[i]); 
    dat.push(res.send[i]); 
	dat2.push(res.send2[i]); 
	
  }
                }
            }).done(function() {
   chart.xAxis[0].setCategories(cats);
   chart.series[0].setData(dat);  
   chart.series[1].setData(dat2);
   
})
var chart = new Highcharts.Chart({
title: {
                text: 'Статистика заказов'
            },
  chart: {
 type: 'spline',
    renderTo: 'curve_chart'//,
    //marginBottom: 80
  },
   xAxis: {
               // type: 'datetime',
			//	title: {
                  //  text: 'Заказы'
                //},
				labels: {
      rotation: 90
    }
            },
			
            yAxis: {
                title: {
                    text: 'Количество заказов'
                }
            },
  series: [{
  
   //type: 'area',
   name:'Заказали',
    data: [0] 
  },
  {
   //type: 'area',
   name: 'Вернулись',
    data: [1]  
  }
  ]
});
 
}else{
alert('Укажите две даты для построения графика.');
if(x=='') {$('#from').focus();}else{$('#to').focus();}
}

		//return false;
}

 function godelivery(x,y,z) {
 if(x!='' && y!=''){
 var cats = [];
var m = [];
var np = [];
var k = [];
var up = [];
var r = [];
		var url = '/admin/analitics/';
		var new_data = '&fromd='+x+'&tod='+y+'&metod=goodelivery&interval='+z;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//alert(res.send_m);
					//alert(res.year);
					for (i=0; i<res.year.length; i++) {
    cats.push(res.year[i]);
	
    m.push(res.send_m[i]); 
	np.push(res.send_np[i]); 
	k.push(res.send_k[i]); 
	up.push(res.send_up[i]); 
	r.push(res.send_retupn[i]);
  }
                }
            }).done(function() {
   chart.xAxis[0].setCategories(cats);
   
   chart.series[0].setData(m);  
   chart.series[1].setData(np);
   chart.series[2].setData(k);
   chart.series[3].setData(up);
   chart.series[4].setData(r);
})
var chart = new Highcharts.Chart({
title: {
                text: 'Статистика заказов'
            },
  chart: {

 type: 'spline',
    renderTo: 'curve_chart'//,
    //marginBottom: 80
  },

   xAxis: {
               // type: 'datetime',
			//	title: {
                  //  text: 'Заказы'
                //},
				labels: {
      rotation: 90
    }
            },
            yAxis: {
                title: {
                    text: 'Количество заказов'
                }
            },
  series: [{
  
   //type: 'area',
   name:'Магазины',
    data: [0]  
  },
  {
   //type: 'area',
   name: 'Нова Почта',
    data: [1]  
  },
  {
   //type: 'area',
   name: 'Курьерские',
    data: [2]  
  },
  {
   //type: 'area',
   name: 'Укр Почта',
    data: [3]  
  },
  {
   //type: 'area',
   name: 'Возвраты',
    data: [4]  
  }
  ]
});
 
}else{
alert('Укажите две даты для построения графика.');
if(x=='') {$('#from').focus();}else{$('#to').focus();}
}

		//return false;
}		


</script>