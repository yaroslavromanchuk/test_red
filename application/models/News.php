<?php
class News extends wsActiveRecord
{
    protected $_table = 'ws_news';
    protected $_orderby = array( 'id' => 'DESC');


	protected function _defineRelations()
	{
		/*
		$this->_composed_of = array('created' => array('field' => 'ctime',
                                                    'class' => 'wsDate',
                                                    'getter' => 'getIsoDate'),
									'start_datetime' => array('field' => 'start_datetime',
                                                    'class' => 'wsDate',
                                                    'getter' => 'getIsoDate'),
									'end_datetime' => array('field' => 'end_datetime',
                                                    'class' => 'wsDate',
                                                    'getter' => 'getIsoDate')													
													);
		*/
	}

	public function getStatusText()
	{
		switch($this->getStatus()) 
		{
			case '0': return 'Архив'; //expire
			case '1': return 'Админы'; //active
			case '2': return 'Клиенты'; //archive
		}
	}


	public function getDate()
	{
		return date('d.m.Y', strtotime($this->getCtime()));
	}

	public function getDescriptionShort()
	{
		if(trim(strip_tags($this->intro)))
			return $this->intro;
		
		$sent = explode('. ', strip_tags($this->content));
		
		return trim(@$sent[0]);
	}

	public function isOnline()
	{
		/* !!! FIX IT
		//only if time checking is set
		if($this->getStartDatetime()->getStamp() != $this->getEndDatetime()->getStamp())
		{
			//before starting time
			if(time() < $this->getStartDatetime()->getStamp())
				return false;
			
			//after end time
			if(time() > $this->getEndDatetime()->getStamp())
				return false;
		}
		*/
		return true;
	}

	public function getPath()
	{
		return "/news/name/" . $this->_generateUrl($this->getTitle()) . "/id/" . $this->getId() .'/';
	}
	
	public static function findActiveNews($limit = 5)
	{
		$news = wsActiveRecord::useStatic('News')->findAll("status=0 AND (start_datetime = '0000-00-00 00:00:00' OR NOW() >= start_datetime) AND (end_datetime = '0000-00-00 00:00:00' OR NOW() <= end_datetime ) ", array(), $limit);

		return $news;
	}
}
?>