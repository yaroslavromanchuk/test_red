<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->errors) {
    ?>
    <div id="errormessage"><img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt="" class="page-img"/>

        <h1>Ошибка:</h1>
        <ul>
            <?php
            foreach ($this->errors as $error) {
                ?>
                <li><?php echo $error;?></li>
            <?php

            }
            ?>
        </ul>
    </div>
<?php

}

if ($this->saved) {
    ?>
    <div id="pagesaved">
        <img src="<?php echo SITE_URL; ?>/img/icons/accept.png" alt="" class="page-img"/>

        <h1>Запись сохранена.</h1>
    </div>
<?php

}
?>

<form method="POST" action="<?php echo $this->path; ?>skidka/id/<?php echo (int)$this->sub->getId(); ?>/"
      enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $this->article->getId(); ?>" name="article_id">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">Товар</td>
            <td>
                <a href="<?php echo $this->article->getPath(); ?>"
                   target="_blank"><?php echo $this->article->getBrand(); ?>(<?php echo $this->article->getModel(); ?>
                    )</a>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="input" id="paginatitle"
                       value="<?php echo $this->sub->getName(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Скидка</td>
            <td><input name="value" type="text" class="input"
                       value="<?php echo $this->sub->getValue(); ?>"/>%
            </td>
        </tr>
        <tr>
            <td class="kolom1">Начало</td>
            <td>
                <input name="start" type="date" class="input"
                       value="<?php echo $this->sub->getStart() ? date('Y-m-d', strtotime($this->sub->getStart())) : date('d.m.Y'); ?>"/>

            </td>
        </tr>
        <tr>
            <td class="kolom1">Конец</td>
            <td><input name="finish" type="date" class="input"
                       value="<?php echo $this->sub->getFinish() ? date('Y-m-d', strtotime($this->sub->getFinish())) : date('d.m.Y'); ?>"/>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Включить</td>
            <td><input name="publish"
                       type="checkbox" <?php if ($this->sub->getPublish()) { ?> checked="checked"<?php } ?>
                       value="1"/></td>
        </tr>


        <tr>

            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>



	