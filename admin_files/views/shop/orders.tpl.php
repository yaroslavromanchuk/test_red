<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" class="page-img">
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<table style="width: 100%;" class="table">
	<tr>
		<td style="width:40%;">
<div class="">
<form action="/admin/shop-orders/" method="get" class="form-horizontal">
	 <div class="form-group">
    <label for="order" class="ct-110 control-label">№ заказа:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['order']?>" name="order" id="order" placeholder="Введите №"/>
    </div>
  </div>
   <div class="form-group">
    <label for="customer_id" class="ct-110 control-label">ID клиента:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['customer_id']?>" name="customer_id" id="customer_id" placeholder="Введите ID"/>
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="ct-110 control-label">Телефон:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['phone']?>" name="phone" id="phone" placeholder="Введите Телефон"/>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="ct-110 control-label">Email:</label>
    <div class="col-xs-6">
	<input type="email" class="form-control input" value="<?=$_GET['email']?>" name="email" id="email" placeholder="Введите Еmail"/>
    </div>
  </div>
   <div class="form-group">
    <label for="uname" class="ct-110 control-label">Имя:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['uname']?>" name="uname" id="uname" placeholder="Введите Имя"/>
    </div>
  </div>
  <div class="form-group">
    <label for="delivery" class="ct-110 control-label">Доставка:</label>
    <div class="col-xs-6">
	 <select name="delivery" class="form-control input" id="delivery" >
				<option value="">Все</option>
				<?php foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=> 1), array('sort'=>'ASC')) as $d) { ?>
				 <option value="<?=$d->id?>" <?php if(isset($_GET['delivery']) and $_GET['delivery'] == $d->id ) echo 'selected="selected"'; ?> ><?=$d->name;?></option>
			<?php	} ?>
				<option
					value="999" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '999') echo 'selected="selected"';?>>
					Магазины
				</option>
			</select>
    </div>
  </div>
  <div class="form-group">
    <label for="status" class="ct-110 control-label">Статус:</label>
    <div class="col-xs-6">
	 <select name="status"  id="status" class="form-control input">
				<option value="999">Все</option>
				<?php foreach ($this->order_status as $key => $item) { ?>
                <option value="<?=$key;?>" <?php if(isset($_GET['status']) and $_GET['status'] == $key ) echo 'selected="selected"'; ?> ><?=$item;?></option>
            <?php } ?>
			</select>
    </div>
  </div>
     <div class="form-group">
    <label for="nakladna" class="ct-110 control-label">Накладная:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['nakladna']?>" name="nakladna" id="nakladna" placeholder="№"/>
    </div>
	<div id="view_serch" class="r-s" onclick="view_block(this);return false;" data-placement="top"  data-tooltip="tooltip" title="Показать детали"></div>
  </div>
  <div id="view_block" class="s-b">
   <div class="form-group">
    <label for="create_from" class="ct-110 control-label">Дата создания:</label>
    <div class="col-xs-6">
	<input type="date"   value="<?=$_GET['create_from']?>" class="form-control input " name="create_from"  id="create_from" placeholder="от" size="9" />
	<input type="date"  value="<?=$_GET['create_to']?>" class="form-control input " name="create_to" placeholder="до" size="9" />
    </div>
  </div>
   <div class="form-group">
    <label for="go_from" class="ct-110 control-label">Дата отправки:</label>
    <div class="col-xs-6">
	<input type="date"   value="<?=$_GET['go_from']?>" class="form-control input " name="go_from"  id="go_from" placeholder="от" size="9" />
	<input type="date"  value="<?=$_GET['go_to']?>" class="form-control input " name="go_to" placeholder="до" size="9" />
    </div>
  </div>
 <div class="form-group">
    <label for="price" class="ct-110 control-label">Цена:</label>
    <div class="col-xs-6">
	<input type="text" class="form-control input" value="<?=$_GET['price']?>" name="price" id="price" placeholder="+- 3 грн"/>
    </div>
  </div>
  </div>
  <div class="form-group " style="padding-left: 15px;">
  <label class="control-label" style="padding: 5px;"><input type="checkbox" name="is_admin" value="1" <?php if ($_GET['is_admin'] == 1) { ?>checked="checked" <?php } ?>>Заказы администрации </label>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="nall" id="nall" value="1" <?php if ($_GET['nall'] == 1) { ?>checked="checked" <?php } ?>>С наличием товара</label><br>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="detail" value="1" <?php if ($_GET['detail'] == 1) { ?>checked="checked" <?php } ?>>Уточнить детали</label>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="online" value="1" <?php if ($_GET['online'] == 1) { ?>checked="checked" <?php } ?>> Онлайн оплаты </label><br>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="kupon" value="1" <?php if ($_GET['kupon'] == 1) { ?>checked="checked" <?php } ?>> Заказы по штрихкоду</label>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="bonus" value="1" <?php if ($_GET['bonus'] == 1) { ?>checked="checked" <?php } ?>> Заказы с бонусами</label><br>
  <label class="control-label s-b" style="padding: 5px;"><input type="checkbox" name="quick_order" value="1" <?php if ($_GET['quick_order'] == 1) { ?>checked="checked" <?php } ?>> Заказы из заявок</label>
  </div>
