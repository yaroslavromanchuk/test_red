<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cart
 *
 * @author PHP
 */
class Cart extends wsActiveRecord
{
    protected $_table = 'ws_cart';
    protected $_orderby = array('ctime' => 'DESC');
    
    protected function _defineRelations()
    {
         $this->_relations = [
             'item' => [
                 'type' => 'hasMany',
                'class' => self::$_shopping_cart_item_class,
                'field_foreign' => 'id_cart',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'
             ],
             'user'=>[
                    'type' => 'hasOne',
                    'class' => self::$_customer_class,
                    'field' => 'id_user'
             ]
         ];
    }
    /**
     * Добавление товара в корзину в БД
     * @param array $user - ['id'=>8005] id пользователя
     * @param array $mass - массив с данными о товаре
     * @return boolean
     */
     public function addCard($user = [], $mass = []){
        
         $cart = wsActiveRecord::useStatic('Cart')->findFirst(['id_user'=>$user['id']]);
         if($cart){
              $item = new CartItem();
             $item->import($mass);
             $item->setIdCart($cart->id);
             $item->save();
            // $cart->setTotalPrice($_SESSION['total_price']);
             //$cart->save();
             return false;
         }else{
             $cart = new Cart();
             $cart->setIdUser($user['id']);
            // $cart->setTotalPrice($_SESSION['total_price']);
             $cart->save(); 
              $item = new CartItem();
             $item->import($mass);
             $item->setIdCart($cart->id);
             $item->save();
             return false;
         }
        
             
           //  return true;
    }
    public function allCart(){
        $da = new DateTime('-1 days');
        $date = $da->format('Y-m-d H:i:s');
       return  wsActiveRecord::useStatic('Cart')->findAll(['ctime <= "'.$date.'"']);
    }


    public function updateCart(){
         $this->setCtime(date("Y-m-d H:i:s"));
         $this->setCount(count($_SESSION['basket']));
         $this->setTotalPrice($_SESSION['total_price']);
         $this->save();
         wsActiveRecord::query("DELETE FROM `red_site`.`ws_cart_item` WHERE id_cart =".$this->id);
         foreach ($_SESSION['basket'] as $b) {
             $item = new CartItem();
             $item->import($b);
             $item->setIdCart($this->id);
             $item->save();
             
         }
         
    }
    

    public function clearCart(){
        if($this->id){
            //print_r($this);
            @$this->destroy();
            //die();
        }
        return false;
    }
    
    static function view_cart($id = false){
        $c = wsActiveRecord::useStatic('Cart')->findFirst(['id_user'=>$id]);
        $res = '<ul class="list-group" style="font-size: 12px;">';
        if($c->id and $c->count > 0){
        foreach ($c->item as $a) {
            $res.='<li class="list-group-item">';
            $res.='<a href="'.$a->article->getPath().'" target="_blank" class="img_pre" style="display: inline-block;">
                    <img src="'.$a->article->getImagePath("small_basket").'" />
                    </a>';
           /* $res.='<div class="simple_overlay" id="imgiyem'.$a->id.'" style="position: fixed;top: 20%;left: 30%; z-index:100">
                    <img src="'.$a->article->getImagePath("detail").'" />
                    </div>';*/
            $res.='<span style="display: inline-block;">'.$a->article->getTitle().'</span>';
            $res.='<div style="display: inline-block;"> Цвет: '.$a->colors->name.' | Размер: '.$a->sizes->size.' </div>';
            if($a->old_price>0){
                $price = $a->old_price;
            }else{
                $price = $a->article->price.'/'.$a->price.' ('.$a->skidka.')';
            }
            $res.='<div style="display: inline-block;"> Цена: '.$price.'</div>';
            $res.='</li>';
        }
        }else{$res.='<li class="list-group-item">На данный момент корзина пуста.</li>';}
        $res.='</ul>';
        return $res;
    }

    


    /**
     * 
     * @param type $customer_id
     * @return type
     */
    public static function getSumCard($customer_id){
        $sum_order = 0.00;
        foreach ($_SESSION['basket'] as  $item) {
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
			$sum_order += $article->getPriceSkidka($customer_id) * $item['count'];
		}	
	}
        return $sum_order;
    }
    /**
     * Товар
     * @param type $article
     * @param type $item
     * @param type $customer
     * @param type $sum_card
     * @return type
     */
    public static function getArticles($article, $item, $param = []){
        
                $skidka = 0;
                $kupon = 0;
                $event_skidka = 0;
                $now_orders = 0;
                $sum_order = 0;
                
                if(isset($param['skidka'])){
                     $skidka = $param['skidka'];
                }
                if(isset($param['kupon'])){
                  $kupon = $param['kupon'];
                }
                if(isset($param['event_skidka'])){
                  $event_skidka =  $param['event_skidka'];
                }
                if(isset($param['now_orders'])){
                   $now_orders = $param['now_orders'];
                }
                if(isset($param['sum_order'])){
                     $sum_order = $param['sum_order'];
                }
               /* if ($customer->getIsLoggedIn()) {
                    $skidka = $customer->getDiscont(false, 0, true);
                    $event_skidka = EventCustomer::getEventsDiscont($customer->getId());
                    $now_orders = $customer->getSumOrderNoNew();
                    }*/
                  // echo $event_skidka;
                   // echo $skidka;
                    
                    
                $arr = [];
        	$size = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $article->getId(), 'id_size' => $item['size'], 'id_color' => $item['color']]);             
        
                $price = $article->getPerc(($now_orders+$sum_order), $item['count'], $skidka, $event_skidka, $kupon, $sum_order);
                
                $arr['id'] =  $article->getId();
                $arr['path'] =  $article->getPath();
                $arr['img'] =  $article->getImagePath('listing');
                $arr['img_big'] =  $article->getImagePath('detail');
                $arr['title'] = htmlspecialchars($article->getTitle());
                //$arr['article_id'] =  $article->getId();
                $arr['color'] =  $size->color->name;
                $arr['size'] =  $size->size->size;
                $arr['size_count'] =  $size->count;
                $arr['comment'] =  $price['comment']?$price['comment']:'';
                $arr['first_price'] =  $article->getFirstPrice();
                //$arr['price'] =  ceil(($price['price']/$item['count']));
                $arr['price'] =  $price['price'];
                $arr['minus'] =  $price['minus'];
                $arr['count'] = $item['count'];
                $arr['skidka'] = ($arr['price']!=$arr['first_price'])?' -'.ceil(100- (($arr['price']/$arr['first_price'])*100)).'%':'';
                $arr['warning'] = self::getWarning($article->getCategoryId());
                $arr['option_id'] = $price['option_id']?$price['option_id']:0;
                $arr['option_price'] = $price['option_price']?$price['option_price']:0;
                $arr['skidka_block'] = $price['skidka_block']?$price['skidka_block']:0;
                
        return $arr;
    }
    /**
     * Предупреждение о не возврате белья
     * @param type $cat - id категории товара
     * @return boolean
     */
    public static function getWarning($cat = 0){
        $arr = [
            74,
            84,
            137,
            138,
            139,
            157, 
            158,
            249,
            140,
            163,
            306,
            297,
            307,
            296,
            3,
            314,
            317,
            316,
            315,
            357,
            158,
            55
        ];
        if (in_array($cat, $arr)) { return true; }
        return false;
    }
    
}
    

