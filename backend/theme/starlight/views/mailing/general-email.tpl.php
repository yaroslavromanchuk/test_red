<?php $dname = Config::findByCode('domain_name')->getValue();?>
<html>
    <head>
		<title><?=Config::findByCode('website_name')->getValue()?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="https://<?=$dname?>/" target="_blank" />
    </head>
    <body style="font-family: Verdana,Tahoma,Arial;font-size:14px;background: white;">
	<img src="<?=$this->openimg?>" width="1" height="1" alt="" />
	<table  style="width:700px;background: black;" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td style="border-bottom: 2px solid #e30613;padding:10px;">
	<span  style="text-align:left;">
	<a href="https://<?=$dname.$this->track?>"><img src="https://<?=$dname?>/backend/img/email/logo.png" alt="RED"  style="width:75px;height:30px;"></a>
	</span>
	<span  style="float: right;text-align:right;">
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
    color: #605f60;"   target="_blank"><img src="https://<?=$dname?>/backend/img/email/cat/brands.png" style="width:40px;margin-top: 5px;"><br>????????????</a>
	</td>
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
					<p><?php 
                               $s =  preg_replace('#(href="https://www.red.ua[^"]+)#i', '$1$2'.$this->track, $this->post->intro); 
                               
                               echo $s;
                                       // echo stripslashes(str_replace('#', '&', $this->post->intro));
                                        ?></p>
				</td>
			</tr>
    <?php }  if($this->post->article_id){ ?>
			<tr>
				<td style="padding-left: 0%;">
					<?php $i = 0; 
                                            foreach ($this->post->article_id as $item) {
					++$i;
					if($i==4) {echo "<br>";}
					$article = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id'=>$item)); //var_dump( $article);
					if(!$article){ continue;}
					?> 
				<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" align="left">
					<tr>				
					<td <?php if ($i==2){?>rowspan="2"<?php } ?>>
						<table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" rel="<?=$i?>" align="center" >
						<tr>
							<td align="center" style="padding: 0px 16px 0 16px">
							<a href="http://<?=$dname.$article->getPath().$this->track?>" style="color:#333;text-decoration:none;">
								<img src="http://<?=$dname.$article->getImagePath('detail')?>" width="200"><br>
								<span style="font-size: 17px;margin:0"><?=$article->getBrand()?></span><br>
								<span style="margin:0"><?=$article->getModel()?></span><br>
								<?php if ((int)$article->getOldPrice()) {echo '<span style="text-decoration: line-through;color: #666;">'.$article->getOldPrice().' ??????.</span>';}?>
								<span style="margin:0">  <?=$article->getPrice()?> ??????.</span>
							</a>
							</td>
						</tr>
						</table>
					</td>
					<?php if ($i==3){?></tr><tr><?php } ?>
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
                               
                               echo $f;
                               // stripslashes(str_replace('#', '&', $this->post->ending))
                               ?></td>
			</tr>
                        <?php } ?>
</table>
<table  align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;color:#6c6c6c;width:700px;">
<tr style="background: #ededed;">
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>CALL-??????????</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>????????????????</b></td>
<td  style="width: 33.33333333%;padding-top:10px;padding-left:10px;"><b>????????????????</b></td>
</tr>
<tr style="background: #ededed;" >
<td  style="width: 33.33333333%;padding-left:10px;">????-????: 09:00 - 18:00<br>????-????: ????????????????</td>
<td  style="width: 33.33333333%;padding-left:10px;" >(044) 224-40-00<br>(063) 809-35-29<br>(067) 406-90-80<br>market@red.ua</td>
<td  style="width: 33.33333333%;padding-left:10px;padding-bottom:10px;" >
<a href="https://<?=$dname?>/advantages/<?=$this->track?>" style="color: #878787;text-decoration: none;">????????????????????????</a><br>
<a href="https://<?=$dname?>/reviews/<?=$this->track?>" style="color: #878787;text-decoration: none;">????????????</a><br>
<a href="https://<?=$dname?>/pays/<?=$this->track?>" style="color: #878787;text-decoration: none;">???????????????? ?? ????????????</a><br>
<a href="https://<?=$dname?>/returns/<?=$this->track?>" style="color: #878787;text-decoration: none;">????????????????</a>
</td>
</tr>
<tr>
<td colspan="3" style="background:#ddd;font-size: 9px;padding:10px; text-align:center;" >
<span>???? ???????????????? ?????? ???????????? ???? ?????????? <?=$this->email?>, ???????????? ?????? ???????????????? ???????????????? ???? ?????????? Red.ua. ???????? ???? ???????????? ???? ???????????? ???????????????? ?????? ????????????, ?????????????? </span><a href="/subscribe/unsubscribe/?email=<?=$this->email.$this->unsubscribe?>"><span style="color:#383838">??????</span></a> <span>?????? ???????? ?????????? ????????????????????.</span>
</td>
</tr>
</table>
</body>
</html>