<div class="form-group">
    <div class="col-xs-offset-1 col-xs-11" style="text-align:center;">
      <button type="submit"  name="go" class="btn  btn-lg btn-default"><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Найти</button>
    </div>
  </div>
</form>
</div>
</td>

<td style="width:60%;" >
<form action="/admin/ordersexcel/" method="get" class=" form-horizontal form-inline form-style" >
<div class="form-group">
<label>В Excel: №</label>
<input type="text" class="form-control input w100" placeholder="c" name="min" size="6" /><input type="text" class="form-control input w100" placeholder="до"  name="max" size="6"/>
<button class="btn btn-small btn-default " type="submit" ><i class="glyphicon glyphicon-save-file" aria-hidden="true"></i> Получить</button>
	</div>
</form>
<div class="form-group form-style">
   <label for="order_status_all">Массовое изменение статуса:</label><br>
    <select name="order_status_all" id='order_status_all' class="form-control input" >
        <option>Выберите статус</option>
        <?php foreach ($this->order_status as $key => $item) { ?>
                <option value="<?=$key;?>"><?=$item;?></option>
            <?php } ?>
    </select>
	<button class="btn btn-small btn-default" id="all_status" type="button" ><i class="glyphicon glyphicon-edit" aria-hidden="true"></i> Изменить</button>
</div>
<script type="text/javascript">
function view_block(e){
if($('.s-b').is(":visible")){
$('#view_serch').css('background-image', 'url("/admin_files/img/icons/niz.png")');
$(".s-b").slideUp();
}else{
$('#view_serch').css('background-image', 'url("/admin_files/img/icons/verch.png")');
$(".s-b").slideDown();

}
}
    $(document).ready(function () {
        $('#all_status').click(function () {
            if ($('#order_status_all option:selected').val() != '') {
                if ($('.order-item:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
                    window.location = '/admin/allstatus/id/' + id + '/status/' + $('#order_status_all option:selected').val();

                }
            }
        });
    });
</script>
<div class="form-group form-style">
<div style="width: 40%;display: inline-block;">
 <label for="all_articles">Получить Excel:</label><br>
 <select class="form-control input" name="all" id="all">
	<option value="0">Выберите действие</option>
	<option value="1">Товары в заказах</option>
	<option value="5">Excel для 1С</option>
	<option value="2">Экспорт товаров в заказах</option>
	<option value="3">Excel выделеных заказов</option>
	<option value="4">Excel курьерских заказов</option>
 </select>
 <span class="form-group  fade all-error" style="color: #ce0000;"><i class="glyphicon glyphicon-ban-circle" aria-hidden="true"></i> Выберите нужные заказы!</span>
<script type="text/javascript">
    $(document).ready(function () {
	$('#all').change(function () {
	if ($('.order-item:checked').val()) {
	$('.all-error').removeClass('in');
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
			   switch(this.value) {
			    case '1' : 
				window.location = '/admin/allarticles/id/' + id;
					break;
				case '2' : 
				window.location = '/admin/allarticlesExcel/id/' + id;
					break;
				case '3' : 
				window.location = '/admin/nowapochtaexel/id/' + id;
					break;
				case '4' : 
				window.location = '/admin/exelkurer/id/' + id;
					break;
				case '5' : 
				window.location = '/admin/exeltoarticles/id/' + id;
					break;
				//default :
				//	alert('Неизвестное значение: ' + this.value);	
			   }  
            }else{
			$('.all-error').toggleClass('in');
			}

	});
    });
</script>
</div>
<div style="width: 50%;display: inline-block;">
<label>Excel заказов с начальной стоимостю</label><br>
<button class="btn btn-small btn-default" id='order_nachal' type="button"><i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Експорт</button>
    <input type="date" class="form-control input w150" id="order_date" value="<?=date('Y-m-d');?>"  size="9" />
	 <span class="form-group  fade order_nachal-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите дату</span>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#order_nachal').click(function () {
		if($('#order_date').val()) { window.location = '/admin/otchets/type/3/?day=' + $('#order_date').val();}else{ $('.order_nachal-error').toggleClass('in'); }
        });
    });
