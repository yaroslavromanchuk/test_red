<?php
class HomeBlock extends wsActiveRecord
{
	protected $_table = 'red_home_blocks';
	protected $_orderby = array('date' => 'DESC');
        protected $_multilang = array('name' => 'name', 'image' => 'image');
	
	protected function _defineRelations()
	{	
		$this->_relations = array();
	}
 public function _beforeDelete()
    {

        @unlink($_SERVER['DOCUMENT_ROOT'].$this->getImage());
        return true;
    }
    public function getBlockText(){
        switch($this->getBlock()){
			case 6: return 'Большой баннер';
			case 2: return 'Женская одежда';
            case 1: return 'Аксессуары';
            case 5: return 'Текстиль';
            case 4: return 'Мужская одежда';
            case 3: return 'Нижний 2';
            
            
            
        }
    }
}
