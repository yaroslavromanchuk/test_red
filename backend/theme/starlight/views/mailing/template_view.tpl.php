<?php $dname = Config::findByCode('domain_name')->getValue();?>
	<table  style="width:700px;background: black;" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td style="border-bottom: 2px solid #e30613;padding:10px;">
		<span  style="text-align:left;">
	<a href="https://<?=$dname.$this->getExtraUrl()?>"><img src="https://<?=$dname?>/backend/img/email/logo_new.png" alt="RED"  style="height:30px;"></a>
	</span>
	<span  style="float: right;text-align:right;">
             <a href="https://t.me/shop_red_ua"><img src="https://<?=$dname.$this->files?>img/email/teleg.png" alt="facebook"   style="width:30px;padding: 0px 1px;" ></a>
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
<a href="https://<?=$dname.$menui->getPath().$this->getExtraUrl()?>"  style="letter-spacing: -1px;text-decoration: none;
    text-transform: uppercase;
    width: 68px;
    text-align: center;
    display: inline-block;
    padding: 0;
    line-height: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #605f60;"  target="_blank">
<img src="https://<?=$dname.$this->files?>img/email/cat/<?=$menui->img_email?>.png" style="width:40px;margin-top: 5px;">
<br><span style="margin-left: -5px;"><?=$menui->getEmail()?></span></a></td>
						<?php } ?>
	<td style="padding:0;">
	<a href="https://www.red.ua/brands/" style="letter-spacing: -1px;text-decoration: none;
    text-transform: uppercase;
    width: 68px;
    text-align: center;
    display: inline-block;
    padding: 0;
    line-height: 20px;
    font-size: 14px;
    font-weight: bold;
    color: #605f60;"   target="_blank"><img src="https://<?=$dname.$this->files?>img/email/cat/brands.png" style="width:40px;margin-top: 5px;"><br>????????????</a>
	</td>
	</tr>
	</tbody>
	</table>
	</td>
	</tr>
	</table>
	
<table style="width:700px;" align="center"><tr><td><?=$this->content?></td></tr></table>

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
<a href="https://<?=$dname?>/advantages/" style="color: #878787;text-decoration: none;">????????????????????????</a><br>
<a href="https://<?=$dname?>/reviews/" style="color: #878787;text-decoration: none;">????????????</a><br>
<a href="https://<?=$dname?>/pays/" style="color: #878787;text-decoration: none;">???????????????? ?? ????????????</a><br>
<a href="https://<?=$dname?>/returns/" style="color: #878787;text-decoration: none;">????????????????</a>
</td>
</tr>
</table>