</script>
<div class="form-group form-style">
<label>Действия над заказами:</label><br>
<button class="btn btn-small btn-default" id='ordercomplect' type="button"><i class="glyphicon glyphicon-resize-small" aria-hidden="true"></i> Совместить заказы</button>
<button class="btn btn-small btn-default" id='order_uncomplect' type="button"><i class="glyphicon glyphicon-resize-full" aria-hidden="true"></i> Разъединить заказ</button>
<span class="form-group  fade complect-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите заказы</span>
</div>
<script type="text/javascript">
    function NowaMail(id, object) {
        $.post('/admin/nowamail/', {id: id}, function (data) {
            object.parent().html(data);
        });
    }
    $(document).ready(function () {
        $('#ordercomplect').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/ordercomplect/id/' + id;

            }else{
			$('.complect-error').toggleClass('in'); 
			}
        });
        $('#order_uncomplect').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.location = '/admin/orderuncomplect/id/' + id;

            }else{
			$('.complect-error').toggleClass('in'); 
			}
        });
    });
</script>
<div class="form-group form-style">
<div style="width: 49%;display: inline-block;vertical-align: top;">
<label>Масcовая печать:</label><br>
<select name="masrintordertype" id="masrintordertype" class="form-control input">
        <option value="1">Магазин</option>
        <option value="2">Укр-почта</option>
        <option value="3">Новая почта</option>
        <option value="4">Курьером</option>
    </select><span class="form-group  fade masrintordertype-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите из списка</span>
	<br>
	<button class="btn btn-small btn-default" id='masrintorder' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Счета</button>
	<button class="btn btn-small btn-default" id='masrintnakl' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Наклейки</button>
	
</div>
<div style="width: 49%;display: inline-block;vertical-align: top;">
<label>Печать выделеных заказов:</label><br>
 <select class="form-control input" name="all_print" id="all_print">
	<option value="0">Выберите печать</option>
	<option value="1">Бланки</option>
	<option value="2">Чеки</option>
	<option value="3">Сообщение на посылку</option>
 </select>
 <span class="form-group  fade all_print-error" style="color: #ce0000;"><i class="glyphicon glyphicon-ban-circle" aria-hidden="true"></i> Выберите нужные заказы!</span><br>
 <?php if(true){ ?>
	<button class="btn btn-small btn-default" id='pr_vozvrat' type="button"><i class="glyphicon glyphicon-copy" aria-hidden="true"></i> Принять на возврат</button>
	
	<?php } ?>
 </div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#masrintorder').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
				window.open ( '/admin/masgenerateorder/ids/' + id + '/type/' + $('#masrintordertype').val(), '_blank');

            }else{
			$('.masrintordertype-error').toggleClass('in'); 
			}
        });

        $('#masrintnakl').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
				window.open ( '/admin/masgeneratenakl/ids/' + id + '/type/' + $('#masrintordertype').val(), '_blank');

            }else{
			$('.masrintordertype-error').toggleClass('in'); 
			}
        });
    });
	 $('#pr_vozvrat').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
		$.ajax({
                url: '/admin/vozrat/',
                type: 'POST',
                dataType: 'json',
                data: '&method=add_order_vozvrat&order='+id,
                success: function (res) {
				var mes = '';
				if(res.ok){
				mes+='<div class="alert alert-success" role="alert">';
				mes+='Заказ '+res.ok.join(',')+' принят на возврат.';
				mes+='</div>';
				}
				if(res.error){
				mes+='<div class="alert alert-danger" role="alert">';
				mes+='Заказ '+res.error.join(',')+' не принят на возврат.';
				mes+='</div>';
				}
				//console.log(res);
				fopen('Прийом заказа на возврат', mes);
                },
				error: function(e){
				console.log(e);
				}
            });
            }else{
			alert('Отметьте нужный заказ!');
			//$('.masrintordertype-error').toggleClass('in'); 
			}
        });
