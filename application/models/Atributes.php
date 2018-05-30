<?php 
class Atributes extends wsActiveRecord
{
protected $_table = 'ws_attributes'; 
	protected $_orderby = array('sort' => 'DESC'); 
	
	/*public function getCountScan($e){
		
		return wsActiveRecord::useStatic('Scan')->count(array('cod' =>$e));
		
		}*/
}
?>