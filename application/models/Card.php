<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Card
 *
 * @author PHP
 */
class Card extends wsActiveRecord
{
    public static function getSumCard($customer_id){
        $sum_order = 0.00;
        foreach ($_SESSION['basket'] as  $item) {
		if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
			$sum_order += $article->getPriceSkidka($customer_id) * $item['count'];
		}	
	}
        return $sum_order;
    }
    public static function getArticles($article, $item, $customer, $sum_card = 0){
        
                $skidka = 0;
                $kupon = 0;
                $event_skidka = 0;
                $now_orders = 0;
                
                if ($customer->getIsLoggedIn()) {
                    $skidka = $customer->getDiscont(false, 0, true);
                    $event_skidka = EventCustomer::getEventsDiscont($customer->getId());
                    $now_orders = $customer->getSumOrderNoNew();
                    }
                $arr = [];
        	$size = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['id_article' => $article->getId(), 'id_size' => $item['size'], 'id_color' => $item['color']]);             
        
                $price = $article->getPerc(($now_orders+$sum_card), $item['count'], $skidka, $event_skidka, $kupon, $sum_card);
                
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
                $arr['price'] =  ($price['price']/$item['count']);
                $arr['minus'] =  $price['minus'];
                $arr['count'] = $item['count'];
                $arr['skidka'] = ($arr['price']!=$arr['first_price'])?' -'.ceil(100- ((($arr['price']/$item['count'])/$arr['first_price'])*100)).'%':'';
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
            3 
        ];
        if (in_array($cat, $arr)) { return true; }
        return false;
    }
    
}
    

