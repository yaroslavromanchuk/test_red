<?php
$mas_delivery_type = array();
	foreach (wsActiveRecord::useStatic('DeliveryType')->findAll(array('active'=> 1), array('sort'=>'ASC')) as $d) {
	$mas_delivery_type[$d->id] = $d->name;
	}
$mas_payment_method = array();
	foreach (wsActiveRecord::useStatic('PaymentMethod')->findAll() as $p) {
	$mas_payment_method[$p->id] = $p->getName();
	}
 ?>
 
      <div class="mailbox-sideleft">
        <a href="" class="btn btn-primary btn-block">Compose</a>

        <nav class="nav nav-mailbox flex-column mg-y-20">
          <a href="" class="nav-link active">
            <i class="icon ion-ios-filing-outline tx-24"></i>
            <span>Inbox</span>
            <span class="mg-l-auto tx-12">20</span>
          </a>
          <a href="" class="nav-link">
            <i class="icon ion-ios-folder-outline tx-20"></i>
            <span>Drafts</span>
            <span class="mg-l-auto tx-12">8</span>
          </a>
          <a href="" class="nav-link"><i class="icon ion-ios-paperplane-outline tx-24"></i> Sent</a>
          <a href="" class="nav-link"><i class="icon ion-ios-trash-outline tx-24"></i> Trash</a>
          <a href="" class="nav-link">
            <i class="icon ion-ios-folder-outline tx-20"></i>
            <span>Spam</span>
            <span class="mg-l-auto tx-12">228</span>
          </a>
        </nav>

        <label class="pd-l-10 tx-11 tx-uppercase">My Folder</label>
        <nav class="nav nav-mailbox flex-column">
          <a href="" class="nav-link"><i class="icon ion-ios-folder-outline tx-20"></i> Updates</a>
          <a href="" class="nav-link"><i class="icon ion-ios-folder-outline tx-20"></i> Promotions</a>
          <a href="" class="nav-link"><i class="icon ion-ios-folder-outline tx-20"></i> Social</a>
          <a href="" class="nav-link"><i class="icon ion-ios-folder-outline tx-20"></i> Technology</a>
          <a href="" class="nav-link"><i class="icon ion-ios-folder-outline tx-20"></i> Advertising</a>
        </nav>
      </div><!-- mailbox-sideleft -->
<div class="mailbox-content">
<div class="row row-sm">
<div class="col-xl-6" >
<form action="/admin/shop-orders/" method="get">
<div class="card pd-20 form-layout form-layout-4">
              <h6 class="card-body-title">Поиск</h6>
              <p class="mg-b-10 mg-sm-b-30">Выберите критерий поиска.</p>
              <div class="row">
                <label class="col-sm-4 form-control-label">Заказ: </label>
                <div class="col-sm-8  mg-t-10 mg-sm-t-0">
                  <input type="text" name="order" class="form-control" placeholder="№..." value="<?=$_GET['order']?>">
                </div>
              </div><!-- row -->
              <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Клиент: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" name="customer_id" id="customer_id" class="form-control" placeholder="id..." value="<?=$_GET['customer_id']?>">
                </div>
              </div>
			  <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Телефон: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" value="<?=$_GET['phone']?>" name="phone" id="phone" class="form-control" placeholder="">
                </div>
              </div>
              <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Email: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" value="<?=$_GET['email']?>" name="email" id="email" class="form-control" placeholder="email address">
                </div>
              </div>
              <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Имя: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" value="<?=$_GET['uname']?>" name="uname" id="uname" class="form-control" placeholder="">
                </div>
              </div>
			  <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Доставка: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <select class="form-control select2" name="delivery" data-placeholder="delivery">
  <option value="0">Все</option>
				<?php foreach($mas_delivery_type as $k =>$name){ ?>
				 <option value="<?=$k;?>" <?php if(isset($_GET['delivery']) and $_GET['delivery'] == $k ) echo 'selected="selected"'; ?> ><?=$name;?></option>
			<?php	} ?>
				<option
					value="999" <?php if (isset($_GET['delivery']) and $_GET['delivery'] == '999') echo 'selected="selected"';?>>
					Магазины
				</option>
</select>
                </div>
              </div>
			   <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Статус: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <select class="form-control select2" name="status"  id="status" data-placeholder="status">
<option value="999">Все</option>
				<?php foreach ($this->order_status as $key => $item) { ?>
                <option value="<?=$key;?>" <?php if(isset($_GET['status']) and $_GET['status'] == $key ) echo 'selected="selected"'; ?> ><?=$item;?></option>
            <?php } ?>
