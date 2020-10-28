<?php
class ShoporderarticlesVozrat extends wsActiveRecord
{
    protected $_table = 'ws_order_articles_vozrat';
    protected $_orderby = array('id' => 'ASC');

    protected function _defineRelations()
    {
        $this->_relations = array(
            'article_db' => array(
            'type' => 'hasOne',
            'class' => self::$_shop_articles_class,
            'field' => 'article_id'),
            'order' => array(
                'type' => 'hasOne',
                'class' => 'ShopordersVozrat',
                'field' => 'order_id'),
            'order' => array(
                'type' => 'hasOne',
                'class' => 'Shoporders',
                'field' => 'order_id')
            );
    }
    static function isArticleVozvat($article){
        $find = wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findFirst(array('old_article'=>$article));
        if($find){
            return true;
        } else {
            return false;
        }
    }



}
