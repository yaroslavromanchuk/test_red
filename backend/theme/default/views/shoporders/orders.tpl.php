 <div class="panel panel-primary" style="background-color: #ffffff78;">
 <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
<div class="panel-body">
<div class="row">
<div class="col-sm-12 col-md-6 col-lg-6">
 <div class="panel panel-success">
 <div class="panel-heading"><h3 class="panel-title">Форма поиска</h3></div>
 <form action="/admin/orders/" method="get" class="form-horizontal">
<div class="panel-body " style="padding-left: 0px; padding-right: 0px">
    <div class="row m-auto">
        <div class="col-sm-12 col-md-7" style="padding-left: 0px; padding-right: 0px">
	 <div class="form-group">
    <label for="order" class="ct-110 control-label">№ заказа:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" onkeyup="this.value = this.value.replace (/[^\d,]/g, '')" value="<?=$_GET['order']?>" name="order" id="order" placeholder="Введите №"/>
    </div>
  </div>
   <div class="form-group">
    <label for="customer_id" class="ct-110 control-label">ID клиента:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" value="<?=$_GET['customer_id']?>" name="customer_id" id="customer_id" placeholder="Введите ID"/>
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="ct-110 control-label">Телефон:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" value="<?=$_GET['phone']?>" name="phone" id="phone" placeholder="Введите Телефон"/>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="ct-110 control-label">Email:</label>
    <div class="col-xs-8">
	<input type="email" class="form-control input" value="<?=$_GET['email']?>" name="email" id="email" placeholder="Введите Еmail"/>
    </div>
  </div>
   <div class="form-group">
    <label for="uname" class="ct-110 control-label">Имя:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" value="<?=$_GET['uname']?>" name="uname" id="uname" placeholder="Введите Имя"/>
    </div>
  </div>
  <div class="form-group">
    <label for="delivery" class="ct-110 control-label">Доставка:</label>
    <div class="col-xs-8">
	 <select name="delivery" class="form-control input select2" id="delivery" >
				<option value="">Все</option>
				<?php foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=> 1, 'shop' => 1), array('sort'=>'ASC')) as $d) { ?>
				 <option value="<?=$d->id?>" <?php if(isset($_GET['delivery']) and $_GET['delivery'] == $d->id ) echo 'selected="selected"'; ?> ><?=$d->name;?></option>
			<?php	} ?>
				<!--<option
					value="999" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '999') echo 'selected="selected"';?>>
					Магазины
				</option>-->
			</select>
    </div>
  </div>
  <div class="form-group">
    <label for="status" class="ct-110 control-label">Стат. заказа:</label>
    <div class="col-xs-8">
	 <select name="status"  id="status" class="form-control input select2">
				<option value="999">Все</option>
				<?php if(isset($this->order_status)){ foreach ($this->order_status as $key => $item) { ?>
                <option value="<?=$key;?>" <?php if(isset($_GET['status']) and $_GET['status'] == $key ) echo 'selected="selected"'; ?> ><?=$item;?></option>
            <?php } } ?>
			</select>
    </div>
  </div>
    <div class="form-group">
    <label for="status" class="ct-110 control-label">Стат. оплаты:</label>
    <div class="col-xs-8">
	 <select name="liqpay_status_id"  id="liqpay_status_id" class="form-control input select2">
				<option label="Выберите статус оплаты">Выберите статус оплаты</option>
                                <option <?php if(isset($_GET['liqpay_status_id']) and $_GET['liqpay_status_id'] == 3 ){ echo 'selected="selected"'; }?> value="3">Оплачен</option>
                                <option <?php if(isset($_GET['liqpay_status_id']) and $_GET['liqpay_status_id'] == 1 ){ echo 'selected="selected"'; }?> value="1">Не оплачен</option>
			</select>
    </div>
  </div>
    
     <div class="form-group">
    <label for="nakladna" class="ct-110 control-label">Накладная:</label>
    <div class="col-xs-8">
	<input type="text" class="form-control input" value="<?=$_GET['nakladna']?>" name="nakladna" id="nakladna" placeholder="№"/>
    </div>
	
  </div>
            </div>
        <div class="col-sm-12 col-md-5">
            
  <div id="view_block" class="s-b">
   <div class="form-group">
    <label for="create_from" class="control-label">Дата создания:</label>
    <div class="col-xs-12">
	<input type="date"   value="<?=$_GET['create_from']?>" class="form-control input " name="create_from"  id="create_from" placeholder="от" size="9" />
	<input type="date"  value="<?=$_GET['create_to']?>" class="form-control input " name="create_to" placeholder="до" size="9" />
    </div>
  </div>
   <div class="form-group">
    <label for="go_from" class="control-label">Дата отправки:</label>
    <div class="col-xs-12">
	<input type="date"   value="<?=$_GET['go_from']?>" class="form-control input " name="go_from"  id="go_from" placeholder="от" size="9" />
	<input type="date"  value="<?=$_GET['go_to']?>" class="form-control input " name="go_to" placeholder="до" size="9" />
    </div>
  </div>
 <div class="form-group">
    <label for="price" class="control-label">Цена:</label>
    <div class="col-xs-12">
	<input type="text" class="form-control input" value="<?=$_GET['price']?>" name="price" id="price" placeholder="+- 3 грн"/>
    </div>
  </div>
  </div>
  <div class="form-group">
            <div id="view_serch" class="r-s" onclick="view_block(this);return false;" data-placement="top"  data-tooltip="tooltip" title="Показать детали"></div>
  </div>
  <div class="form-group " style="padding-left: 15px;">
  <label class="ckbox"><input type="checkbox" name="is_admin" value="1" <?php if ($_GET['is_admin'] == 1) { ?>checked="checked" <?php } ?>>
  <span>Заказы администрации</span></label>
  <label class="ckbox "><input type="checkbox" name="nall" id="nall" value="1" <?php if ($_GET['nall'] == 1) { ?>checked="checked" <?php } ?>>
  <span>С наличием товара</span></label>
  <label class="ckbox "><input type="checkbox" name="detail" value="1" <?php if ($_GET['detail'] == 1) { ?>checked="checked" <?php } ?>>
  <span>Уточнить детали</span></label>
  <label class="ckbox "><input type="checkbox" name="online" value="1" <?php if ($_GET['online'] == 1) { ?>checked="checked" <?php } ?> >
  <span>Онлайн оплаты</span></label>
  <label class="ckbox "><input type="checkbox" name="kupon" value="1" <?php if ($_GET['kupon'] == 1) { ?>checked="checked" <?php } ?>>
  <span>Заказы по штрихкоду</span></label>
  <label class="ckbox "><input type="checkbox" name="bonus" value="1" <?php if ($_GET['bonus'] == 1) { ?>checked="checked" <?php } ?>><span>
  Заказы с бонусами</span></label>
  <label class="ckbox "><input type="checkbox" name="quick_order" value="1" <?php if ($_GET['quick_order'] == 1) { ?>checked="checked" <?php } ?>> 
  <span>Заказы из заявок</span></label>
  </div>
        </div>
    </div>
