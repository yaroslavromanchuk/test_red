<h1><?=$this->trans->get('Акции')?></h1>
<?php //echo $this->getCurMenu()->getPageBody(); ?>
<div class="row mx-auto">
<?php
$today = date("Y-m-d H:i:s"); 
$all_news = wsActiveRecord::useStatic('News')->findAll(array("status"=> 2, "start_datetime <= '$today' and '$today' <= end_datetime"),array(),array(6));
if($all_news->count())
foreach($all_news as $news) { ?>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1">
<div class="bg-white p-2 h-100 text-center">
	<h2><?=$news->getTitle()?></h2>
	<p class="text-center"><?=$news->getIntro()?></p>
	<a class="btn btn-danger" href="<?=$news->getPath()?>"><?=$this->trans->get('Смотреть детали')?></a><br/>
	</div>
	</div>
<?php }else{ ?>
<div class="col-xl-12 p-1">
<div class="bg-white p-2 h-100 text-center">
<h5 class="text-danger d-inline-block p-3 font-weight-bold"><?=$this->trans->get('В данный момент нет действующих акций')?></h5><br>
<p><?=$this->trans->get('Подпишитесь на нашу новостную рассылку, чтобы всегда располагать последней информацией и узнавать о наших особых предложениях')?>.</p>
</div>
</div>
<?php } ?>

</div>