<?php
class OptionLog extends wsActiveRecord
{
	protected $_table = 'ws_articles_option_log';
	protected $_orderby = array('ctime'=>'DESC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array(
                   /* 'options' => array(
                                        'type'=>'hasMany',
					'class'=>'Shoparticlesoption',
					'field_foreign'=>'id'
                                        ),*/
                'admin' => array(
                'type' => 'hasOne',
                'class' => 'Customer',
                'field' => 'admin_id'),
					);
	}
        public static function add($param = []){
            $add = new OptionLog();
            $add->import($param);
            $add->save();
            return true;
        }

}
