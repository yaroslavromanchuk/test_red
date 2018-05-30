<?php
require_once('../cron_init.php');
//$path = 'list.xlsx';
//$res = parse_excel_file($path);


//$a = 'SR0610279-180917';
//$a = 'SR0577458-310717';
//$articl = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code LIKE "'.$a.'"'));
//echo count($articl);

//foreach ($articl as $a) {
//echo $a->id.'<br>';
//}
//print_r(count($articl));
//echo $articl->id;
//echo count($articl).'<br>';
/*
if(count($articl) > 1){
print_r($articl);
}else{
echo $articl->code;
}
*/
/*$aa = wsActiveRecord::useStatic('Revisiya')->findAll(array('flag'=>3));
foreach ($aa as $a) {
$sql = "SELECT * FROM  `temp_sr` WHERE  `sr` LIKE  '%".$a->sr."%'";
$ee = wsActiveRecord::useStatic('Revisiya')->findByQuery($sql);
if($ee){
//echo $a->getCount().'<br>';

//echo $ee[0]['seriya'].'<br>';
}

}
*/
$i=0;
if(false){
//print_r($res);
foreach ($res as $a) {
$sql = "INSERT INTO `temp_sr`(`sr`, `title`, `charakteristika`, `seriya`) VALUES ('".$a['sr']."','".$a['ct']."','".$a['ch']."','".$a['ser']."')";
wsActiveRecord::query($sql);
}
}
/* // сверка ревизии
if($res){
//print_r($res);
//echo count($res);
foreach ($res as $a) {
$c = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code LIKE "%'.$a['sr'].'%"'));
if($c){
  if($c->count != $a['ct']){
  if($c->count > $a['ct']){
  $co = $c->count - $a['ct'];
  $f = 2;
  $log_text = 'Ревизия --';
  }else{
  $co = $a['ct'] - $c->count;
  $f = 1;
   $log_text = 'Ревизия ++';
  }
  echo $c->id_article.' - '.$c->code.' - '.$c->count.' != '.$a['ct'].' : '.$log_text.'  '.$co.'<br>';
 
  
 
									$log = new Shoparticlelog();
                                    $log->setCustomerId(8005);
                                    $log->setUsername('programmer');
                                    $log->setArticleId($c->id_article);
									if ($c->getIdSize() and $c->getIdColor()) { 
									$size = new Size($c->getIdSize());
									$color = new Shoparticlescolor($c->getIdColor());
                                        $log->setInfo($size->getSize() . ' ' . $color->getName());
                                    }
									$log->setTypeId($f);
									
									$log->setCount($co);
									
									$log->setComents($log_text);
									
									$log->setCode($c->code);
									$log->save();
                $c->setCount($a['ct']);
                $c->save();
				
				
				$i++;
				}



}
}
}*/
/* //откат добавленых товаров по ревизии
$cc = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT  `code` 
FROM  `red_article_log` 
WHERE  `coments` LIKE  '%Ревизия - Добавлено%'
AND  `type_id` =1
AND  `count` =1
AND customer_id = 8005 ");
if($cc){
foreach ($cc as $c) {
$art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('code LIKE "%'.$c->code.'%"', 'count > 0'));
if($art){
$art->setCount($art->getCount() - 1);
$art->save();


$log = new Shoparticlelog();
                                    $log->setCustomerId(8005);
                                    $log->setUsername('programmer');
                                    $log->setArticleId($art->id_article);
									if ($art->getIdSize() and $art->getIdColor()) { 
									$size = new Size($art->getIdSize());
									$color = new Shoparticlescolor($art->getIdColor());
                                        $log->setInfo($size->getSize() . ' ' . $color->getName());
                                    }
									$log->setTypeId(2);
									
									$log->setCount(1);
									
									$log->setComents('Ревизия - Удалено');
									
									$log->setCode($c->code);
									$log->save();
									$i++;

}

}

}
*/
//echo 'Изменено:'.$i;

function parse_excel_file($file)
    {
	//$limit = 100;
	$i=0;
	$mass = array();
	
        require_once('PHPExcel/IOFactory.php');
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex(0);
      $aSheet = $objPHPExcel->getActiveSheet()->toArray();
	  
	  foreach ($aSheet as $s) {
	  $mass[$i] = array('sr'=>$s[0], 'ct'=>$s[1], 'ch'=>$s[2], 'ser'=>$s[3]);
	  $i++;
	 // if($i >= $limit) break;
	  }
	  return $mass;
    }
	/*
$cc = wsActiveRecord::useStatic('Shopcategories')->findByQuery("SELECT * 
FROM  `ws_categories` 
WHERE  `active` =1
AND  `id` 
IN ( 14, 15, 54, 59, 33 ) ");	
foreach($cc as $c){

$aa = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>$c->id));
if($aa){
foreach($aa as $a){
$s = wsActiveRecord::useStatic('Shoparticles')->findByQuery("SELECT SUM(  `stock` ) AS sum FROM ws_articles WHERE category_id = ".$a->id )->at(0)->sum;
echo '<br>'.$a->id.':'.$c->name.':'.$a->name.'('.$s.')';
$bb = wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id'=>$a->id));
if($bb){
foreach($bb as $b){
$s = wsActiveRecord::useStatic('Shoparticles')->findByQuery("SELECT SUM(  `stock` ) AS sum FROM ws_articles WHERE category_id = ".$b->id )->at(0)->sum;
echo '<br>'.$b->id.':'.$c->name.':'.$a->name.':'.$b->name.'('.$s.')';
}
}else{

}
}
}
	} 
	*/
	
?>