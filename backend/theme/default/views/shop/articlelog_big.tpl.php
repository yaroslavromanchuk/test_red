<img src="<?=$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img" height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>

<form action="" id='histori_find' method="get">
    <select name="customer">
        <option></option>
<?php
	foreach($this->admins as $customer){
?>
        <option value="<?php echo $customer->getId()?>" <?php if($customer->getId()==@$_GET['customer']){?>selected="selected" <?php } ?>><?php echo $customer->getUsername()?></option>
<?php
	}
?>
    </select>
    <br />
    Дата с: <input type="text" name="from" value="<?php echo @$_GET['from']?>" class="datetime" />
    по: <input type="text" name="to" value="<?php echo @$_GET['to']?>" class="datetime" />
    <br />
    <input type="submit" value="Искать" />
</form>

	<table id="products" cellpadding="4" cellspacing="0" style="width:900px;">
		<tr>
			<th>Дата</th>
			<th>Пользователь</th>
			<th>Обьект</th>
			<th>Действия</th>
		</tr>
<?php
    $row = 'row1';
    foreach ($this->logs as $log) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
		$dsc = '';
		if ($log->article_id > 0) {
			$dsc = '<a href="/admin/shop-articles/edit/id/'.$log->article_id.'">
						'.$log->BRA.' ('.$log->MDL.')
					</a>';
		}
		if ($log->order_id > 0) {
			if ($dsc!='') $dsc .= '<br/>';
			$dsc .= '<a style="color: darkblue;" href="/admin/shop-orders/edit/id/'.$log->order_id.'">
						Заказ № '.$log->order_id.'
					</a>';
			$info = str_replace (',', ', ', $log->info);
		}
		echo '
			<tr class="'.$row.'">
				<td>
					'.date('d-m-Y H:i:s', strtotime($log->ctime)).'
				</td>
				<td>
					'.$log->FIO.'<br />('.$log->USR.')
				</td>
				<td>
					'.$dsc.'
				</td>
				<td style="word-wrap: break-word;">
					'.$info.'
				</td>
			</tr>
		';
	}
?>
</table>

