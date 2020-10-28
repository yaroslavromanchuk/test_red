<?php $dname = Config::findByCode('domain_name')->getValue();
 $k = '?&utm_source=newsletter&utm_medium=email&utm_campaign=collaboration_red&utm_term=third_letter';
 ?>
<html>
    <head>
		<title><?=Config::findByCode('website_name')->getValue()?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="https://<?=$dname?>/" target="_blank" />
    </head>
    <body style="font-family: Verdana,Tahoma,Arial;font-size:14px;background: white;">
	<img src="<?=$this->openimg?>" width="1" height="1" alt="" />
        <img src="<?=$this->track_open?>" width="1" height="1" alt="" />
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
        $c = 0;
foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(['parent_id'=>0, 'active'=>1, 'email IS NOT NULL'], [], [0,10]) as $menui) {?>
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
						<?php $c++; }
                                                if($c<10) {?>
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
        <?php } ?>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	</table>
<table   style="width:700px;background: white;" align="center">
    <?php if($this->post->intro) { ?>
			<tr>
				<td style="color:#383838; padding:0">
					<?php 
                                       
                               $s =  preg_replace('#(href="https://www.red.ua[^"]+)#i', '$1$2'.$this->track, $this->post->intro); 
                               $s =  preg_replace('#(href="https://kimberli.ua[^"]+)#i', '$1$2'.$k, $s);
                               echo $s;
                              // echo $this->post->intro;
                                       // echo stripslashes(str_replace('#', '&', $this->post->intro));
                                        ?>
				</td>
			</tr>
    <?php } ?>
    <?php if($this->deposit){ ?>
                        <tr>
                            <td style="padding-left: 0%;">
                                <p style="background: red;
    text-align: center;
    color: white;
    padding: 15px 20px;
    font-size: 20px;
    font-weight: bold;">
                                    У вас на депозите <?=$this->deposit?> грн.
                                </p> 
                            </td>
                           
                        </tr>
                        <tr> <td style="text-align: center;
    padding-left: 0%;
    font-size: 16px;
    color: red;
    font-weight: bold;">Удачных покупок с RED.UA</td></tr>
                            
    <?php } ?>
                        <?php if($this->coin){ ?>
                        <tr>
                            <td style="padding-left: 0%;">
                                <p style="background: red;
    text-align: center;
    color: white;
    padding: 15px 20px;
    font-size: 20px;
    font-weight: bold;">
                                    У вас на счету <?=$this->coin?> redcoin.
                                </p> 
                            </td>
                           
                        </tr>
                        <tr> <td style="text-align: center;
    padding-left: 0%;
    font-size: 16px;
    color: red;
    font-weight: bold;">Совершайте покупки в интернет магазине RED.UA и расплачивайтесь доступными REDCOIN</td></tr>
                            
    <?php } ?>
    <?php if($this->post->article_id){ ?>
			<tr>
				<td style="padding: 0;padding-top: 40px;">
					<?php $i = 0; 
                                            foreach ($this->post->article_id as $item) {
					
					$article = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id'=>$item)); //var_dump( $article);
					if(!$article){ continue;}
                                        ++$i;
					if($i==4) { echo "<br>";}
					?> 
				<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" align="left">
					<tr>				
					<td <?php if ($i==2){?>rowspan="2"<?php } ?>>
						<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom: 40px;" rel="<?=$i?>" align="center" >
						<tr>
							<td align="center" style="padding: 0px 16px 0 16px">
							<a href="https://<?=$dname.$article->getPath().$this->track?>" style="color:#333;text-decoration:none;">
								<img src="https://<?=$dname.$article->getImagePath('detail')?>" width="200"><br>
								<!--<span style="font-size: 17px;margin:0"><?=$article->getBrand()?></span><br>
								<span style="margin:0"><?=$article->getModel()?></span><br>-->
								<?php 
                                                                $price = $article->getPerc();
                                                                $pr = $price['option_price']?$price['option_price']:$price['price'];
                                                               
                                                                $first_price = $article->getFirstPrice();
                                                                if ($first_price != $pr) {
                                                                    echo '<span style="text-decoration: line-through;color: #9E9E9E;font-size: 12px;display: block;">'.$first_price.' ₴</span>';
                                                                    
                                                                }?>
								<span style="display: inline-block;
    background-color: #e00e36;
    text-align: center;
    padding: 6px 8px;
    color: #fff;
    font-size: 17px;
    font-weight: 400;
    font-family: Arial, Helvetica, sans-serif;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;">  <?=$pr?> ₴</span>
							</a>
							</td>
						</tr>
						</table>
					</td>
					<?php if ($i==3){ ?></tr><tr><?php } ?>
					</tr>
				</table>					
					<?php }
                                         ?>
				</td>
			</tr>
    <?php } if($this->post->ending) { ?>
			<tr>
				<td style="color:#383838; padding:0"><?php
                                 $f =  preg_replace('#(href="https://www.red.ua[^"]+)#i', '$1$2'.$this->track, $this->post->ending); 
                                $f =  preg_replace('#(href="https://kimberli.ua[^"]+)#i', '$1$2'.$k, $f);
                               echo $f;
                               ?></td>
			</tr>
                        <?php }
                        if($this->brand){ ?>
                            <tr>
				<td style="padding:10px;text-align: center;">
                                     <h4 style="text-align: center;">Лучшие бренды</h4>
                                    <?php
             foreach ($this->brand as $b) { ?>
                                    <div style="display:inline-block; padding:15px;">
                                        <a href="https://www.red.ua<?=$b->getToSitemapUrl().$this->track?>" style="padding: 10px 15px;
    background: #e30e13;
    color: white;
    text-decoration: none;
    font-weight: bold;" target="_blank"><?=$b->name?></a>
                                    </div>
           <?php  }
                               
                               ?></td>
			</tr>
                       <?php  }
                       
                      
                        ?>
</table>
<table  align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;color:#6c6c6c;width:700px;">
<tr style="background: #ededed;">
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>CALL-ЦЕНТР</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>КОНТАКТЫ</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>СТРАНИЦЫ</b></td>
</tr>
<tr style="background: #ededed;" >
<td  style="width: 33.33333333%;padding-left:10px;">Пн-Пт: 09:00 - 18:00<br>Сб-Вс: Выходные</td>
<td  style="width: 33.33333333%;padding-left:10px;" >(044) 224-40-00<br>(063) 809-35-29<br>(067) 406-90-80<br>market@red.ua</td>
<td  style="width: 33.33333333%;padding-left:10px;padding-bottom:10px;" >
<a href="https://<?=$dname?>/advantages/<?=$this->track?>" style="color: #878787;text-decoration: none;">Преимущества</a><br>
<a href="https://<?=$dname?>/reviews/<?=$this->track?>" style="color: #878787;text-decoration: none;">Отзывы</a><br>
<a href="https://<?=$dname?>/pays/<?=$this->track?>" style="color: #878787;text-decoration: none;">Доставка и оплата</a><br>
<a href="https://<?=$dname?>/returns/<?=$this->track?>" style="color: #878787;text-decoration: none;">Возвраты</a>
</td>
</tr>
<tr>
<td colspan="3" style="background:#ddd;font-size: 9px;padding:10px; text-align:center;" >
<span>Вы получили это письмо на адрес <?=$this->email?>, потому что оформили подписку на сайте Red.ua. Если Вы больше не хотите получать эти письма, нажмите </span><a href="/subscribe/unsubscribe/?email=<?=$this->email.$this->unsubscribe?>"><span style="color:#383838">тут</span></a> <span>для того чтобы отписаться.</span>
</td>
</tr>
</table>
</body>
</html>
