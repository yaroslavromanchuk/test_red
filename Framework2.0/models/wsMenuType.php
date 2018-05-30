<?php
class wsMenuType extends wsActiveRecord
{
	protected $_table = 'ws_menu_types';
	protected $_orderby = array('id' => 'ASC');


	protected function _defineRelations()
	{	
		$this->_relations = array('menus' => array('type' => 'hasMany',
													'class' => self::$_menu_class,
													'field_foreign' => 'type_id',
													'onDelete' => 'null'));   
	}
}
?>