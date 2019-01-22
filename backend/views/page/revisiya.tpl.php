
<div class="row row-sm mg-x-0">
    <div class="col-sm-12">
        
   
<?php
 $all = wsActiveRecord::useStatic('Revisiya')->count(array('flag' => 0));
 $sql = "SELECT  count(ws_articles_sizes.id) as ctn FROM ws_articles_sizes
join ws_articles on ws_articles_sizes.id_article = ws_articles.id
 	WHERE ws_articles.status != 1 and ws_articles_sizes.`ctime` !=  '0000-00-00 00:00:00'
AND  ws_articles_sizes.`ctime` >  '2017-01-01 00:00:00' ";
 $ostatok = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sql)->at(0)->ctn;
 if ($this->errors) { ?>
    <div id="errormessage" style="margin: auto;">
	<img src="/img/icons/error.png" alt=""  class="page-img"/>
        <span style="font-size: 14px;font-weight: bold;">Найдены ошибки:</span><br>
        <ul>
            <?php foreach ($this->errors as $error) {echo "<li>{$error}</li>"; }?>
        </ul>
    </div>
<?php
}
if($this->add_count) {echo 'Загружено : '.$this->add_count;}
?>
    <div id="pagesaved" align="center" style="margin: auto;display:none;font-size: 14px;">
        <img src="<?=SITE_URL?>/img/icons/accept.png" alt="" width="25" height="25" class="page-img"/>
        <div><span style="font-weight: bold;">Данные успешно сохранены</span><br>
		<span>Затронуто <span id="saved"></span> едениц товара.</span>
		<br>
		<span>Повторы <span id="coll"></span> едениц.</span>
		<br><span>В заказах нашлось <span id="order"></span> товара</span>
		</div>
    </div>
<div class="card pd-30">
    <h6 class="card-body-title">Форма загрузки результатов ревизии</h6>
<p>Выберите excel файл. Структура: А-артикул, B-количество</p>
    <form method="POST" action="" id="form_pa" enctype="multipart/form-data" class="<?php if($all > 0){ echo 'd-none'; }?>">
    <div class="form-layout">
        <div class="row mg-b-25">
		<div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="exel" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            </div>
                    <button class="btn btn-info mg-r-5" name="save" id="save" type="submit">Загрузить</button>
            </div>
    </div>    
</form>

            </div>
         </div>
     <div class="col-sm-12">
         <div class="card pd-30">
    <h6 class="card-body-title">Сверка</h6>
<div class="mailing_start" style="display: none;" class="m-auto text-center" >
	<img src="/images/loader.gif" alt="loading"/> Идёт сверка, подождите...<br/>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
         
    <p style="display:none;" class="m-auto text-center" id="div_res">
<input type="hidden" id="all" name="all" value="<?=$all?>">
<input type="hidden" id="ostatok" name="ostatok" value="<?=$ostatok?>">
<label class="font-weight-bold text-primary">Для проверки загружено <?=$all?> SKU.</label><br>
<button id="revizor" class="btn btn-outline-success" >Запустить сверку</button>
<button id="dell" class="btn btn-outline-danger" >Очистить базу!</button>


</p>
<p id="ad_del" style="display:none;" class="m-auto">
    <span class="font-weight-bold text-success">Добавлено <span id="ad"></span> единиц</span><br>
    <span class="font-weight-bold text-danger">Удалено <span id="del"></span> единиц</span>
    <br>
    <button id="stop"  class="btn btn-outline-danger" >Остановить!</button>
</p>
</div>
         </div>
   <div class="col-sm-12"> 
       <div class="card pd-30">
    <h6 class="card-body-title">Таблица результатов сверки</h6>
 <table id="products1" cellpadding="4" cellspacing="0" style="display:none;" class="table text-center m-auto" >
    
     <thead>
         <tr>
        <th>id</th>
        <th>Артикул</th>
        <th>На сайте</th>
	<th>В заказах</th>
        <th>В 1С</th>
        <th>Собитие</th>
        <th>Изменено</th>
    </tr>
    </thead>
    <tbody id="products1_body">
        
    </tbody>
	</table>
 </div>
        </div>
</div>

 <script>
     $(document).ready(function () {
	 var stop = false;
	 var ad = 0;
	 var del = 0;
	 var saved = 0;
	 var coll = 0;
	var order = 0;
        var count_sr = $('#ostatok').val();//количество записей в базе
		if(count_sr > 0) $('#div_res').show();
		
        var start = 0;//nachalo
	var limit = 100; //limit
		
        //var count = 0;
		
        var mails ='';
		
		$('#stop').click(function () {
		stop = true;
		$('#stop').hide();
		});
  $('#revizor').click(function () {
  stop = false;
  $('#stop').show();
           $('.mailing_start').show();
            send(0);
			//$('#show_emails').show();
            $('#products1').show();
			 $('#ad_del').show();
$('#div_res').hide();
$('#form_pa').hide();
          //  alert('Сверка стартовала. Дождитесь окончания!');
			
        });
		  $('#dell').click(function () {
            var url = '/admin/revisiya/';
           // var data = $('#mail_form').serialize();
            //$(this).attr('disabled', 'true');
          // $('.mailing_start').show();
		   var new_data = '&method=dell';
		var surl = url+new_data;
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				if(res) alert(res);
				}
				});
				 location.reload();

			
        });
		
		function send(go) {
            $.ajax({
                url: '/admin/revisiya/',
                type: 'POST',
                dataType: 'json',
                data: '&method=start&limit='+limit+'&from='+go,
                success: function (res) {
                    if (res.status == 'send') {
					
					console.log(res);
					//console.log(res.article.length);
					saved +=res.saved; //сохранено с изменениями 
					$('#saved').html(saved); // отобразить колл. изменений
					coll +=res.coll; //
					$('#coll').html(coll);
					order +=res.order;// в заказах
					$('#order').html(order);

					////
                        start += limit;
					for (index = 0; index < res.article.length; ++index) {
					if(res.article[index]["type"] == 3){ ad += res.article[index]["ct_edit"]; }else{ del += res.article[index]["ct_edit"];}
					mails = '<tr><td>'+res.article[index]["id_article"] +'</td><td>'+res.article[index]["code"]+'</td><td>'+res.article[index]["count"]+'</td><td>'+res.article[index]["count_r"]+'</td><td>'+res.article[index]["ct_r"]+'</td><td>'+res.article[index]["text"]+'</td><td>'+res.article[index]["ct_edit"]+'</td><tr>'; 
					$('#products1_body').append(mails);
				}	
						
					$('#ad').html(ad);	
                    $('#del').html(del);	    
                        var proc = (start / count_sr) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
						
                        if (start <= count_sr && stop == false) {
                            send(res.from);
                        } else {
                           // alert('Сверка закончилась.');
                            $('.mailing_start').hide();
							$('#pagesaved').show();
                        }
						
						/////
                    }else{
					console.log(res);
					}
                },
				error: function (res) {
				console.log(res);
				
				}
            });

        }
		
		});
 
 </script>