<?php
	class Faq extends wsActiveRecord {
		protected $_table = 'ws_faq';
		protected $_orderby = array( 'id' => 'ASC');
		protected $_multilang = array('question' => 'question', 'answer' => 'answer');

		protected function _defineRelations() {

		}

	/*	public function getPath() {
			return "http://www.red.ua/event/activ/id/".substr(strtotime($this->getCtime()),3).'_'.$this->getId();
		}

		public function getCustomersCount() {
			return wsActiveRecord::useStatic('EventCustomer')->count(array('event_id'=>$this->getId(),'status'=>1));
		}
		*/


	}