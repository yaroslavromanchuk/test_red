<?php

class wsDate
{
	protected $_time;
	
	public function __construct($time = 0)
	{
		if(!$time)
			$this->setTime(time());
		else
			$this->setTime($time);
	}

	public function setTime($time)
	{
		if(!is_numeric($time))
			$this->_time = strtotime($time);
		else
			$this->_time = strtotime(date('Y-m-d H:i:s',$time));
		return $this;
	}


	public function getStamp()
	{
		return $this->_time;
	}

	public function getFormattedMySQLDateTime()
	{
		return date("Y-m-d H:i:s", $this->getStamp());
	}
	
	public function getFormattedDateTime()
	{
		return date("d-m-Y H:i", $this->getStamp());
	}
	
	public function getFormattedDate()
	{
		return date("d-m-Y", $this->getStamp());
	}
	
	public function getUnixDateTime()
	{
		return $this->getStamp();
	}
	
	public function getHour()
	{
		return date('H', $this->getStamp());
	}
	
	public function getMinute()
	{
		return date('i', $this->getStamp());
	}

	public function getSecond()
	{
		return date('s', $this->getStamp());
	}
	
	public function compare($date_obj)
	{
		if(!is_object($date_obj))
			$date_obj = new wsDate($date_obj);
			
		if($this->getStamp() > $date_obj->getStamp())
			return 1;
			
		if($this->getStamp() < $date_obj->getStamp())
			return -1;
			
		return 0;
	}
}
?>