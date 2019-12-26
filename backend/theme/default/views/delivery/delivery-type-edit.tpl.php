<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?></h1>
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
<form method="POST" action="<?php echo $this->path;?>delivery_type/edit/id/<?php echo (int)$this->delivery->getId();?>/">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">ИД доставки</td>
            <td><?php echo $this->delivery->getId();?></td>
        </tr>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="name" type="text" class="formfields" id="name" value="<?php echo $this->delivery->getName();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Стоимость</td>
            <td><input name="price" type="text" class="formfields" id="price" value="<?php echo $this->delivery->getPrice();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Доступен для выбора</td>
            <td><input name="active" type="checkbox" value="1" <?php if ($this->delivery->getActive()) { ?>checked="checked"<?php } ?>/></td>
        </tr>
    </table>
	<table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">ИД типа оплаты</td>
            <td class="kolom1">Название</td>
            <td class="kolom1">Стоимость</td>
        </tr>
<?php
	foreach ($this->payments as $payment) {
?>
        <tr>
            <td><?php echo $payment->getId(); ?></td>
            <td><?php echo $payment->payment->getName(); ?></td>
            <td><input name="<?php echo $payment->getId(); ?>" type="text" class="formfields" id="<?php echo $payment->getId(); ?>" value="<?php echo $payment->getPrice(); ?>"/></td>
        </tr>
<?php
	}
?>
        <tr>
            <td class="kolom1">&nbsp;</td>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>