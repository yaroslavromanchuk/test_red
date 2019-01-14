<?php 
class Scan extends wsActiveRecord
{
protected $_table = 'ws_scan'; 
	protected $_orderby = array('id' => 'DESC'); 
	
	public function getCountScan($e){
		
		return wsActiveRecord::useStatic('Scan')->count(array('cod' =>$e));
		
		}
}