</div>
<div class="panel-footer">
<div class="form-group">
    <div class="col-xs-offset-1 col-xs-11" style="text-align:center;">
      <button type="submit"  name="go" class="btn btn-primary  btn-lg "><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Найти</button>
  </div>
  </div>
  </div>
</form>
</div>
</div>
<div class="col-sm-12 col-md-6 col-lg-6">
 <div class="panel panel-danger">
 <div class="panel-heading"><h3 class="panel-title">Операции</h3></div>
<div class="panel-body">
    <!--
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body">
<form action="/admin/ordersexcel/" method="get" style="margin-bottom: 0px;">
<label>В Excel: №</label>
<input type="text" class="form-control input w100" placeholder="c" name="min" size="6" /><input type="text" class="form-control input w100" placeholder="до"  name="max" size="6"/>
<button class="btn btn-small btn-default " type="submit" ><i class="glyphicon glyphicon-save-file" aria-hidden="true"></i> Получить</button>
</form>
  </div>
</div>-->
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body" style="padding: 10px;">
      <div class="input-group">
  <span class="input-group-addon" >Массовое изменение статуса:</span>
  <select name="order_status_all" id='order_status_all' class="form-control" >
        <option value="">Выберите статус</option>
        <?php if(isset($this->order_status)){ foreach ($this->order_status as $key => $item) { ?>
                <option value="<?=$key?>"><?=$item?></option>
        <?php } } ?>
    </select>
  <span class="input-group-btn"><button class="btn btn-primary" id="all_status" type="button" ><i class="glyphicon glyphicon-edit" aria-hidden="true"></i> Изменить</button></span>
  </div>
	
