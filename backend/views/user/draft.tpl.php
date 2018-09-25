<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<form action="" method="POST">
	<input type="submit" value="Начать розыгрыш" name="button" />
</form>
<table id="pageslist" cellpadding="2" cellspacing="0" style="width: 100%">
    <tr>
        <th>Действия</th>
        <th class="c-projecttitle">Имя</th>
        <th class="c-projecttitle">Е-мейл</th>
        <th class="c-clientname">Участие</th>
    </tr>
    <?php
    $row = 'row1';
    foreach ($this->getSubscribers() as $sub) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row;?>">
            <td class="kolomicon"><a href="<?php echo $this->path;?>user/edit/id/<?php echo $sub->getId();?>/"><img
                    src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование"/></a></td>
            <td class="c-projecttitle"><?php echo $sub->getFullname();?></td>
            <td class="c-projecttitle"><?php echo $sub->getEmail();?></td>
            <td class="c-projecttitle"><?php if ($sub->getDrawing()) { echo 'Участвует'; } else { echo ' ';} ?></td>
        </tr>
        <?php
    }
    ?>
</table>

