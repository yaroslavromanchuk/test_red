<?php
class Emailpost extends wsActiveRecord
{
	protected $_table = 'ws_mailing';
	protected $_orderby = array( 'id' => 'DESC');
        
            protected function _defineRelations()
    {
        $this->_relations = [
            'segment' => [
            'type' => 'hasOne',
            'class' => 'Subscriberstype',
            'field' => 'segment_id'
                ],
            'customer_segment' => [
            'type' => 'hasOne',
            'class' => 'CustomersSegment',
            'field' => 'segment_customer'
                ],
            'admin_create' =>[
            'type' => 'hasOne',
            'class' => 'Customer',
            'field' => 'id_customer_new'
            ],
            'admin_go' =>[
            'type' => 'hasOne',
            'class' => 'Customer',
            'field' => 'id_customer_go'
            ]
        ];
    }
    
    public static function openEmail($get){
       $tr = wsActiveRecord::useStatic('Emailpost')->findFirst(["track  LIKE  '".$get['track']."' "]);
       if($tr){
           $tr->setCountOpen(($tr->getCountOpen()+1));
           $tr->save();
            // wsLog::add($tr->track, 'OPEN_EMAIL');
       }
       return false;
    }
    
    public static function quickOrderEmail($get){
        $tr = wsActiveRecord::useStatic('Emailpost')->findFirst(["track  LIKE  '".$get['track']."' "]);
       if($tr){
           $tr->setCountOrder(($tr->getCountOrder()+1));
           $tr->setOrderAmount(($tr->getOrderAmount()+(int)$get['amount']));
           $tr->setCountOrderArticle(($tr->getCountOrderArticle()+(int)$get['count_article']));
           $tr->save();
           //  wsLog::add('Order: '.$get['order'].' Track: '.$tr->track, 'ORDER_EMAIL');
       }
       return false;
    }
    public static function linkEmail($get){
       $tr = wsActiveRecord::useStatic('Emailpost')->findFirst(["track  LIKE  '".$get['track']."' "]);
       if($tr){
           $tr->setCountLink(($tr->getCountLink()+1));
           $tr->save();
            // wsLog::add($tr->track, 'OPEN_EMAIL');
       }
       return false;
    }
    
    
}