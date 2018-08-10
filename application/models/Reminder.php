<?php 
class Reminder extends wsActiveRecord
{
protected $_table = 'ws_articles_reminder_admin'; 
	protected $_orderby = array('id' => 'DESC'); 
	
	protected function _defineRelations()
    {
        $this->_relations = array(
		'customer' => array('type' => 'hasOne', //belongs to
            'class' => self::$_customer_class,
            'field' => 'admin_id'),
		);
		}
	
	public function getArticleActiv($article_id = false){
		
		return wsActiveRecord::useStatic('Reminder')->findAll(array('article_id' =>$article_id, 'flag'=>0));
		
		}
}
?>