<html>
<head>
</head>
<body>
<!--
<center>
<div id="content">
	<div id="head">
<a href="?subscribe" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #888888;
    border-radius: 5px;
    font-size: 18px;
">Подписаны</a>
<a href="?notified" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #249c1b;
    border-radius: 5px;
    font-size: 18px;
">Уведомлены</a>
<a href="?go-notified" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #f70000;
    border-radius: 5px;
    font-size: 18px;
" >Ждут уведомления</a>
	</div>
	</div>
	</center>-->
<!--	<div style="position: fixed; display: none; top: 200px; left: 20px;" id="prefoto"><img src="" alt="preview"/></div>-->
<script type="text/javascript">
   /* $(document).ready(function () {
        $('img.img_pre').hover(function () {
            $('#prefoto img').attr('src', $(this).parent().find('.big_image').val());
            $('#prefoto').show();
        }, function () {
            $('#prefoto').hide();
        })
    });*/
</script>
	<div class="row">
		<div class="col-xs-12">
			<p>Не активных пользователей: <?php echo count($this->notactive); ?><br>
			Отображено последних 100!
			</p>
<table id="pageslist" cellpadding="2" cellspacing="0" width="980">
    <tr>
        <th></th> 
        <th class="c-projecttitle"><span>Имя</span></th>
        <th class="c-projecttitle">Е-мейл/Логин</th>
        <th class="c-projecttitle" width="102">Телефон</th>
        <th class="c-clientname" >Последний визит</th>
        <th class="c-clientname">Скидка<br></th>
        <th class="c-clientname">Заказов<br></th>
        <th class="c-clientname">Депозит<br></th>
    </tr>
<?php
   $i = 0;
    foreach ($this->notactive as $not) {
	if($i==100){break;}
		$notact = wsActiveRecord::findByQueryArray("SELECT * FROM  `ws_orders` WHERE  `customer_id` =".$not->id);
		?> 
        <tr>
            <td class="kolomicon"><a href="http://www.red.ua/admin/user/edit/id/<?php echo $not->id; ?>/">
			<img
                        src="<?php echo SITE_URL; ?>/img/icons/edit-small.png" alt="Редактирование"/></a>
				<a href="http://www.red.ua/account/login/&j25k17l2517=&password=&login=<?php echo $not->email;
                if ($not->email != $not->username) echo ' /<br>' . $not->username; ?>">Login</a>
			</td> 
			<td class="c-projecttitle"><?php echo $not->first_name; ?></td>
            <td class="c-projecttitle"><?php echo $not->email;?></td>
            <td class="c-projecttitle"><?php echo $not->phone1; ?></td>
            <td class="c-projecttitle"><?php echo $not->utime; ?></td>
            <td class="c-projecttitle"><?php if($not->real_skidka > 0) {echo $not->real_skidka;}else{echo 0;}?>%</td>
            <td class="c-projecttitle"><?php echo count($notact); ?></td>
            <td class="c-projecttitle"><?php echo $not->deposit; ?></td>

        </tr>
    <?php
	$i++;
    }
    ?>
</table>
		</div>
	</div>
</body>
</html>