<?php 
class Shoporderstatuses extends wsActiveRecord
{
protected $_table = 'ws_order_statuses';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
        {
            $this->_relations = [
                'group_name' => [
                        'type' => 'hasOne',
                        'class' => 'OrderStatusesGroup',
                        'field' => 'group'
                    ]
            ];
        }
}
