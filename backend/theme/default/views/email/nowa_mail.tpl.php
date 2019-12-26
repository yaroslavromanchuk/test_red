<?php $dname = Config::findByCode('domain_name')->getValue();?>
<html>
    <head>
		<title><?=Config::findByCode('website_name')->getValue()?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="https://<?=$dname?>/" target="_blank" />
    </head>
    <body style="background-color:#c4c5c7">
       <table  style="width:700px;background: black;" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td style="border-bottom: 2px solid #e30613;padding:10px;">
	<span  style="text-align:left;">
	<a href="https://<?=$dname?>"><img src="https://<?=$dname?>/img/email/logo_new.png" alt="RED" style="height:30px;"></a>
	</span>
	<span  style="float: right;text-align:right;">
       <a href="https://t.me/shop_red_ua"><img src="https://<?=$dname?>/backend/img/email/teleg.png" alt="telegram"   style="width:30px;padding: 0px 1px;" ></a>
	<a href="http://www.facebook.com/pages/RED-UA/148503625241218?sk=wall"><img src="https://<?=$dname?>/backend/img/email/fb.png" alt="facebook"   style="width:30px;padding: 0px 1px;" ></a>
	<a href="http://instagram.com/red_ua"><img src="https://<?=$dname?>/backend/img/email/inst.png" alt="instagramm"  style="width:30px;padding: 0px 1px;"></a>
	<a href="https://www.youtube.com/user/SmartRedShopping"><img src="https://<?=$dname?>/backend/img/email/youtube.png" alt="youtube" style="width:30px;padding: 0px 1px;"></a>
	<a  href="https://www.red.ua/blog/"><img src="https://<?=$dname?>/backend/img/email/blog.png" alt="blog"  style="width:30px;padding: 0px 1px;"></a>
	</span>
	</td>
	</tr>
	<tr>
	<td  style="border-bottom: 1px solid #9E9E9E;text-align:center;">
	<table style="width:700px;font-family: monospace; background: white;" >
	<tbody style="width:700px;">
	<tr>
	<?php 
foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>0, 'active'=>1, 'email IS NOT NULL')) as $menui) {?>
<td style="padding:0;">
<a href="https://<?=$dname.$menui->getPath()?>"  style="letter-spacing: -1px;text-decoration: none;
    text-transform: uppercase;
    width: 68px;
    text-align: center;
    display: inline-block;
    padding: 0;
    line-height: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #605f60;"  target="_blank">
<img src="https://<?=$dname?>/backend/img/email/cat/<?=$menui->img_email?>.png" style="width:40px;margin-top: 5px;">
<br><span style="margin-left: -5px;"><?=$menui->getEmail()?></span></a></td>
						<?php } ?>
	<td style="padding:0;">
            <a href="https://www.red.ua/brands/?>" style="letter-spacing: -1px;text-decoration: none;
    text-transform: uppercase;
    width: 68px;
    text-align: center;
    display: inline-block;
    padding: 0;
    line-height: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #605f60;"   target="_blank"><img src="https://<?=$dname?>/backend/img/email/cat/brands.png" style="width:40px;margin-top: 5px;"><br>Бренды</a>
	</td>
	</tr>
	</tbody>
	</table>
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
                        </table>
        <table  align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;color:#6c6c6c;max-width:700px;">
<tr style="background: #ededed;">
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>CALL-ЦЕНТР</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>КОНТАКТЫ</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>СТРАНИЦЫ</b></td>
</tr>
<tr style="background: #ededed;" >
<td  style="width: 33.33333333%;padding-left:10px;">Пн-Пт: 09:00 - 18:00<br>Сб-Вс: Выходные</td>
<td  style="width: 33.33333333%;padding-left:10px;" >(044) 224-40-00<br>(063) 809-35-29<br>(067) 406-90-80<br>market@red.ua</td>
<td  style="width: 33.33333333%;padding-left:10px;padding-bottom:10px;" >
<a href="https://<?=$dname?>/advantages/" style="color: #878787;text-decoration: none;">Преимущества</a><br>
<a href="https://<?=$dname?>/reviews/" style="color: #878787;text-decoration: none;">Отзывы</a><br>
<a href="https://<?=$dname?>/pays/" style="color: #878787;text-decoration: none;">Доставка и оплата</a><br>
<a href="https://<?=$dname?>/returns/" style="color: #878787;text-decoration: none;">Возвраты</a>
</td>
</tr>
</table>
    </body>
</html>
