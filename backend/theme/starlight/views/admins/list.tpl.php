<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<table id="pageslist" cellpadding="2" cellspacing="0" align="center">
    <tr>
	 <th>№</th>
        <th>Ред.</th>
        <th class="c-projecttitle">Имя</th>
        <th class="c-projecttitle">Е-мейл</th>
        <th class="c-clientname">Тип</th>
    </tr>
    <?php
	$i=1;
    $row = 'row1';
    foreach ($this->getAdmins() as $sub) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row;?>">
		<td style="text-align: center;"><?=$i?></td>
            <td class="kolomicon"><a href="<?php echo $this->path;?>adminedit/edit/<?php echo $sub->getId();?>/"><img
                    src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование"/></a></td>
            <td class="c-projecttitle"><?php echo $sub->getFullname();?></td>
            <td class="c-projecttitle"><?php echo $sub->getEmail();?></td>
            <td class="c-projecttitle">
                <?php 
				switch ($sub->getCustomerTypeId()) {
    case 2:
        echo 'Администратор';
        break;
    case 3:
        echo 'Супер-Админ.';
        break;
    case 4:
       echo 'Разработчик';
        break;
	case 5:
       echo 'Комплектовщик';
        break;
	case 6:
      echo 'Админ. пункта выдачи';
        break;
	case 7:
      echo 'Админ. по возвратам';
        break;
		case 8:
      echo 'Оператор';
        break;
} ?>
            </td>

        </tr>
        <?php
		$i++;
    }
    ?>
</table>