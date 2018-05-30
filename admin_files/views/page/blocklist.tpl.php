<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<p>
    <form action="" method="get">
    <select name="block">
        <option value="0">Все блоки</option>
		<option value="6" <?php if(@$_GET['block']==6){?>selected="selected" <?php } ?>>Большой баннер</option>
		<option value="2" <?php if(@$_GET['block']==2){?>selected="selected" <?php } ?>>Женская одежда</option>
        <option value="1" <?php if(@$_GET['block']==1){?>selected="selected" <?php } ?>>Аксессуары</option>
        <option value="5" <?php if(@$_GET['block']==5){?>selected="selected" <?php } ?>>Текстиль</option>
        <option value="4" <?php if(@$_GET['block']==4){?>selected="selected" <?php } ?>>Мужская одежда</option>
       <!-- <option value="3" <?php //if(@$_GET['block']==3){?>selected="selected" <?php// } ?>>Нижний 2</option>-->
        
        
    </select>
    <input type="submit" value="выбрать" />
    </form>
</p>
<p><img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Pagina toevoegen" width="24" height="24"/><a
        href="<?php echo $this->path;?>homeblock/edit/0">Добавить блок</a></p>
<table id="pageslist" cellpadding="2" cellspacing="0">
    <?php
    $row = 'row1';
    $cur = -1;
    $count = $this->getPages()->count();
    foreach ($this->getPages() as $page)
    {
        $cur++;
        $is_first = (0 == $cur);
        $is_last = ($count == $cur + 1);
        if ($page->getUrl() == 'admin') continue;
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row;?>">
            <td class="kolomicon"><a href="<?php echo $this->path;?>homeblock/edit/<?php echo $page->getId();?>/"><img
                    src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактировать" width="24"
                    height="24"/></a></td>

            <td class="kolomicon"><a href="<?php echo $this->path;?>homeblock/delete/<?php echo $page->getId();?>/"
                                     onclick="return confirm('Вы действиельно хотите удалить блок?')"><img
                    src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24"/></a>
            </td>

            <td class="kolomicon"><?php echo $page->getName();?></td>

            <td class="kolomtitle"><?php echo $page->getTitle();?></td>
            <td class="kolomicon"><?php echo $page->getBlockText();?></td>
        </tr>
        <?php

    }
    ?>
</table>