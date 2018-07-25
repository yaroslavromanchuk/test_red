<?php
    class Other extends wsActiveRecord
    {
        protected $_table = 'ws_other_code';
        protected $_orderby = array( 'id' => 'DESC');
		
		protected function _defineRelations() {

		}
		static function findByCode($cod)
    {
	$today_c = date("Y-m-d H:i:s");
    	return wsActiveRecord::useStatic('Other')->findFirst(array('cod = "'.(string)$cod.'" and "'.$today_c.'" <= utime'));
    }

    }
?>