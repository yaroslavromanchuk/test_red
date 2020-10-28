<?php

class AjaxController extends controllerAbstract
{

    public function init()
    {
        parent::init();

    }
    public function getbasketcountAction(){
        if (!isset($_SESSION['basket'])){$_SESSION['basket'] = [];}
        $this->basket  = $_SESSION['basket'];
        $articles_count = 0;
        $articles_price = 0.0;
        if ( $this->basket){
            foreach ( $this->basket as $item) {
                $articles_count += $item['count'];
                $articles_price += $item['price'] * $item['count'];
            }
    }
        die(json_encode(array('cnt'=>$articles_count,'price'=>$articles_price)));
    }
    /**
     * Содержимое корзины
     */
public function getviewbacketAction() {
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
$rez.='</div></li>';
}
if($i>=10) {$j+=$item['count'];}

$count+=$item['count'];
$i++;
}
if($i >=10){
    $rez.='<li style="list-style-type: none;"><div><p style="text-align: center;">Еще '.$j.'</p></div>';
  $rez.='</ul><p>'.$this->trans->get("Общее количество товаров").' '.$count.'</p>'; 
}
 }else{
   $rez.='<p style="text-align: center;">'.$this->trans->get("В корзине ничего нет").'</p>';
 }
die(json_encode(html_entity_decode($rez)));
 
    }
	
	public function setlangAction(){
	if($this->post->lang){
            $lang = wsLanguage::findLangActive($this->post->lang);
           //l($lang);
            if($lang){
                 $_SESSION['lang'] = $lang->code;
                // Registry::set('lang', $lang->code);
               //  Registry::set('lang_id', $lang->id);
                 if($lang->code == 'uk'){
                     $page = '/uk'.$this->post->ur;
                 }else{
                     $page = substr($this->post->ur, 3);
                 }
                 die($page);
            }
            
            //$_SESSION['lang'] = $this->post->lang; 
           // Registry::set('lang', 'ru');
           // Registry::set('lang_id', 1);
			//if($this->post->lang == 'uk'){
			//	$page = '/uk'.$this->post->ur;
			//}else{
				
			//}
           // die($page);
            return false;
        }		
	return false;
	}
	
	public function getcountarticlescategoryAction(){
	$arrr = [];
	
	$cats = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => $this->post->cat, 'active' => 1));
        
$cat = implode(",", array_unique($cats->getKidsIds()));

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
        
        public function setorderamountbasketAction() {
            $_SESSION['total_price'] = $this->post->amount;
            die(); 
        }
        public function setpromoAction() {
            if($this->post->promo){
              //  $kod = wsActiveRecord::useStatic('Other')->findFirst(["cod"=>]);
               $kod =  (object)Other::findActiveCode($this->post->promo);
               if($kod->flag){
                   $_SESSION['promo'] = $kod->cod;
                 //  die(json_encode('<span style="color:green">'.$kod->message.'</span>'));
               }
               die(json_encode($kod));
                
            }
            die('error'); 
        }
        public function deletepromoAction() {
            unset($_SESSION['promo']);
        }
         public function clearAction(){
            if($this->ws->getCustomer()->getId()){
                $_SESSION['desires'] = [];
                $sql = "DELETE FROM `ws_desires` WHERE `id_customer` = ".$this->ws->getCustomer()->getId();
                wsActiveRecord::query($sql);
            }else{
                $_SESSION['desires'] = [];
            }
            die(json_encode('ok'));
        }
        /**
         * Количество пользователей онлайн в реальном времени
         */
        public function usersiteAction(){
             $base = INPATH."session.txt";
             $file = file($base);
             $s = sizeof($file);
            die(json_encode($s));
        }
          public function  sitkaAction()
                {
              $this->cur_menu->nofollow = 1;
             // echo $this->get->lang;
              $src = '';
              if($this->get->user == 8005){
                  $this->view->lang = $this->get->lang;
                  $res = $this->view->render('/size/men.php');
              }else{
              switch ($this->get->id){
                  case 1: $src = '/img/size/size1.png'; break;
                   case 2: $src = '/img/size/size2.png'; break;
                    case 3: $src = '/img/size/size3.png'; break;
                     case 4: $src = '/img/size/size4.png'; break;
                      case 5: $src = '/img/size/size5.png'; break;
                       case 6: $src = '/img/size/size6.png'; break;
                        case 7: $src = '/img/size/baby_ob.png'; break;
                         case 8: $src = '/img/size/baby.png'; break;
                default : $src = '/img/size/size1.png'; break;
              }
             // print_r($this->get->id);
                 //print($this->post->id);
              $res = '<img style="width: 100%;" class="" src="'.$src.'" alt="size"/>';
              }
                     die(json_encode($res));
            
                }
}
