<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartController
 *
 * @author PHP
 */
class CartController extends controllerAbstract
{
    public function init()
    {
        
        parent::init();
       
        

    }
    //put your code here
    public function indexAction()
	{
		$this->view->setArticlesTop(wsActiveRecord::useStatic('Shoparticlestop')->findAll());
		echo $this->render('shop/index.tpl.php');

	}
/**
 * Корзина
 */
	public function cartAction()
                {
            if($this->get->dellkupon){
                unset($_SESSION['kupon']);
                $this->_redir('basket');
            }
                if('delete' == $this->cur_menu->getParameter()) {
			$basket = $_SESSION['basket'];
			if ($basket) {
				$_SESSION['basket'] = [];
				foreach ($basket as $key => $value){
					if ($key != (int)$this->get->getPoint()){
                                            $_SESSION['basket'][] = $value;
                        }
                                        }
			$this->_redir('basket');
			}
		}elseif('change' == $this->cur_menu->getParameter() && $this->get->getCount()) {
			if (isset($_SESSION['basket'][(int)$this->get->getPoint()]['count'])) {
				$count = $this->get->getCount();
				$_SESSION['basket'][(int)$this->get->getPoint()]['count'] = $count;
			}
			$this->_redir('basket');
		}elseif('clear' == $this->cur_menu->getParameter()) {
                    
			$_SESSION['basket'] = [];
                        $c = $this->ws->getCustomer()->getCart();
                        if($c->id){
                            $c->clearCart();
                        }
			$this->_redir('basket');
		}
		$error = [];
                
                if (count($_POST)) {
			if (isset($_POST['tostep2'])) {
				foreach ($_POST as &$value) {
					$value = stripslashes(trim($value));
				}
				if ($this->post->deposit == 1) {
					$_SESSION['deposit'] = $this->ws->getCustomer()->getDeposit();
				}else{ unset($_SESSION['deposit']);}
                                
				if ($this->post->bonus == 1) {
					$_SESSION['bonus'] = $this->ws->getCustomer()->getBonus();
				}else {unset($_SESSION['bonus']);}

				if (!isset($_SESSION['basket_contacts'])) {
					$_SESSION['basket_contacts'] = [];
				}
				$this->_redirect('/shop-checkout-step2/');
			}
		}  
                $card = [];
                $card_article = [];
                $total_price = 0;
                $param = [];
                $total_price_minus = 0;
                $customer = $this->ws->getCustomer();
                $boll = $customer->getIsLoggedIn();
                $sum_order = Cart::getSumCard($boll?$customer->getId():false);
                $_SESSION['or_sum'] = $param['sum_order'] = $sum_order;
              // unset($_SESSION['kupon']);
                if($this->get->kupon){
                  // echo $this->get->kupon;
                  $k =   Other::findActiveCode($this->get->kupon);
                  if($k['flag']){
                      $_SESSION['kupon'] = $k['cod'];
                      $param['kupon'] = $k['cod'];
                       $this->view->kupon_a = $k;
                       $this->view->kupon = $k;
                      //$this->view->mes_kupon = $k['message'];
                  }
                  $this->view->kupon_a = $k;
                 // print($k['message']);
                }elseif(isset($_SESSION['kupon'])){
                    $k =   Other::findActiveCode($_SESSION['kupon']);
                    if($k['flag']){
                      $param['kupon'] = $k['cod'];
                      $this->view->kupon = $k;
                  }else{
                      unset($_SESSION['kupon']);
                  }
                }
                if ($boll) {
                    $param['skidka'] = $customer->getDiscont(false, 0, true);
                    $param['event_skidka'] = EventCustomer::getEventsDiscont($customer->getId());
                    $param['now_orders'] = $customer->getSumOrderNoNew();
                    }
                
               // echo $sum_order;
                $id_in_cart = [];
    foreach($this->basket as $key => $item){
        if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
            $id_in_cart[] = $item['article_id'];
        $_SESSION['basket'][$key]['option_price'] = 0;
	$_SESSION['basket'][$key]['option_id'] = 0;
        $_SESSION['basket'][$key]['skidka_block'] = 0;
        
               $card_article[$key] = Cart::getArticles($article, $item, $param);
               
               if( $_SESSION['basket'][$key]['price'] != $card_article[$key]['price']){
                   $_SESSION['basket'][$key]['price'] = $card_article[$key]['price'];
               }
               
        $_SESSION['basket'][$key]['skidka'] = $card_article[$key]['skidka'];
            
        if($card_article[$key]['option_id']){ $_SESSION['basket'][$key]['option_id'] = $card_article[$key]['option_id'];}
        if($card_article[$key]['option_price']){ $_SESSION['basket'][$key]['option_price'] = $card_article[$key]['option_price'];}
        if($card_article[$key]['skidka_block']){ $_SESSION['basket'][$key]['skidka_block'] = $card_article[$key]['skidka_block'];}
                
               $total_price += ($card_article[$key]['price']*$card_article[$key]['count']);
               $total_price_minus += $card_article[$key]['minus']*$card_article[$key]['count'];
        }
                    
    }
                $card['article'] = $card_article;
                $card['total_price'] = $_SESSION['total_price']  = $total_price;
		$card['total_price_minus'] = $total_price_minus;
                
