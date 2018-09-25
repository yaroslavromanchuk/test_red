<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
    <form action="" method="get">
        <p>Статус посылки
            <select name="status_ttn" class="input" id="status_ttn">
                <option value="0" <?php if(@$_GET['status_ttn']==0){?>selected="selected" <?php } ?>>Новый</option>
                <option value="1" <?php if(@$_GET['status_ttn']==1){?>selected="selected" <?php } ?>>Создана ТТН</option>
                <option value="2" <?php if(@$_GET['status_ttn']==2){?>selected="selected" <?php } ?>>Отправлен</option>
            </select></p>
        <input type="submit" class="button" value="Поиск" />
    </form>
	<?php
 $mas_stat = array(
            '0' => 'Новый',
            '1' => 'Создан ТТН',
            '2' => 'Отправлен'
        );
		?>
 <p><a href="/admin/addmeestttn/register/print/">К заявкам</a></p>
<input style="display:none;" type="button" id='massprintttn' value="Создать заявку на курьера"/>
<input style="display:none;" type="button" id='massprintstiker' value="Печать стикеров"/></br>
<?php  echo $this->message;?>
</br><a onclick="chekAll(); return false;" href="#">Отметить/Снять все</a>
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
<table id="productss" cellpadding="4" cellspacing="0" style="width:900px;">
    <tr>
	<th>Действие</th>
		<th>Статус</th>
        <th>Заказ</th>
        <th>Заказ Создан</th>
        <th>Создан ТТН</th>
		<th>ТТН</th>
        <th>Тип доставки</th>
        <th>Масса посылки</th>
		<th>Дата доставки</th>
		<th>Отследить</th>
    </tr>
    <?php
	$count = 0;
	if($this->ttn){
                      $row = 'row1';
    foreach ($this->ttn as $or)
    {
	$count ++;
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?php echo $row;?>">
				<td><input type="checkbox" class="order-item cheker" id="<?php echo $or->idt; ?>" name="<?php echo $or->ttn; ?>"/><a href="/admin/addmeestttn/edit/<?php echo $or->id; ?>/" target="_blank" ><img src="/img/icons/edit-small.png" alt="Редактировать" width="24" height="24"></a></td>
				<td><?php  echo $mas_stat[$or->status];?></td>
                 <td><?php  echo $or->id;?></td>
                 <td><?php  echo $or->ctime;?></td>
				 <td><?php  echo $or->gotime;?></td>
				  <td><?php  echo $or->ttn;?></td>
                 <td><?php  if($or->type == 1) { echo 'Адрес'; }else{ echo 'Отделение';}?></td>
                 <td><?php echo $or->massa.' кг.';?></td>
                 <td><?php echo $or->datego;?></td>
				 <td><?php if($or->status == 2) {?> <img style="cursor:pointer;" 
                            src="<?php echo SITE_URL; ?>/img/icons/help.png" alt="Отследить" width="20px"
                            height="20px" onclick="meest_tracking('<?php echo $or->ttn;?>');"/> <?php } ?></td>
            </tr>
        <?php } 
		}
		?>
</table>
<?php echo 'Всего записей: '. $count; ?>
<div id="mess" ><span id="mess_span" style="font-size:16px;color:red;"></span></div>
<input type="hidden" id="reg" name="red" value=""/>
<script type="text/javascript">
//получение информации по посылке
function meest_tracking(x) {
$.get('/admin/addmeestttn/ttn/'+x+'/metod/tracking/',
		function (data) {
		if(data){
		
		//alert(data);
		 $('#popup').html(data);
		 fopen();
		 }
		});

		return false;
}
//открытие всплывающего окна нова почта
function fopen(){
$('#popup').fadeIn();
$('#popup').append('<a id="popup_close" onclick="FormClose()"></a>');
$('body').append('<div id="fade" onclick="FormClose()"></div>');
$('#fade').css({'filter':'alpha(opacity=40)'}).fadeIn();
return false;
}
//закрытие всплывающего окна нова почта
function FormClose(){
$('#popup').fadeOut();
$('#fade').fadeOut();
$('#fade').remove();
$('#popup_close').remove();
}
    $(document).ready(function () {
	var z = 5<?php  echo $_GET['status_ttn']; ?>;
	
	if(z == 51){$('#massprintttn').show();}else{$('#massprintttn').hide();}
	if(z == 51 || z == 52){ $('#massprintstiker').show();}else{$('#massprintstiker').hide(); }

	
	
        $('#massprintttn').click(function () {
            if ($('.order-item:checked').val()) {
				ord = '';
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                        id += ', ' + $(this).attr('name');
                    } else {
						ord += $(this).attr('id');
                        id += $(this).attr('name');
                    }
                    i++;
                });
				console.log(id);
				
				$.ajax({
			beforeSend: function( data ) {
				$('#massprintttn').attr('value', 'Создается...');
			},
			type: "POST",
			url: '/admin/addmeestttn/',
			data: '&method=add_register&idm='+ord+'&mas='+id,
			success: function( data ) {
			console.log(data);
			$('#reg').val(data);
			$('#mess_span').html(data);
			print(data);
				console.log(data);
				//alert(data.ttn);
				 
			},
			dataType: 'json',
			complete: function( data ) {
			$('#massprintttn').hide();
			//$('#mess_span').html('TTН Создана!');
				$('#massprintttn').attr('value', 'Создать заявку на курьера');
				
			},
			error: function( e ) {
			alert('Вы ввели что-то не верно! Реестр не создан, попробуйте снова!');
			}
		});
		
		
		

               

            }
        });
		
		$('#massprintstiker').click(function () {
            if ($('.order-item:checked').val()) {
				ord = '';
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                        id += ',' + $(this).attr('name');
                    } else {
						ord += $(this).attr('id');
                        id += $(this).attr('name');
                    }
                    i++;
                });
				}
				printstiker(id);
				
				});
		
		
    });
	
	function print(id){
		window.open ( 'https://apii.meest-group.com/services/print/register.php?number=' + id , '_blank');
		}
function printstiker(id){
		window.open ( 'https://www.meest-express.com.ua/services/print/sticker.php?pos=1&declar='+ id +'&agent=35a4863e-13bb-11e7-80fc-1c98ec135263' , '_blank');
		}
		
</script>
