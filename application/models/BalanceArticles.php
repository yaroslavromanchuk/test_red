<?php

class BalanceArticles extends wsActiveRecord
{

		protected $_table = 'ws_balance_articles';
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
            'article' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticles',
                'field' => 'id_article'
                ),
        );
    }
    
	
    /*	public function getCountBrand($id)
    {
	$sql = "SELECT SUM(`count`) AS ctn FROM  `ws_balance_category` WHERE  `id_balance` = ".$id." AND `id_brand` =".$this->id_brand;
     return $this->findByQuery($sql)->at(0)->getCtn();
    }
     */
}