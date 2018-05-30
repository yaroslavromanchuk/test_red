<?php
    class Returnarticle extends wsActiveRecord
    {
        protected $_table = 'ws_return_article';
        protected $_orderby = array( 'id' => 'ASC');
		
		    protected function _defineRelations()
    {

        $this->_relations = array(
            'return_article' => array(
                'class' => 'Returnarticle',
                'field_foreign' => 'id'
            ),

        );


    }
    }
?>