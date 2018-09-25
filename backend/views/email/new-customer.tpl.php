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
                        <table border="0" cellpadding="0" cellspacing="0" width="700" style="font-family: Verdana">
                            <tr>
                                <td width=700 style="vertical-align:top">
                                    <table width=450 cellpadding=20 style="border:1px solid #e4e4e4; margin-left:200px">
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838;" align="left">
                                                <p><b>Благодарим Вас за Ваш заказ на сайте интернет-магазина RED.</b></p> 
                                                <p>Чтобы получить доступ к Вашим заказам и контактной информации Вы можете сейчас войти в личный кабинет на <a href="https://<?php echo Config::findByCode('domain_name')->getValue();?>/account/login/">https://<?php echo Config::findByCode('domain_name')->getValue();?>/account/login/</a> используя следующие учетные данные:</p>
                                                <p>Ваш логин: <?=$this->login?><br /> 
                                                    Ваш пароль: <?=$this->pass?><br />
                                                    Если у Вас возникли вопросы напишите нам на <a href="mailto:market@red.ua">market@red.ua</a></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838; border-top:1px solid #e4e4e4;line-height:140%;" align="left">
                                                <strong>RED</strong><br>

                                                <a style="color:#383838" title="Перейти на сайт RED" href="https://<?php echo Config::findByCode('domain_name')->getValue();?>"><?php echo Config::findByCode('domain_name')->getValue();?></a><br>
                                                <a style="color:#383838" title="Написать в компанию RED" href="mailto:market@red.ua">market@red.ua</a>
                                            </td>
                                        </tr>
                                </table>      </td>
                            </tr>

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
