<div style="width: 880px;margin-left: 50px;">
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

<form method="POST" action="<?php echo $this->path;?>order_statuses/edit/id/<?php echo (int)$this->order_statuses->getId();?>/">

    <table id="editpage" style="width:540px;" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">ИД</td>
            <td><?php echo $this->order_statuses->getId();?></td>
        </tr>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="formfields" id="name" value="<?php echo $this->order_statuses->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Доступен для выбора</td>
            <td><input name="active" type="checkbox" value="1" <?php if ($this->order_statuses->getActive()) { ?>checked="checked"<?php } ?>/></td>
        </tr>
        <tr>
            <td class="kolom1">Порядок</td>
            <td><input name="order" type="text" size="3" id="price" value="<?php echo $this->order_statuses->getOrder();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">SMS</td>
			<td><input name="send_sms" type="checkbox" value="1" <?php if ($this->order_statuses->getSendSms()) { ?>checked="checked"<?php } ?>/></td>
        </tr>
        <tr>
            <td class="kolom1">Email</td>
			<td><input name="send_email" type="checkbox" value="1" <?php if ($this->order_statuses->getSendEmail()) { ?>checked="checked"<?php } ?>/></td>
        </tr>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>
</div>