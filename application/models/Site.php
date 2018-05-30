<?php
class Site extends wsSite
{
	

	public function getPath()
	{
		return Registry::get('lang')!=Registry::get('default_language')? '/' . Registry::get('lang') : '';
	}
		
	
}
?>