<img src="<?=$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img" height="32"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?=$this->getCurMenu()->getPageBody()?>
    <form action="" method="get">
    <select name="block" class="select2">
        <option value="0">Все блоки</option>
		<option value="6" <?php if(@$_GET['block']==6){?>selected="selected" <?php } ?>>Большой баннер</option>
		<option value="2" <?php if(@$_GET['block']==2){?>selected="selected" <?php } ?>>Женская одежда</option>
        <option value="1" <?php if(@$_GET['block']==1){?>selected="selected" <?php } ?>>Аксессуары</option>
        <option value="5" <?php if(@$_GET['block']==5){?>selected="selected" <?php } ?>>Текстиль</option>
        <option value="4" <?php if(@$_GET['block']==4){?>selected="selected" <?php } ?>>Мужская одежда</option>
       <!-- <option value="3" <?php //if(@$_GET['block']==3){?>selected="selected" <?php// } ?>>Нижний 2</option>-->
        
        
    </select>
    <input type="submit" class="btn btn-secondary" value="Отобразить" >
    </form>

<p><a href="<?=$this->path?>homeblock/edit/0/" class="btn btn-success btn-small">Добавить блок</a></p>
<table  class="table table-hover" >
    <thead>
        <tr>
            <th colspan="2">Действия</th>
            <th>Название блока</th>
            <th>Описание</th>
            <th>Тип блока</th>
            <th>Дата публикации</th>
            <th>Дата удаления</th>
                        <th>Статус</th>
        </tr>
    </thead>
    <?php
    $row = 'row1';
    $cur = -1;
    $count = $this->getPages()->count();
    foreach ($this->getPages() as $page)
    {
        $cur++;
        $is_first = (0 == $cur);
        $is_last = ($count == $cur + 1);
        if ($page->getUrl() == 'admin') {continue;}
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row;?>">
            <td>
                <a href="<?=$this->path?>homeblock/edit/<?=$page->getId()?>/">
                    <img src="/img/icons/edit-small.png" alt="Редактировать" width="24"height="24"/>
                </a>
            </td>

            <td>
                <a href="<?=$this->path?>homeblock/delete/<?=$page->getId()?>/"
                                     onclick="return confirm('Вы действиельно хотите удалить блок?')">
                    <img src="/img/icons/remove-small.png" alt="Удалить" width="24" height="24"/>
                </a>
            </td>

            <td><?=$page->getName()?></td>

            <td><?=$page->getTitle()?></td>
            <td><?=$page->getBlockText()?></td>
            <td><?=$page->getDate()?></td>
            <td><?=$page->exitdate?></td>
            <td><?php
            $p = strtotime($page->getDate());
            $d = strtotime($page->exitdate);
            if(time() > $p and time()< $d){
                echo 'Активный';
            }else{
                echo 'Не активный';
            } ?></td>
        </tr>
        <?php

    }
    ?>
</table>