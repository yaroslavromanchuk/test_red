<!DOCTYPE html >
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="<?=htmlspecialchars($this->getCurMenu()->getMetatagDescription()); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="google-site-verification" content="5KsgGP4-JCTjV0dafIfi5_AI73MIryFuGqLvAgIthAI" />
        <meta name="robots" content="noindex, nofollow"/>
    <title><?=htmlspecialchars($this->getCurMenu()->getTitle())?></title>
	<link rel="alternate" hreflang="ru-UA" href="https://www.red.ua/ru<?=$_SERVER['REQUEST_URI']?>" />
		<link rel="alternate" hreflang="uk-UA" href="https://www.red.ua/uk<?=$_SERVER['REQUEST_URI']?>" />
	<link rel="shortcut icon" href="/favicon.ico"/>	

    <link rel="stylesheet" type="text/css" href="/css/bs/css/bootstrap.css?v=1.0"/>
</head>
<body>
     <?php
if($this->user->id == 8005){
  // echo '<pre>';
   // print_r($this->getCurMenu());
   //echo '</pre>';
}
 ?> 
<div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 py-3">
                    <h1 itemprop="name"  class="h1 text-dark font-size-100 text-center"><?=$this->getCurMenu()->getName()?></h1>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
                        <?=$this->getContent()?>
                </div>
        
            </div>
</div>
</body>
</html>
