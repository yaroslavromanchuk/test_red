<?php
	class Event extends wsActiveRecord {
		protected $_table = 'red_events';
		protected $_orderby = array( 'id' => 'DESC');

		protected function _defineRelations() {

		}
                /**
                 * Ссылка на акцию
                 * @return type
                 */
		public function getPath() {
			return "/event/activ/id/".substr(strtotime($this->getCtime()),3).'_'.$this->getId();
		}
                /**
                 * Пользователей участвующих в акции
                 * @return type
                 */
		public function getCustomersCount() {
			return wsActiveRecord::useStatic('EventCustomer')->count(array('event_id'=>$this->getId(),'status'=>1));
		}
                /**
                 * Количество реальных заказов по акции
                 * @return type
                 */
                public function getOrdersCount(){
                    return wsActiveRecord::useStatic('Shoporderarticles')->findByQuery("SELECT COUNT( DISTINCT (
`ws_orders`.`id`
) ) as ctn 
FROM  `ws_orders` 
INNER JOIN  `ws_order_articles` ON  `ws_orders`.`id` =  `ws_order_articles`.`order_id` 
WHERE  `ws_order_articles`.`event_id` = ".$this->getId()."
AND  `ws_order_articles`.`count` >0")->at(0)->ctn;
                   // return wsActiveRecord::useStatic('Shoporderarticles')->count(['event_id'=>$this->getId(),'count > 0']);
                }
                /**
                 * 
                 * @return \Customer
                 */
		public function getAllCustomers() {
			$c_id = array();
			$customers =wsActiveRecord::useStatic('EventCustomer')->findAll(array('event_id'=>$this->getId(),'status'=>1));
			foreach($customers as $customer) {
				$c_id [] =  new Customer($customer->getCustomerId());
			}
			return $c_id;
		}
	}