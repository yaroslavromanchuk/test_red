<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>"  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?php //echo $this->getCurMenu()->getPageBody();?>
<?php
$d =  wsActiveRecord::useStatic('Customer')->findByQuery('SELECT SUM(  `deposit` ) AS cdep
FROM  `ws_customers` 
WHERE  `deposit` >0');
 ?>
<form action="" method="get" class="form-horizontal" style="    margin: auto;width: 350px;">
    <p>Поиск</p>
	<table class="table">
	<tr>
	<td>
    <span style="width:140px;"> Id пользователя </span>
	</td>
	<td>
	<input type="text" class="form-control input" name="email" value="<?=$_GET['email']; ?>"/><br>
	</td>
	</tr>
	<tr>
	<td>
	<span style="width:140px;"> Id Админа </span>
	</td>
	<td>
	<input type="text" class="form-control input" name="admin" value="<?=$_GET['admin']; ?>"/>
	</td>
	</tr>
	<tr>
	<td> <span style="width:140px;"> От: </span>
	</td>
	<td><input type="date" class="form-control input" name="from"   id="from" />
	</td>
	</tr>
	<tr>
	<td> <span style="width:140px;"> До: </span>
	</td>
	<td><input type="date" class="form-control input" name="to"   id="to" />
	</td>
	</tr>
	</table>
<input type="submit" class="btn btn-small btn-default" value="Найти"/>
	</form>
<table class="table" style="    margin: auto;width: 350px;">
<tr>
<td>Сумма всего депозита пользователей:</td>
<td><?=round($d[0]['cdep'],2);?></td>
</tr>
<tr>
<td>Сумма зачисленого депозита:</td>
<td><?=round($this->sumadd[0]['sum'], 2);?></td>
</tr>
<tr>
<td>Сумма использованого депозита:</td>
<td><?=round($this->summin[0]['sum'], 2); ?></td>
</tr>
<tr>
<td>На депозите:</td>
<td><?=round(($this->sumadd[0]['sum']-$this->summin[0]['sum']), 2); ?></td>
</tr>
</table>

    <table id="pageslist" cellpadding="2" cellspacing="0"  class="table  table-hover">
		<tr>
			<th style="width: 125px;text-align: center;">Дата</th>
			<th style="width: 150px;text-align: center;">Редактировал</th>
			<th style="width: 150px;text-align: center;">Пользователь</th>
			<th style="width: 150px;text-align: center;">Действие</th>
			<th style="width: 50px;text-align: center;">Сумма</th>
			<th style="width: 100px;text-align: center;">Заказ</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getDeposit() as $value )
		{
		
		$admin = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $value->getAdminId()));
		$customer = wsActiveRecord::useStatic('Customer')->findFirst(array('id' => $value->getCustomerId()));
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">

			<td class="cat-seq"><?=$value->getCtime();?></td>
			<td class="kolomicon"><?php echo $admin['id']."<br>".$admin['first_name']." ".$admin['middle_name']."<br>".$admin['email'];?></td>
			<td class="kolomicon"><?php echo $customer['id']."<br>".$customer['first_name']." ".$customer['middle_name']."<br>".$customer['email'];?></td>
			<td class="kolomicon"><?=$value->getAction();?></td>
			<td class="kolomicon"><?=$value->getInfo();?></td>
			<td class="kolomicon"><?php if($value->getOrders() > 0){ echo ' <a href="http://www.red.ua/admin/shop-orders/edit/id/'.$value->getOrders().'/" >'.$value->getOrders().' </a>'; }else{echo "Редактирование";}?></td>
			
  		</tr>
	<?php
		}
	?>
    </table>