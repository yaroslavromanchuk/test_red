<h1><?=$this->getCurMenu()->getName()?></h1>
<p><?=$this->getCurMenu()->getPageBody()?></p>
<?php 
if($this->test){
//echo print_r($this->test);
$m_paytype = array(
'card'=>'оплата картой',
'liqpay'=>'кабинет liqpay',
'privat24'=>'кабинет приват24',
'masterpass'=>'кабинет masterpass',
'moment_part'=>'рассрочка',
'cash'=>'наличными',
'invoice'=>'счет на e-mail',
'qr'=>'сканирование qr-кода',
);
?>
<div class="row px-2 mx-0">
<div class="col-xl-12 px-1">
<div role="alert" class="alert alert-light d-block">
<div class="d-inline-block">
<p style="font-size: 14px;" class="text-dark text-uppercase font-weight-bold px-2"><?=$this->test['description']?></p>
<p><span class="text-dark font-weight-bold ">Статус оплаты: </span><span class="d-inline-block"> <?=$this->test['status_d']?></span></p>
<p><span class="text-dark font-weight-bold ">Способ оплаты: </span><span class="d-inline-block"><?=$m_paytype[$this->test['paytype']]?> </span></p>
</div>
</div>
</div>
</div>
<?php } ?>