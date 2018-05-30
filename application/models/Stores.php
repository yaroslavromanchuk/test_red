<?php 
class Stores extends wsActiveRecord
{
protected $_table = 'ws_stores'; 
	protected $_orderby = array('id' => 'DESC'); 
	
	public function getAllSrores(){
		
		return wsActiveRecord::useStatic('Stores')->findAll(array('active' => 1));
		
		}
}
?>