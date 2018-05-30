<html>
<head>
<title><?php echo Config::findByCode('website_name')->getValue();?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body style="background-color:#c4c5c7">
<center>
<table border="0" cellpadding="0" cellspacing="0" style="border:10px solid #ffffff; background:#ffffff" width="700">
<tr>
  <td>

    <table border="0" cellpadding="0" cellspacing="0" width="700" margin-bottom="10px">
    <tr><td>
    <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail/mail-nieuwsbrief.gif" width="700" height="200" alt="<?php echo Config::findByCode('website_name')->getValue();?>" title="<?php echo Config::findByCode('website_name')->getValue();?>">

    </td>
    </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="700" style="font-family: Verdana">
    <tr>
      <td width=700 style="vertical-align:top"><img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail/mail-shadow.gif" width="700" height="30" alt="">
      <table width=600 cellpadding=20 style="border:1px solid #e4e4e4; margin-left:80px">
      <tr>
      <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838"; align="left"><p>Кому: <?php echo $this->name;?></p>

        <p><?php echo $this->post->intro;?></p>
       </td>
      </tr>
 
<?php
	if(@$this->post->news)
	foreach($this->post->news as $id) {
		$anons = new News($id);
?>
      <tr>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838; border-top:1px solid #e4e4e4;line-height:140%;"; align="left"><p><strong style="color:#091d34"><?php echo $anons->getTitle();?></strong></p>
          <table cellspacing="0" cellpadding="2">
            <tr>
              <td></td>

              <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#383838; ine-height:140%; vertical-align:top"><?php echo $anons->getIntro();?>
              <p>Нажмите  <a style="color:#383838" href="http://<?php echo Config::findByCode('domain_name')->getValue() . $anons->getPath();?>">тут</a> для ознакомления с полной версией новости</p></td>
            </tr>
          </table>
         </td>
      </tr>
<?php } ?>
      <tr>
        <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px; color:#383838; border-top:1px solid #e4e4e4;line-height:140%;"; align="left">
		<strong style="color:#091d34">Про эту рассылку</strong><br>
          Это письмо было отправлено на адрес <?php echo $this->email;?> так как на него была оформлена подписка на сайте RED.
          Если Вы больше не хотите получать эти письма, нажмите  
          <a style="color:#383838" href="http://<?php echo Config::findByCode('domain_name')->getValue();?>/subscribe/?email=<?php echo $this->email;?>">тут</a> для того что бы отписаться.<br></td>

      </tr>
      </table>      </td>
    </tr>
    <tr>
      <td valign="top"><img style="margin-top:20px; margin-bottom:5px;" src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail/mail-footer.gif" height="30" width="700" alt="">
        <img src="http://<?php echo Config::findByCode('domain_name')->getValue();?>/img/mail/mail-shadow.gif" width="700" height="30" alt=""></td>
      </tr>
    </table>

  </td>
</tr>
</table>
</center>
</body>
</html>