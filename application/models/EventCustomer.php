<?php
	class EventCustomer extends wsActiveRecord {
		protected $_table = 'red_event_customers';
		protected $_orderby = array( 'id' => 'ASC');

		protected function _defineRelations()
		{

		}

		static function getEventsDiscont($customer_id, $order_id=null){
		$utime = date("Y-m-d H:i:s");
			$time = time();
			if($order_id){
				$order = new Shoporders($order_id);
				$time = strtotime($order->getDateCreate());
			}
			$q="
				SELECT
					*
				FROM
					red_events
					JOIN red_event_customers
					on red_events.id = red_event_customers.event_id
				WHERE
					red_event_customers.status = 1
					AND red_events.publick = 1
					AND red_event_customers.customer_id = ".$customer_id."
					AND red_events.start <= '".date('Y-m-d',$time)."'
					AND red_events.finish >= '".date('Y-m-d',$time)."'
					AND ( red_events.disposable = 0 OR (red_events.disposable = 1 AND red_event_customers.st <= 2))
					AND red_event_customers.end_time > '".$utime."'
			";//AND red_event_customers.session_id = '".session_id()."'
			$events = wsActiveRecord::useStatic('Event')->findByQuery($q);
			if($events->at(0)){
				$sk = $events->at(0)->getDiscont();
				if ($events->at(0)->getSumforgift()) {
					$sum = 0;
					foreach ($_SESSION['basket'] as $key => $value) {
						$sum += $value['price'] * $value['count'];
					}
					if ($sum < $events->at(0)->getSumforgift()) {
						$sk = 0;
					}
				}
			}
			return $sk;
		}
		
		public function getEventsCustomerCount($id){
		$sql = "SELECT COUNT(  `id` ) AS ctn FROM  `red_event_customers` WHERE DATE_FORMAT(  `ctime` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) and customer_id = ".$id;
		$count = wsActiveRecord::useStatic('EventCustomer')->findByQuery($sql)->at(0)->getCtn();
		return $count;
		}
	
	
	}