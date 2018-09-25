<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?></h1>
    <p><a href="/admin/shop-orders/edit/id/<?=$this->id?>">К заказу</a></p>
    <form action="" method="get" class="form-inline">
	   <div class="form-group">
	 <select name="type" class="form-control input">
                <option value="0">Тип записи</option>
                <option value="1" <?php if(@$_GET['type']==1){?>selected="selected" <?php } ?>>Смена статуса</option>
                <option value="2" <?php if(@$_GET['type']==2){?>selected="selected" <?php } ?>>Удаление товара</option>
                <option value="3" <?php if(@$_GET['type']==3){?>selected="selected" <?php } ?>>Удаление товара без возврата</option>
                <option value="4" <?php if(@$_GET['type']==4){?>selected="selected" <?php } ?>>Изменение товара</option>
                <option value="5" <?php if(@$_GET['type']==5){?>selected="selected" <?php } ?>>Новый товар</option>
                <option value="6" <?php if(@$_GET['type']==6){?>selected="selected" <?php } ?>>Смена доставки</option>
                <option value="7" <?php if(@$_GET['type']==7){?>selected="selected" <?php } ?>>Смена номера накладной</option>
                <option value="8" <?php if(@$_GET['type']==8){?>selected="selected" <?php } ?>>Совмещение заказов</option>
            </select>
  </div>
           
		<button type="submit" class="btn  btn-default"><i class="glyphicon glyphicon-search" aria-hidden="true"></i> Поиск</button>
    </form>


<table cellpadding="4" cellspacing="0" class="table">
    <tr>
        <th>Заказ</th>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Действия</th>
        <th>Коментарий</th>
    </tr>
    <?php
                      $row = 'row1';
    foreach ($this->logs as $log)
    {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?php echo $row;?>">
                 <td><?=$log->getOrderId();?></td>
                 <td><?=date('d-m-Y H:i:s',strtotime($log->getCtime()));?></td>
                 <td><?php echo $log->admin->getFullName();?><br />(<?php echo $log->admin->getUsername();?>)</td>
                 <td><?=$log->getName();?></td>
                 <td><?=$log->getInfo();?>
        <?php if($log->getArticleId()){?>
                             <a href="/admin/shop-articles/edit/id/<?=$log->getArticleId()?>">Товар</a>
            <?php } ?>
    </td>
            </tr>
        <?php } ?>
</table>