</div>
</div>
<script>
function view_block(e){
if($('.s-b').is(":visible")){
$('#view_serch').css('background-image', 'url("/backend/img/icons/niz.png")');
$(".s-b").slideUp();
}else{
$('#view_serch').css('background-image', 'url("/backend/img/icons/verch.png")');
$(".s-b").slideDown();

}
}
    $(document).ready(function () {
        $('#all_status').click(function () {    
            if ($('#order_status_all option:selected').val() !== '') {
                if ($('.order-item:checked').val()) {
                   var arr = [];
                    jQuery.each($('.order-item:checked'), function () {
                        arr.push($(this).attr('name').substr(5));
                    });
                    var id = arr.join(',');
                  //console.log(id);
                   window.location = '/admin/allstatus/id/' + id + '/status/' + $('#order_status_all option:selected').val()+'/';
                }else{
                    alert('Выберите заказы!');
                }
            }else{
                alert('Выберите статус для изменения!');
            }
        });
    });
</script>
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body" style="padding: 10px;">
      <div class="input-group">
  <span class="input-group-addon" id="basic-addon1">Выгрузить в Excel файл:</span>
 <select class="form-control input" name="all" id="all">
	<option value="0">Выберите действие</option>
	<option value="1">Товары в заказах</option>
	<option value="5">Excel для 1С</option>
	<!--<option value="2">Экспорт товаров в заказах</option>
	<option value="3">Excel выделеных заказов</option>
	<option value="4">Excel курьерских заказов</option>-->
 </select><span class="form-group  fade all-error" style="color: #ce0000;"><i class="glyphicon glyphicon-ban-circle" aria-hidden="true"></i> Выберите заказы!</span>
</div>
<script>
    $(document).ready(function () {
	$('#all').change(function () {
	if ($('.order-item:checked').val()) {
	$('.all-error').removeClass('in');
               var arr = [];
                jQuery.each($('.order-item:checked'), function () {
                   arr.push($(this).attr('name').substr(5));
                });
                 var id = arr.join(',');
			   switch(this.value) {
                                case '1' : 
				window.location = '/admin/allarticles/id/' + id+'/';
					break;
				case '2' : 
				window.location = '/admin/allarticlesExcel/id/' + id+'/';
					break;
				case '3' : 
				window.location = '/admin/nowapochtaexel/id/' + id+'/';
					break;
				case '4' : 
				window.location = '/admin/exelkurer/id/' + id+'/';
					break;
				case '5' : 
				window.location = '/admin/exelarticles/id/' + id+'/';
					break;
				default :
					alert('Неизвестное значение: ' + this.value);	
			   }  
            }else{
			$('.all-error').toggleClass('in');
			}

	});
    });
</script>
</div>
</div>
<!--
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body" style="padding: 10px;">
<label>Excel заказов с начальной стоимостю</label><br>
<button class="btn btn-small btn-default" id='order_nachal' type="button"><i class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></i> Експорт</button>
    <input type="date" class="form-control input w150" id="order_date" value="<?=date('Y-m-d');?>"  size="9" />
	 <span class="form-group  fade order_nachal-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите дату</span>
