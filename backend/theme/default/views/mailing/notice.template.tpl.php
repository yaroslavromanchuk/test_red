<?php  header('Access-Control-Allow-Origin: *'); header('X-XSS-Protection: 0'); ?>
<?php $dname = Config::findByCode('domain_name')->getValue();?>
	<?php $utm = '/?utm_source=noticeEmail&utm_medium=email&utm_content=NoticeEmail&utm_campaign=NoticeEmail';?> 
<html>
    <head>
		<title><?php echo Config::findByCode('website_name')->getValue();?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="http://<?php echo $dname?>/" target="_blank" />
    </head>

    <body style="background-color:#c4c5c7">
	<img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?php echo $this->getEmail();?>&t=event&ec=notice_email_open&ea=open&el=<?php echo $this->getEmail();?>&cs=notice_email_open&cm=email&cn=NoticeEmail" width="1" height="1" alt="" />
		<style>
   hr {
    background-color: #e6e6e6; /* Цвет линии */
    color: #e6e6e6; /* Цвет линии для IE6-7 */
    height: 1px; /* Толщина линии */
	margin: 15px 5px 0 0;
   }
  </style>
           <table  style="width:700px;background: black;" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td style="border-bottom: 2px solid #e30613;padding:10px;">
	<span  style="text-align:left;">
	<a href="https://<?=$dname.$this->track?>"><img src="https://<?=$dname?>/img/email/logo_new.png" alt="RED" style="height:30px;"></a>
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
<a href="https://<?=$dname.$menui->getPath().$this->track?>"  style="letter-spacing: -1px;text-decoration: none;
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
            <a href="https://www.red.ua/brands/<?=$this->track?>" style="letter-spacing: -1px;text-decoration: none;
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
		
   <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
    <tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
	<tr>
	<td colspan="2">
	<h2 align="center" style="color:#0966ba;">Товар появился в наличии. Спеши купить!</h2>
	</td>
	</tr>
	<tr>
	<td width="300">
	<div style="margil-left:20px;" >
	<a href="http://<?php echo $dname . $this->art1->getPath() . $utm; ?>" class="img">
	<img src="http://<?php echo $dname . $this->art1->getImagePath('detail');?>" alt="<?php echo $this->art1->getTitle();?>" style="width:280px;"/>  
	</a>
	</div>
	</td>
	<td width="390">

	<div>
	<p style="font-size:18px; "><b><?php echo $this->art1->getBrand();?></b></p>
	<p style="font-size:14px; "><?php echo $this->art1->getModel();?></p>
	<hr>
	<p style="font-size:14px; ">
	Цвет: <?php echo $this->art->color->getName();?>
    Размер: <?php echo $this->art->size->getSize();?>
	</p>
	<hr>
	<p style="color:#ff7100; font-size:16px; ">Цена: <?php echo $this->art1->getPrice();?> грн. 
	<a href="http://<?php echo $dname . $this->art1->getPath() . $utm; ?>">
	<img style="margin-left: 110px;margin-bottom: -10px; width: 110px;" src="http://<?php echo $dname?>/images/kupit.png" alt="Купить">
	</a>
	</p>
	<hr>
	<div style="font-size:14px; margin-right: 5px;">
	<?php echo $this->art1->getLongText();?> 
	</div>
	<hr>
	<p style="font-size:14px;">
	Состав: <?php echo $this->art1->getSostav();?>
	</p>
	<hr>
	</td>
	</tr>
	</table>
	</td>
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
