<?php
class Shoparticlesoptions extends wsActiveRecord
{
	protected $_table = 'ws_articles_options';
	protected $_orderby = array('id'=>'ASC');
	
	protected function _defineRelations()
	{	
            $this->_relations = array(
                'option' => array(
                                'type'=>'hasOne',
                                'class'=>'Shoparticlesoption',
                                'field'=>'option_id'
                    ),
                                    );
	}
	
	/*public function getRealPrice() {
		return $this->getPrice();
	}*/
	
	
}