</select>
                </div>
              </div>
			  <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Создан: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
			<div class="input-group">
              <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
              <input type="text" class="form-control fc-datepicker" name="create_from"  placeholder="от" >
            </div>
			<div class="input-group">
              <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
              <input type="text" class="form-control fc-datepicker" name="create_to" placeholder="до" >
            </div>
                </div>
              </div>
			 <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Отправлен: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
			<div class="input-group">
              <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
              <input type="text" class="form-control fc-datepicker" name="go_from"  placeholder="от" >
            </div>
			<div class="input-group">
              <span class="input-group-addon"><i class="icon ion-calendar tx-16 lh-0 op-6"></i></span>
              <input type="text" class="form-control fc-datepicker" name="go_to" placeholder="до" >
            </div>
                </div>
              </div>
			  <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Цена: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" value="<?=$_GET['price']?>" name="price" id="price" placeholder="+- 3 грн" class="form-control">
                </div>
              </div>
			  <div class="row mg-t-10">
                <label class="col-sm-4 form-control-label">Накладная: </label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" value="<?=$_GET['nakladna']?>" name="nakladna" id="nakladna" placeholder="№" class="form-control" >
                </div>
              </div>
			  <div class="row mg-t-10">
			  <div class="col-lg-4">
              <label class="ckbox">
                <input name="is_admin" value="1" type="checkbox" <?=$_GET['is_admin'] == 1? 'checked="checked"': ''?>><span>Админ. Заказы</span>
              </label>
            </div>
			<div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox" name="nall" value="1" <?=$_GET['nall'] == 1? 'checked="checked"': ''?>><span>С наличием товара</span>
              </label>
            </div>
			<div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox"  name="detail" value="1" <?=$_GET['detail'] == 1? 'checked="checked"': ''?>><span>Уточнить детали</span>
              </label>
            </div>
			<div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox" name="online" value="1" <?=$_GET['online'] == 1? 'checked="checked"': ''?>><span>Онлайн оплаты</span>
              </label>
            </div>
			<div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox" name="kupon" value="1" <?=$_GET['kupon'] == 1? 'checked="checked"': ''?>><span>Штрихкод</span>
              </label>
            </div>
			<div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox" name="bonus" value="1" <?=$_GET['bonus'] == 1? 'checked="checked"': ''?>><span>Бонусы</span>
              </label>
            </div><div class="col-lg-4">
              <label class="ckbox">
                <input type="checkbox" name="quick_order" value="1" <?=$_GET['quick_order'] == 1? 'checked="checked"': ''?>><span>Заявки</span>
              </label>
            </div>
			  </div>
			  <div class="form-layout-footer mg-t-30 text-center">
                <button  type="submit"  name="go" class="btn btn-info mg-r-5">Искать</button>
              </div><!-- form-layout-footer -->
			</div>
			</form>
</div>
<div class="col-xl-6" >
<div class="card pd-20 form-layout form-layout-4">
              <h6 class="card-body-title">Left Label Alignment</h6>
              <p class="mg-b-20 mg-sm-b-30">A basic form where labels are aligned in left.</p>
              <div class="row">
                <label class="col-sm-4 form-control-label">Firstname: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control" placeholder="Enter firstname">
                </div>
              </div><!-- row -->
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Lastname: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control" placeholder="Enter lastname">
                </div>
              </div>
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Email: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control" placeholder="Enter email address">
                </div>
              </div>
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Address: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea rows="2" class="form-control" placeholder="Enter your address"></textarea>
                </div>
              </div>
              <div class="form-layout-footer mg-t-30">
                <button class="btn btn-info mg-r-5">Submit Form</button>
                <button class="btn btn-secondary">Cancel</button>
              </div><!-- form-layout-footer -->
            </div>
