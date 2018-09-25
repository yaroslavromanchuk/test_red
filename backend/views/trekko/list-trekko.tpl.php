<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
	<?php
 $mas_stat = array(
 '0' =>'Новый',
            '9' => 'Собран',
            '13' => 'В процессе доставки',
            '15' => 'Собран 2',
			'16' => 'Собран 3'
        );
		?>
<?php  echo $this->message;?>
<input hidden type="text" id="em" name="em"  value="">
<p style="margin-left:20px;"><a onclick="chekAll(); return false;" href="#">Отметить/Снять все</a><input type="button" name="save" id="save" class="button" style="margin-left: 20px;" value="Создать"><input type="button" name="print" id="print" class="button" value="Печать" style="margin-left: 20px;"></p>
<script type="text/javascript">
    var clik_ok = 0;
    function chekAll() {
        if (!clik_ok) {
            $('.cheker').attr('checked', true);
            clik_ok = 1;
        } else {
            $('.cheker').attr('checked', false);
            clik_ok = 0;
        }

        return false;
    }
</script>
<table id="productss" cellpadding="4" cellspacing="0" style="width:900px;margin-left:20px;" >
    <tr>
	<th>Действие</th>
		<th>Заказ</th>
		<th>Статус</th>
        <th>Заказ Создан</th>
		<th>Дата доставки</th>
        <th>Время доставки</th>
		<th>Оплата</th>
    </tr>
    <?php
	$count = 0;
	if($this->order_trekko){
                      $row = 'row1';
    foreach ($this->order_trekko as $or)
    {
	$count ++;
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?php echo $row;?>">
				<td><input type="checkbox" class="order-item cheker" id="<?=$or->id;?>" name="<?=$or->id;?>"/><a href="/admin/trekko/edit/<?=$or->id;?>/" target="_blank" ><img src="/img/icons/edit-small.png" alt="Редактировать" width="24" height="24"></a></td>
				 
				 <td><?php  echo $or->id;?></td>
				<td><?php  echo $mas_stat[$or->status];?></td>
                
                 <td><?php  echo $or->date_create;?></td>
				 <td><?php  echo $or->delivery_date;?></td>
				  <td><?php  echo $or->delivery_interval;?></td>
				  <td><?=$or->getPaymentMethod()->getName();?></td>
            </tr>
        <?php } 
		}
		?>
</table>
<?php echo 'Всего записей: '. $count; ?>
<table align="center" style="display:none;" id="resultat">
<tr><th>Создано</th><th>Ошибки</th></tr>
<tr><td id="ok" style="color:green;font-weight: bold;"></td><td id="off" style="color:red;font-weight: bold;"></td></tr>
</table>
<div id="mess" ><span id="mess_span" style="font-size:16px;color:red;"></span></div>
<script type="text/javascript">
    $(document).ready(function () {

	    });

        $('#save').click(function () {
		$('#resultat').fadeOut(500);
		ord = '';
                id = '';
            if ($('.order-item:checked').val()) {
				
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                    } else {
						ord += $(this).attr('id');
                    }
                    i++;
                });
				}
			if(ord != ''){	
			$.ajax({
			beforeSend: function( data ) {
				$('#save').attr('value', 'Создается...');
			},
			type: "POST",
			url: '/admin/trekko/',
			dataType: 'json',
			data: '&method=add_ttn_all&id='+ord,
			success: function( data ) {
			console.log(data);

			if(data.ok.length != 0){
			//console.log('ok = '+data.ok);
			var oke = '';
			for (index = 0; index < data.ok.length; ++index) {
			//console.log(data.ok[index]);
			oke += 'Заказ '+data.ok[index].order_id +' добавлен!<br>';
				}
				$('#ok').html(oke);
				//console.log('oke = '+oke);
			}
			if(data.off.length != 0){
			//console.log('off'+data.off[0].error);
			var offe ='';
			for (index = 0; index < data.off.length; ++index) {
			offe +=data.off[index].error+'!<br>';
   // console.log(data.off[index].error);
			}
				$('#off').html(offe);
			}
			if(data.cur != ''){
			console.log('cur'+data.cur);
			}
			$('#resultat').fadeIn(500);
			},
			complete: function( data ) {
			$('#save').attr('value', 'Создать');
			},
			error: function( e ) {
			//console.log(e);
			alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
		});
		
		}
        });
		
			$('#print').click(function () {
			 if ($('.order-item:checked').val()) {
				ord = '';
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                    } else {
						ord += $(this).attr('id');
                    }
                    i++;
                });
				}
				mailGo(ord);
	
			});
			
				function mailGo(id){
				var ff = false;
		$.ajax({
			type: "GET",
			url: '/admin/exelkurer/',
			dataType: 'json',
			data: '&id='+id+'&flag=1',
			success: function( data ) {
			if(data)window.open("/admin/trekko/prints/"+id, '_blank');
			//console.log(data);
			},
			error: function( e ) {
			$('#popup').html('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
					fopen();
			}
		});
	return ff;
		
		}

		//window.onbeforeunload = function() {return "Данные не сохранены. Точно перейти?";};

</script>
