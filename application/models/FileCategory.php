<?php
class FileCategory extends wsActiveRecord
{
	protected $_table = 'file_categories';
	protected $_orderby = array('name' => 'ASC');

	protected function _defineRelations()
	{	
		$this->_relations = array('files' => array('type' => 'hasMany',
													'class' => 'wsFile',
													'field_foreign' => 'category_id'),												
								);
	}
	
	public function getPath()
	{
		return "/gallery/category/name/" . $this->_generateUrl($this->getName()). "/";
	}	
	
}
?>