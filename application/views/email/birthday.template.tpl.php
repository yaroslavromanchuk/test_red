<?php $dname = Config::findByCode('domain_name')->getValue();
        $basket = $this->track;// '?utm_source=returnbasket&utm_medium=email&utm_content=Return_Basket&utm_campaign=Return_Basket';
        ?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>RED</title>
</head>
<body style="margin: 0; width: 100%">
    <img src="<?=$this->track_open?>" width="1" height="1" alt="" />
    <img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?=$this->email?>&t=event&ec=birthday_open&ea=open&el=<?=$this->email?>&cs=birthday_open&cm=email&cn=Birthday" width="1" height="1" alt="" />
	<table  style="    background: #E5E5E5;
    border-collapse: collapse;
    color: #333;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: 400;
    height: 100%;
    margin: 0;
    padding: 0;
    width: 100%;" align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td class="center" align="center" valign="top" style=" margin: 0; padding: 0; text-align: left; vertical-align: middle;">
            <center style="min-width: 650px;width: 100%;">
                <table align="center" class="container" style=" border-collapse: collapse; margin: 0  auto; padding: 0; width: 650px;" >
                    <tbody>
                        <tr>
                            <td style=" margin: 0; padding: 0;">
                                <table class="row" style="border-collapse: collapse; display: table; padding: 0; position: relative; width: 100%;">
                                    <tbody>
                                        <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                        <tr>
                                            <td style=" margin: 0; padding: 0 16px;">
                                                <table style="border-collapse: collapse; display: table; padding: 0; position: relative; width: 100%;" >
                                                    <tr>
                                                        <td style="margin: 0 auto;padding: 0;text-align: left;">
                                                            <span  style="text-align:left;">
	<a href="https://<?=$dname?>"><img src="https://<?=$dname?>/img/logo/pay_red.png" alt="RED"  style="height:50px;"></a>
	</span>
                                                        </td>
                                                        <td class="columns" style=" margin: 0  auto; padding: 0; padding-left: 24px; text-align: right;">
                                                            <div style="text-align: right">
                                                                <span class="btn-more" style="text-align: center; vertical-align: middle; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; background-color: #e00e36; display: inline-block;">
                                <a href="<?=$this->link?>" class="btn-more-link" style="color: #fff; font-size: 16px; font-weight: 400; text-decoration: none; line-height: 40px; display: inline-block; padding: 0  16px; font-family: Arial, Helvetica, sans-serif;" target="_blank">??????????????</a>
                                 </span>
                                                            </div> 
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style=" margin: 0; padding: 0;">
                                                <table class="container" style=" background: #fff; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; margin: 0; margin: 0  auto; padding: 0; width: 650px;">
                                                    <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style=" margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;" >
                                                            <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">???????????? ????????, <a href="<?=$this->link?>" style="color: #3e77aa; text-decoration: none;" target="_blank"><?=$this->name?></a></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
<span class="text-title" style="display: block; font-size: 28px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">?????????????????????? ?? ???????? ????????????????!</span>
</td>
</tr>
<tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                    <td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
      <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">?????????????????? ???????????? ?? ????????! ?????????? ?????? 200 redcoin ???? ?????????????? ?? ?????????? ???????????????? ????????????????.<br><span style="display: block;text-align: center"><b> 1 redcoin = 1 ??????.</b></span></span>
   </td>
                                                </tr>
<tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
                                                            <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">  <a href="<?=$this->link?>" style="color: #3e77aa; text-decoration: none;" target="_blank">??????????????????????????????</a> ???????????????????????? redcoin, ???? ???????????? ?? ???????????? ?? <?=date("d.m.Y")?> ???? <?=date("d.m.Y", strtotime("now +6 days"))?></span>
   </td>
