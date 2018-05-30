<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
echo date( $days[date( 'N' )] . ' d.m.Y' );
//$today = date("D M j G:i:s T Y"); 
// $item_time = strtotime('2018-05-13');
      //  $day = (time() - $item_time) / (24 * 60 * 60);
		//echo $day;
		// echo (int)$day;
		
		//$d = date("Y-m-d", strtotime("- 6 days", strtotime('2018-05-13')));
		//echo $d;
//print_r($_SERVER);
//require_once('cron_init.php');
//var_dump($_SERVER);
//print_r($_SERVER);
//print_r($_SESSION);
//echo printf("<pre>%s</pre>",print_r($GLOBALS, true));
 //$string = 'php@red.ru';
 // if(stristr($string, '.ru')) {
  //  echo 'ok';
  //}else{
  //echo "No";
  //}
  
?>

<?php


//require_once('conect.php');
/*
//////////$kost = wsActiveRecord::useStatic('Shopcategories')->findByParentId(59);
//echo printf("<pre>%s</pre>",print_r($kost, true));
$array = array();
		$arr = '30, 69, 70, 75, 77, 80, 113, 142, 143, 144, 147, 148, 157, 249, 255, 32, 39, 40, 73, 78, 84, 263, 107, 141, 149, 35, 56, 67, 244, 163, 110, 150, 151, 55, 65, 53, 79, 71, 114, 115, 117, 134, 152, 154, 155, 268, 247, 251, 253, 140, 174, 137, 158, 37, 57, 62, 58, 36, 60, 68';
		$array = explode(", ", $arr);
		asort($array);
		foreach ($array as $z) {
		//echo $z."<br>";
		}
		//echo printf("<pre>%s</pre>",print_r($array, true));
?>
<!--
<table cellspacing="1" border="1" cellpadding="1" >
<tr>
<th>id</th>
<th>parent_id</th>
<th>cat_name</th>
</tr>
<?php
foreach ($kost as $k) {
?>
<tr>
<td>
<?php echo  $k->id; ?>
</td>
<td>
<?php echo$k->parent_id; ?>
</td>

<td>
<?php echo $k->name; ?>
</td>
</tr>

			<?php }
	   */
		?>
		
	<!--</table>
	-->
	<!--	<table cellspacing="1" border="1" cellpadding="1" >

		<?php
		//$c = 70;
		//$mas = array();
		//$array = array();
		//$arr = '30, 69, 70, 75, 77, 80, 113, 142, 143, 144, 147, 148, 157, 249, 255, 32, 39, 40, 73, 78, 84, 263, 107, 141, 149, 35, 56, 67, 244, 163, 110, 150, 151, 55, 65, 53, 79, 71, 114, 115, 117, 134, 152, 154, 155, 268, 247, 251, 253, 140, 174, 137, 158, 37, 57, 62, 58, 36, 60, 68';
		//$arr = '273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 163';
		//$array = explode(", ", $arr);
		//echo printf("<pre>%s</pre>",print_r($array, true));
		//echo '<br>';
		//asort($array);
		
		/*
		$i = 0;
		$mas_c = array();
		$temp = array();
		$q2 = "SELECT `id` FROM `ws_customers` WHERE `id`  < 100";
						// $ostatok = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q2)->at(0)->cnt;
		
		$mas = wsActiveRecord::useStatic('Customer')->findByQuery($q2);
		//echo print_r($mas);
		foreach($mas as $id){
		$q = 'SELECT  sis.`size` AS rozmer, COUNT(  `ws_order_articles`.`size` ) AS count_rozmer
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ord ON  `ws_order_articles`.`order_id` = ord.`id` 
INNER JOIN  `ws_sizes` sis ON  `ws_order_articles`.`size` = sis.`id` 
WHERE ord.`customer_id` = '.$id->id.'
GROUP BY  `ws_order_articles`.`size` 
HAVING COUNT(  `ws_order_articles`.`size` ) > 5
ORDER BY  `count_rozmer` DESC
LIMIT 0, 10 ';
		?>
<tr><td colspan="2" style="
    text-align: center;
"><?php echo $id->id; ?></td></tr><tr>
<th>Size</th>
<th>Count</th>
</tr>
		<?php
$arr = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);
	
		foreach ($arr as $k) {
		$temp[] = $k->rozmer.' - '.$k->count_rozmer;;
?>
		<tr>
<td>
<?php echo $k->rozmer; ?>
</td>
<td>
<?php echo $k->count_rozmer; ?>
</td>
</tr>		<?php


}
?>



<?php
$mas_c[$id->id] = $temp;
$temp = '';
}
*/
?>


		
		</table>	-->	
<?php //echo printf("<pre>%s</pre>",print_r($mas_c, true));?>


<?php 

//require_once('meestexpress/include.php');
	//$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
/*	
require_once('np/NovaPoshta.php');
	require_once('np/NovaPoshtaApi2Areas.php');
	$np = new NovaPoshta(
    '5936c1426b742661db1dd37c5639f7b6',
    'ru', // Язык возвращаемых данных: ru (default) | ua | en
    true, // При ошибке в запросе выбрасывать Exception: FALSE (default) | TRUE
    'curl' // Используемый механизм запроса: curl (defalut) | file_get_content
);	
*/
	
	//$cities = $api->getCityAll('C35B6195-4EA3-11DE-8591-001D600938F8');
	//$x = '5CB61671-749B-11DF-B112-00215AEE3EBE';
//$x = '5CB61671-749B-11DF-B112-00215AEE3EBE';
	//	$street = $api->getStreetAll();
	//$br = $api->getBranch($x);
	//echo print_r($strit);
	//$z = '8d5a980d-391c-11dd-90d9-001a92567626';
//$st = $np->getStreetAll($z);
//$cities = $np->getCities();
//echo printf("<pre>%s</pre>",print_r($street, true));
//echo printf("<pre>%s</pre>",print_r($br, true));
//echo printf("<pre>%s</pre>",print_r($st, true));
/*
foreach ($strit as $c) {
		//$mas[$i]['label'] =
		echo (string)$c->DescriptionRU.'</br>';
		//$mas[$i]['value'] = (string)$c->StreetTypeRU.' '.$c->DescriptionRU;
		//$mas[$i]['id'] = (string)$c->uuid; 
		//$i++;
			}
			
			*/

//foreach($st['data'] as $c){ 
//$x = new CityNp();
//$x->setName($c['DescriptionRu']);
//$x->setUid($c['Ref']);
//$x->save();
//echo $c['Description'].'</br>';
//}




?>