</script>
 </div>
    <script type="text/javascript">
        $(document).ready(function () {
		$('#all_print').change(function () {
		  if ($('.order-item:checked').val()) {
                    id = '';
                    i = 0;
                    jQuery.each($('.order-item:checked'), function () {
                        if (i != 0) {
                            id += ',' + $(this).attr('name').substr(5);
                        } else {
                            id += $(this).attr('name').substr(5);
                        }
                        i++;
                    });
		  switch(this.value) {
			    case '1' : 
				 window.open ( '/admin/masgenerateblank/ids/' + id , '_blank');
					break;
				case '2' : 
				window.open ( '/admin/masgeneratechek/ids/' + id , '_blank');
					break;
				case '3' : 
				window.open ( '/admin/masgenerateblank_test/ids/' + id , '_blank');
					break;
				//default :
				//	alert('Неизвестное значение: ' + this.value);	
			   } 
			   }else{
			   $('.all_print-error').toggleClass('in'); 
			   }
		
		});
        });
    </script>
<?php if($this->admin_rights['494']['right'] == 1 and false){ ?>
<p><input type="button" id='view_ttn_np' class="btn" value="Внести ТТН-НП"/></p>
<?php } ?>

</td>
</tr>
</table>
<script type="text/javascript">
$('#view_ttn_np').click(function () {
	fopen('Внесение ТТН', '<p>ОТСКАНИРУЙТЕ ТТН:</p><form action="" method="POST" id="myform" align="center"><input type="text" style="border: 1.5px solid #1963d1;" name="ttn" id="ttn" value="" autofocus maxlength="14" class="input"></form><p id="text" style="text-align:center;"> </p>');
		var k = 0;
	  $('#ttn').keypress(function(e){
	 if(e.key == 'Enter')  $('#myform').submit();
      e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey) return;
	  if(e.which > 47 && e.which < 58 ){
	 // console.log(e.which);
	  return true;
	  }else{
	   return false;
	  }
    }); 
	
	$('#myform').submit(function(){
var z = $('#ttn').val();
console.log(z+' - '+z.length);
if( z != '' && z.length == 14){ 
	  var url = '/admin/addttnorder/';
		var new_data = '&ttn='+ z;
		$.ajax({
		beforeSend: function( data ) {
		//$('#pageslist').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				if(res == 'error'){
				$('#text').html('<span style="font-size: 16px;color: red;font-weight: 600;">ERROR<span>');
				}else{
				$('#text').html('<span style="font-size: 16px;color: #1a961f;font-weight: 600;">OK<span>');
				setTimeout(function(){$('#text').html(' ');}, 300);
				}
                }
            });
	  }
$("#ttn").val('');
$("#ttn").focus();
	  return false;
	});
});


    $(document).ready(function () {
        $('#masrintblank').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.open ( '/admin/masgenerateblank/ids/' + id , '_blank');

            }
        });
		
		
    });
</script>
<br/>
<?php if ($this->getOrders()->count()) { ?>
    <script type="text/javascript">
        function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
        }
		
	
