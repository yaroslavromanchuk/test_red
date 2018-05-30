<?php $dname = Config::findByCode('domain_name')->getValue();?>
<html>
	<head>
		<title><?php echo Config::findByCode('website_name')->getValue();?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="http://<?php echo $dname?>/" target="_blank" />
	</head>
	<body style="background-color:#c4c5c7;font-family: Verdana, Tahoma, Arial;font-size: 14px;margin: 0;padding: 8px;">
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
					<a href="http://<?php echo $dname;?>" class="logo"><img src="http://<?php echo $dname?>/img/_red_logo.png" alt="RED" height="50" width="130"/></a>
				</td>
				<td>
					<span style="color: #666;font-size: 16pt;left: 195px;top: 40px;text-align: center;margin: 0;font-family: Verdana, Tahoma, Arial;">Большие бренды — маленькие цены</span>
				</td>
			</tr>
			<tr>
				<td colspan="2"><img src="http://<?php echo $dname?>/images/lightbox-blank.gif" height="10" border="0"/></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse;margin: 0;width: 100%;border-top: 1px solid;border-bottom: 1px solid;height: 48px;">
						<tr><?php 
						$menu = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>0, 'active'=>1));
						foreach ($menu as $menui) {
							echo '
								<td align="center" border="0" style="padding: 7px 0; width: 75px;">
									<span style="color: #2D2D2D;font-family: Arial;font-size: 14px;font-weight: bold;height: 48px;padding: 0 2px;text-decoration: none;">
										<a style="color: #2D2D2D;text-decoration: none;line-height: 14px;" href="'.$menui->getPath(). $this->getExtraUrl() . '">
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
		<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="border:1px solid #e4e4e4; background:#fff;border-top: none;padding-top: 5px;border-collapse:collapse; font-size: 14pt">
			<tr>
				<td style="color:#383838; padding:0">
					<p></br>Нажмите на ссылку ниже для подтверждения изменения Вашего Email:<a href="http://<?php echo $dname?>/activeemail/email/<?php echo $this->email;?>/">Подтвердить изменение email в интернет магазине red.ua</a></p>
					</br>
				</td>
			</tr>
			<tr>
				<td style="background:#ddd;text-align:center; padding:10px;">
					<a href="http://<?php echo $dname?>/returns/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Возвраты ></span></a>
					<a href="http://<?php echo $dname?>/store-locator/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Магазины сети </span><span style="color:red">RED</span><span style="color:#333;"> ></span></a>
					<a href="http://<?php echo $dname?>/pays/" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Доставка и оплата ></span></a>
				</td>
			</tr>
			<tr>
				<td style="background:#ddd;text-align:center; padding:10px;font-size: 9px;">
				<span>Вы получили это письмо на адрес <?php  echo $this->email;?>, потому что указали его в личном кабинете пользователя на сайте Red.ua</span>
				</td>
			</tr>
			
		</table>
	</body>
</html>