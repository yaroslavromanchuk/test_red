<?php

class Timer
{
	private $s;
	private $p = 0;
	
	private function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	public function start()
	{
		$this->s = $this->getmicrotime();
	}
	
	public function pause()
	{
		$this->p = $this->getmicrotime();
	}
	
	public function unpause()
	{
		$this->s += ($this->getmicrotime() - $this->p);
		$this->p = 0;
	}
	
	public function fetch($decimalPlaces = 3)
	{
		return round(($this->getmicrotime() - $this->s), $decimalPlaces);
	}
}

?>