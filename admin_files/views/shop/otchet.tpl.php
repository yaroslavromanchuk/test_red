<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?></h1><br/>
<form action="/admin/otchets/type/1/" method="post">
<h2>Товары по категориям</h2>
    <p>Дата: <input type="date" class="form-control input" value="<?=date('Y-m-d');?>" name="day"/></p>
    <p>
        <input type="radio" checked="checked" value="1" name="type"/> За день <br/>
        <input type="radio" value="7" name="type"/> За неделю <br/>
        <!-- <input type="radio" value="30" name="type"/> За месяц <br/>-->
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
<?php
$sql = "SELECT COUNT(  `ws_articles_sizes`.`id` ) AS ctn
FROM  `ws_articles_sizes` 
INNER JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`active` =  'y'";

 $ost = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql)->at(0)->getCtn(); ?>
<div class="row">
<div class="col-lg-4">
Количество товара ( <?=$ost?> ) на <?=date('d.m.Y'); ?>
</div>
    <div class="col-lg-2">
	<button  class="btn btn-small btn-default" onclick="Otchet(<?=$ost?>);" >Скачать отчёт <i class="glyphicon glyphicon-flash" aria-hidden="true"></i></button>
    </div>
  </div>
  <br>
 <div class="mailing_start" style="display: none;">Формирование отчета, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
 <div class="row1" id="return" style="text-align:center;"></div>
  <br>
<form action="/admin/otchets/type/2/" method="post">
<h2>Заказы за период</h2>
    <p class="checkbox" >
        Дата с: <input type="date" class="form-control input" value="<?=date('Y-m-d'); ?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?=date('Y-m-d'); ?>" name="order_to"/> <br/>
     <input type="checkbox"  name="no_new" id="no_new" value="1">
	 <label for="no_new"> только статусы (В процессе, Доставлен в магазин, Отправлен
        укрпочтой, Отправлен Новая почта, Оплачен, Собран, Ждёт оплаты, В процессе доставки)</label>
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
<form action="/admin/otchets/type/8/" method="post">
<h2>Отчет заказов по категории за период</h2>
    Категория <select name="cat" class="form-control input">
			<?php
			$mas = array();

			foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array(), array('name' => 'ASC', 'parent_id' => 'ASC')) as $cat) {
				$mas[$cat->getRoutez()]['id'] = $cat->getId();
			}

			ksort($mas);
			foreach ($mas as $kay => $value) {
			?>
			<option value="<?php echo $value['id']; ?>" <?php if ($this->cur_category and $value['id'] == @$this->cur_category->getId()) echo "selected";?>><?php echo $kay ;?></option>
			<?php } ?>
    </select><br>
    Дата создания товаров с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
    по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>

    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<form action="/admin/otchets/type/4/" method="post">
<h2>Отчет по бренду за период</h2>
    Бренд <select name="brend" class="form-control input">
        <?php foreach (wsActiveRecord::useStatic('Brand')->findAll() as $brand) { ?>
            <option value="<?php echo $brand->getId() ?>"><?php echo $brand->getName()?></option>
        <?php } ?>
    </select><br>
	 Дата добавления товаров с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="add_from"/>
    по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="add_to"/> <br/>
	
    Дата создания заказов с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
    по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>

    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<br/>
<a href="/admin/zgoda">Согласия пользователей</a>

<form action="/admin/otchets/type/6/" method="post">
<h2>Товары с блоками скидок</h2>
    <p>
        Дата с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>
        <input type="checkbox" name="no_new" value="1"> только статусы (В процессе, Доставлен в магазин, Отправлен
        укрпочтой, Отправлен Новая почта, Оплачен, Собран, Ждёт оплаты, В процессе доставки)
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<form action="/admin/otchets/type/7/" method="post">
<h2>Уценка</h2>
    <p>
        Дата с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>
        <input type="checkbox" name="to_day" value="1"> по дням
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<form action="/admin/otchets/type/14/" method="post">
<h2>Уценка по каждому товару</h2>
    <p>
        Дата с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>
    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<form action="/admin/otchets/type/9/" method="post">
<h2>Количество товаров по ключевым категориям</h2>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
<!--
<form action="/admin/otchets/type/10/" method="post">
<h2>Отчёт по покупонам</h2>
    <input type="submit" value="Скачать"/>
</form>
-->
<form action="/admin/otchets/type/11/" method="post">
<h2>Заказы по Городам</h2>
    <p>
        Дата с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
        по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>

    </p>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>

<!--
<form action="/admin/otchets/type/12/" method="post">
<h2>Заказы по промокоду red2014</h2>
    <input type="submit" value="Скачать"/>
</form>
-->
<form action="/admin/otchets/type/15/" method="post">
<h2>Товары в избранном</h2><br>
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
            <option value="<?php echo $brand->brand_id?>"><?php echo $brand->brand?></option>
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
            <option value="<?php echo $model->model?>"><?php echo $model->model?></option>
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
<script type="text/javascript">
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

<form action="/admin/otchets/type/16/" method="post">
<h2>Уведомления</h2><br>
    Выберите категорию<select name="category" class="form-control input">
	<option value="0">Все уведомления</option>
	<option value="1">Ждут уведомления</option>
	<option value="2">Получили уведомления</option>
    </select><br>
	<br>
    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
<form action="/admin/otchets/type/13/" method="post">
<h2>Отчет для закупщиков!</h2>

    Дата поступления товаров с: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_from"/>
    по: <input type="date" class="form-control input" value="<?php echo date('Y-m-d'); ?>" name="order_to"/> <br/>

    <input type="submit" class="btn btn-small btn-default" value="Скачать"/>
</form>
<script>
 function Otchet(e){
var day = new Date();
console.log(day.getMinutes()+':'+day.getSeconds());
 var start = 0;
var end = e;  
 //var end = 50; 
console.log(end);
 $('#return').html('<img src="/admin_files/views/chart/load.gif" style="height: 250px;padding: 10px;" >');
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
				console.log(d.getMinutes()+':'+d.getSeconds());
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
				console.log(d.getMinutes()+':'+d.getSeconds());
				console.log(res);
					}
            });
 
 //return false;
 }
</script>