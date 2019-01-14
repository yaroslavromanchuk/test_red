<?php
    class Shoparticlessize extends wsActiveRecord
    {
        protected $_table = 'ws_articles_sizes';
        protected $_orderby = array( 'count' => 'DESC');


        protected function _defineRelations()
        {
            $this->_relations = [
                                    'size' => ['type'=>'hasOne', 'class'=>'Size', 'field'=>'id_size'], 
                                    'color' => ['type'=>'hasOne', 'class'=>'Shoparticlescolor', 'field'=>'id_color'],
                                    'article_rod' => ['type'=>'hasOne', 'class'=>self::$_shop_articles_class, 'field'=>'id_article']									       
                                ];

        }
    }
