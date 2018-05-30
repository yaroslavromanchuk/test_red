<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?></h1>
<br>
<?php
 $all = wsActiveRecord::useStatic('Revisiya')->count(array('flag' => 0));
 if ($this->errors) { ?>
    <div id="errormessage" style="margin: auto;">
	<img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt=""  class="page-img"/>
        <span style="font-size: 14px;font-weight: bold;">Найдены ошибки:</span><br>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
<?php
}
if($this->add_count) echo 'Загружено : '.$this->add_count;
?>
    <div id="pagesaved" align="center" style="margin: auto;display:none;font-size: 14px;">
        <img src="<?php echo SITE_URL; ?>/img/icons/accept.png" alt="" width="25" height="25" class="page-img"/>
        <div><span style="font-weight: bold;">Данные успешно сохранены</span><br>
		<span>Затронуто <span id="saved"></span> едениц товара.</span>
		<br>
		<span>Повторы <span id="coll"></span> едениц.</span>
		<br><span>В заказах нашлось <span id="order"></span> товара</span>
		</div>
    </div>

<div align="center" style="margin-top: 5px;<?php if($all > 0) echo 'display:none;'; ?>" id="form_pa">
<form method="POST" action="" enctype="multipart/form-data" class="form-inline">
<div class="form-group">
<input type="file" name="exel" class="form-control input" >
<button type="submit" name="save" id="save" class="btn  btn-default"><i class="glyphicon glyphicon-floppy-open" aria-hidden="true"></i> Открыть</button>
</div>
</form>
</div>
<div class="mailing_start" style="display: none;text-align: center;">
	<img src="/images/loader.gif" alt="loading"/> Идёт сверка, подождите...<br/>
	<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
  </div>
</div>
</div>
<p style="text-align: center;display:none;" id="div_res">
<input type="hidden" id="all" name="all" value="<?=$all?>">
<label>Для проверки загружено <?=$all?> товара.</label>
<input type="button" id="revizor" value="Запустить сверку" class="button" >
<input type="button" id="dell" value="Очистить базу!" class="button" >

</p>
<p id="ad_del" style="display:none;    text-align: center;"><span style="color: green;">Добавлено <span id="ad"></span> единиц</span><span style="margin-left: 30px;color: red;">Удалено <span id="del"></span> единиц</span><br><input style="" id="stop" class="button" type="button" value="Остановить"></p>
 <table id="products1" cellpadding="4" cellspacing="0" style="text-align: center;display:none;" class="table" align="center">
    <tr>
        <th>id</th>
        <th>Артикул</th>
        <th>Количество<br>на сайте</th>
		<th>Количество<br>в заказах</th>
        <th>Количество<br>в 1С</th>
        <th>Действие по<br>сайту</th>
        <th>Количество<br>изменено</th>
    </tr>
	</table>
 <script>
     $(document).ready(function () {
	 var stop = false;
	 var ad = 0;
	 var del = 0;
	 var saved = 0;
	 var coll = 0;
	var order = 0;
        var count_sr = $('#all').val();
		if(count_sr > 0) $('#div_res').show();
        var send_sr = 0;
		var to_str = 20;
        var count = 20;
        var mails ='';
		
		$('#stop').click(function () {
		stop = true;
		$('#stop').hide();
		});
  $('#revizor').click(function () {
  stop = false;
  $('#stop').show();
            var url = '/admin/revisiya/';
           // var data = $('#mail_form').serialize();
            //$(this).attr('disabled', 'true');
           $('.mailing_start').show();
            send(url, 0);
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
			surl = url+new_data;
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
		
		function send(url, go) {
var new_data = '&from=' + send_sr + '&count=' + to_str +'&go='+go;
			surl = url+new_data;
		console.log(surl);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    if (res.status = 'send') {
					
					console.log(res);
					//console.log(res.article.length);
					saved +=res.saved;
					$('#saved').html(saved);
					coll +=res.coll;
					$('#coll').html(coll);
					order +=res.order;
					$('#order').html(order);

					////
                        send_sr += count;
					for (index = 0; index < res.article.length; ++index) {
					if(res.article[index]["type"] == 1){ad += res.article[index]["ct_edit"];}else{del += res.article[index]["ct_edit"];}
					mails = '<tr><td>'+res.article[index]["id_article"] +'</td><td>'+res.article[index]["code"]+'</td><td>'+res.article[index]["count"]+'</td><td>'+res.article[index]["count_r"]+'</td><td>'+res.article[index]["ct_r"]+'</td><td>'+res.article[index]["text"]+'</td><td>'+res.article[index]["ct_edit"]+'</td><tr>'; 
					$('#products1').append(mails);
				}	
						
					$('#ad').html(ad);	
                    $('#del').html(del);	    
                        var proc = (send_sr / count_sr) * 100;
						$(".progress-bar").css('width', Math.round(proc , 2)+'%');
						$(".progress-bar").html(Math.round(proc, 2)+'%');
						
                        if (send_sr <= count_sr && go == 0 && stop == false) {
                            send(url, 0);
                        } else {
                           // alert('Сверка закончилась.');
                            $('.mailing_start').hide();
							$('#pagesaved').show();
                        }
						
						/////
                    }
                }
            });

        }
		
		});
 
 </script>