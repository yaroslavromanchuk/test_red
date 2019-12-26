	<div class="row">
		<div class="col-xs-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item active">
                            <a class="nav-link" style="font-size: 14px;" href="#ser" data-toggle="tab">Поиск</a>
                        </li>
			<li class="nav-item ">
                            <a class="nav-link active show" style="font-size: 14px;" href="#ok" data-toggle="tab" aria-expanded="true">Прошла оплата</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 14px;" href="#no" data-toggle="tab">Ошибка оплаты</a>
                        </li>
                        
	</ul>
                    </div>
            <div class="col-xs-12">
                <?php
        $payment_s[21] = 'Visa/MasterCard';
	$payment_s[49] = 'Приват 24';
	$payment_s[1] = 'Webmoney';
                ?>
                <div class="tab-content" style="border-left: 1px solid #ddd;border-right: 1px solid #ddd;border-bottom: 1px solid #ddd;padding: 20px;background-color: #fff;">
                   
                    <div id="ser" class="tab-pane fade  active in">
                        <form class="form-horizontal" action="" method="get">
<fieldset>
<!-- Form Name -->
<legend>Форма поиска оплаты</legend>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">№ заказа</label>  
  <div class="col-md-4">
      <input id="textinput" name="order" type="text" value="<?=$_GET['order']?$_GET['order']:''?>" placeholder="000000"  class="form-control input-md" required="">
  <span class="help-block">введите номер заказа который был оплачен</span>  
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
      <button id="singlebutton" name="singlebutton"  type="submit" class="btn btn-success">Искать</button>
  </div>
</div>
</fieldset>
</form>
                        <div>
                            <?php if($this->searsh){
                                ?>
                            <table class="table table-hover  table-dark" >
				<thead>
					<tr>
						<th scope="col">№ заказа</th>
						<th scope="col">Сумма оплаты</th>
						<th scope="col">Дата оплаты</th>
						<th scope="col">Платежная система</th>
					</tr>
                                        </thead>
                                        <tbody>
                                        <td>
                                            <a target="_blank" href="/admin/shop-orders/edit/id/<?=$this->searsh->LMI_PAYMENT_NO?>/"><?= $this->searsh->LMI_PAYMENT_NO?></a>
                                        </td>
                                        <td><?=$this->searsh->LMI_PAYMENT_AMOUNT?></td>
						<td><?=$this->searsh->cdt?></td>
						<td><?=$payment_s[$this->searsh->LMI_PAYMENT_SYSTEM]?></td>
                                        </tbody>
                            </table>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <div id="ok" class="tab-pane fade">
                        <table class="table table-hover  table-dark" >
				<thead>
					<tr>
						<th scope="col">№ заказа</th>
						<th scope="col">Сумма оплаты</th>
						<th scope="col">Дата оплаты</th>
						<th scope="col">Платежная система</th>
					</tr>
                                        </thead>
				<tbody>
<?php foreach ($this->payments as $payment) { ?>
					<tr>
						<td scope="row">
                                                    <a target="_blank" href="/admin/shop-orders/edit/id/<?= $payment->LMI_PAYMENT_NO?>/"><?= $payment->LMI_PAYMENT_NO?></a>
                                                </td>
						<td><?=$payment->LMI_PAYMENT_AMOUNT?></td>
						<td><?=$payment->cdt?></td>
						<td><?=$payment_s[$payment->LMI_PAYMENT_SYSTEM]?></td>
					</tr>
<?php
	}
?>
				</tbody>
				
			</table>
                    </div>
                    <div id="no" class="tab-pane fade">
                        <table class="table table-hover  table-dark" >
				<thead>
					<tr>
						<th scope="col">№ заказа</th>
						<th scope="col">Сумма оплаты</th>
						<th scope="col">Дата оплаты</th>
						<th scope="col">Платежная система</th>
					</tr>
                                        </thead>
				<tbody>
<?php foreach ($this->paymentsno as $payment) { ?>
					<tr>
						<td scope="row">
                                                    <a target="_blank" href="/admin/shop-orders/edit/id/<?= $payment->LMI_PAYMENT_NO?>/"><?= $payment->LMI_PAYMENT_NO?></a>
                                                </td>
						<td><?=$payment->LMI_PAYMENT_AMOUNT?></td>
						<td><?=$payment->cdt?></td>
						<td><?=$payment_s[$payment->LMI_PAYMENT_SYSTEM]?></td>
					</tr>
<?php
	}
?>
				</tbody>
				
			</table>
                    </div>
                    
                </div> 
                
			
		</div>
	</div>