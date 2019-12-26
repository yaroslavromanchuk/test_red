<?php
class LiqPayStatus extends wsActiveRecord
{
 protected $_table = 'ws_liqpay_status';
 protected $_orderby = array('id' => 'DESC');
	protected $_multilang = array('name' => 'name');

    protected function _defineRelations()
    {
        $this->_relations = array(
            
        );
    }
	

}
