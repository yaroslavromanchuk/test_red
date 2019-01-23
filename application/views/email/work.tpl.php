<?=$this->render('email/email.header.tpl.php');?>
    <table id="contacttab" border="0" cellpadding="0" cellspacing="0" width="700" style="font-family: Verdana">
      

			<tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>ФИО</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->name);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Дата рождения</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo date("d.m.Y", strtotime($this->data->date));?>
                </td></tr>
       <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Адрес (фактическое проживание)</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->city).", ".strip_tags($this->data->streat);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Контактный телефон</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->phone);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Предполагаемая должность</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php if($this->data->works == 10) { echo strip_tags($this->data->namework);}else{ echo strip_tags($this->data->works);}?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Образование</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->education);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Оконченные учебные заведения</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->educationall);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Последнее место работы</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->xwork);?>
                </td></tr>
				<tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Выд деятельности</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->typework);?>
                </td></tr>
        <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Приступить к работе</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <?php echo strip_tags($this->data->datevuxod);?>
                </td></tr>
        <?php if($this->file){?>
            <tr>
                <td width="250" style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <b>Прикрепленное резюме</b>:
                </td>
                <td style="padding: 5px 0;font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;">
                    <a href="<?php echo 'http://www.red.ua'.$this->file;?>">Резюме</a>
                </td></tr>
        <?php } ?>

    
    
</table>
<?=$this->render('email/email.footer.tpl.php');?>                            

