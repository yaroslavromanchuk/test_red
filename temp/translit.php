<?php
function trans_uk($str, $lang_from, $lang_to) {
 $apiKey = 'AIzaSyC5MeHPcuEKqiWH7Oqlxvp8GhY7TTYwUf8';  
 $text = $str;  
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source='.$lang_from.'&target='.$lang_to;  
  $handle = curl_init($url);  
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);  
  $response = curl_exec($handle);  
  $responseDecoded = json_decode($response, true);  
  $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE); 
  curl_close($handle);  
  if($responseCode != 200) {  
        echo 'Fetching translation failed! Server response code:' . $responseCode . '<br>';  
        echo 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];  
    } else {  
       // echo 'Source: ' . $text . '<br>';  
        $trans = $responseDecoded['data']['translations'][0]['translatedText'];  
		return $trans;
    }
}  
  
  

//require_once('../cron_init.php');

/*
$color = wsActiveRecord::useStatic('Shoparticlescolor')->findAll(array('name != ""', 'name_uk = ""'), array(), array(500));
foreach($color as $c){
$c->setNameUk(gtranslate($c->name, 'ru', 'uk'));
$c->save();
}*/

/*
$brand = wsActiveRecord::useStatic('Blog')->findAll();
foreach($brand as $b){
$b->setPreviewPostUk($gt = gtranslate($b->preview_post, 'ru', 'uk'));
//$b->setContentPostUk($gt = gtranslate($b->content_post, 'ru', 'uk'));
$b->save();
echo 'Ok';
}*/
/*
$brand = wsActiveRecord::useStatic('Brand')->findAll(array('text != ""'));
foreach($brand as $b){
$b->setTextUk($gt = gtranslate($b->text, 'ru', 'uk'));
echo 'Ok';
}*/

/*
$menu = wsActiveRecord::useStatic('Menu')->findAll(array('page_body_uk = "" '));
foreach($menu as $c){

//$c->setNameUk($gt = gtranslate($c->name, 'ru', 'uk'));
//$c->setPageTitleUk($gt = gtranslate($c->page_title, 'ru', 'uk'));
//$c->setPageBodyUk($gt = gtranslate($c->page_body, 'ru', 'uk'));
//$c->save();
 //echo '<pre>'.$c->id.' = '.$c->name.' = '.$c->name_uk.'<pre>';
echo "OK";
}*/

/*
$categories = wsActiveRecord::useStatic('Shopcategories')->findAll(array('active' => 1));
foreach($categories as $k=>$c){
if($c->name_uk == ''){
$c->setNameUk($gt = gtranslate($c->name, 'ru', 'uk'));
$c->save();
 //echo '<pre>'.$c->id.' = '.$c->name.' = '.$c->name_uk.'<pre>';
}
}
*/
$c=0;
/*
$articles = wsActiveRecord::useStatic('Shoparticles')->findAll(array('active' => 'y', 'category_id != 16', 'data_new > "2017-08-24"', 'model_uk = ""'), array(), array(100));//, 'model_uk = ""', 'sostav_uk = ""'
foreach($articles as $a){
$a->setModelUk(trans_uk($a->model, 'ru', 'uk'));
$a->setLongTextUk(trans_uk($a->long_text, 'ru', 'uk'));
$a->setSostavUk(trans_uk($a->sostav, 'ru', 'uk'));
/*
if(!@$a->model_uk){
$a->setModelUk(trans_uk($a->model, 'ru', 'uk'));
}

if(!@$a->long_text_uk){
$a->setLongTextUk(trans_uk($a->long_text, 'ru', 'uk'));
$a->save();
}

if(!@$a->sostav_uk){
$a->setSostavUk(trans_uk($a->sostav, 'ru', 'uk'));
}

$a->save();
$c++;
}*/



/*
$blog = wsActiveRecord::useStatic('Blog')->findAll(array('id in(152,150)'));
foreach($blog as $a){
//$a->setPostNameUk(trans_uk($a->post_name, 'ru', 'uk'));
//$a->setPreviewPostUk(trans_uk($a->preview_post, 'ru', 'uk'));
$a->setContentPostUk(trans_uk($a->content_post, 'ru', 'uk'));
$a->save();
$c++;
}
*/
//echo "OK";

//echo $gt = gtranslate('пример использования машинного масла', 'ru', 'uk');
//echo '<pre>'; var_dump($gt); echo '</pre>';
//echo $gt->sentences['0']->trans;





echo $c.'OK';


?>