</div>
</div>
<script>
    $(document).ready(function () {
        $('#order_nachal').click(function () {
		if($('#order_date').val()) { window.location = '/admin/otchets/type/3/?day=' + $('#order_date').val();}else{ $('.order_nachal-error').toggleClass('in'); }
        });
    });
</script>-->
<?php if(/*!$this->user->isPointIssueAdmin()*/true){ ?>
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body" style="padding: 10px;">
      <div class="btn-group ">
        <?php  if(/*$this->user->id == 8005*/ true){ ?>
            <button class="btn btn-primary" id='ordercomplect' type="button" data-placement="top" title="Совмещать, можно только 'Новый' и 'Доставлен в магазин'"  data-tooltip="tooltip"><i class="glyphicon glyphicon-resize-small" aria-hidden="true"></i> Совместить заказы</button>
      <?php  } ?>  
<button class="btn btn-danger" id='order_uncomplect' type="button"><i class="glyphicon glyphicon-resize-full" aria-hidden="true"></i> Разъединить заказ</button>
      </div>
<span class="form-group  fade complect-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите заказы</span>
</div>
</div>
<?php } ?>
<script>
    function NowaMail(id, object) {
        $.post('/admin/nowamail/', {id: id}, function (data) {
            object.parent().html(data);
        });
    }
    $(document).ready(function () {
        $('#ordercomplect').click(function () {
            if ($('.order-item:checked').val()) {
            let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                });
                window.location = '/admin/ordercomplect/id/' +or.join(',')+'/';
            }else{
			$('.complect-error').toggleClass('in'); 
            }
        });
        $('#order_uncomplect').click(function () {
            if ($('.order-item:checked').val()) {
                let or = [];
                jQuery.each($('.order-item:checked'), function () {
                     or.push($(this).attr('name').substr(5));
                });
                window.location = '/admin/orderuncomplect/id/' +or.join(',')+'/';

            }else{
		$('.complect-error').toggleClass('in'); 
            }
        });
    });
</script>
<div class="panel panel-default" style="margin-bottom:5px;">
  <div class="panel-body" style="padding: 10px;">
     <div class="input-group">
  <span class="input-group-addon" >Печать</span>
  <select name="masrintordertype" id="masrintordertype" class="form-control">
        <option value="1">Магазин</option>
        <option value="2">Укр-почта</option>
        <option value="3">Новая почта</option>
        <option value="4">Курьером</option>
        <option value="5">Justin</option>
    </select>
  <span class="input-group-addon" style='padding: 0px' >
      <button class="btn btn-sm btn-warning" id='masrintnakl' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Наклейки</button></span>
</div>
  <span class="form-group  fade all_print-error" style="color: #ce0000;"><i class="glyphicon glyphicon-ban-circle" aria-hidden="true"></i> Выберите нужные заказы!</span>
        <span class="form-group  fade masrintordertype-error" style="color: #ce0000;"><i class="glyphicon glyphicon-arrow-left" aria-hidden="true"></i> Выберите из списка</span>
<div class="btn-group ">
	<button class="btn btn-small btn-primary" id='masrintorder' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Счета</button>
	
       <!-- <button class="btn btn-small btn-info" id='chack' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Чеки</button>-->
        <button class="btn btn-small btn-info" id='lenta' type="button"><i class="glyphicon glyphicon-print" aria-hidden="true"></i> Лента</button>
            <?php if(true){ ?>
	<button class="btn btn-small btn-danger" id='pr_vozvrat' data-placement="top" title="На возврат можно принимать заказы, в статусе 'Отправлен' и 'Оплачен'"  data-tooltip="tooltip" type="button"><i class="glyphicon glyphicon-copy" aria-hidden="true"></i> Принять на возврат</button>
	<?php } ?>
</div>
      <!--
<div style="width: 49%;display: inline-block;vertical-align: top;">
<label>Печать выделеных заказов:</label><br>
 <select class="form-control input" name="all_print" id="all_print">
	<option value="0">Выберите печать</option>
        <option value="2">Чеки</option>
	<option value="1">Бланки</option>
	<option value="3">Сообщение на посылку</option>
 </select>
 </div>-->
