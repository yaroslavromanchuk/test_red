<?php
class Poll extends wsActiveRecord{
	protected $_table = 'poll';
	protected $_orderby = array('active'=>'DESC','date' => 'DESC');
	protected function _defineRelations()
	{
		$this->_relations = array(
			'questions' => array('type'=>'hasMany',
							   'class'=>'PollQuestions',
							   'field_foreign'=>'poll_id',
							   'onDelete' => null
	                   )
		);
	}

	public static function drawLastPoll($customerId = null){
		$view = Registry::get('View');
		$poll = wsActiveRecord::useStatic('Poll')->findFirst( array ('active'=>1));
		if ($poll){
			//search user id base with current Poll  // NULL id for open . but need add COOKIE for UNIQUE
			//if (wsActiveRecord::useStatic('PollResults')->findAll(array('poll_id'=>$poll->getId(),'customer_id'=>$customerId))->count()== 0){
				$view->pollTitle = $poll->getName();
				$view->questions = $poll->getQuestions(array(), array('sequence'=>'ASC'));
				return $view->render('/poll/index.tpl.php');
		}
		return '';
	}
}