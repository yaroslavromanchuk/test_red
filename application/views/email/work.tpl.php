<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>RED</title>
    </head>
    <body style="background-color:#c4c5c7">
        <center>
            <table border="0" cellpadding="0" cellspacing="0" style="border:10px solid #ffffff; background:#ffffff" width="700">
                <tr>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="700" margin-bottom="10px">
                            <tr><td>
                                    <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-top.jpg" width="700" alt="RED" title="RED">

                                </td>
                            </tr>
                        </table>
                        <table border="0" cellpadding="20" cellspacing="0" width="700" style="font-family: Verdana">
                            <tr>
                                <td width=700 style="vertical-align:top">
    <table border="0" cellpadding="0" cellspacing="0" width="700">
    <tr style="height:60px"><td style="text-align: center; font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;font-weight:bold">АНКЕТА:</td></tr>
    </table>

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


                            <tr>
                                <td valign="top">
                                    <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-bottom.jpg" width="700" height="30" alt=""></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>

