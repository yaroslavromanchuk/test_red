<div class="card pd-30">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
<div class="card-body">
<?php
if ($this->errors) {
    ?>
<div id="errormessage"><img src="/img/icons/error.png" alt="" class="page-img"/>

    <h1>Ошибка:</h1>
    <ul>
        <?php
                    foreach ($this->errors as $error)
    {
        ?>
        <li><?php echo $error;?></li>
        <?php

    }
        ?>
    </ul>
</div>
        <?php

}
if ($this->saved) { ?>
<div id="pagesaved">
    <img src="/img/icons/accept.png" alt="" class="page-img"/>
    <h1>Запись сохранена.</h1>
</div>
<?php } ?>

<form method="POST" action="<?=$this->path?>color/id/<?=(int)$this->sub->id?>/"   enctype="multipart/form-data">
    <table   class="table">
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="form-control" id="paginatitle"
                       value="<?php echo $this->sub->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Цвет</td>
            <td><input name="color" type="text" class="form-control"
                       value="<?php echo $this->sub->getColor();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">id-1C</td>
            <td><input name="id_1c" type="text" class="form-control" value="<?=$this->sub->id_1c?>"/></td>
        </tr>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>
</div>
</div>
	


	