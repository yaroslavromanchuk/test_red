<?php

class BalanceCategory extends wsActiveRecord
{

		protected $_table = 'ws_balance_category';
		protected $_orderby = array('id' => 'ASC');
		
		 protected function _defineRelations()
    {

        $this->_relations = array(
            'balance' => array(
                'type' => 'hasOne',
                'class' => 'Balance',
                'field_foreign' => 'id_balance',
                'orderby' => array('id' => 'ASC')
            ),
            'article_brand' => array(
                'type' => 'hasOne',
                'class' => 'Brand',
                'field' => 'id_brand'),
			'category_name' => array(
                'type' => 'hasOne',
                'class' => 'Shopcategories',
                'field' => 'id_category',
                'orderby' => array('id' => 'ASC')
            ),
        );
    }
	
	public function getCountBrand($id)
    {
	//d($this->id_balance, false);
	//d($this->id_brand, false);
	$sql = "SELECT SUM(`count`) AS ctn FROM  `ws_balance_category` WHERE  `id_balance` = ".$id." AND `id_brand` =".$this->id_brand;
     $c =  $this->findByQuery($sql)->at(0)->getCtn();
	 // d($sql, false);
		 return $c;
    }
}