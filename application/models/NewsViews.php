<?php 
class NewsViews extends wsActiveRecord
{
	protected $_table = 'ws_news_views';
    protected $_orderby = array( 'id' => 'DESC');
	
	public function getViews($id)
	{
	//$news = wsActiveRecord::useStatic('News')->findFirst(array('id_news'=>, 'user'=>$this->user->getId()))->getFlag();
	
	//if($news) return $news;
	
	//return ;
	}
}
?>