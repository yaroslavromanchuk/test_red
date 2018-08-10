 <div class="row">
 <div class="panel panel-primary">
 <div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle()?></h3></div>
 <div class="panel-body">
  <div class="panel panel-success" style="display:none;">
 <div class="panel-heading"><h3 class="panel-title">Форма поиска</h3></div>
 <div class="panel-body">
<form action="<?=$this->path?>shop-quick-orders/" method="get">
	<table  style="margin: auto;"  id="search">
		<tr>
			<td>Номер заявки:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['order']; ?>" name="order"/></td>
			<td>Телефон:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['phone']; ?>" name="phone"/></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['email']; ?>" name="email"/></td>
			<td>Имя:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['uname']; ?>" name="uname"/></td>
		</tr>
		<tr>
			<td colspan="4" align="center">Дата создания   от <input type="date"  class="form-control input" name="create_from" size="9" />
			до <input type="date"  class="form-control input" name="create_to" size="9" /></td>
			
		</tr>
		<tr>
			<td colspan="4" align="center">Дата отправки  от <input type="date"  class="form-control input" name="go_from" size="9" />
			до <input type="date"  class="form-control input" name="go_to" size="9" /></td>
			
		</tr>
		<tr>
			<td colspan="4" align="center">Стоимость: <input type="text" class="form-control input" value="<?php echo @$_GET['price']; ?>" name="price"/> +- 3 грн.</td>
		</tr>
		<tr>
			<td colspan="4" align="center"><input type="submit" class="button" value="Найти" style="padding: 5px 100px; cursor: pointer;" /></td>
		</tr>
	</table>
</form>
</div>
</div>
<?php if ($this->getOrders()->count()) { ?>
    <script>
        function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
        }
		

    </script>
  <div class="panel panel-info">
 <div class="panel-heading"><h3 class="panel-title">Список заявок</h3></div>
 <div class="panel-body">
	<table id="orders" class="table">
        <tr>
		<th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы"><input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label></th>
            <th>Действия</th>
            <th>Номер</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <th>Скидка</th>
            <th>Комментарии</th>
			<th>Связь</th>
        </tr>
        <?php $row = 'row2'; foreach ($this->getOrders() as $order) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $order_owner = new Customer($order->getCustomerId());
		?>
            <tr class="<?=$row?>"
                <?php if ($order_owner->getAdminComents()) { ?>style="background: #ff6666;" <?php
                } else {
					if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false)
							echo "style='background: #ff9900'";
						elseif ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")))
							echo "style='background: #ff9900'";
						elseif($order_owner->getBlockM() == 1)
								echo 'style="background: #d77de7;" ';
				
            }
		?>>
		
                <td><label class="ckbox"><input type="checkbox" class="order-item cheker" name="item_<?=$order->getId()?>"/><span></span></label>
				</td>
				<td>
					
					<a href="<?=$this->path?>edit-quick-order/id/<?=$order->getId()?>/">
						<i class="icon ion-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="right" title="" data-tooltip="tooltip" data-original-title="Редактировать заказ"></i>
					</a>
					<?php if ($this->user->isSuperAdmin()) { ?>
							<i class="icon ion-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$order->getId()?>" data-placement="right" title="" data-tooltip="tooltip" data-original-title="Смотреть историю заказа"></i>
<?php
					}
?>
                </td>
                <td><?=$order->getQuickNumber()?></td>
                <td><?=$order->getStat()->getName()?></td>
                <td><?=$order->getDateCreate()?></td>
                <td><?=$order->getName().' '.$order->getMiddleName()?></td>
                <td><?=$order->getArticlesCount()?></td>
                <td><?=$order->getArticlesCount()?$order->getAmount():''?></td>
                <td><?=$order->getSkidka()?$order->getSkidka():0?>%</td>
				<td>
				<?php if ($order->getRemarks()->count() or $order->getComments()) {
					$remar = array();
					foreach ($order->getRemarks() as $remark) { $remar[] = $remark->getRemark()."-".$remark->getName(); } ?>
						<?php if ($order->getRemarks()->count()) { ?>
							<div style="background: #C0FFD4; border: 1px solid #000; padding: 5px; margin: 5px;">
							<b>Внутренний комментарий :</b><br/>
							<?=implode(';', $remar)?>
							</div>
<?php
							}
?>
						<?php if ($order->getComments()) { ?>
							<div style="background: #ffff33; border: 1px solid #000; padding: 5px; margin: 5px;">
							<b>Комментарий клиента :</b><br/>
							<?=$order->getComments()?>
							</div>
						<?php } ?>
				<?php } ?>
				</td>
				<td>
				<?php if($order->getCallMail() == '0000-00-00 00:00:00') { ?>
<span id="<?php echo $order->getQuickNumber();?>">
<i class="icon ion-email tx-30 pd-5 " alt="отправить письмо" onclick="PhoneMail(<?=$order->getId()?>,$(this)); return false;" data-id="<?=$order->getId()?>" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Отправить письмо"></i>
										  </span>
                            <?php }else{ ?>
<span id="<?=$order->getQuickNumber()?>"> письмо отправлено: <?=date('d-m-Y', strtotime($order->getCallMail()))?>
<i class="icon ion-email tx-30 pd-5 " alt="отправить повторно" onclick="PhoneMail(<?=$order->getId()?>,$(this)); return false;" data-id="<?=$order->getId()?>" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Отправить письмо повторно"></i>
</span>
                            <?php } ?>
                        
				</td>
            </tr>


        <?php } ?>

    </table>
	</div>
	</div>
	
</div>
</div>
</div>
<?php
	}
	else {
		echo 'Нет записей';
	}
?>
  <script>
$('.history').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.get('/admin/orderhistory/id/'+id+'/m/1',function (data) {fopen('История изменения заказа №'+id, data);});	
});
function PhoneMail(id, object) {
var getcall = 'getcall';
$.post('/admin/nowamail/', {
id: id,
metod: getcall
}, function (data) {
            object.parent().html(data);
        });
}

	  </script>