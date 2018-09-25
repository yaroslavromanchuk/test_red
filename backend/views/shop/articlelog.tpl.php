<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>


<table id="products" cellpadding="4" cellspacing="0" style="width:900px;">
    <tr>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Действия</th>
        <th>Информация</th>
		<th>Количество</th>
		<th>Артикул</th>
    </tr>
    <?php
                      $row = 'row1';
    foreach ($this->logs as $log)
    {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?php echo $row;?>">
                 <td><?php  echo date('d-m-Y H:i:s',strtotime($log->getCtime()));?></td>
                 <td><?php echo $log->admin->getFullName();?><br />(<?php echo $log->getUsername();?>)</td>
                 <td><?php echo $log->getComents();?></td>
                 <td><?php echo $log->getInfo();?></td>
				 <td><?php if($log->getTypeId() == 1){ echo '(+) '.$log->getCount();}elseif($log->getTypeId() == 2){ echo '(-) '.$log->getCount();} ?></td> 
				 <td><?php echo $log->getCode(); ?></td>
            </tr>
        <?php } ?>
</table>
