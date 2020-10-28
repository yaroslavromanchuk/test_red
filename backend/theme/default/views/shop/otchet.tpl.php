<img src="<?=$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1><br/>
  <div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Товары по категориям</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/1/" method="post">
    <p>Дата: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="day"/>  <input type="submit" class="btn btn-small btn-default" value="Скачать"/></p>
    <p>
        <input type="radio" checked="checked" value="1" name="type"/> За день <br/>
        <input type="radio" value="7" name="type"/> За неделю <br/>
        <!-- <input type="radio" value="30" name="type"/> За месяц <br/>-->
    </p>
</form>
</div>
</div>
</div>
<?php
/*
$sql = "SELECT COUNT(  `ws_articles_sizes`.`id` ) AS ctn
FROM  `ws_articles_sizes` 
INNER JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`active` =  'y'";
*/

 //$ost = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql)->at(0)->getCtn(); ?>
<!--<div class="row">
<div class="col-lg-4">
Количество товара ( <?=$ost?> ) на <?=date('d.m.Y')?>
</div>
    <div class="col-lg-2">
	<button  class="btn btn-small btn-default" onclick="Otchet(<?=$ost?>);" >Скачать отчёт <i class="glyphicon glyphicon-flash" aria-hidden="true"></i></button>
    </div>
  </div>-->

  
 <div class="mailing_start" style="display: none;">Формирование отчета, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
 <div class="row1" id="return" style="text-align:center;"></div>
 
  <div class="row">
   <div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title">Заказы за период</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/2/" method="post">
    <p class="checkbox" >
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/>   <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
	 <br>
	 <label class="ckbox">
			 <input type="checkbox" class="order-item cheker" name="no_new" id="no_new"  value="1" >
			 <span>Только активные статусы</span>
			 </label>
    </p>
    
