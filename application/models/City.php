<?php
class City extends wsActiveRecord
{
    protected $_table = 'ws_city_np'; 
	protected $_orderby = array('name' => 'ASC'); 
	protected $_multilang = array('name' => 'name');

	protected function _defineRelations()
	{	
		$this->_relations = array(); 
	}
	
	public function listcity($city){
	$date = 'name LIKE "' . mysql_real_escape_string($city) . '%" or name_uk LIKE "' . mysql_real_escape_string($city) . '%"';

            $find = wsActiveRecord::useStatic('City')->findAll(array('name LIKE"'.mysql_real_escape_string($city).'%" or name_uk LIKE"'.mysql_real_escape_string($city).'%"'));
			$mas = array();
		$i = 0;
		$l='';
		if($_SESSION['lang'] == 'uk') $l = '_uk';
		foreach ($find as $c) {
		$mas[$i]['label'] = @$l ? $c->name_uk : $c->name;
		$mas[$i]['value'] = @$l ? $c->name_uk : $c->name;
		$mas[$i]['id'] = $c->uid; 
		$i++;
			}
			
			return $mas;
	
	}

}
