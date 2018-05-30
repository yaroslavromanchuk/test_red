<?php
class Shoparticlestop extends wsActiveRecord
{
	protected $_table = 'ws_articles_top';
	protected $_orderby = array('sequence'=>'DESC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array('article' => array('type' => 'hasOne',
													'class' => self::$_shop_articles_class,
													'field' => 'article_id')
													);
	}
	
	public function findLastSequenceRecord() {
		return $this->findFirst(array(), array('sequence'=>'DESC'));
	}
	
	public function findMaxSequence() {
		if ($tmp = $this->findLastSequenceRecord())
			return $tmp->getSequence();
		else
			return 0;
	}
}
?>