</form>
</div>
</div>
</div>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Уценка</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/18/" method="post">
<select name="ucenka_data" class="form-control input">
<option value="">Выберите дату уценки</option>
<?php 
$sql2 = "SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ucenka_history` 
WHERE  `admin_id` =8005
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` DESC";
$ucenka = wsActiveRecord::useStatic('UcenkaHistory')->findByQueryArray($sql2);
foreach ($ucenka as $c => $uc){
echo $c.'->'.$uc->dat.'<br>';
 ?>
 <option value="<?=$uc->dat?>"><?=$uc->dat.' ('.$uc->ctn.')'?></option>
 <?php } ?>
</select>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
<!--
<form action="/admin/otchets/type/8/" method="post">
<label><h2>Отчет заказов по категориям</h2></label>
    Категория <select name="cat" class="form-control input">
			<?php
			$mas = array();
			foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('active' => 1, 'id not in(106,85,267)'), array('name' => 'ASC')) as $cat) {
			$mas[$cat->getRoutez()]['id'] = $cat->getId();
			}
			ksort($mas);
			foreach($mas as $k=>$c){
				$pos = strripos($k, 'SALE');
				if ($pos === false){
			?>
			<option value="<?=$c['id']?>" <?php if ($this->cur_category and $c['id'] == $this->cur_category->getId()) echo "selected";?> ><?=$k?></option>

		
	<?php 	}
        } ?>
    </select>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
 <hr>-->
<?php
if(/*$this->user->id == 8005*/true){ ?>
<?php
$sql = "SELECT COUNT(  `ws_articles_sizes`.`id` ) AS ctn
FROM  `ws_articles_sizes` 
INNER JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`active` =  'y'";

 $ost = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql)->at(0)->getCtn(); ?>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Продажи товара по категориям</h3></div>
<div class="panel-body">
<select  class="form-control input" id="cat_select" onChange="return Count_aricle(this);" >
<option value="">Выберите категорию</option>
<?php foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('active' => 1, 'parent_id = 0', 'id not in(106,85,267)'), array('name' => 'ASC')) as $c){ ?>
			<option value="<?=$c->id?>" <?php if ($this->cur_category and $c->id == $this->cur_category->getId()) echo "selected";?> ><?=$c->getRoutez()?></option>
	<?php } ?>
</select> Для скачивания выберите категорию
<div class="">
 <div class="mailing_start_19" style="display: none;">Формирование отчета, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
 <div class="return_19" style="text-align:center;"></div>
  <br>
</div>
</div>
</div>
</div>
 <div class="row">
 <div class="panel panel-primary">
 <div class="panel-heading"><h3 class="panel-title">Отчет по пользователям</h3></div>
<div class="panel-body">
 <script>
 function Customer_otchot(e){
 console.log(e.value);
 //window.open ( '/admin/otchets/type/22/id/'+e.value, '_blank');

 switch(e.value){
 case '1': window.open ( '/admin/otchets/type/22/id/'+e.value, '_blank'); break;
 case '2': window.open ( '/admin/otchets/type/22/id/'+e.value, '_blank'); break;
 case '3': window.open ( '/admin/otchets/type/22/id/'+e.value, '_blank'); break;
 case '4': window.open ( '/admin/otchets/type/22/id/'+e.value, '_blank'); break;
 //case '5': Otchet_21($('#count_all_customers').val()); break;
 }
 return false;
 }
 </script>
 <?php $s = "SELECT COUNT( id ) AS ctn
FROM  `ws_customers` 
WHERE  `customer_type_id` =1 ORDER BY  `ws_customers`.`id` DESC ";
$customers = wsActiveRecord::useStatic('Customer')->findByQuery($s)->at(0)->getCtn();
 ?>
 <input type="text" hidden value="<?=$customers?>" id="count_all_customers">
 <select class="form-control input"  onChange="return Customer_otchot(this);">
	<option value="">Выберите отчет</option>
	<option value="1">Топ 25 с покупками</option>
	<option value="2">Больше года с нами и регулярно заказывают</option>
	<option value="3">Пол года и более не заказывали и не заходили</option>
	<option value="4">Больше года не заказывали и не заходили</option>
	<option value="5">Все пользователи</option>
 </select>
<!--<button  class="btn btn-small btn-default" onclick="Otchet_21(<?=$customers?>);" >Скачать отчёт <i class="glyphicon glyphicon-flash" aria-hidden="true"></i></button>-->

<div class="col-lg-12">
 <div class="mailing_start_21" style="display: none;">Формирование отчета, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
 <div class="return_21" style="text-align:center;"></div>
  <br>
</div>
 </div>
 </div>

 </div>
  <div class="row">
   <div class="panel panel-primary">
    <div class="panel-heading"><h3 class="panel-title">Отчет по уцененному товару</h3></div>
<div class="panel-body">
  <?php
$sql = "SELECT `ws_articles`.`ucenka` , SUM( `ws_articles_sizes`.`count` ) AS ctn
FROM `ws_articles`
INNER JOIN `ws_articles_sizes` ON `ws_articles`.`id` = `ws_articles_sizes`.`id_article`
WHERE `ws_articles_sizes`.`count` >0
AND `ws_articles`.status in (3,4)
GROUP BY `ws_articles`.`ucenka` ";
$ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
?>
  <select name="ucenka_proc" id="ucenka_proc" class="form-control input"  >
  <option value="">Выберите процент уценки</option>
<?php
foreach($ucenka as $c){ ?>
 <option value="<?=$c->ucenka?>" data-proc="<?=$c->ucenka?>"><?=$c->ucenka.'% ('.$c->ctn.')'?></option>
<?php }
  ?>
</select> Для скачивания выберите процент уценки

<div class="col-lg-12">
        <div class="return_ucenka" id="return_ucenka" style="text-align:center;">
 
    </div>
 </div>
  </div>
  </div>
  </div>
  <script>
  function Count_aricle(e){
  
 // console.log(e.value);
		$.ajax({
                url: "/ajax/getcountarticlescategory/",
                type: 'POST',
				dataType: 'json',
                data: "&cat="+e.value,
                success: function (res) {
				//$('#cat').val(res.ctn);
				//$('#cat_list').val(res.category);
				console.log(res);
				Otchet_19(res.ctn, res.category);
                },
				error: function(res){
				console.log(res);
					}
            });



  return false;
  }
  
    $("#ucenka_proc").change(function() {
		$.ajax({
                url: '/report/otchets/type/5/',
                type: 'POST',
                dataType: 'json',
                data: {method:'list_articles', proc : $(this).val()},
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                success: function (res) {
				console.log(res);
				var l = res.length;
                                console.log(l);
                                
                                $('#table_return_ucenka').detach();
                                $('#download_table_return_ucenka').detach();
                            
                                var t = document.getElementById("return_ucenka");
                                var button = document.createElement("button");
                                      
    button.innerHTML = "Скачать таблицу";
    button.setAttribute("class", "btn btn-danger");
    button.setAttribute("id", "download_table_return_ucenka");
    button.setAttribute("onClick", "return download('table_return_ucenka')");
                                
                    var table = document.createElement("table");
                    table.setAttribute("id", "table_return_ucenka");
                    table.setAttribute("class", "table");
                    t.appendChild(table);
                    t.appendChild(button);
                             
var header = table.createTHead();
  var row = header.insertRow(0);
            row.insertCell(0).innerHTML = "<b>Бренд</b>";
            row.insertCell(1).innerHTML = "<b>Категория</b>";
            row.insertCell(2).innerHTML = "<b>Модель</b>";
            row.insertCell(3).innerHTML = "<b>Артикул</b>";
            row.insertCell(4).innerHTML = "<b>Цвет</b>";
            row.insertCell(5).innerHTML = "<b>Размер</b>";
            row.insertCell(6).innerHTML = "<b>Приход</b>";
            row.insertCell(7).innerHTML = "<b>Расход</b>";
            row.insertCell(8).innerHTML = "<b>Возвраты</b>";
            row.insertCell(9).innerHTML = "<b>Остаток</b>";
            row.insertCell(10).innerHTML = "<b>Уценка</b>";
            row.insertCell(11).innerHTML = "<b>Цена до уценки</b>";
            row.insertCell(12).innerHTML = "<b>Цена после уценки</b>";
            row.insertCell(13).innerHTML = "<b>Добавлен</b>";
            row.insertCell(14).innerHTML = "<b>Уценен</b>"; 
            row.insertCell(15).innerHTML = "<b>ИД</b>"; 
             var body = table.createTBody();
            
             console.log(res[0]);
             size_load(0, res, body);                
                },
				error: function(res){
				console.log(res);
					}
            });
        
	//var proc = $('#ucenka_proc option:selected').html();
	//Otchet_ucenka(v, proc);
	
	//console.log($('#ucenka_proc option:selected').html());

});

function size_load(key, array, body){
    console.log(array);
    $.ajax({
                url: '/report/otchets/type/5/',
                type: 'POST',
                dataType: 'json',
                data: { id : array[key], key: key},
                success: function (res) {
                    console.log(res);
                    if(res.mas.length > 0){
                    for(var i in res.mas){
                var row = body.insertRow(); 
    row.insertCell(0).innerHTML = res.mas[i].brand;
    row.insertCell(1).innerHTML = res.mas[i].h1;
    row.insertCell(2).innerHTML = res.mas[i].model;
    row.insertCell(3).innerHTML = res.mas[i].acode;
    row.insertCell(4).innerHTML = res.mas[i].colors;
    row.insertCell(5).innerHTML =res.mas[i].sizes;
    row.insertCell(6).innerHTML = res.mas[i].prichod;
    row.insertCell(7).innerHTML = res.mas[i].rozhod;
    row.insertCell(8).innerHTML = res.mas[i].vozrat;
    row.insertCell(9).innerHTML = res.mas[i].sklad;
    row.insertCell(10).innerHTML = res.mas[i].ucenka;
    row.insertCell(11).innerHTML = res.mas[i].old_price;
    row.insertCell(12).innerHTML = res.mas[i].price;
    row.insertCell(13).innerHTML = res.mas[i].data_new;
    row.insertCell(14).innerHTML = res.mas[i].data_ucenki;
    row.insertCell(15).innerHTML = res.mas[i].id;
    }
    }
    $('#download_table_return_ucenka').focus();
                    if(res.key < array.length){
                        size_load(res.key, array,  body);
                    }else{
                        $('#foo').detach(); 
                    }
                },error: function(res){
				console.log(res);
					}
});
}

  </script>
  
  
  
<?php } ?>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Отчет по бренду за период</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/4/" method="post">
    Бренд <select name="brend" class="form-control input select2-show-search">
        <?php foreach (wsActiveRecord::useStatic('Brand')->findAll() as $brand) { ?>
            <option value="<?=$brand->getId()?>"><?=$brand->getName()?></option>
        <?php } ?>
    </select><br>
	 Дата добавления товаров с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="add_from"/>
    по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="add_to"/> <br/>
	
    Дата создания заказов с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
    по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/> <br/>

    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
  <div class="row">
<div class="panel panel-success">
<div class="panel-heading"><h3 class="panel-title">Отчет по накладной</h3></div>
<div class="panel-body">
    <div class="col-lg-8">
    <form action="/report/otchets/type/44/" method="post"  id="load_nakladna">
       <div class="input-group">
  <span class="input-group-addon" id="basic-addon1">Накладная №</span>
  <input type="text" class="form-control" name="ttn" placeholder="хххххх" onkeyup="this.value = this.value.replace (/[^\d,]/g, '')" aria-describedby="basic-addon1">
  <span class="input-group-btn">
        <input type="submit" class="btn btn-small btn-primary" value="Смотреть"/>
      </span>
</div> 
</form>
        </div>
</div>
    <div class="panel-body" id="result_load_nakladna"></div>
</div>
      <script>
              $("#load_nakladna").submit(function(){
    // для читаемости кода
    var form = $(this);
    // вы же понимаете, о чём я тут толкую?
    // это ведь одна из ипостасей AJAX-запроса 

         $.ajax({
                url: form.attr("action"),
                type: 'POST',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                success: function (res) {
				//console.log(res);
                                 var t = document.getElementById("result_load_nakladna");
                                 
                                $('#table_result_load_nakladna').detach();
                                $('#download_result_load_nakladna').detach();
                             var table = document.createElement("table");
                    table.setAttribute("id", "table_result_load_nakladna");
                    table.setAttribute("class", "table");
                    t.appendChild(table);
                              // if(!$('button').is('#download_result_load_nakladna')){
                                  var button = document.createElement("button"); 
                                        button.innerHTML = "Скачать таблицу";
                                        button.setAttribute("class", "btn btn-danger");
                                        button.setAttribute("id", "download_result_load_nakladna");
                                        button.setAttribute("onClick", "return download('table_result_load_nakladna')");
                                t.appendChild(button); 
                               //}
     
                             
var header = table.createTHead();
  var row = header.insertRow(0);
            row.insertCell(0).innerHTML = "<b>Накладная</b>";
            row.insertCell(1).innerHTML = "<b>Приход</b>";
            row.insertCell(2).innerHTML = "<b>Расход</b>";
            row.insertCell(3).innerHTML = "<b>Остаток</b>";
            row.insertCell(4).innerHTML = "<b>Продано грн.</b>";
            row.insertCell(5).innerHTML = "<b>Маржа ед</b>";
            row.insertCell(6).innerHTML = "<b>Маржа</b>"; 
            row.insertCell(7).innerHTML = "<b>С/С продано</b>";
            row.insertCell(8).innerHTML = "<b>С/С остатка</b>";
            row.insertCell(9).innerHTML = "<b>С/С приход</b>";
            row.insertCell(10).innerHTML = "<b>Дней</b>";
            row.insertCell(11).innerHTML = "<b>Просмотров</b>";
          
             var body = table.createTBody();
            var prihod = 0;
            var prodano = 0;
            var ostatok = 0;
            var prodano_summ = 0;
            var marga_prodano = 0;
            var ss = 0;
            var ss_ostatok = 0;
            var ss_prihod = 0;
            var views = 0;
           
                    for(var i in res){
                var row = body.insertRow(); 
    row.insertCell(0).innerHTML = res[i].code;
    row.insertCell(1).innerHTML = res[i].prihod;
        prihod+=parseInt(res[i].prihod);
    row.insertCell(2).innerHTML = res[i].prodano;
        prodano+=parseInt(res[i].prodano);
    row.insertCell(3).innerHTML = res[i].ostatok;
        ostatok+=parseInt(res[i].ostatok);
    row.insertCell(4).innerHTML = res[i].prodano_summ;
        prodano_summ+=parseInt(res[i].prodano_summ);
    row.insertCell(5).innerHTML = res[i].marga_od;
    row.insertCell(6).innerHTML = res[i].marga_prodano;
        marga_prodano+=parseInt(res[i].marga_prodano);
    row.insertCell(7).innerHTML = res[i].ss;
        ss+=parseInt(res[i].ss);
    row.insertCell(8).innerHTML = res[i].ss_ostatok;
        ss_ostatok+=parseInt(res[i].ss_ostatok);
    row.insertCell(9).innerHTML = res[i].ss_prihod;
        ss_prihod+=parseInt(res[i].ss_prihod);
    row.insertCell(10).innerHTML = res[i].day;   
    row.insertCell(11).innerHTML = res[i].views; 
        views+=parseInt(res[i].views);
    }
    var foot = table.createTFoot();
    var row = foot.insertRow();
    row.insertCell(0).innerHTML = "";
            row.insertCell(1).innerHTML = "<b>"+prihod+"</b>";
            row.insertCell(2).innerHTML = "<b>"+prodano+"</b>";
            row.insertCell(3).innerHTML = "<b>"+ostatok+"</b>";
            row.insertCell(4).innerHTML = "<b>"+prodano_summ+"</b>";
            row.insertCell(5).innerHTML = "";
            row.insertCell(6).innerHTML = "<b>"+marga_prodano+"</b>"; 
            row.insertCell(7).innerHTML = "<b>"+ss+"</b>";
            row.insertCell(8).innerHTML = "<b>"+ss_ostatok+"</b>";
            if((ss + ss_ostatok) === ss_prihod){
                var color = "style='color:#00d200'";
            }else{
                var color = "";
            }
            row.insertCell(9).innerHTML = "<b "+color+" >"+ss_prihod+"</b>";
            row.insertCell(10).innerHTML = "";
            row.insertCell(11).innerHTML = "<b>"+views+"</b>";
           
                },
				error: function(res){
				console.log(res);
                                 $('#foo').detach();
					}
            }).done(function() {
         $('#foo').detach();
        });
          return false;
      });
      </script>
</div>
  <div class="row">
<div class="panel panel-success">
<div class="panel-heading"><h3 class="panel-title">Отчет остатки категории по грейдам</h3></div>
<div class="panel-body">
    <div class="col-lg-8">
    <form action="/report/otchets/type/45/" method="post"  id="load_ostatok_graid">
       <p class="checkbox">
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d', strtotime('-60 days'))?>" name="from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="to"/><input type="submit" class="btn btn-small btn-default" value="Скачать"/>
    </p>
</form>
        </div>
</div>
    <div class="panel-body" id="result_load_ostatok_graid"></div>
</div>
    <script>
              $("#load_ostatok_graid").submit(function(){
                    var form = $(this);
                    $.ajax({
                url: form.attr("action"),
                type: 'POST',
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                success: function (res) {
				console.log(res);
                                 var t = document.getElementById("result_load_ostatok_graid");
                                 
                                $('#table_result_load_ostatok').detach();
                                $('#download_result_load_ostatok').detach();
                             var table = document.createElement("table");
                    table.setAttribute("id", "table_result_load_ostatok");
                    table.setAttribute("class", "table");
                    t.appendChild(table);
                              // if(!$('button').is('#download_result_load_nakladna')){
                                  var button = document.createElement("button"); 
                                        button.innerHTML = "Скачать таблицу";
                                        button.setAttribute("class", "btn btn-danger");
                                        button.setAttribute("id", "download_result_load_ostatok");
                                        button.setAttribute("onClick", "return download('table_result_load_ostatok')");
                                t.appendChild(button); 
                               //}
     
                             
var header = table.createTHead();
  var row = header.insertRow(0);
            row.insertCell(0).innerHTML = "<b>Грейд</b>";
            row.insertCell(1).innerHTML = "<b>Категория</b>";
            row.insertCell(2).innerHTML = "<b>Ср.Остаток</b>";
            row.insertCell(3).innerHTML = "<b>Ср.Сум.Остаток</b>";
          
             var body = table.createTBody();
          /*  var prihod = 0;
            var prodano = 0;
            var ostatok = 0;
            var prodano_summ = 0;
            var marga_prodano = 0;
            var ss = 0;
            var ss_ostatok = 0;
            var ss_prihod = 0;
            var views = 0;*/
           
                    for(var i in res){
                        for(var j in res[i]){
                            var row = body.insertRow(); 
    row.insertCell(0).innerHTML = 'Грейд '+i;
    row.insertCell(1).innerHTML = res[i][j].h1;
      //  prihod+=parseInt(res[i].prihod);
    row.insertCell(2).innerHTML = res[i][j].ctn;
       // prodano+=parseInt(res[i].prodano);
    row.insertCell(3).innerHTML = res[i][j].summ;
                        }
                
       // ostatok+=parseInt(res[i].ostatok);
   // row.insertCell(4).innerHTML = res[i].prodano_summ;
      //  prodano_summ+=parseInt(res[i].prodano_summ);
  //  row.insertCell(5).innerHTML = res[i].marga_od;
   // row.insertCell(6).innerHTML = res[i].marga_prodano;
       // marga_prodano+=parseInt(res[i].marga_prodano);
   // row.insertCell(7).innerHTML = res[i].ss;
      //  ss+=parseInt(res[i].ss);
  //  row.insertCell(8).innerHTML = res[i].ss_ostatok;
       // ss_ostatok+=parseInt(res[i].ss_ostatok);
 //   row.insertCell(9).innerHTML = res[i].ss_prihod;
       // ss_prihod+=parseInt(res[i].ss_prihod);
  //  row.insertCell(10).innerHTML = res[i].day;   
  //  row.insertCell(11).innerHTML = res[i].views; 
      //  views+=parseInt(res[i].views);
    }
    /*
    var foot = table.createTFoot();
    var row = foot.insertRow();
    row.insertCell(0).innerHTML = "";
            row.insertCell(1).innerHTML = "<b>"+prihod+"</b>";
            row.insertCell(2).innerHTML = "<b>"+prodano+"</b>";
            row.insertCell(3).innerHTML = "<b>"+ostatok+"</b>";
            row.insertCell(4).innerHTML = "<b>"+prodano_summ+"</b>";
            row.insertCell(5).innerHTML = "";
            row.insertCell(6).innerHTML = "<b>"+marga_prodano+"</b>"; 
            row.insertCell(7).innerHTML = "<b>"+ss+"</b>";
            row.insertCell(8).innerHTML = "<b>"+ss_ostatok+"</b>";
            if((ss + ss_ostatok) === ss_prihod){
                var color = "style='color:#00d200'";
            }else{
                var color = "";
            }
            row.insertCell(9).innerHTML = "<b "+color+" >"+ss_prihod+"</b>";
            row.insertCell(10).innerHTML = "";
            row.insertCell(11).innerHTML = "<b>"+views+"</b>";
           */
                },
				error: function(res){
				console.log(res);
                                 $('#foo').detach();
					}
            }).done(function() {
         $('#foo').detach();
        });
                  return false;
              });
    </script>
</div>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Товары с блоками скидок</h3></div>
<div class="panel-body">
<a href="/admin/zgoda">Согласия пользователей</a>
<form action="/report/otchets/type/6/" method="post">
    <p>
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/> <br/>
        <input type="checkbox" name="no_new" value="1"> только статусы (В процессе, Доставлен в магазин, Отправлен
        укрпочтой, Отправлен Новая почта, Оплачен, Собран, Ждёт оплаты, В процессе доставки)
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Уценка</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/7/" method="post">
<h2></h2>
    <p>
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/> <br/>
        <input type="checkbox" name="to_day" value="1"> по дням
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
    </div>
</div>
</div>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Уценка по каждому товару</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/14/" method="post">
    <p>
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/> <br/>
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Количество товаров по ключевым категориям</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/9/" method="post">
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
  <div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Количество товаров по Брендам (все что есть в наличии)</h3></div>
<div class="panel-body">
<form action="/report/otchets/type/10/" method="post">
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>

<!--
<form action="/admin/otchets/type/10/" method="post">
<h2>Отчёт по покупонам</h2>
    <input type="submit" value="Скачать"/>
</form>
-->
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Заказы по Городам</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/11/" method="post">
    <p>
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/> <br/>

    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
<!--
<form action="/admin/otchets/type/12/" method="post">
<h2>Заказы по промокоду red2014</h2>
    <input type="submit" value="Скачать"/>
</form>
-->
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Товары в избранном</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/15/" method="post">
<h2></h2><br>
<table>
<tr>
<td>
    Бренд <select name="brend" onChange="brends(this.value)"; id="br" class="form-control input" >
	<option value="0">Бренд</option>
        <?php
		$sql = "SELECT  `as`.`code` ,  `d`.`id_articles` ,  `a`.`brand` ,  `a`.`brand_id` ,  `a`.`model` ,  `a`.`price` 
FROM  `ws_desires` AS  `d` 
LEFT JOIN  `ws_articles_sizes` AS  `as` ON  `d`.`id_articles` =  `as`.`id_article` 
LEFT JOIN  `ws_articles` AS  `a` ON  `d`.`id_articles` =  `a`.`id` 
WHERE EXISTS (
SELECT  `d`.`id_articles` 
FROM  `ws_desires`
)
GROUP BY  `a`.`brand_id`";
$mas = wsActiveRecord::findByQueryArray($sql);
		foreach ($mas as $brand) { ?>
            <option value="<?=$brand->brand_id?>"><?=$brand->brand?></option>
        <?php } ?>
    </select>
	</td>
	<td  >
	<div id="model" >
	<div class="model_fac">
	</div>
	</div>
	</td>
	</tr>
	<tr>
	<td>
		Модель <select name="model" onChange="models(this.value)"; id="md" class="form-control input">
	<option value="Модель">Модель</option>
        <?php
		
		$sql = "SELECT  `as`.`code` ,  `d`.`id_articles` ,  `a`.`brand` ,  `a`.`brand_id` ,  `a`.`model` ,  `a`.`price` 
FROM  `ws_desires` AS  `d` 
LEFT JOIN  `ws_articles_sizes` AS  `as` ON  `d`.`id_articles` =  `as`.`id_article` 
LEFT JOIN  `ws_articles` AS  `a` ON  `d`.`id_articles` =  `a`.`id` 
WHERE EXISTS (
SELECT  `d`.`id_articles` 
FROM  `ws_desires`
)
GROUP BY  `a`.`model`";
$mas = wsActiveRecord::findByQueryArray($sql);
		foreach ($mas as $model) { ?>
            <option value="<?=$model->model?>"><?=$model->model?></option>
        <?php } ?>
    </select>
	</td>
	<td>
	<div id="brend" >
	<div class="brend_fac">
	</div>
	</div>
	</td>
	</tr>
	<tr>
		<td>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
	</td>
	</tr>
	</table>
</form>
    </div>
</div>
</div>
<script>
 function brends(x){
document.getElementById('md').disabled = true;
 $.get('/admin/otchets/id/' + x + '/type/getbrends/',
		function (data) {
		 $('#model .model_fac').html(data);
	
		});
}
 function models(x){ 
 document.getElementById('br').disabled = true;
 $.get('/admin/otchets/name/' + x + '/type/getmodels/',
		function (data) {
		 $('#brend .brend_fac').html(data);
	
		});
}
</script>
<div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Отчет по уведомлениях</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/16/" method="post">
    <select name="category" class="form-control input">
	<option >Выберите категорию</option>
	<option value="0">Все уведомления</option>
	<option value="1">Ждут уведомления</option>
	<option value="2">Получили уведомления</option>
    </select>  
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>

  <div class="row">
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">Отчет для закупщиков!</h3></div>
<div class="panel-body">
<form action="/admin/otchets/type/13/" method="post">

    Дата поступления товаров с: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_from"/>
    по: <input type="date" class="form-control input" value="<?=date('Y-m-d')?>" name="order_to"/>  <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
</div>
</div>
</div>
<script>
 function Otchet_19(e, cat){
 //var cat = $('#cat_list').val();

// console.log(e, cat);
var day = new Date();
console.log('start-'+day.getHours()+':'+day.getMinutes()+':'+day.getSeconds());
 var start = 0;
var end = e;  
 //var end = 50; 
//console.log(end);
 $('.return_19').html('<img src="/backend/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
 $('.mailing_start_19').show();
 
 send19(start, end, cat);
  
 return false;
 
 }
  function Otchet_21(e){
 //var cat = $('#cat_list').val();

// console.log(e, cat);
var day = new Date();
console.log('start-'+day.getHours()+':'+day.getMinutes()+':'+day.getSeconds());
 var start = 0;
var end = e;  
 //var end = 50; 
//console.log(end);
 $('.return_21').html('<img src="/backend/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
 $('.mailing_start_21').show();
 
 send21(start, end);
  
 return false;
 
 }
 function send21(start, end){
 
 console.log(start, end);
 //return false;
 var url = '/admin/otchets/type/21/';
		var new_data = '&start='+start+'&end='+end;
		console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				if(!res.exit){
				var proc = (res.start / res.end) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
				//console.log(res);
				if(res.start < res.end){
				console.log(res);
				send21(res.start, res.end);
				}
				}else{
				
				var d = new Date();
				console.log('exit-'+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
				 $('.mailing_start_21').hide();
				 var text = '';
				text+= '<p style="font-size:16px;padding:15px;color:red;">'+res.exit+'</p>';
				text+= '<p style="padding: 10px;"><a href="'+res.src+'" style="font-size:14px;"> > СКАЧАТЬ ФАЙЛ < </a></p>';
				//console.log(text);
				//alert(res.exit);
				$('.return_21').html(text);
				//window.open(res.src);
				}
                },
				error: function(res){
				var d = new Date();
				console.log('exit-'+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
				console.log(res);
					}
            });
 
 //return false;
 }
  function Otchet_ucenka(e, proc){
  
  
  var day = new Date();
console.log('start-'+day.getHours()+':'+day.getMinutes()+':'+day.getSeconds());
 var start = 0;
var end = e;  
 //var end = 50; 
//console.log(end);
 //$('.return_ucenka').html('<img src="/backend/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
 //$('.mailing_start_19').show();
 
 
   var url = '/report/otchets/type/5/';
 console.log(url);
		var new_data = '&method=list_brand&proc='+proc;
		console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                success: function (res) {
				console.log(res);
				var r = 0;
				var l = res.length;
				var i = 0;
				console.log(l);
				for (var key in res) {
				var rr = send_uc(r, end, proc, res[key]);
				console.log(rr);
				r = rr;
				i++;	
				}
				var d = new Date();
				var dd = d.getDate();
				var mm = d.getMonth()+1;
			if(dd<10) { dd = '0'+dd; } 
			if(mm<10) { mm = '0'+mm; } 
			console.log(res);
				 var name = 'otchet_ucenka_'+proc+'_'+dd+'-'+mm+'-'+d.getFullYear()+'.xls';
                                 
				$('.return_ucenka').html('<p style="font-size:16px;padding:15px;color:red;">Отчёт сформирован!</p><p style="padding: 10px;"><a href="https://www.red.ua/backend/excel/'+name+'" style="font-size:14px;"> > СКАЧАТЬ ФАЙЛ < </a></p>Отчет создан');
                                $('.return_ucenka').focus();
    $('.modal-backdrop').hide();
                                $('#foo').detach();
				console.log('exit-'+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
                },
				error: function(res){
				console.log(res);
					}
            });

 return false;
 
 }
  function send_uc(start, end, proc, brand){
  
 var ret;
 var url = '/report/otchets/type/5/';
		var new_data = '&start='+start+'&end='+end+'&proc='+proc+'&brand='+brand;
		console.log(new_data);
	  response = $.ajax({
                url: url,
		async: false,
                type: 'POST',
                dataType: 'json',
                data: new_data
            });
			ret = $.parseJSON(response.responseText);
			ret = ret.start;

    return ret;

 }
 function send19(start, end, cat){
 
 console.log(start, end, cat);
 //return false;
 var url = '/admin/otchets/type/19/';
		var new_data = '&start='+start+'&end='+end+'&cat='+cat;
		console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				if(!res.exit){
				var proc = (res.start / res.end) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
				//console.log(res);
				if(res.start < res.end){
				console.log(res);
				send19(res.start, res.end, res.cat);
				}
				}else{
				
				var d = new Date();
				console.log('exit-'+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
				 $('.mailing_start_19').hide();
				 var text = '';
				text+= '<p style="font-size:16px;padding:15px;color:red;">'+res.exit+'</p>';
				text+= '<p style="padding: 10px;"><a href="'+res.src+'" style="font-size:14px;"> > СКАЧАТЬ ФАЙЛ < </a></p>';
				//console.log(text);
				//alert(res.exit);
				$('.return_19').html(text);
				//window.open(res.src);
				}
                },
				error: function(res){
				var d = new Date();
				console.log('exit-'+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
				console.log(res);
					}
            });
 
 //return false;
 }
 function Otchet(e){
var day = new Date();
console.log(day.getHours()+':'+day.getMinutes()+':'+day.getSeconds());
 var start = 0;
//var end = e;  
 var end = 50; 
console.log(end);
 $('#return').html('<img src="/backend/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
 $('.mailing_start').show();
 
 send(start, end);
  
 return false;
 
 }
 function send(start, end){
 
 var url = '/admin/otchets/type/17/';
		var new_data = '&start='+start+'&end='+end;
		console.log(new_data);
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				if(!res.exit){
				var proc = (res.start / res.end) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
				//console.log(res);
				if(res.start < res.end){
				console.log(res);
				send(res.start, res.end);
				}
				}else{
				var d = new Date();
				console.log(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
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
				var d = new Date();
				console.log(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds());
				console.log(res);
					}
            });
 
 //return false;
 }
</script>