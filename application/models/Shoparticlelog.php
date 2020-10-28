<?php
    class Shoparticlelog extends wsActiveRecord
    {
        protected $_table = 'red_article_log';
        protected $_orderby = array('id' => 'DESC');

        protected function _defineRelations()
        {
            $this->_relations = array('article_db' => array(
                'type' => 'hasOne',
                'class' => self::$_shop_articles_class,
                'field' => 'article_id'),
                'admin' => array(
                    'type' => 'hasOne',
                    'class' => 'Customer',
                    'field' => 'customer_id'),
            );
        }


    }
