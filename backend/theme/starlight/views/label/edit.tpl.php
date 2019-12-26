<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

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

<form method="POST" action="<?php echo $this->path;?>label/edit/id/<?php echo (int)$this->sub->getId();?>/"
      enctype="multipart/form-data">
    <input type="hidden" value="<?php echo @$_GET['order_id'];?>" name="order_id">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="formfields" id="paginatitle"
                       value="<?php echo $this->sub->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Рисунок</td>
            <td>
                <label>
                    <input name="image" type="file" class="formfields-1" id="fileField"/>
                </label>
                <?php if ($this->sub->getImage()) {
                echo '<br/><img src="' . $this->sub->getImage() . '" />';
            } ?>
            </td>
        </tr>



        <tr>

            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>



	