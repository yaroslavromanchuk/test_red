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
                                    <table  cellpadding=20 style="border:1px solid #e4e4e4;">
                                        <tr>
                                            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838;" align="left">
                                                <p><strong>Добрый день, Уважаемый Клиент!</strong></p>
                                                <br />
                                                <p>Высылаем Вам счет на заказанный Вами товар.</p>
                                                <p>Для того, чтобы Ваш заказ был обработан максимально быстро, пожалуйста:</p>
                                                <p>1. Оплатите счет в указанный срок (счет действителен 3 дня, с момента выставления счета)</p>
                                                <p>2. Отправьте на e-mail <a style="color:#383838" title="Написать в компанию RED" href="mailto:market@red.ua">market@red.ua</a> подтверждение оплаты счета.
<br>После того, как мы получим подтверждение оплаты, мы отправим Ваш заказ и номер транспортной накладной для отслеживания Вашего заказа.</p>
                                                <br />
                                                <br />
                                                <p style="color: #FF0000;">! Заказ укомплектован и ожидает оплату. Совмещение данного заказа невозможно.</p>
                                                <br />
                                                <br />
                                                <p><strong>Счет за заказ № <?php echo $this->order->getId()?> от <?php echo date('d/m/Y',strtotime($this->order->getDateCreate()));?> г.</strong></p>
                                                <p style="font-size: 11px;"><?php echo $this->shet;?></p>
                                                <br />
                                                <p><a href="https://www.red.ua/account/novaposhta/id/<?php echo $this->order->getId()?>">Просмотреть счет на сайте.</a></p>
                                                <p>
                                                    Желаем приятных покупок!<br />
                                                    С Уважением, RED.UA. <br />
                                                    (044) 224-40-00,<br />
                                                    <a style="color:#383838" title="Перейти на сайт RED" href="https://<?php echo Config::findByCode('domain_name')->getValue();?>"><?php echo Config::findByCode('domain_name')->getValue();?></a>
                                                </p>
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
