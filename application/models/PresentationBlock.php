<?php
class PresentationBlock extends wsActiveRecord
{
	protected $_table = 'ws_presentation_blocks';
	protected $_orderby = array('id' => 'ASC ');

	
	protected function _defineRelations()
	{	
		$this->_relations = array(); 
	}
 public function _beforeDelete()
    {

        @unlink($_SERVER['DOCUMENT_ROOT'].$this->getImage());
        return true;
    }
    public function getBlockPresentationText(){ 
        switch($this->getBlock()){
            case 1: return 'первый';
            case 2: return 'второй';
            case 3: return 'тритий';
            case 4: return 'четвертый';
            case 5: return 'пятый';
            case 6: return 'шестой';
			case 7: return 'седьмой';
			case 8: return 'восьмой';
			case 9: return 'девятый';
			case 10: return 'десятый';
			case 11: return 'одиннадцатый';
			case 12: return 'двеннадцатый';
			case 13: return 'тринадцатый';
        }
    }
}
?>