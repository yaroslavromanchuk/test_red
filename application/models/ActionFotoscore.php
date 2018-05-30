<?php
    class ActionFotoscore extends wsActiveRecord
    {
        protected $_table = 'red_action_fotoscores';
        protected $_orderby = array( 'id' => 'ASC');


      protected function _defineRelations()
        {
            $this->_relations = array(	'foto' => array(
										'type'=>'hasOne',
										'class'=>'ActionFoto',
										'field'=>'image_id'));

        }
    }
?>