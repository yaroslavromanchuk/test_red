<?php  header('Access-Control-Allow-Origin: *'); ?>
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
					<a href="http://<?php echo $dname . $this->getExtraUrl();?>" class="logo"><img src="http://<?php echo $dname?>/img/_red_logo.png" alt="RED" height="50" width="130"/></a>
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
								<td align="center" border="0" style="padding: 7px 0;">
									<span style="color: #2D2D2D;font-family: Arial;font-size: 15px;font-weight: bold;height: 48px;padding: 0 2px;text-decoration: none;">
										<a style="color: #2D2D2D;text-decoration: none;line-height: 15px;" href="'.$menui->getPath(). $this->getExtraUrl() . '">
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
		<table border="0" cellpadding="0" cellspacing="0" width="700" align="center" style="border:1px solid #e4e4e4; background:#fff;border-top: none;padding-top: 5px;border-collapse:collapse;">
			<tr>
				<td style="color:#383838; padding:0">
					<p><?php if(@$this->post->intro) echo stripslashes(str_replace('#', '&', $this->post->intro));?></p>
				</td>
			</tr>
			<tr>
			<td>
			<p style="text-align: center;" ><a href="http://<?php echo $dname;?>/account/edit//l/<?php echo $this->login; ?>/p/<?php echo $this->pass; ?>" class="logo"><img src="http://<?php echo $dname?>/img/editemail.jpg" alt="RED" width="200"/></a></p>
			</td>
			</tr>
			<tr>
				<td style="padding-left: 0%;">
					<?php $i = 0; if(@$this->post->article_id)foreach ($this->post->article_id as $item) {
					++$i;
					if($i==4) echo "<br>";
					$article = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id'=>$item)); //var_dump( $article);
					if(!$article) continue;
					?> 
				<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" align="left">
					<tr>				
					<td <?php if ($i==2){?>rowspan="2"<?php } ?>>
						<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" rel="<?php echo $i;?>" align="center" >
						<tr>
							<td align="center" style="padding: 0px 16px 0 16px;">
							<a href="http://<?php echo $dname . $article->getPath() . $this->getExtraUrl();?>" style="color:#333;text-decoration:none;">
								<img src="http://<?php echo $dname . $article->getImagePath('detail');?>" width="200"><br>
								<span style="font-size: 17px;margin:0;"><?php echo $article->getBrand();?></span><br>
								<span style="margin:0;"><?php echo $article->getModel();?></span><br>
								<?php if ((int)$article->getOldPrice()) echo '<span style="text-decoration: line-through;color: #666;">'.$article->getOldPrice().' грн.</span>';?>
								<span style="margin:0;">  <?php echo $article->getPrice();?> грн.</span>
							</a>
							</td>
						</tr>
						</table>
					</td>
					<?php if ($i==3){?></tr><tr><?php } ?>
					</tr>
				</table>					
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><img src="http://<?php echo $dname?>/images/lightbox-blank.gif" height="10" border="0"/></td>
			</tr>
			<tr>
				<td style="color:#383838; padding:0"><?php if(@$this->post->ending) echo stripslashes(str_replace('#', '&', $this->post->ending));?></td>
			</tr>

			<?php
				if(@$this->post->news)
					foreach($this->post->news as $id=>$val) {
						$anons = new News($id);
					?>
					<tr>
						<td style="font-size:12px; color:#383838; border-top:1px solid #e4e4e4;line-height:140%;"; align="left"><p><strong style="color:#091d34"><?php echo $anons->getTitle();?></strong></p>
						<table cellspacing="0" cellpadding="2">
							<tr>
								<td></td>
								<td style="font-size:12px; color:#383838; ine-height:140%; vertical-align:top"><?php echo $anons->getIntro();?>
									<p>Нажмите  <a style="color:#383838" href="http://<?php echo $dname . $anons->getPath() . $this->getExtraUrl();?>">тут</a> для ознакомления с полной версией новости</p>
								</td>
							</tr>
						</table>
						</td>
					</tr>
			<?php } ?>


			<tr>
				<td><img src="http://<?php echo $dname?>/images/lightbox-blank.gif" height="10" border="0"/></td>
			</tr>
			<tr>
				<td style="background:#ddd;text-align:center; padding:10px;">
					<a href="http://<?php echo $dname?>/returns/<?php echo $this->getExtraUrl();?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Возвраты ></span></a>
					<a href="http://<?php echo $dname?>/store-locator/<?php echo $this->getExtraUrl();?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Магазины сети </span><span style="color:red">RED</span><span style="color:#333;"> ></span></a>
					<a href="http://<?php echo $dname?>/pays/<?php echo $this->getExtraUrl();?>" style="text-decoration:none;padding:0 16px;text-align: center;"><span style="color:#333;">Доставка и оплата ></span></a>
				</td>
			</tr>
			<tr>
				<td><img src="http://<?php echo $dname?>/images/lightbox-blank.gif" height="10" border="0"/></td>
			</tr>
			<tr>
				<td style="background:#ddd;text-align:center; padding:10px;font-size: 9px;">
				<span>Вы получили это письмо на адрес <?php echo $this->email;?>, потому что оформили подписку на сайте Red.ua. Если Вы больше не хотите получать эти письма, нажмите </span><a href="http://<?php echo $dname?>/subscribe/?email=<?php echo $this->email;?>"><span style="color:#383838">тут</span></a> <span>для того чтобы отписаться.</span>
				</td>
			</tr>
		</table>
	</body>
</html>