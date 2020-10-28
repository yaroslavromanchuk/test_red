<div class="card pd-20 mb-3">
  <h6 class="card-body-title">Форма поиска</h6>
 <div class="card-body">
<form action="<?=$this->path?>shop-quick-orders/" method="get">
    <div class="form-layout">
            <div class="row mg-b-25">
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Номер заявки: </label>
                  <input class="form-control" type="text" value="<?=$_GET['quick_number']?>" name="quick_number" placeholder="Заявка">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Номер заказа: </label>
                  <input class="form-control" type="text" name="order" value="<?=$_GET['order']?>" placeholder="Заказ">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Телефон: <span class="tx-danger">*</span></label>
                  <input class="form-control" type="text" value="<?=$_GET['phone']?>" name="phone" placeholder="000 000 00 00">
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label">Email: </label>
                  <input class="form-control" type="text" value="<?=$_GET['email']?>" name="email" placeholder="email@red.ua">
                </div>
              </div><!-- col-3 -->
              <div class="col-lg-2">
                <div class="form-group">
                  <label class="form-control-label">Статус: </label>
                  <select class="form-control select2" name="status" data-placeholder="Статус" tabindex="-1" aria-hidden="true">
                    <option label="Статус"></option>
                    <option value="100">Новый</option>
                    <option value="2">Отменён</option>
                  </select>
                </div>
              </div><!-- col-4 -->
              <div class="col-lg-2">
                  <div class="form-group mt-4 pt-1">
                      <button class="btn btn-info mg-r-5" type="submit">Искать</button>
                     <!-- <button class="btn btn-secondary" type="reset">Очистить</button>-->
                  </div>
                  </div>
              </div><!-- row -->
          </div>
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
  <div class="card pd-20">
        <h6 class="card-body-title">Список заявок</h6>
 <div class="card-body">
	<table id="orders" class="table table-hover datatable1">
            <thead class="thead-dark">
        <tr>
            <th>
                <label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы">
                    <input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span>
                </label>
            </th>
            <th scope="col">Действия</th>
            <th scope="col">Номер</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <th>Скидка</th>
            <th>Комментарии</th>
            <th>Связь</th>
        </tr>
          </thead>
          <tbody>
        <?php foreach ($this->getOrders() as $order) {
            $order_owner = new Customer($order->getCustomerId());
		?>
            <tr 
                <?php if ($order_owner->getAdminComents()) { ?> style="background: #ff6666;" <?php
                } else {
					if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false)
							echo "style='background: #ff9900'";
						elseif ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")))
							echo "style='background: #ff9900'";
						elseif($order_owner->getBlockM() == 1)
								echo 'style="background: #d77de7;" ';
            }?> >
                <td>
                    <label class="ckbox"><input type="checkbox" class="order-item cheker" name="item_<?=$order->getId()?>"/><span></span></label>
                </td>
		<td>
					
					<a href="<?=$this->path?>edit-quick-order/id/<?=$order->getId()?>/">
                                         
						<i class="icon ion-ios-clipboard bleak1 tx-30 pd-5" alt="Редактировать" data-placement="right" title="" data-tooltip="tooltip" data-original-title="Редактировать заказ"></i>
					</a>
					<?php if ($this->user->isSuperAdmin()) { ?>
							<i class="icon ion-ios-clock bleak tx-30 pd-5 history" alt="История" data-id="<?=$order->getId()?>" data-placement="right" title="" data-tooltip="tooltip" data-original-title="Смотреть историю заказа"></i>
<?php
					}
?>
                </td>
                <td><?=$order->getQuickNumber()?><br/>#<?=$order->id?><br/>id:<?=$order->customer_id?></td>
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
<i class="icon ion-ios-mail tx-30 pd-5 " alt="отправить письмо" onclick="PhoneMail(<?=$order->getId()?>,$(this)); return false;" data-id="<?=$order->getId()?>" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Отправить письмо"></i>
										  </span>
                            <?php }else{ ?>
<span id="<?=$order->getQuickNumber()?>"> письмо отправлено: <?=date('d-m-Y', strtotime($order->getCallMail()))?>
<i class="icon ion-ios-mail tx-30 pd-5 " alt="отправить повторно" onclick="PhoneMail(<?=$order->getId()?>,$(this)); return false;" data-id="<?=$order->getId()?>" data-placement="left" title="" data-tooltip="tooltip" data-original-title="Отправить письмо повторно"></i>
</span>
                            <?php } ?>
                        
				</td>
            </tr>


        <?php } ?>
</tbody>
    </table>
	</div>
	</div>
	
<?php
	}else{ ?>
		<div class="card pd-20">
  <div class="card-body text-center">
      <p class="card-text"> Нет записей!</p>
  </div>
                     </div>
	<?php }
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