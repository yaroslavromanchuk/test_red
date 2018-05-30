	<div class="row">
		<div class="col-xs-12">
		<table style="width: 50%;     font-size: 18px;">
		<tr>
				<th style="width: 50%;     background: #9c9e9f;"><a href="?good_pay" style="color: #2d8441;">Прошла оплата</a></th>
				<th style="width: 50%;     background: #9c9e9f;"><a href="?no_pay">Ошибка оплаты</a></th>
				</tr>
		</table>
			<table class="table table-hover" style="width: 100%;">
			
				<caption>Оплаты</caption>
				
				<thead>
					<tr>
						<th>№ заказа</th>
						<th>Сумма оплаты</th>
						<th>Дата оплаты</th>
						<th>Платежная система</th>
						<th style="width: 300px;">Ошибка</th>
					</tr>
				<tbody>
<?php
/*				
		1 = Webmoney
		6 = MoneXy
		12 = EasyPay
		15 = NSMEP
		17 = Webmoney Terminal
		21 = PaymasterCard
		20 = Приват 24
		19 = LiqPay
		23 = Київстар
		2 = x20 WebMoney моб. платежи
		22 - Терминалы через кнопку пеймастера

		18 = test
*/
	$payment_s[21] = 'Visa/MasterCard';
	$payment_s[20] = 'Приват 24';
	$payment_s[1] = 'Webmoney';
	
	foreach ($this->payments as $payment) {
?>
					<tr>
						<th scope="row"><a style="color:white;" target="_blank" href="/admin/shop-orders/edit/id/<?= $payment->LMI_PAYMENT_NO ?>/"><?= $payment->LMI_PAYMENT_NO ?></a></th>
						<td><?= $payment->LMI_PAYMENT_AMOUNT ?></td>
						<td><?= $payment->cdt ?></td>
						<td><?= $payment_s[$payment->LMI_PAYMENT_SYSTEM] ?></td>
						<td><?= $payment->LMI_CLIENT_MESSAGE ?></td>
					</tr>
<?php
	}
?>
				</tbody>
				</thead>
			</table>
		</div>
	</div>