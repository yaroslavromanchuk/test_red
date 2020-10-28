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
    <img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?=$this->email?>&t=event&ec=return_basket_open&ea=open&el=<?=$this->email?>&cs=return_basket_open&cm=email&cn=Return_Basket" width="1" height="1" alt="" />
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
                                <a href="<?=$this->link?>" class="btn-more-link" style="color: #fff; font-size: 16px; font-weight: 400; text-decoration: none; line-height: 40px; display: inline-block; padding: 0  16px; font-family: Arial, Helvetica, sans-serif;" target="_blank">Перейти</a>
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
                                                            <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">Добрый день, <a href="<?=$this->link?>" style="color: #3e77aa; text-decoration: none;" target="_blank"><?=$this->cart->user->getFirstName()?></a></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
<span class="text-title" style="display: block; font-size: 28px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">У вас незавершёная покупка</span>
</td>
</tr>
<tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
                                                            <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">Недавно вы добавили в корзину товар, но не завершили покупку. Выбранный вами товар еще есть в наличии и вы можете  <a href="<?=$this->link?>" style="color: #3e77aa; text-decoration: none;" target="_blank">оформить заказ.</a></span>
   </td>
</tr>
<tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                    <td style="margin: 0; padding: 0  16px; vertical-align: middle; text-align: left;">
      <span class="text-description" style="display: block; font-size: 17px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">Вы можете вернуться к этим товарам позже, но напоминаем, что количество товаров на складе ограничено и они будут зарезервированы за вами только после оформления заказа.</span>
   </td>
                                                </tr>
                                                <tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr><td style=" margin: 0; padding: 0  16px; vertical-align: middle;"><span style="display: block; font-size: 24px; width: 100%; font-weight: 400; font-family: Arial, Helvetica, sans-serif;">Корзина</span></td>
</tr>
<tr>
                                                        <td height="16" style="line-height: 16px; height: 16px; padding: 0;">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td style=" margin: 0; padding: 0  16px;" >
                                                            <table>
                                                                <?php
                                                                
                                                                $item = $this->cart->item;
                                                                $total = 0;
                                                                $total_count = 0;
                                                                foreach ($item as $a) {
                                                                    $total_count+=$a->count;
                                                                    ?>
                                                                <tr>
                                                                    <td>
                                                                        <a href="<?=$this->link.'&referral='.$a->article->getPath()?>"> 
                                                                            <img style="width:200px;" src="https://<?=$dname?><?=$a->article->getImagePath('detail')?>" alt="<?=htmlspecialchars($a->article->getTitle())?>"/>
                                                                        </a>
                                                                    </td>
                                                                    <td> <span style="display: block; width: 100%;"><a href="<?=$this->link.'&referral='.$a->article->getPath()?>" style="color: #3e77aa; margin: 0; padding: 0; text-decoration: none; font-weight: 400; font-size: 17px; line-height: 20px; font-family: Arial, Helvetica, sans-serif;">
                                                                            <?=$a->article->getTitle()?>
                                                                            Цвет: <?=$a->colors->name?>, Размер: <?=$a->sizes->size?>
                                                                            </span>
                                                                        </a>
                                                                        <p>
                                                                            <?php $price = $a->article->getPerc();
                                                                             $p = isset($price['option_price'])?$price['option_price']:$price['price'];
                                                                             
                                                                             $total+=($p*$a->count);
                                                                            ?>
                                                                            <span style="display: inline-block; background-color: #e00e36; text-align: center; padding: 6px  8px; color: #fff; font-size: 17px; font-weight: 400; font-family: Arial, Helvetica, sans-serif; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                                                                                <?=$p?>
                                                                                <span style="font-size: 13px;">&nbsp;₴</span>
                                                                            </span>
                                                                                 </p>
                                                                    </td>
                                                                </tr>
                                                                       <?php } ?>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style=" margin: 0; padding: 0  16px;" >
                                                            <table style="width: 100%; border-top: 1px #9e9e9e  solid;">
                                                                <tr style="text-align: center;">
                                                                    <td style="margin: 0;padding: 14px;width: auto;display: inline-block;margin-top: 9px;border-radius: 5px;">
                                                                        <table style="width: 100%;min-width: 200px;color: #fff;font-size: 20px;font-weight: 400;">
                                                                            
                                                                            <tr>
                                                                                <td style="color: black;text-align: center;">
                                                                                    Итого: <?=$total?> ₴
                                                                                </td>
                                                                            </tr>
                                                                            <tr><td style="margin: 0; padding: 0;">
<center style="width: 100%;margin-top: 15px;">
    <span style="text-align: center; vertical-align: middle; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; background-color: #e00f36; display: inline-block; font-weight: 400;">
        <a href="<?=$this->link?>" class="btn-more-link" style="color: #fff; font-size: 18px; font-weight: 400; text-decoration: none; line-height: 48px; display: inline-block; padding: 0  24px; font-family: Arial, Helvetica, sans-serif;" target="_blank">Оформить заказ</a>
    </span></center>
</td>
</tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                
                                                            </table>
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
                                <h4 style="text-align: center;">Активные акции в интернет-магазине</h4>
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
<td  style="color: white;line-height: 2rem;" >Пн-Пт: 09:00 - 18:00<br>Сб-Вс: Выходные</td>
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
                                                                            <a href="/subscribe/unsubscribe/?email=<?=$this->email?>" style="color: grey;font-size: .7em;text-decoration: none;"><span style="">Отписаться от рассылки</span></a>
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
                                        <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle;"> <span style="color: #999; display: block; font-size: 11px; text-align: center; width: 100%; font-family: Arial, Helvetica, sans-serif; font-weight: 400;"> Письмо содержит данные для доступа в личный кабинет, не передавайте его третьим лицам.</span></td></tr>
                                        <tr><td style="margin: 0; padding: 0  16px; vertical-align: middle;"> <span style="color: #999; display: block; font-size: 11px; text-align: center; width: 100%; font-family: Arial, Helvetica, sans-serif; font-weight: 400;">Добавьте наш адрес market@red.ua в ваш список контактов, чтобы не пропустить ни одной скидки.</span></td></tr>
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
