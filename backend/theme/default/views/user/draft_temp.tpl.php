<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); 
echo "Всего заказов - ".$count = $this->getSubscribers()->count(); 
?>
<!--
<form action="" method="POST">
	<input type="submit" value="Начать розыгрыш" name="button" />
</form>
-->
<table id="pageslist" cellpadding="2" cellspacing="0" style="width: 100%">
    <tr>
        <th>Действия</th>
        <th class="c-projecttitle">Имя</th>
        <th class="c-projecttitle">Е-мейл</th>
		<!--<th class="c-projecttitle">Дата</th>-->
		<th class="c-projecttitle">Сума заказов</th>
		<th class="c-projecttitle">Кол.</th>
       <!-- <th class="c-clientname">Доставки</th>-->
    </tr>
    <?php
	
    $row = 'row1';
    foreach ($this->getSubscribers() as $sub) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row;?>">
            <td class="kolomicon"><a href="<?=$this->path?>user/edit/id/<?=$sub->getCustomerId()?>/"><img
                    src="/img/icons/edit-small.png" alt="Редактирование"/></a></td>
            <td class="c-projecttitle"><?php echo $sub->getName();?></td>
            <td class="c-projecttitle"><?php echo $sub->getEmail();?></td>
			<!--<td class="c-projecttitle"><?php //echo $sub->getDateCreate();?></td>-->
			<td class="c-projecttitle"><?php echo Shoparticles::showPrice($sub->getSumm()); ?></td>
            <td class="c-projecttitle"><?php echo $sub->getCount();?></td>
		<!--	<td class="c-projecttitle"><?php// if ($sub->getDeliveryTypeId()== 4) { echo 'Укрпочта'; } elseif($sub->getDeliveryTypeId()== 8) { echo 'Новая почта';} else{echo 'НП(Наложка)';} ?></td>
                        -->
        </tr>
        <?php
    }
    ?>
</table>

