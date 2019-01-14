<?php
	class Event extends wsActiveRecord {
		protected $_table = 'red_events';
		protected $_orderby = array( 'id' => 'DESC');

		protected function _defineRelations() {

		}

		public function getPath() {
			return "https://www.red.ua/event/activ/id/".substr(strtotime($this->getCtime()),3).'_'.$this->getId();
		}

		public function getCustomersCount() {
			return wsActiveRecord::useStatic('EventCustomer')->count(array('event_id'=>$this->getId(),'status'=>1));
		}

		public function getAllCustomers() {
			$c_id = array();
			$customers =wsActiveRecord::useStatic('EventCustomer')->findAll(array('event_id'=>$this->getId(),'status'=>1));
			foreach($customers as $customer) {
				$c_id [] =  new Customer($customer->getCustomerId());
			}
			return $c_id;
		}
	}