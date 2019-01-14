<?php
class OrderHistory extends wsActiveRecord
{
    protected $_table = 'order_history';
    protected $_orderby = array('id' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
            'admin' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'customer_id'),
        );
    }

    static function getPaymentText($old_d, $old_t, $new_d, $new_t)
    {
        $text = 'C  "';
        $d = new DeliveryType($old_d);
        $text .= $d->getName() . ' - ';
        $t = new PaymentMethod($old_t);
        $text .= $t->getName() . '" ';
        $text .= ' на "';
        $d = new DeliveryType($new_d);
        $text .= $d->getName() . ' - ';
        $t = new PaymentMethod($new_t);
        $text .= $t->getName() . '"';

        return $text;
    }

    static function getStatusText($old, $new)
    {
        $trans = new Translator();
        $status = explode(',', $trans->get('new,processing,canceled,ready_shop,ready_post'));
        if($old == 100){ $old = 0; }
        
        $text = 'C "' . $status[$old] . '" ';
        $text .= ' на "' . $status[$new] . '"';

        return $text;
    }

    static function getOrderArticle($order_article_id, $size, $color, $count)
    {
        $article = new Shoporderarticles($order_article_id);
        $old_size = new Size($article->getSize());
        $old_color = new Shoparticlescolor($article->getColor());
        $text = 'C "' . $article->getTitle() . ' ' . $old_size->getSize() . ' ' . $old_color->getName() . '" ' . $article->getCount() . ' шт. ';
        $new_size = new Size($size);
        $new_color = new Shoparticlescolor($color);
        $text .= ' на "' . $article->getTitle() . ' ' . $new_size->getSize() . ' ' . $new_color->getName() . '" ' . $count . ' шт. ';

        return $text;

    }
    static function getNewOrderArticle($order_article_id, $mes=''){
         $article = new Shoporderarticles($order_article_id);
        $old_size = new Size($article->getSize());
        $old_color = new Shoparticlescolor($article->getColor());
		$text = '';
		if(strlen($mes) > 1){ $text .='('.$mes.') ';}
         $text .= '' . $article->getTitle() . ' ' . $old_size->getSize() . ' ' . $old_color->getName() . '';
        return $text;
    }

	static function getGoArticle($customer, $order, $orders, $title, $size, $color, $price, $article_id = 0){ 
			$old_size = new Size($size);
			$old_color = new Shoparticlescolor($color);
			$name = 'Перемещение товара с №'.$orders;
			$text = 'Товар '. $title . ' ' . $old_size->getSize() . ' ' . $old_color->getName().' '. $price .' грн.';
			
		$history = new OrderHistory();
        $history->setCustomerId($customer);
        $history->setOrderId($order);
        $history->setName($name);
        $history->setInfo($text);
        $history->setArticleId($article_id);
        $history->save();
        return true;
	
	}
    static function newHistory($customer, $order, $name, $text, $article_id = 0)
    {
        $history = new OrderHistory();
        $history->setCustomerId($customer);
        $history->setOrderId($order);
        $history->setName($name);
        $history->setInfo($text);
        $history->setArticleId($article_id);
        $history->save();
        return true;
    }
	static function newOrder($customer, $order, $sum_order = 0, $count_order = 0)
    {
        $history = new OrderHistory();
        $history->setCustomerId($customer);
        $history->setOrderId($order);
        $history->setName('Заказ оформлен');
       // $history->setInfo($text);
        //$history->setArticleId($article_id);
		$history->setSumOrder($sum_order);
		$history->setCountArticle($count_order);
        $history->save();
        return true;
    }
	static function cancelOrder($customer, $order, $sum_order = 0, $count_order = 0)
    {
        $history = new OrderHistory();
        $history->setCustomerId($customer);
        $history->setOrderId($order);
        $history->setName('Отмена заказа');
       // $history->setInfo($text);
        //$history->setArticleId($article_id);
		$history->setSumOrder($sum_order);
		$history->setCountArticle($count_order);
        $history->save();
        return true;
    }
	static function returnOrder($customer, $order, $sum_order = 0, $count_order = 0)
    {
        $history = new OrderHistory();
        $history->setCustomerId($customer);
        $history->setOrderId($order);
        $history->setName('Отмена заказа');
       // $history->setInfo($text);
        //$history->setArticleId($article_id);
		$history->setSumOrder($sum_order);
		$history->setCountArticle($count_order);
        $history->save();
        return true;
    }
}

