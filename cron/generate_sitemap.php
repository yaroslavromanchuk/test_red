<?php
//03:30
require_once('cron_init.php');
$summ = 0;
 $summ+=womenSitemap();
 $summ+=menSitemap();
$summ+=tovarydlyadomaSitemap();
 $summ+=shoesSitemap();
$summ+=saleSitemap();
 $summ+=accessorySitemap();
 $summ+=babySitemap();
 $summ+=productsSitemap();
 $summ+=serviceSitemap();
 $summ+=blogSitemap();
 $summ+=brandsSitemap();
    
  updateSitemap();
  // Telegram::sendMessageTelegram(404070580, "Sitemap : ".$summ);//Yarik
