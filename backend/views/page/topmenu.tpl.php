<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<form action="" method="POST">
    <table id="pageslist" cellpadding="2" cellspacing="0">
        <tr>
            <th align="justify"><?php echo $this->trans->get('Управление'); ?></th>
            <th align="justify"><?php echo $this->trans->get('Название'); ?></th>
			<th align="justify"><?php echo $this->trans->get('Название'); ?> uk</th>
            <th><?php echo $this->trans->get('Ссылка'); ?></th>
        </tr>
        <?php
        $row = 'row1';

//echo print_r($this->getMenus());
        foreach ($this->getMenus() as $menu) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            ?>
<tr class="<?php echo $row;?>">
<td class="kolomicon">
<a href="<?php echo $this->path;?>topmenu/delete/<?php echo $menu->getId();?>/" onclick="return confirm('<?php echo $this->trans->get('Вы действиельно хотите удалить пункт меню?'); ?>')">
<img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" />
</a>
</td>
<td class="kolomtitle">
<input name="title_<?php echo $menu->getId();?>" type="text" value="<?php echo $menu->getTitle();?>">
</td>
<td class="kolomtitle">
<input name="title_uk_<?php echo $menu->getId();?>" type="text" value="<?php echo $menu->getTitleUk();?>" >
</td>
<td class="kolomtitle">
<input name="url_<?php echo $menu->getId();?>" type="text" value="<?php echo $menu->getUrl();?>">
</td>
            </tr>
            <?php
        }
        ?>
        <tr class="<?php echo $row;?>">
            <td class="kolomtitle"><?php echo $this->trans->get('Новый'); ?></td>
            <td class="kolomtitle"><input name="new_" type="text"
                                          value=""></td>
			<td class="kolomtitle"><input name="new_uk_" type="text"
                                          value=""></td>
            <td class="kolomtitle"><input name="newurl_" type="text"
                                          value=""></td>
        </tr>
    </table>
    <input type="submit" name="save" value="<?php echo $this->trans->get('Сохранить'); ?>" />
</form>