</div>
</div>
<?php if ($this->getOrders()->count()) { ?>
    <script type="text/javascript">
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
function meest_tracking(x) {
/*
$.get('/admin/addmeestttn/ttn/'+x+'/metod/tracking/',
		function (data) {
		if(data){
		//alert(data);
		 $('#popup').html(data);
		 fopen();
		 }
		});*/
		 fopen('Отслеживание заказа', 'Сервис временно недоступен.');
		return false;

}

    </script>
<div class="card pd-20 pd-sm-20 mg-t-10">
<div class="table-responsive" >
<table class="table table-striped table-hover mg-b-0">
<thead class="bg-info">
                <tr>
                  <th>
                    <label class="ckbox mg-b-0">
                      <input type="checkbox" id="chek_all"><span></span>
                    </label>
                  </th>
                  <th colspan="2">Операции</th>
                  <th>Статус</th>
                  <th>Заказ</th>
				  <th>Создан</th>
				  <th>Ф.И.О</th>
				  <th>Колл.тов.</th>
				  <th>Стоимость</th>
				  <th>Доставка</th>
				  <th>Скидка</th>
				  <th>Редактирование</th>
                </tr>
              </thead>
<tbody>
 <?php foreach ($this->getOrders() as $order) { ?>
<tr>
	<td><label class="ckbox mg-b-0"><input type="checkbox"class="order-item cheker" name="item_<?=$order->getId()?>"><span></span></label></td>
	<td><a href="<?=$this->path;?>shop-orders/edit/id/<?=$order->getId();?>/" >
				<img src="<?=SITE_URL?>/img/icons/edit-small.png" alt="Редактировать"  data-placement="left"  data-tooltip="tooltip" class="img_return"  title="Редактировать заказ"></a></td>
	<td><?php if ($this->user->isSuperAdmin()) { ?><img alt="История" src="/img/icons/histori.png"  data-id="<?=$order->getId()?>" data-placement="left"  data-tooltip="tooltip" class="img_return history" title="Смотреть историю заказа"><?php } ?></td>
	<td><?php echo isset($this->order_status[$order->getStatus()]) ? $this->order_status[$order->getStatus()] : ""; ?>
                    <?php if ($order->getComlpect()) { ?>
                        Совмещенный заказ
                    <?php } ?>
                    <?php if ($order->getCallMy() == 1) { ?>
                        <b>Уточнить детали</b>
                    <?php } ?>
                    <?php if ($order->getCallMy() == 2) { ?>
                        <b>Нет необходимости подтверждать заказ по телефону</b>
                    <?php } ?></td>
	<td><?=$order->getId();?><?php if ($order->getOldid()) echo ' / '.$order->getOldid(); ?></td>
	<td><?=date("d-m-y H:i", strtotime($order->getDateCreate()));?></td>
	<td><?php echo $order->getName() . ' ' . $order->getMiddleName(); ?><br><span class="help-block">id: <?=$order->getCustomerId()?></span></td>
	<td><?=$order->getArticlesCount()?></td>
	<td><?php if ($order->getArticlesCount() != 0) {
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
	<td><?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName().'<br><span style=" font-size:10px;color: #cf0f81;">'.$order->getPaymentMethod()->getName().'</span>' : "&nbsp;"; ?>
				<?php 
				if($order->payment_method_id != 0 and ($order->payment_method_id == 4 or $order->payment_method_id == 5 or $order->payment_method_id == 6)){
				$sql = "
			SELECT pay_result.LMI_PAYMENT_AMOUNT as res, pay_result.LMI_PAYMENT_NO FROM pay_result
				LEFT JOIN pay_send ON pay_send.LMI_PAYMENT_NO = pay_result.LMI_PAYMENT_NO
			where pay_result.LMI_PAYMENT_NO = ".$order->id;
			$payments = wsActiveRecord::findByQueryArray($sql);
			if(count($payments) > 0) {echo '<img src="/storage/menu/check_3.png" alt="" class="img_return" data-placement="right"  data-tooltip="tooltip"  title="Заказ оплачен">'; }else{
			echo '<img src="/img/icons/remove-small.png" alt="" class="img_return" data-placement="right"  data-tooltip="tooltip" title="Оплата не прошла">';
			} 
				} ?></td>
	<td><?php  if ($order->getSkidka() != '') {
                        echo $order->getSkidka();
                    } else {
                        $order->save();
                        echo $order->getSkidka();
                    } ?> %</td>
	<td><?php if ($order->getBoxNumber()) { ?>
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
                        <?php if (/*$order->getDeliveryTypeId() == 8*/false) {
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
                        <select class="order_status form-control select2" name="order_status" >
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
                    <?php } ?></td>
</tr>
<?php } ?>
<tbody>				  
</table>
</div>
</div>
<?php } ?>
</div>
	    <script>
      $(function(){
	  	$('#chek_all').change(function(){
		if($(this).is(":checked")) {
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
  //alert('Элемент foo был изменен.');
});
$('.history').click(function (e) {

var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/orderhistory/id/'+id+'/m/1',function (data) {

fopen('История изменения заказа №'+id, data);
});	
});
 $('.select2').select2({
          minimumResultsForSearch: Infinity
        });

        // Select2 by showing the search
        $('.select2-show-search').select2({
          minimumResultsForSearch: ''
        });

        // Select2 with tagging support
        $('.select2-tag').select2({
          tags: true,
          tokenSeparators: [',', ' ']
        });

        // Datepicker
        $('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        });
		});
</script>	
	
