<div class="card pd-30">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
<div class="card-body">
<?php
if ($this->errors) {
    ?>
<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" class="page-img"/>

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
    <img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" class="page-img"/>

    <h1>Запись сохранена.</h1>
</div>
<?php

}
?>

<form method="POST" action="<?=$this->path?>size/id/<?=(int)$this->sub->getId()?>/"
      enctype="multipart/form-data">
    <table class="table">
        <tr>
            <td>Название</td>
            <td><input name="size" type="text" class="form-control"
                       value="<?=$this->sub->getSize()?>"/></td>
        </tr>
        <tr>
            <td>Категория</td>
            <td>
                <select name="category_id" class="form-control">
                    <option value="0">Без категории</option>
                    <?php foreach (wsActiveRecord::useStatic('SizeCategory')->findAll() as $cat) { ?>
                    <option value="<?=$cat->id?>" <?php if($this->sub->category_id == $cat->getId()){ echo 'selected';} ?>><?=$cat->getName()?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>id-1C</td>
             <td><?=$this->sub->id_1c?></td>
        </tr>


        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>
</div>
</div>



	