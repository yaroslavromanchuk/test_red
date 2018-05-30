<?php
class News extends wsActiveRecord
{
    protected $_table = 'ws_photos';
    protected $_orderby = array( 'id' => 'DESC');


/*
	public function getPath()
	{
		return "/news/name/" . $this->_generateUrl($this->getTitle()) . "/" . $this->getId() .'/';
	}

	
	public static function findActiveNews()
	{
		$news = wsActiveRecord::useStatic('News')->findAll("status=0 AND (start_datetime = '0000-00-00 00:00:00' OR NOW() >= start_datetime) AND (end_datetime = '0000-00-00 00:00:00' OR NOW() <= end_datetime ) ");

		return $news;
	}
*/
}
?>