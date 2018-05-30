<?php
class Shoparticlesoffer extends wsActiveRecord
{
	protected $_table = 'ws_articles_offer';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array('article' => array('type'=>'hasOne',
													'class'=>self::$_shop_articles_class,
													'field'=>'article_id')
													);
	}
	
	public function deleteCurImages() {
		$filename = INPATH."files/i4/".$this->getImage();
		if (is_file($filename))
			@unlink($filename);
	}
	
	public function getImagePath() {
		return SITE_URL . '/files/i4/' . $this->getImage();
	}
	
	static public function findOne() {
		//return self::findFirst();
		return wsActiveRecord::useStatic('Shoparticlesoffer')->findFirst();
	}
	
}
?>