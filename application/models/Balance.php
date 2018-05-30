<?php

class Balance extends wsActiveRecord
{
		protected $_table = 'ws_balance';
		protected $_orderby = array('id' => 'ASC');
		
		 protected function _defineRelations()
    {

        $this->_relations = array(
            'category' => array(
                'type' => 'hasMany',
                'class' => 'BalanceCategory',
                'field_foreign' => 'id_balance',
                'orderby' => array('id' => 'ASC')
            ),
        );
    }
	 public function findId()
    {
        return $this->findFirst(array(), array('id' => 'DESC'));
    }



}