//получение информации по посылке нова почта
function np_tracking(x) {

if(x.length == 14){
$.get('/admin/novapochta/tracking/'+x+'/metod/tracking/',
		function (data) {
		if(data){
		fopen('Отслеживание заказа', data);
		 }
		});
		}else{
		fopen('Отслеживание заказа', 'В номере ТТН ошибка!');
		}
		 //$('#popup').html('Сервис временно недоступен!');
		//fopen();
		return false;
}
//получение информации по посылке ukr почта
function up_tracking(x) {
$.get('/admin/novapochta/ukr/'+x+'/metod/ukr/',
		function (data) {
		if(data){
		 fopen('Отслеживание заказа', data);
		 }
		});
		// $('#popup').html('Сервис временно недоступен!');
		//fopen();
		return false;
}
//получение информации по посылке trekko
function k_tracking(x) {
$.get('/admin/trekko/metod/status/id/'+x,
		function (data) {
		if(data){
		console.log(data);
		 fopen('Отслеживание заказа', data);
		 }
		});
		return false;
}
    </script>
    <table cellspacing="0" cellpadding="4" id="orders" class="table  table-hover">
        <tr>
		<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
            <th></th>
            <th>Статус</th>
            <th>Номер</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <th>Магазин</th>
			<th>Оплата</th>
            <th>Скидка</th>
            <th>Статус/ТТН</th>
			<th>Пометка</th>
        </tr>
        <?php $row = 'row2'; foreach ($this->getOrders() as $order) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $order_owner = new Customer($order->getCustomerId());
            ?><?php if($order->getIsUnitedly() != 1){ ?>
            <tr class="<?=$row?>" >
                <td>
                    <label class="ckbox"><input type="checkbox" class="order-item cheker" name="item_<?=$order->getId()?>"/><span></span></label>
                </td>
                <td class="kolomicon"><a href="<?=$this->path;?>shop-orders/edit/id/<?=$order->getId();?>/" >
				<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="Редактировать заказ"  data-tooltip="tooltip"></i></a>
               <?php if ($this->user->isSuperAdmin()) { ?>
              <?php if(true){ ?>
			  <i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$order->getId()?>" data-placement="left" title="Смотреть историю заказа"  data-tooltip="tooltip" ></i>

			<?php  }else{ ?><a target="_blank" href="/admin/orderhistory/id/<?=$order->getId();?>">
               <img alt="История" src="/img/icons/histori.png" data-placement="left"  data-tooltip="tooltip" class="img_return" title="Смотреть историю заказа" ></a><?php }?>
               <?php } ?>
                </td>
                <td><?=$order->getStat()->getName()?>
                    <?php if ($order->getComlpect()) { ?>
                        Совмещенный заказ
                    <?php } ?>
                    <?php if ($order->getCallMy() == 1) { ?>
                        <b>Уточнить детали</b>
                    <?php } ?>
                    <?php if ($order->getCallMy() == 2) { ?>
                        <b>Нет необходимости подтверждать заказ по телефону</b>
                    <?php }
?>
                </td>
                <td><?=$order->getId();?><?php if ($order->getOldid()) echo ' / '.$order->getOldid(); ?></td>
                <td style="width: 105px;"><?=date("d-m-Y H:i", strtotime($order->getDateCreate()));?></td>
                <td><?php echo $order->getName() . ' ' . $order->getMiddleName(); ?><span class="help-block">id: <?=$order->getCustomerId()?></span></td>
                <td><?php echo $order->getArticlesCount(); ?></td>
                <td style="width: 85px;"><?php if ($order->getArticlesCount() != 0) {
                        $sttt = '';
                        $sttt2 = '';
                        $price_1 = number_format((double)$order->getTotal('a'), 2, ',', ' ');
                        $price_2 = number_format((double)$order->getAmount(), 2, ',', ' ');
						 if ($order->isUcenArticle() or ($price_1 != $price_2)) {
                            $sttt = '<span style="color:#a51515">';
                            $sttt2 = '</span>';
                        }
                        echo  $price_1 . ' грн<br/>' . $sttt . $price_2 . ' грн' . $sttt2;
                    } ?></td>

                <td><?=$order->getDeliveryTypeId() ? $order->getDeliveryType()->getName(): ''?>
				</td>
				<td style="font-size:12px;">
				<?=$order->getPaymentMethod()->getName()?><br>
				<?php 
				if($order->payment_method_id == 4 or $order->payment_method_id == 6){
if($order->liqpay_status_id == 3){
 echo '<i class="icon ion-ios-checkmark green tx-25 pd-5 history_pay_status" data-placement="right" data-id="'.$order->getId().'"  data-tooltip="tooltip"  title="'.$order->liqpay_status->name.'"></i>';
 }else{
echo '<i class="icon ion-ios-close red tx-25 pd-5 history_pay_status" data-placement="right" data-id="'.$order->getId().'"   data-tooltip="tooltip"  title="'.$order->liqpay_status->name.'"></i>';
} 
 } ?>
				</td>
      <td><?php  if ($order->getSkidka() != '') { echo $order->getSkidka();} //else { //$order->save(); // echo $order->getSkidka(); // } ?> %</td>
      <td>
      <?php if ($order->getBoxNumber()) { ?>
                        Номер ячейки: <?php echo $order->getBoxNumber(); ?>
                    <?php } ?>
                    <form id="order<?= $order->getId() ?>" style="margin-bottom: 2px;" action="/admin/shop-orders/edit/id/<?=$order->getId()?>/" method="get" onsubmit="return false;">
						<input type="hidden" id="id" name="id" value="<?= $order->getId() ?>"/>
						<?php if(in_array($order->getDeliveryTypeId(), array(4,8,16,9))){ ?>
			<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">ТТН</span>
  <input type="text" class="form-control nakladna" aria-describedby="basic-addon1" id="nakladna" name="nakladna" value="<?=$order->getNakladna(); ?>" pattern="[0-9]{5,14}">
</div>			
	   <?php if(($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16) and @$order->getNakladna()){ ?>
		<img style="padding-left: 5px;" class="img_return" src="<?=SITE_URL;?>/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="np_tracking('<?=$order->getNakladna();?>');"/>
		<?php }else if($order->getDeliveryTypeId() == 4 and @$order->getNakladna()){
	?><img style="padding-left: 5px;" class="img_return" src="<?=SITE_URL; ?>/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="up_tracking('<?php echo $order->getNakladna();?>');"/>
	<?php }else if($order->getDeliveryTypeId() == 9 and @$order->getNakladna()){?>
	<img style="padding-left: 5px;" class="img_return" src="<?=SITE_URL; ?>/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="k_tracking('<?php echo $order->getNakladna();?>');"/><?php } ?>
                        <br/><?php  } ?>
                        <?php if (false) {
                            if ($order->getNowaMail() == '0000-00-00 00:00:00') {
                                ?>
                                <span> <a href="#"
                                          onclick="NowaMail(<?php echo $order->getId(); ?>,$(this)); return false;"
                                          class="nowa_mail">отправить счет</a></span>
                            <?php } else { ?>
                                <span> счет отправлен: <?php echo date('d-m-Y', strtotime($order->getNowaMail()));?>
                                    <a href="#"
                                       onclick="NowaMail(<?php echo $order->getId(); ?>,$(this)); return false;"
                                       class="nowa_mail">отправить повторно</a></span>
                            <?php } ?>
                            <br/>
                        <?php } ?>
                        <select class="order_status form-control input" name="order_status" >
                            <?php foreach ($this->order_status as $key => $item) { ?>
                                <option value="<?=$key; ?>" <?php if ($key == $order->getStatus()) echo "selected"; ?>><?=$item;?></option>
                            <?php } ?>
                        </select>
                    </form>
					<?php if(@$order->getDeliveryDate() != '0000-00-00' and in_array($order->getDeliveryTypeId(), array(8,16,4,9)))  echo '<span style="color: red;">Доставка на: '.date('d.m.Y', strtotime($order->getDeliveryDate())).'</span>';?>
                    <?php

                    if ($order->getRemarks()->count() or $order->getComments()) {
                        $remar = array();
                        foreach ($order->getRemarks() as $remark) {
						$rem = $remark->getRemark()."-".$remark->getName();
							$remar[] = $rem;
                        }
                        ?>
                            <?php if ($order->getRemarks()->count()) { ?>
								<div class="comm_ins">
                                <b>Внутренний комментарий :</b><br/>
                                <?=implode(';', $remar);?>
								</div>
                            <?php } ?>
                            <?php if (strlen($order->getComments()) > 1) { ?>
								<div class="comm_cli">
                                <b>Комментарий клиента :</b><br/>
                                <?=$order->getComments();?>
								</div>
                            <?php } ?>
                    <?php } ?>
                </td>
				<td>
				 <?php
				 
	if ($order_owner->getAdminComents()){ echo '<div class="pometka adm" data-placement="right"  data-tooltip="tooltip" title="'.$order_owner->admin_coments.'" style="background: #ff0000;"></div>';}
	if($order->getDeposit() > 0) echo '<div class="pometka dep" data-placement="right"  data-tooltip="tooltip" title="Использован депозит" style="background: #00a600;"></div>';
	if($order->getKuponPrice() > 0 and $order->getKupon()) echo '<div class="pometka kup" data-placement="right"  data-tooltip="tooltip" title="Присутствует штрихкод: '.$order->getKupon().'" style="background: #aeb200;"></div>';
	if($order->getBonus() > 0)  echo '<div class="pometka bon" data-placement="right"  data-tooltip="tooltip" title="Присутствует бонус '.$order->getBonus().' грн." style="background: #41befc;"></div>';
	if($order->getArticlesEvent()) echo '<div class="pometka event" data-placement="right"  data-tooltip="tooltip" title="Присутствует дополнительная скидка" style="background: #41befc;"></div>';
	if($order_owner->getBlockM()) echo '<div class="pometka b_m" data-placement="right"  data-tooltip="tooltip" title="Блок на магазин" style="background: #d77de7;"></div>';
	if($order_owner->isBlockNpN()) echo '<div class="pometka b_np" data-placement="right"  data-tooltip="tooltip" title="Блок НП: Наложка" style="background: #b61010;"></div>';
	if($order_owner->isBlockCur()) echo '<div class="pometka b_kur" data-placement="right"  data-tooltip="tooltip" title="Блок Курьр" style="background: #008aa2;"></div>';
	if($order_owner->isBlockQuick()) echo '<div class="pometka b_quick" data-placement="right"  data-tooltip="tooltip" title="Блок быстрая заявка" style="background: #3F51B5;"></div>';
	
	if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false) echo '<div class="pometka" style="background: #ff9900"></div>';
	
	if ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y"))) echo '<div class="pometka g_op" data-placement="right"  data-tooltip="tooltip" title="Превышено ожидание оплаты" style="background: #ff9900"></div>';
			?>
				</td>
            </tr>
<?php } ?>

        <?php } ?>

    </table>
