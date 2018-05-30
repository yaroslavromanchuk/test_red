<?php

class ImportAbstract
{
	/**
	 * @var SimpleDB
	 */
	protected $_db;
	protected $_filename = '';
	protected $_log = null;
	protected $_folder = '';
	protected $_elementDelimiter = "\t";
	protected $_startFrom = 0;
	protected $_report_per_lines = 100;
	protected $_days_to_keep = 30;
	protected $_no_header = 0;

	public $errors = array();
	public $success = '';
	public $total = 0;
	
	public $all_tables = array();


	/**
	 * Constructor
	 *
	 * @param string path to import file
	 * @return int
	 */
	public function ImportAbstract($folder)
	{
		GLOBAL $e;

		$this->_db = SimpleDB::getInstance();
		$this->_folder = $folder;
		$e = 0;
	}

	/**
	 * Main import function
	 *
	 * @return int true on success
	 */
	public function import()
	{
		GLOBAL $e;

		set_time_limit(600);

		$contents = '';
		if(!$this->_filename)
			return $this->_error('Import file name is missing');
		$fname = $this->_folder . $this->_filename;

		$handle = @fopen($fname, 'r');
		if(!$handle)
			return $this->_error('Error openning file ' . $fname);

		if (!$this->_no_header)
		{
			//remove header
			$this->headers = fgetcsv($handle, 10000, $this->_elementDelimiter, '"');
			$this->headers = array_map('strtolower',$this->headers);
			$this->headers = array_map('trim',$this->headers);
		}
		if(!$this->prepareImport())
			return $this->_error('Init failed');

		//created tables backup
		if(count($this->all_tables))
		foreach($this->all_tables as $table)
		{
			$d = date('Ymd');
			$oldd = date('Ymd',strtotime("-{$this->_days_to_keep} days"));
			
			$q = "DROP TABLE `{$table}_{$oldd}`";
			$this->_db->executeQuery($q);
			
			$q = "CREATE TABLE {$table}_{$d} SELECT * FROM $table";
			$this->_db->executeQuery($q);			
		}

		$this->beforeImport();
		
		//go through all lines
		while (($columns = fgetcsv($handle, 10000, $this->_elementDelimiter, '"')) !== FALSE)
		{
			@set_time_limit(5);
			//parse line by columns
			//$columns = explode($this->_elementDelimiter,trim($line));
			
			if(! ($this->total % $this->_report_per_lines))
			{
				echo str_pad("Processing rows " . ($this->total+1) . ' - ' . ($this->total + $this->_report_per_lines) . "<br>\n",4096);
				@ob_flush();
				flush();
			}
			
			$this->total++;


			if (!is_array($columns) or !count($columns))
			{
				continue;
			}

			//randomize SQL log
			$e = mt_rand(0,100);

			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/stop.now')) die('Termination');

			if($this->total >= $this->_startFrom)
				$this->importRow($columns);

			if(!$e)
				$this->_writeLog($this->success);
		}

		$this->finishImport();
		
		@fclose($handle);
		
		//optimize tables
		if(count($this->all_tables))
		foreach($this->all_tables as $table)
		{
			$q = "OPTIMIZE TABLE `$table`";
			$this->_db->executeQuery($q);
		}

		return true;
	}

	/**
	 * Set log file name
	 *
	 * @param string full path
	 * @return int
	 */
	public function setLogfile($log)
	{
		$this->_log = $log;

		return 1;
	}


	/**
	 * Set file to import
	 *
	 * @param string filename
	 * @return int true is file exists
	 */
	public function setFilename($fname)
	{
		if(file_exists($this->_folder.$fname))
			$this->_filename = $fname;
		else
			return false;

		return true;
	}

	/**
	 * Set folder were import files
	 *
	 * @param string name of folder
	 * @return int true is folder exists
	 */
	public function setFolder($fname)
	{
		if(file_exists($fname))
			$this->_folder = $fname;
		else
			return false;

		return true;
	}

    /**
     *  Set element delimiter
     *
     *  @param string $delimiter
     */
    public function setElementDelimiter($delimetr)
    {
    	if(!$delimetr) return false;
        $this->_elementDelimiter = $delimetr;
        return true;
    }

	/**
	 * This will be called before import - to clear and prepare inner vars
	 *
	 * @return int  1
	 */
	protected function prepareImport()
	{
		return 1;
	}

	/**
	 * This will be called after import
	 *
	 * @param array $data
	 * @return int  0 or 1
	 */

	protected function finishImport()
	{
		return 1;
	}

	/**
	 * This will be called before import rows
	 *
	 * @param array $data
	 * @return int  0 or 1
	 */

	protected function beforeImport()
	{
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
		die('Import row not defined');
	}

	/**
	 * Write data to log if it is defined
	 *
	 * @param string lines to write
	 * @return int
	 */
	protected function _writeLog($lines)
	{
		if($this->_log)
			@file_put_contents($this->_log,$lines);

		return 1;
	}

	/**
	 * Add error to global errors
	 *
	 * @param string error string to add
	 * @return int always false
	 */
	protected function _error($str='')
	{
		$this->errors[] = $str;

		return false;
	}


	protected function num2assoc($data)
	{
		$i = 0;
		$row = array();
		
		foreach($data as $key=>$value)
		{
			$nkey = @trim($this->headers[$key]);
			if(!$nkey) $nkey = $key;
			//if($value) 
				$row[$nkey] = $value;
		}

		return $row;
	}

	public function setNoHeader()
	{
		$this->_no_header = 1;
		return true;
	}


}


if( !function_exists('memory_get_usage') )
{
    function memory_get_usage()
    {
 		return 0;
    }
}

?>