<?php
	class EventCustomer extends wsActiveRecord {
		protected $_table = 'red_event_customers';
		protected $_orderby = array( 'id' => 'ASC');

		protected function _defineRelations()
		{

		}

		static function getEventsDiscont($customer_id, $order_id=null){
		//$utime = date("Y-m-d H:i:s");
                    $sk = 0;
			$time = time();
			if($order_id){
				$order = new Shoporders($order_id);
				$time = strtotime($order->getDateCreate());
			}
                        $cust = new Customer($customer_id);
			/*$q="
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
					AND red_events.start <= '".date('Y-m-d', $time)."'
					AND red_events.finish >= '".date('Y-m-d', $time)."'
					AND ( red_events.disposable = 0 OR (red_events.disposable = 1 AND red_event_customers.st <= 2))
					AND red_event_customers.end_time > '".$utime."'
			";*/
                        
                        $q=" SELECT red_events.discont, red_events.sumforgift FROM
							red_events
							JOIN red_event_customers
							on red_events.id = red_event_customers.event_id
						WHERE
							red_event_customers.status = 1
							AND red_events.publick = 1
							AND red_event_customers.customer_id = ".$customer_id."
							AND red_events.start <= '".date('Y-m-d')."'
							AND red_events.finish >= '".date('Y-m-d')."'
							AND ( red_events.disposable = 0 OR (red_events.disposable = 1 AND red_event_customers.st <= 2))
							AND red_event_customers.end_time > '".date('Y-m-d H:i:s')."' order by red_events.id desc";
			$events = wsActiveRecord::useStatic('Event')->findByQuery($q)->at(0);
			if($events){ 
				$sk = $events->getDiscont();
				if ($events->getSumforgift()) {
					$sum = 0;//$_SESSION['or_sum'];
                                        
                                        foreach($_SESSION['basket'] as $key => $item){
                                            if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $item['count'] > 0) {
                                            $sum +=   $article->getPerc(($cust->getSumOrderNoNew()+$_SESSION['or_sum']), $item['count'], $cust->getDiscont(false, 0, true), 0, 0, $_SESSION['or_sum'])['price'];
                                           }
                                        }
					if ($sum < $events->getSumforgift()) {
						$sk = 0;
					}
				}
			}
			return $sk;
		}
                
                static function getEvents($customer_id){
                    $cust = new Customer($customer_id);
                    $q=" SELECT red_events.discont, red_events.disposable, red_event_customers.event_id, red_event_customers.id, red_event_customers.st  FROM
							red_events
							JOIN red_event_customers
							on red_events.id = red_event_customers.event_id
						WHERE
							red_event_customers.status = 1
							AND red_events.publick = 1
							AND red_event_customers.customer_id = ".$customer_id."
							AND red_events.start <= '".date('Y-m-d')."'
							AND red_events.finish >= '".date('Y-m-d')."'
							AND ( red_events.disposable = 0 OR (red_events.disposable = 1 AND red_event_customers.st <= 2))
							AND red_event_customers.end_time > '".date('Y-m-d H:i:s')."' order by red_events.id desc";
                    $events = wsActiveRecord::useStatic('Event')->findByQuery($q)->at(0);
                   if($events){
                       if ($events->getSumforgift()) {
					$sum = 0;//$_SESSION['or_sum'];
                                        
                                        foreach($_SESSION['basket'] as $key => $item){
                                            if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $item['count'] > 0) {
                                            $sum +=   $article->getPerc(($cust->getSumOrderNoNew()+$_SESSION['or_sum']), $item['count'], $cust->getDiscont(false, 0, true), 0, 0, $_SESSION['or_sum'])['price'];
                                           }
                                        }
					if ($sum < $events->getSumforgift()) {
						return false;
					}
				}
                    return $events;
                    }
                    return false;
                }
		
		public function getEventsCustomerCount($id){
		$sql = "SELECT COUNT(  `id` ) AS ctn FROM  `red_event_customers` WHERE DATE_FORMAT(  `ctime` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) and customer_id = ".$id;
		$count = wsActiveRecord::useStatic('EventCustomer')->findByQuery($sql)->at(0)->getCtn();
		return $count;
		}
	
	
	}