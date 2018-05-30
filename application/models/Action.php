<?php
    class Action extends wsActiveRecord
    {
        protected $_table = 'red_actions';
        protected $_orderby = array( 'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(	'fotos' => array(
										'type'=>'hasMany',
										'class'=>'ActionFoto',
										'field_foreign'    => 'action_id',
                                        'orderby'        => array('id' => 'ASC'),
                                        'onDelete'        => 'delete')
                );

        }
    static function getRealActions(){
        return wsActiveRecord::useStatic('Action')->findAll(array('archive'=>0));
    }
     static function getArchiveActions(){
        return wsActiveRecord::useStatic('Action')->findAll(array('archive'=>1));
    }



    }
?>