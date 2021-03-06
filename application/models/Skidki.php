<?php
class Skidki extends wsActiveRecord
    {
        protected $_table = 'article_skidka';
        protected $_orderby = array('start' => 'DESC');

        protected function _defineRelations()
        {
            $this->_relations = array(
			'article' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticles',
                'field' => 'article_id'),
			'category_skidka' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticles',
                'field' => 'category_id'),
            'customer' => array(
                    'type' => 'hasOne',
                    'class' => 'Customer',
                    'field' => 'customer_id'),
            );
        }

        static function getActiv($article_id)
        {
		$article_id = (int)$article_id;
		$a = wsActiveRecord::useStatic('Skidki')->findFirst(array(' article_id = '.$article_id.' and publish = 1 and start <= "' . date('Y-m-d H:i:s') . '" and  "' . date('Y-m-d H:i:s') . '" <= finish '));
            return $a;
        }

        static function getAllActiv($article_id)
        {
            return wsActiveRecord::useStatic('Skidki')->findAll(array('article_id' => $article_id, 'publish' => 1, 'start <= "' . date('Y-m-d H:i:s') . '"', 'finish >= "' . date('Y-m-d H:i:s') . '"'));
        }
		
		static function getActivCat($category_id){
		return wsActiveRecord::useStatic('Skidki')->findFirst(array("category_id = ".$category_id." and publish = 1 and start <= '". date('Y-m-d H:i:s') ."' and  '". date('Y-m-d H:i:s') ."' <= finish"));
		}

    }