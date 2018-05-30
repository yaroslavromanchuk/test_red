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
           <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">
			<tr>
				<td align="right" colspan="2" style="padding: 10px 10px 0 0;">
					<a href="http://vk.com/club21090760"><img src="http://<?php echo $dname?>/img/social_black/social_black_vk.png" alt="vkontakte" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://www.facebook.com/pages/RED-UA/148503625241218?sk=wall"><img src="http://<?php echo $dname?>/img/social_black/social_black_fb.png" alt="facebook" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://twitter.com/#!/red_ukraine"><img src="http://<?php echo $dname?>/img/social_black/social_black_tw.png" alt="twitter" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://instagram.com/red_ua"><img src="http://<?php echo $dname?>/img/social_black/social_black_inst.png" alt="instagramm" border="0" width="25" height="25" style="text-decoration:none;"></a>
					<a href="http://www.odnoklassniki.ru/group/56643212738594"><img src="http://<?php echo $dname?>/img/social_black/social_black_odnkl.png" alt="odnoklasniki" border="0" width="25" height="25" style="text-decoration:none;"></a>
				</td>
			</tr>
			<tr>
				<td style="padding: 0 0 0 10px;">
					<a href="http://<?php echo $dname . $utm;?>" class="logo"><img src="http://<?php echo $dname?>/img/_red_logo.png" alt="RED" height="50" width="130"/></a>
				</td>
				<td>
					<span style="color: #666;font-size: 16pt;left: 195px;top: 40px;text-align: center;margin: 0;font-family: Verdana, Tahoma, Arial;">Большие бренды — маленькие цены</span>
				</td>
			</tr>
			</br>
			<tr>
				<td colspan="2" align="center">
					<table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse;margin: 0;width: 100%;border-top: 1px solid;border-bottom: 1px solid;height: 48px;">
						<tr><?php 
						$menu = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>0, 'active'=>1));
						foreach ($menu as $menui) {
							echo '
								<td align="center" border="0" style="padding: 7px 0;">
									<span style="color: #2D2D2D;font-family: Arial;font-size: 15px;font-weight: bold;height: 48px;padding: 0 2px;text-decoration: none;">
										<a style="color: #2D2D2D;text-decoration: none;line-height: 15px;" href="'.$menui->getPath(). $utm . '">
											'.$menui->getName().'
										</a>
									</span>
								</td>
							';
						} ?>
						</tr>
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
</br>
<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="background:#fff;border-collapse:collapse;">



			<tr>
				<td style="background:#ddd;text-align:center; padding:10px;">
					<a href="http://<?php echo $dname;?>/returns<?php echo $utm;?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Возвраты ></span></a>
					<a href="http://<?php echo $dname;?>/store-locator<?php echo $utm;?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Магазины сети </span><span style="color:red">RED</span><span style="color:#333;"> ></span></a>
					<a href="http://<?php echo $dname;?>/pays<?php echo $utm;?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Доставка и оплата ></span></a>
				</td>
			</tr>
                        </table>
    </body>
</html>
