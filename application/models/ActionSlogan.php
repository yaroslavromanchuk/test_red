<?php
    class ActionSlogan extends wsActiveRecord
    {
        protected $_table = 'red_action_slogans';
        protected $_orderby = array( 'status'=>'ASC' ,'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(	'score' => array(
										'type'=>'hasMany',
										'class'=>'ActionSloganscore',
										'field_foreign'=> 'slogan_id',
                                        'orderby'=> array('id' => 'ASC'),
                                        'onDelete'=> 'delete')
                );

        }
 public function isScore(){
     $score = wsActiveRecord::useStatic('ActionSloganscore')->findFirst(array('ip'=>$_SERVER['REMOTE_ADDR'],'slogan_id'=>$this->id));
     if($score) return false;
     else return true;
 }


    }
?>