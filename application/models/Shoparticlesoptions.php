<?php
class Shoparticlesoptions extends wsActiveRecord
{
	protected $_table = 'ws_articles_options';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array('article' => array('type'=>'hasOne',
													'class'=>self::$_shop_articles_class,
													'field'=>'article_id')
													);
	}
	
	public function getRealPrice() {
		return $this->getPrice();
	}
}
?>