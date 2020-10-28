<?php
    class SearchLog extends wsActiveRecord
    {
        protected $_table = 'red_search_logs';
        protected $_orderby = array( 'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(	'customer' => array(
                                      'type' => 'hasOne',
                                      'class' => 'Customer',
                                      'field' => 'customer_id')
                );

        }
static function setToLog($search_world,$customer_id=0){

    $search = new SearchLog();
    $search->setSearch($search_world);
    $search->setCustomerId($customer_id);
    $search->save();
    return true;
}

    }
