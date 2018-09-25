<?php
class Desires extends wsActiveRecord
{
	protected $_table = 'ws_desires';
	protected $_orderby = array( 'id' => 'DESC');
	
        protected function _defineRelations()
    {
            $this->_relations = [
                'articles' => ['type' => 'hasMany',
                                'class' => self::$_shop_articles_class,
				'field_foreign' => 'id_articles',
				'orderby' => ['id' => 'DESC'],
                    ]
            ];
    }
}
