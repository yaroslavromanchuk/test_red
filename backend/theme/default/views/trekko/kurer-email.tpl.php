<?php $dname = Config::findByCode('domain_name')->getValue();?>
<html>
	<head>
		<title><?php echo Config::findByCode('website_name')->getValue();?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<base href="https://<?=$dname?>/" target="_blank" />
	</head>
	<body style="background-color:#c4c5c7;font-family: Verdana, Tahoma, Arial;font-size: 14px;margin: 0;padding: 8px;">
	<p>
	<?php echo $this->name.' Вы получили это письмо на адрес '.$this->email; ?>
	</p>
	</body>
</html>