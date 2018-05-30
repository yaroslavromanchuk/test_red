<?php

class Importgeneric extends ImportAbstract {

	public $all_tables = array();
	public $model = '';
	
	protected $_m;
	protected $_unique;


	public function __construct($model, $filename='') {
		$this->model = $model;
		$this->_m = new $model();
		$this->all_tables = array($this->_m->getTable());
		parent::ImportAbstract('./tmp/');
		if($filename)
			$this->setFilename($filename);	
		ini_set('auto_detect_line_endings',true);	
	}
	
	public function setUnique($field) {
		$this->_unique = $field;
	}

	public function prepareImport()
	{
		//set default file path
		$this->time_start = microtime(true);
		$this->total = 0;
		$this->insert = 0;
		$this->update = 0;
		$this->no_update = 0;
		
		return 1;
	}
	
	/**
	 * Process one row from import file
	 *
	 * @param array $data
	 * @return int  0 or 1
	 */
	protected function importRow($columns)
	{	
		$columns = $this->num2assoc($columns);
		foreach($columns as $key=>$value) {
			if(!$this->isUtf8($value))
				$columns[$key] = iconv('WINDOWS-1251', 'UTF-8', $value);
		}
		$m = null;
		//insert or update?
		if(!$this->_unique)
			$this->_unique = 'Id';

		if(isset($columns[$this->_unique]))
			$m = wsActiveRecord::useStatic($this->model)->{"findBy". $this->_m->_implodeCase($this->_unique)}($columns[$this->_unique])->at(0);
		
		if(!$m)
			$m = new $this->model();
		
		$m->import($columns);
		
		//counters
		if($m->isDirty()) {
			if($m->getId())
				$this->update++;
			else
				$this->insert++;
		} else
			$this->no_update++;
		
		$m->save();	
		unset($m);
		
		$resultmsg = '';
		$resultmsg .= 'Execution time: ' . round(microtime(true) - $this->time_start, 2) . "s\n";
		$resultmsg .= 'Memory used: ' . number_format(memory_get_usage() / 1024 / 1024, 3, '.', ' ') . "Mb\n";
		$resultmsg .= 'Inserted entries: ' . $this->insert . "\n";
		$resultmsg .= 'Updated entries: ' . $this->update . "\n";		
		$resultmsg .= 'No update entries: ' . $this->no_update . "\n\n";
		$resultmsg .= 'Total rows processed: ' . $this->total . "\n";
		$this->success = $resultmsg;		
	}
	
	public function isUtf8($str) {
		if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
			return true;
		return false;
	}
	
		
	}
?>