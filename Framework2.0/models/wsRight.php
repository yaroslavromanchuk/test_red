<?php
class wsRight extends wsActiveRecord
{
    protected $_table = 'ws_customer_rights';
	protected $_orderby = array('sequence' => 'ASC');


	protected function _defineRelations()
	{

	}

	static public function findByIds($ids)
	{
		return wsActiveRecord::useStatic(self::$_right_class)->findAll("id IN (" . implode(",", $ids) . ")");
	}
}
?>