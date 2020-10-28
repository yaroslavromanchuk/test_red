<?php

class CartLog extends wsActiveRecord
{
    protected $_table = 'ws_cart_log';
  //  protected $_orderby = array('ctime' => 'DESC');
    
    
  public static function add($c, $track){
      $l = new CartLog();
      $l->setDate(date('Y-m-d H:i:s'));
      $l->setCount($c);
      $l->setTrack($track);
      $l->save();
  }
  public static function open($get){
       $tr = wsActiveRecord::useStatic('CartLog')->findFirst(["track  LIKE  '".$get['track']."' "]);
       if($tr){
           $tr->setOpen(($tr->getOpen()+1));
           $tr->save();
            // wsLog::add($tr->track, 'OPEN_EMAIL');
       }
       return false;
    }
    public static function link($get){
       $tr = wsActiveRecord::useStatic('CartLog')->findFirst(["track  LIKE  '".$get['track']."' "]);
       if($tr){
           $tr->setLink(($tr->getLink()+1));
           $tr->save();
            // wsLog::add($tr->track, 'OPEN_EMAIL');
       }
       return false;
    }
}
