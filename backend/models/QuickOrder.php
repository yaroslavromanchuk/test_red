<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuickOrder
 *
 * @author PHP
 */
class QuickOrder extends wsActiveRecord
{
    public static  function listOrder() {
        
        return ;
    }
    public static  function otmena($order) {
        foreach ($order->articles as $art) {
            $article = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array('id_article' => $art->getArticleId(), 'id_size' => $art->getSize(), 'id_color' => $art->getColor()));
		if ($article) {
				$artic = new Shoparticles($art->getArticleId());
                                $pos = strpos($article->code, 'SR');
                                if($pos === false){
                                    if($article->getCount() == 0 and $artic->getCategoryId() != 16){
					if(wsActiveRecord::useStatic('Returnarticle')->count(array('code' => $article->getCode(), 'utime is null')) > 0){
					$this->sendMailAddCount($article->getCode(), $article->getIdArticle());
                                            }	
                                    }
					
					$article->setCount($article->getCount() + $art->getCount());
					$artic->setStock($artic->getStock() + $art->getCount());
                                        }
					$article->save();
					$artic->save();	
					$art->setCount(0);
                                        $art->save();
					}
                   
                }
               // $deposit = $order->getDeposit();
                $order->setStatus(2);
              //  $order->setDeposit(0);
                $order->save();		
                
        return  true;
    }
    public static function toOrder($order = false) {
                $order->setQuick(0);
                $order->setDateCreate(date('Y-m-d H:i:s'));
                $order->save();
        return true;
    }
    public static  function addRemark($order, $comment = '', $name = '') {
                $data = [
                    'order_id' => $order->getId(),
                    'date_create' => date("Y-m-d H:i:s"),
                    'remark' => $comment,
                    'name' => $name
                ];
                $remark = new Shoporderremarks();
                $remark->import($data);
                $remark->save();
        return true;
    }
    
}
