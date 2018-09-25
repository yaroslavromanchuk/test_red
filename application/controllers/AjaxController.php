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
	
	public function setlangAction(){
	if($this->post->lang){ $_SESSION['lang'] = $this->post->lang; }
	//if($this->post->lang == 'ua') 
	 die($this->post->lang);
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
