<?=$this->render('email/email.header.tpl.php');?>
    <table border="0" cellpadding="0" cellspacing="0" width="600">
    <tr style="height:60px"><td style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;font-weight:bold">Данное сообщение было отправленно пользователем с сайта <?php echo Config::findByCode('website_name')->getValue();?>.</td></tr>
    </table>

    <table id="contacttab" border="0" cellpadding="0" cellspacing="0" width="700" style="font-family: Verdana">
    
    <?php
		foreach ($this->fields as $field)
		{
	?>
			<tr><td width="150" style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><b><?php echo $field->getName();?></b>:</td><td style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><?php echo $this->post[$field->getCode()];?></td></tr>
	<?php
		}
	?>
    
    
</table>
<?=$this->render('email/email.footer.tpl.php');?>