<script type="text/javascript">
$('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/orderhistory/id/'+id+'/m/1',function (data) {fopen('История изменения заказа №'+id, data);});	
});

$('.history_pay_status').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/historypaystatus/id/'+id,function (data) {fopen('История изменения статуса оплаты №'+id, data);});	
});

	$('.order_status').change(function () {
		$.ajax({
			url: '/admin/shop-orders/edit/id/'+this.form.id.value+'/',
			type: 'POST',
			dataType: 'json',
			data: $('#order'+this.form.id.value).serialize()
		});
	});

	  $('.nakladna').keypress(function(e){
      e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey) return;
	  if(e.which > 47 && e.which < 58 ){
	  return;
	  }else{
	   return false;
	  }
    });
</script>
    <p>
        <label></label>
    </p>
    <?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
    $paginator = '&nbsp;&nbsp;';
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
    }
    $start = 1;
    $end = $this->totalPages;
    if ($this->page > $limitLeft) {
        $paginator .= '...&nbsp;';
        $start = $this->page - $limitLeft;
    }
    if (($this->page + $limitRight) < $this->totalPages) {
        $end = $this->page + $limitRight;
    }
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
    }
    echo $paginator;

    ?><br/>
    Всего страниц: <?=$this->totalPages?>, записей: <?=$this->count?> 


<?php } else echo 'Нет записей'; ?>