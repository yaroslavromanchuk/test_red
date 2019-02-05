<?=$this->render('email/email.header.tpl.php');?>
    <table border="0" cellpadding="0" cellspacing="0" width="700" align="center">
    <tr style="height:60px">
        <td colspan="2"  style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;font-weight:bold">Данное сообщение было отправленно пользователем с сайта <?php echo Config::findByCode('website_name')->getValue();?>.</td>
    </tr>
    <?php foreach ($this->fields as $field){ ?>
		<tr>
                    <td width="150" style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><b><?=$field->getName()?></b>:</td>
                    <td style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><?=$this->post[$field->getCode()]?></td>
                </tr>
	<?php } ?>
</table>
<?=$this->render('email/email.footer.tpl.php');?>