<script>
    $(document).ready(function () {
        
        $('#chack').click(function () {
           if ($('.order-item:checked').val()) {
                let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                }); 
                setLang('uk');
		  window.open ( '/uk/admin/masgeneratechek/ids/' + or.join(',')+'/' , '_blank'); 
			   }else{
			   $('.all_print-error').toggleClass('in'); 
			   }
        });
        $('#lenta').click(function () {
           if ($('.order-item:checked').val()) {
                let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                }); 
                setLang('uk');
		  window.open ( '/uk/admin/masgeneratelentashop/ids/' + or.join(',')+'/' , '_blank'); 
			   }else{
			   $('.all_print-error').toggleClass('in'); 
			   }
        });
        
        $('#masrintorder').click(function () {
            if ($('.order-item:checked').val()) {
                 let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                });
				//window.open ( '/admin/masgenerateorder/ids/' + id + '/type/' + $('#masrintordertype').val()+'/', '_blank');
                setLang('uk');
                window.open ('/uk/admin/masgenerateorder/ids/' + or.join(',') + '/count/2/', '_blank');

            }else{
			$('.all_print-error').toggleClass('in'); 
			}
        });

        $('#masrintnakl').click(function () {
            if ($('.order-item:checked').val()) {
                let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                });
		window.open ( '/admin/masgeneratenakl/ids/' + or.join(',') + '/type/' + $('#masrintordertype').val()+'/', '_blank');

            }else{
			$('.masrintordertype-error').toggleClass('in'); 
			}
        });
    });
	 $('#pr_vozvrat').click(function () {
            if ($('.order-item:checked').val()) {
                 let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                });
		$.ajax({
                url: '/admin/vozrat/',
                type: 'POST',
                dataType: 'json',
                data: {method: 'add_order_vozvrat', order : or.join(',')},
                success: function (res) {
				var mes = '';
				if(res.ok){
				mes+='<div class="alert alert-success" role="alert">';
				mes+='Заказ '+res.ok.join(',')+' принят на возврат.';
				mes+='</div>';
				}
				if(res.error){
				mes+='<div class="alert alert-danger" role="alert">';
				mes+='Заказ '+res.error.join(',')+res.message;
				mes+='</div>';
				}
				fopen('Прийом заказа на возврат', mes);
                },
				error: function(e){
				console.log(e);
				}
            });
            }else{
			alert('Отметьте нужный заказ!');
			}
        });
</script>
 </div>
<?php if($this->admin_rights['494']['right'] == 1 and false){ ?>
<p><input type="button" id='view_ttn_np' class="btn" value="Внести ТТН-НП"/></p>
<?php } ?>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
$('#view_ttn_np').click(function () {
	fopen('Внесение ТТН', '<p>ОТСКАНИРУЙТЕ ТТН:</p><form action="" method="POST" id="myform" align="center"><input type="text" style="border: 1.5px solid #1963d1;" name="ttn" id="ttn" value="" autofocus maxlength="14" class="input"></form><p id="text" style="text-align:center;"> </p>');
		var k = 0;
	  $('#ttn').keypress(function(e){
	 if(e.key == 'Enter') { $('#myform').submit();}
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
                let or = [];    
                jQuery.each($('.order-item:checked'), function () {
                    or.push($(this).attr('name').substr(5));
                });
                window.open ( '/admin/masgenerateblank/ids/' +or.join(',')+'/' , '_blank');

            }
        });
		
		
    });
