<?php
class News extends wsActiveRecord
{
    protected $_table = 'ws_news';
    protected $_orderby = array( 'id' => 'DESC');

        /**
         * Возвращает статус акции
         * 
         * @return string
         */
	public function getStatusText()
	{
		switch($this->getStatus()) 
		{
			case '0': return 'Архив'; //expire
			case '1': return 'Админы'; //active
			case '2': return 'Клиенты'; //archive
		}
	}

        /**
         * Конвертация формата даты 1999-01-01 00:00:00 -> 01.01.1999
         * @return string
         */
	public function getDate()
	{
		return date('d.m.Y', strtotime($this->getCtime()));
	}
        
        /**
         * Возвращает описание акции
         * @return srting
         */
	public function getDescriptionShort()
	{
		if(trim(strip_tags($this->intro))){return $this->intro;}
		
		$sent = explode('. ', strip_tags($this->content));
		
		return trim($sent[0]);
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
        
        
        /**
         * Ссылка на акцию
         * 
         * @return string
         */
	public function getPath()
	{
		return "/news/name/" . $this->_generateUrl($this->getTitle()) . "/id/" . $this->getId() .'/';
	}
        
      
        
	/**
         * Список активных акций
         * @param type $limit - количество возвращаемых записей, по умолчанию 5
         * @return array
         */
	public static function findActiveNews($limit = 5)
	{
		$news = wsActiveRecord::useStatic('News')->findAll("status=0 AND (start_datetime = '0000-00-00 00:00:00' OR NOW() >= start_datetime) AND (end_datetime = '0000-00-00 00:00:00' OR NOW() <= end_datetime ) ", array(), $limit);

		return $news;
	}
}
