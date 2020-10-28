<?php
class RedCoinStatus extends wsActiveRecord
{
	protected $_table = 'ws_red_coin_status';
	protected $_orderby = array('id' => 'ASC');
	
    protected function _defineRelations()
    {
        $this->_relations = [
            'coin' => [
                'type' => 'hasMany',
		'class' => 'RedCoin',
		'field_foreign' => 'status',
                ]
        ];
    }


}