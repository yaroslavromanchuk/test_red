	<h1><?=$this->getCurMenu()->getName()?></h1>
	<?php if(Config::findByCode('new_grafik')->getValue()){?>
	<p style="text-align: center;color: red;font-size: 18px;"><?=Config::findByCode('new_grafik')->getValue()?></p>
	<?php } ?>
	<?=$this->getCurMenu()->getPageBody()?>	
