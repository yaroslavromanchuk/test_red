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
                                    <img src="https://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-top.jpg" width="700" alt="RED" title="RED">

                                </td>
                            </tr>
                        </table>
                        <table border="0" cellpadding="20" cellspacing="0" width="700" style="font-family: Verdana">
                            <tr>
                                <td width=700 style="vertical-align:top">
    <table border="0" cellpadding="0" cellspacing="0" width="600">
    <tr style="height:60px"><td style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;font-weight:bold">Это копия сообщения отправленного Вами с сайта <?php echo Config::findByCode('website_name')->getValue();?>.</td></tr>
    </table>

    <table id="contacttab" border="0" cellpadding="0" cellspacing="0" width="500" style="font-family: Verdana">
    
    <?php 
		foreach ($this->fields as $field)
		{
	?>
			<tr><td width="150" style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><b><?php echo $field->getName();?></b>:</td><td style="font-family: Verdana, Times, serif; color: #333333; font-size: 12px; margin: 5px 0 5px 0;"><?php echo $this->post[$field->getCode()];?></td></tr>
	<?php
		}
	?>
    
    
</table>


                            <tr>
                                <td valign="top">
                                    <img src="https://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail-bottom.jpg" width="700" height="30" alt=""></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>

