<?php
class Field extends wsActiveRecord
{
	protected $_table = 'ws_fields';
	protected $_orderby = array('sequence'=>'ASC');
        
        public static function poblagodarit_pojaluvatsa(){
            
            return wsActiveRecord::useStatic('Field')->findAll(['form'=>'poblagodarit_pojaluvatsa']);
        }
        
}
