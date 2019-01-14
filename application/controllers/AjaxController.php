<?php

class AjaxController extends controllerAbstract
{

    public function init()
    {
        parent::init();

    }
    public function getbasketcountAction(){
        if (!isset($_SESSION['basket']))
            $_SESSION['basket'] = array();
        $this->basket  = $_SESSION['basket'];
        $articles_count = 0;
        $articles_price = 0.0;
        if ( $this->basket)
            foreach ( $this->basket as $item) {
                $articles_count += $item['count'];
                $articles_price += $item['price'] * $item['count'];
            }
        die(json_encode(array('cnt'=>$articles_count,'price'=>$articles_price)));
    }
    /**
     * Содержимое корзины
     */
    public function getviewbacketAction() {
$sum=0;
$count = 0;
$rez='';
$i=0;
$j=0;
  if(count($this->basket) > 0){
  $rez.='<ul style="padding-left: 20px;">';
foreach ($this->basket as $item) {
if($i < 10){
$article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id']);
$img = $article->getImagePath('small_basket');
  $rez.='<li style="    list-style-type: decimal;"><div>';
$rez.='<img src="'.$img.'" alt="'.$article->getTitle().'" style="padding-right: 5px;"/>';
$rez.=$article->getTitle();
$rez.=' '.$item['count'].' шт.';
//$rez.=' '.$article->getPriceSkidka().' грн.';
$rez.='</div></li>';
//$sum+=$item['count']*$article->getPriceSkidka();
}else{
   // $article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id']);
  //$sum+=$item['count']*$article->getPriceSkidka();
}
if($i>=10) {$j+=$item['count'];}

$count+=$item['count'];
$i++;
			}
			if($i >=10) $rez.='<li style="list-style-type: none;"><div><p style="text-align: center;">Еще '.$j.'</p></div>';
 $rez.='</ul><p>'.$this->trans->get("Общее количество товаров").' '.$count.'</p>'; //<!--<p>'.$this->trans->get("Сумма к оплате").' ~'.$sum.' грн.</p>';-->
 }else{
   $rez.='<p style="text-align: center;">'.$this->trans->get("В корзине ничего нет").'</p>';
 }
//print_r($this->basket);
die(json_encode(html_entity_decode($rez)));
        
    }
	
	public function setlangAction(){
		//echo print_r($this->post);
		
	if($this->post->lang){
		
            $_SESSION['lang'] = $this->post->lang; 
			
			
			if($this->post->lang == 'uk'){
				$page = '/uk'.$this->post->ur;
				
			}else{
				$page = substr($this->post->ur, 3);
			}
			
			//header("Refresh: 0; url=$page");
			//header("Location: $page",TRUE,301);
			//exit();
			
			//$sec = "10";
			/*
			if(isset($_COOKIE['lang'])){
			$_COOKIE['lang'] = $this->post->lang;
		}else{
			setcookie('lang', $this->post->lang);
			}
			*/
			//header("Refresh: 0; url=$page");
        }
		
	//if($this->post->lang == 'ua')	
//echo print_r($this->post->lang);		
	 die($page);
	return false;
	}
	
	public function getcountarticlescategoryAction(){
	$arrr = array();
	
	$cats = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => $this->post->cat, 'active' => 1));
$arr = $cats->getKidsIds();
$arr = array_unique($arr);
$cat = implode(",", $arr);

	$count = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT count(  `ws_articles_sizes`.`id` ) AS ctn
FROM  `ws_articles_sizes` 
INNER JOIN  `ws_articles` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
WHERE  `ws_articles_sizes`.`count` >0
AND  `ws_articles`.`active` =  'y'
AND  `ws_articles`.`category_id` 
IN (".$cat." )")->at(0)->getCtn();
$arrr['category'] = $this->post->cat;
$arrr['ctn'] = $count;
	die(json_encode($arrr));
	}



}
