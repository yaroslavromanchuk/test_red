<?php
require_once('../cron_init.php');
$segment = false;//wsActiveRecord::useStatic('CustomersSegment')->findAll(['active'=>1, 'id'=>13]);
if($segment){
    foreach ($segment as $s) {
       // echo $s->sql.'<br>';
        $customer =  wsActiveRecord::useStatic('Shoporders')->findByQuery($s->sql);
       // l($customer);
       foreach ($customer as $c) {
           echo $c->customer_id.'<br>';
           $cust = wsActiveRecord::useStatic('Customer')->findById($c->customer_id);
           if($cust->id){
               $cust->setSegmentId($s->id);
               $cust->save();
           }
       }
        break;
    }
}
$customer = false;// wsActiveRecord::useStatic('Customer')->findAll(['segment_id in (5)']);
if($customer){
    foreach ($customer as $c) {
       // echo $c->id.'<br>';
        wsActiveRecord::query("INSERT INTO `red_event_customers` ( `ctime` , `end_time` , `event_id` , `customer_id` , `status` , `st` )
VALUES (
'2019-09-25 00:00:00', '2019-09-30 23:59:59', 19, ".$c->id.", 1, 1
)");
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

