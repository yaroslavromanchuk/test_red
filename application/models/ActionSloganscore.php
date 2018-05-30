<?php
    class ActionSloganscore extends wsActiveRecord
    {
        protected $_table = 'red_action_sloganscores';
        protected $_orderby = array( 'id' => 'ASC');


      protected function _defineRelations()
        {
            $this->_relations = array(	'slogan' => array(
										'type'=>'hasOne',
										'class'=>'ActionClogan',
										'field'=>'slogan_id'));

        }
    }
?>