</script>
<br/>
<?php if (isset($this->orders) && $this->getOrders()->count()) { ?>
    <script>
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
		return false;
}
//получение информации по посылке ukr почта
function up_tracking(x) {
      $.ajax({
          url: "/admin/ukrpost/",
          method: "POST",
          dataType: "json",
          data: { method : "trac", barcode : x},
          success: function( data ) {
              console.log(data);
                  fopen('Отслеживание заказа', data);
   
          },
          error: function (data, dgsd){
               console.log(data);
              console.log( dgsd);
          }
        } );
		return false;
}
//получение информации по посылке ukr почта
function justin_tracking(x, fop_id) {
    // fopen('Отслеживание заказа', 'В разработке');
     $.ajax({
          url: '/admin/justin/',
          method: "POST",
         // dataType: "json",
          data: { method : "trac", barcode : x, fop: fop_id},
          success: function( data ) {
              console.log(data);
                  fopen('Отслеживание заказа', data);
   
          },
          error: function (data, dgsd){
               console.log(data);
              console.log( dgsd);
          }
        } );
        
		return false;
}
//получение информации по посылке trekko
function k_tracking(x) {
$.get('/admin/trekko/metod/status/id/'+x+'/',
		function (data) {
		if(data){
		console.log(data);
		 fopen('Отслеживание заказа', data);
		 }
		});
		return false;
}
    </script>
<div class="row">
    <?php $item_page = $_COOKIE['item_page']?$_COOKIE['item_page']:40; ?>
    <nav aria-label="..." style="text-align:  center;">
<ul class="pagination" style="margin: 2px auto">
    <li <?php if($item_page == 40){echo 'class="active"';  } ?>><a href="#" <?php if($item_page != 40){ echo 'disabled'; ?> onclick="set_item_onPage(40);"  <?php } ?> ><span >40</span></a></li>
    <li <?php if($item_page == 60){echo 'class="active"'; } ?>><a href="#" <?php if($item_page != 60){echo 'disabled'; ?> onclick="set_item_onPage(60);"  <?php  } ?> ><span>60</span></a></li>
    <li <?php if($item_page == 80){echo 'class="active"'; } ?>><a href="#" <?php if($item_page != 80){echo 'disabled'; ?> onclick="set_item_onPage(80);"  <?php  } ?> ><span>80</span></a></li>
</ul>
    </nav>

<script>
    function set_item_onPage(e){
        document.cookie = "item_page =" + e;
location.reload();
    }
