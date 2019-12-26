<?php
class LiqPayHistory extends wsActiveRecord
{
 protected $_table = 'ws_liqpay_history';
    protected $_orderby = array('id' => 'DESC');

    protected function _defineRelations()
    {
        $this->_relations = array(
		'liqpay_status_name' => array(
				'type' => 'hasOne',
				'class' => 'LiqPayStatus',
				'field' => 'status_id'
			)
            
        );
    }
	static function newHistory($id, $status, $error){
	$h = new LiqPayHistory();
	$h->setOrderId($id);
	$h->setStatusId($status);
	$h->setError($error);
	$h->save();
	
	}
	
	
	

}
