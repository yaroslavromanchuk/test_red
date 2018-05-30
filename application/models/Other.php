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

/*
    static function getRealActions(){
        return wsActiveRecord::useStatic('Action')->findAll(array('archive'=>0));
    }
     static function getArchiveActions(){
        return wsActiveRecord::useStatic('Action')->findAll(array('archive'=>1));
    }
*/


    }
?>