</tr>

                                                <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                   
                                                   
                                                    <tr>
                                                        <td  style="margin: 0; padding: 0;">
                                                            <table style="width: 100%">
                                                     <?php            $ac = wsActiveRecord::useStatic('Shoparticlesoption')->findByQuery("SELECT *
FROM `ws_articles_option`
WHERE `status` = 1 and type != 'all'
AND `start` <= '".date("Y-m-d")."'
AND `end` >= '".date("Y-m-d")."'
    ORDER BY `ws_articles_option`.`start` ASC
LIMIT 0 , 5");
                       if($ac->count()){ ?>
                         <tr>
                            <td style="text-align: center">
                                <h4 style="text-align: center;">???????????????? ?????????? ?? ????????????????-????????????????</h4>
                        <ul>
                        <?php   foreach ($ac as $a) { ?>
                            <li style="padding: 15px;">
                                <a href="https://www.red.ua<?=$a->getPath().$this->track?>" style="color: red;text-decoration: none;font-weight: bold;" target="_blank"><?=$a->option_text?></a>
                            </li>  
                          <?php } ?>
                            </ul>
                              </td>
                        </tr>
                         <?php   }
                        ?>
                                                                
                                                            </table>
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td  style="margin: 0;     padding: 15px 30px;background: #505050;">
                                                            <table style="width: 100%">
                                                                <tr>
                                                                    <td>
<table   border="0" cellpadding="0" cellspacing="0" style="width:100%;color:white;font-size: 14px;font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; margin: 0; margin: 0  auto; padding: 0;">
<tr  >
<td  style="color: white;line-height: 2rem;" >????-????: 09:00 - 18:00<br>????-????: ????????????????</td>
<td style="line-height: 1.5rem;" >
    <br><span class="d-inline-block">
        <a style="color: white;" href="tel:+380442244000">+38(044) 224-40-00</a>
        <br><a style="color: white;" href="tel:+380638093529">+38(063) 809-35-29</a>
        <br><a style="color: white;" href="tel:+380674069080">+38(067) 406-90-80</a></span>
        <br><a style="color: white;" href="mailto:market@red.ua"> market@red.ua </a></td>
</tr>
</table>
                                                                    </td>
                                                                    <td style="text-align: right;">
                                                                        <span  style="text-align: right;display: block;">
        <a href="https://t.me/shop_red_ua"><img src="https://<?=$dname?>/backend/img/email/teleg.png" alt="telegram"   style="width:30px;padding: 0px 1px;" ></a>
	<a href="http://www.facebook.com/pages/RED-UA/148503625241218?sk=wall"><img src="https://<?=$dname?>/backend/img/email/fb.png" alt="facebook"   style="width:30px;padding: 0px 1px;" ></a>
	<a href="http://instagram.com/red_ua"><img src="https://<?=$dname?>/backend/img/email/inst.png" alt="instagramm"  style="width:30px;padding: 0px 1px;"></a>
	<a href="https://www.youtube.com/user/SmartRedShopping"><img src="https://<?=$dname?>/backend/img/email/youtube.png" alt="youtube" style="width:30px;padding: 0px 1px;"></a>
	<a  href="https://www.red.ua/blog/"><img src="https://<?=$dname?>/backend/img/email/blog.png" alt="blog"  style="width:30px;padding: 0px 1px;"></a>
	</span>
                                                                        <span style="display: inline-block;margin: auto;padding: 15px;">
                                                                            <a href="/subscribe/unsubscribe/?email=<?=$this->email?>" style="color: grey;font-size: .7em;text-decoration: none;"><span style="">???????????????????? ???? ????????????????</span></a>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr><td height="8" style="line-height: 8px; height: 8px; padding: 0;">&nbsp;</td></tr>
                                        <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle;"> <span style="color: #999; display: block; font-size: 11px; text-align: center; width: 100%; font-family: Arial, Helvetica, sans-serif; font-weight: 400;"> ???????????? ???????????????? ???????????? ?????? ?????????????? ?? ???????????? ??????????????, ???? ?????????????????????? ?????? ?????????????? ??????????.</span></td></tr>
                                        <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle;"> <span style="color: #999; display: block; font-size: 11px; text-align: center; width: 100%; font-family: Arial, Helvetica, sans-serif; font-weight: 400;">???????????????? ?????? ?????????? market@red.ua ?? ?????? ???????????? ??????????????????, ?????????? ???? ???????????????????? ???? ?????????? ????????????.</span></td></tr>
                                        <tr><td height="8" style="line-height: 8px; height: 8px; padding: 0;">&nbsp;</td></tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </center>
                    </td>
                </tr>
            </tbody>
    </table>
    </body>
</html>