</script>
    <table  id="orders" class="table  table-hover">
        <thead>
        <tr>
            <th colspan="2">
                <label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы">
                    <input onchange="chekAll();" class="chekAll" type="checkbox">
                    <span></span>
                </label>
            </th>
           
            <th>Статус</th>
            <th>Номер</th>
            <!--<th>Продавец</th>-->
            <th>ФОП</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <th>Доставка</th>
            <th>Оплата</th>
            <th>%</th>
            <th>Статус/ТТН</th>
            <th>Пометка</th>
        </tr>
        </thead>
        <tbody>
        <?php $row = 'row2'; foreach ($this->getOrders() as $order) {
            $admin = [24148=>'Сергей',  37075=>'Андрей', 40184=>'Богдан'];
            
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $order_owner =  $order->customer;
            ?><?php if($order->getIsUnitedly() != 1){
                if ($order->getStatus() == 3 and $order->delivery_type_id == 3  and  $order->getOrderGo() and $order->getOrderGo() != '0000-00-00 00:00:00' and strtotime($order->getOrderGo()) < mktime(0, 0, 0, date("m"), date("d") - 3, date("Y"))){
                    $style = 'style="background:#fd9d9d;"';
                }else{
                    $style = '';
                }
                ?>
            <tr class="<?=$row?>" <?=$style?> >
                <td>
                    <label class="ckbox"><input type="checkbox" class="order-item cheker" name="item_<?=$order->getId()?>"/><span></span></label>
                </td>
                <td class="kolomicon">
                    <a target="_blank" href="/admin/shop-orders/edit/id/<?=$order->getId()?>/"><i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="left" title="Редактировать заказ"  data-tooltip="tooltip"></i></a>
               <?php if ($this->user->isSuperAdmin()) { ?>
			  <i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$order->getId()?>" data-placement="left" title="Смотреть историю заказа"  data-tooltip="tooltip" ></i>
               <?php }
               if($this->admin_rights['535']['right'] == 1 and $order->delivery_type_id == 4 and !$order->nakladna){ ?>
                   
                <a target="_blank" href="/admin/ukrpost/new-shipment/id/<?=$order->getId()?>/">
                 <i class="icon n ion-ios-briefcase-outline bleak tx-30 pd-5 " alt="Посылка" data-id="<?=$order->getId()?>" data-placement="left" title="Оформить посылку"  data-tooltip="tooltip" ></i>
                </a> 
           <?php    }elseif($this->admin_rights['525']['right'] == 1 and ($order->delivery_type_id == 8 or  $order->delivery_type_id == 16) and !$order->nakladna){ ?>
                <a target="_blank" href="/admin/novapochta/new/id/<?=$order->getId();?>/">
                 <i class="icon n ion-ios-briefcase-outline bleak tx-30 pd-5 " alt="Посылка" data-id="<?=$order->getId()?>" data-placement="left" title="Оформить посылку"  data-tooltip="tooltip" ></i>
                </a> 
        <?php   }elseif($this->admin_rights['562']['right'] == 1 and $order->delivery_type_id == 18 and !$order->nakladna){?>
             <a target="_blank" href="/admin/justin/new/id/<?=$order->getId()?>/">
                 <i class="icon n ion-ios-briefcase-outline bleak tx-30 pd-5 " alt="Посылка" data-id="<?=$order->getId()?>" data-placement="left" title="Оформить посылку"  data-tooltip="tooltip" ></i>
                </a> 
     <?php   }
               ?>
                </td>
                <td>
                 <?php if($order->admin){?>
                    <span style="font-size: 12px;color: red;">(<?=$admin[$order->admin]?>)</span><br>
                <?php } ?>   
        <?=$order->getStat()->getName()?>
                   
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
    <?php if($order->isActiya()){ echo '<br><img src="/backend/img/icons/ac.gif" style="width:60px" data-placement="bottom" title="Нужно добавить шарф!"  data-tooltip="tooltip"';}?>
                </td>
                <td><?=$order->getId()?><?=$order->getOldid()?'/'.$order->getOldid():''?>
                <?=$order->from_quick?'<br><span style="font-size:10px;color:red">Заявка:<br>'.$order->quick_number.'</span>':''?>
                </td>
                <!--<td><?=$order->shop->name?></td>-->
                <td><?=$order->fop->name?></td>
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

                <td><?=$order->getDeliveryTypeId() ? $order->getDeliveryType()->getName(): ''?></td>
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
      <td><?=$order->skidka?$order->skidka:0?>%</td>
      <td>
      <?php if ($order->getBoxNumber()) { ?>
                        Номер ячейки: <?php echo $order->getBoxNumber(); ?>
                    <?php } ?>
                    <form id="order<?= $order->getId() ?>" style="margin-bottom: 2px;" action="/admin/shop-orders/edit/id/<?=$order->getId()?>/" method="get" onsubmit="return false;">
						<input type="hidden"  name="id" value="<?= $order->getId() ?>"/>
						<?php if(in_array($order->getDeliveryTypeId(), array(4,8,16,9,18))){ ?>
			<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">ТТН</span>
  <input type="text" class="form-control nakladna" aria-describedby="basic-addon1"  name="nakladna" value="<?=$order->getNakladna()?>" pattern="[0-9]{5,14}">
</div>			
	   <?php if(($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16) and $order->getNakladna()){ ?>
		<img style="padding-left: 5px;" class="img_return" src="/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="np_tracking('<?=$order->getNakladna()?>');"/>
		<?php }else if($order->getDeliveryTypeId() == 4 and @$order->getNakladna()){
	?><img style="padding-left: 5px;" class="img_return" src="/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="up_tracking('<?php echo $order->getNakladna()?>');"/>
	<?php }else if($order->getDeliveryTypeId() == 9 and @$order->getNakladna()){?>
	<img style="padding-left: 5px;" class="img_return" src="/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="k_tracking('<?php echo $order->getNakladna()?>');"/>
            <?php }elseif($order->getDeliveryTypeId() == 18 and @$order->getNakladna()){?>
                <img style="padding-left: 5px;" class="img_return" src="/img/icons/help.png" alt="Отследить" data-placement="right"  data-tooltip="tooltip" title="Отследить"  onclick="justin_tracking('<?=$order->getNakladna()?>', <?=$order->fop_id?>);"/>
            <?php } ?>
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
	if($order->getDeposit() > 0) echo '<div class="pometka dep" data-placement="right"  data-tooltip="tooltip" title="Использован депозит '.$order->getDeposit().' грн." style="background: #00a600;"></div>';
	if($order->getKuponPrice() > 0 and $order->getKupon()) echo '<div class="pometka kup" data-placement="right"  data-tooltip="tooltip" title="Присутствует штрихкод: '.$order->getKupon().'" style="background: #aeb200;"></div>';
	if($order->getBonus() > 0)  echo '<div class="pometka bon" data-placement="right"  data-tooltip="tooltip" title="Присутствует бонус '.$order->getBonus().' грн." style="background: #41befc;"></div>';
	if($order->getArticlesEvent()) echo '<div class="pometka event" data-placement="right"  data-tooltip="tooltip" title="Присутствует дополнительная скидка" style="background: #41befc;"></div>';
	if($order_owner->getBlockM()) echo '<div class="pometka b_m" data-placement="right"  data-tooltip="tooltip" title="Блок на магазин" style="background: #d77de7;"></div>';
	if($order_owner->isBlockNpN()) echo '<div class="pometka b_np" data-placement="right"  data-tooltip="tooltip" title="Блок НП: Наложка" style="background: #b61010;"></div>';
	if($order_owner->isBlockCur()) echo '<div class="pometka b_kur" data-placement="right"  data-tooltip="tooltip" title="Блок Курьр" style="background: #008aa2;"></div>';
	if($order_owner->isBlockQuick()) echo '<div class="pometka b_quick" data-placement="right"  data-tooltip="tooltip" title="Блок быстрая заявка" style="background: #3F51B5;"></div>';
	
	if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false) echo '<div class="pometka" style="background: #ff9900"></div>';
	
	if ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y"))) echo '<div class="pometka g_op" data-placement="right"  data-tooltip="tooltip" title="Превышено ожидание оплаты" style="background: #ff9900"></div>';
		
if(/*$this->user->id == 8005*/ true){ ?>
<i class="icon ion-email tx-30 pd-5 " alt="отправить письмо" onclick="return OrderEmail(<?=$order->getId()?>);" data-id="<?=$order->getId()?>" data-placement="right" title="" data-tooltip="tooltip" data-original-title="Отправить письмо"></i>
<?php
}
		?>
			
				</td>
            </tr>
<?php } ?>

        <?php } ?>
        </tbody>
    </table>
	</div>
<script>
$('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/orderhistory/id/'+id+'/m/1/',function (data) {fopen('История изменения заказа №'+id, data);});	
});

$('.history_pay_status').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/historypaystatus/id/'+id+'/',function (data) {fopen('История изменения статуса оплаты №'+id, data);});	
});
function OrderEmail(id) {
var form ='<div class="form-group"><label for="recipient-name" class="col-form-label">Тема:</label><input type="text" class="form-control" id="email_subject"></div><div class="form-group"><label for="message-text" class="col-form-label">Сообщение:</label><textarea class="form-control" id="mesageemail"></textarea></div>';
var footer = '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button><button class="btn btn-primary" onclick="return go_email('+id+');" >Отправить</button>';

fopen('Отправка Email по заказу №'+id, form, footer);
		return false;
}
function go_email(e){
var message = $("#mesageemail").val(); 
var subject = $("#email_subject").val();
console.log(e);
console.log(message);


$.ajax({
			url: '/admin/nowamail/',
			type: 'POST',
			dataType: 'json',
			data: {id: e, metod:'getmail', message: message, subject: subject},
			success: function (res) {
				console.log(res);
				fopen('Отправка Email по заказу №'+e, res.message, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
return false;
}
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
<?php } else { echo 'Нет записей'; }?>