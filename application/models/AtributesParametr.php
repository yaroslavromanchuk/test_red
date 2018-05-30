<?php 
class AtributesParametr extends wsActiveRecord
{
protected $_table = 'ws_attributes_list'; 
	protected $_orderby = array('id' => 'DESC'); 
	
	/*public function getCountScan($e){
		
		return wsActiveRecord::useStatic('Scan')->count(array('cod' =>$e));
		
		}*/
}
?>