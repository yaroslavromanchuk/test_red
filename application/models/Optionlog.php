<?php
class Optionlog extends wsActiveRecord
{
	protected $_table = 'ws_articles_option_log';
	//protected $_orderby = array('id'=>'DESC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array(
                    'options' => array(
                                        'type'=>'hasMany',
					'class'=>'Shoparticlesoption',
					'field_foreign'=>'id'
                                        ),
					);
	}

}