                $c = $this->ws->getCustomer()->getCart();
                if($c->id){
                    $c->updateCart();
                }elseif($this->ws->getCustomer()->getIsLoggedIn() and count($card_article)){
                    $this->ws->getCustomer()->newCart();
                }
                if (isset($error)) {
			$this->view->error = $error;
		}
        $css = [
                     '/js/slider-fhd/slick.css',
                    '/css/article/article.css',
                 ];
        $scripts = [
            '/js/slider-fhd/slick.min.js'
        ];
                $this->view->css = $css;
                $this->view->scripts = $scripts;
                $this->view->card = $card;
                
               $this->view->history =  $this->history(implode(',',$id_in_cart));

                echo $this->render('cart/cart.tpl.php');
	}
        /**
         * История просмотренного товьара
         * 
         * @param type $id - список товаров в корзине
         * @return boolean
         */
        function history($id = ''){
              if($this->ws->getCustomer()->getId()){ 
		$sql = "SELECT  `ws_articles`. * 
FROM  `ws_articles` 
INNER JOIN  `ws_articles_history` ON  `ws_articles`.`id` =  `ws_articles_history`.`article_id` 
WHERE  `ws_articles_history`.`customer_id` =".$this->ws->getCustomer()->getId()."
AND ws_articles.`stock`  not like '0' and `ws_articles`.`status` = 3
and ws_articles.id not in(".$id.")
GROUP BY  `ws_articles`.`id` 
ORDER BY  `ws_articles_history`.`id` DESC 
LIMIT 6";      
        return  wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
                }elseif(count($_SESSION['hist']) > 0){
                    $id_ses =  implode(',',array_unique($_SESSION['hist']));
                    $sql = "SELECT  `ws_articles`. * 
FROM  `ws_articles` 
INNER JOIN  `ws_articles_history` ON  `ws_articles`.`id` =  `ws_articles_history`.`article_id` 
WHERE   ws_articles.`stock`  not like '0' and `ws_articles`.`status` = 3
and ws_articles.id not in(".$id.")
    and ws_articles.id in(".$id_ses.")
GROUP BY  `ws_articles`.`id` 
ORDER BY  `ws_articles_history`.`id` DESC 
LIMIT 6";
 return  wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
                }else{
                      return false;
                }
        }
        
        
        public function _redir($param)
	{
		if ($param == 'index'){
                    $this->_redirect(SITE_URL . '/');
                    
                }else{
                    $this->_redirect(SITE_URL . '/' . $param . '/');
                    
                }
	}
}
