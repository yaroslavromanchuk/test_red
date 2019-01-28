<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminSection
 *
 * @author PHP
 */
class Bufer extends wsActiveRecord
{
   
    
    public static function getNorma($post){
        
        $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz); 
                if($cat->id == 267){
                    $c = '';
                    $c_m = [];
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                          $c .=  implode(',', $kid);
                        }else{
                            $c_m[] = $v->id;
                        }
                    }
                    $c.= implode(',', $c_m); 
                }elseif($cat->getKidsIds()){
                    $c = implode(',', $cat->getKidsIds());
                                 }else{
                    $c = $cat->id;
                                  }          
                  return  self::getMassNorma($c, $from, $to);             
                                  
    }
    
    private static function getMassNorma($c, $from, $to){
        
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_category in (".$c.") 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
              $rr = [];
            foreach($ok as $key => $k){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` in (".$c.")");

                  $sd = $k->dbeg.' 00:00:00';
                  $ed = $k->dend.' 23:59:59';
                  
                  $activ = wsActiveRecord::findByQueryFirstArray(""
                          . "SELECT SUM(  `count` ) AS ctn FROM  `red_article_log`"
                          . "inner join `ws_articles` on `red_article_log`.`article_id` = `ws_articles`.`id`"
                          . " WHERE `red_article_log`.`type_id` = 4"
                          . " and `ws_articles`.`category_id` in (".$c.")  "
                          . " AND  `red_article_log`.`ctime` >=  '" . $sd."'"
                          . "and `red_article_log`.`ctime` <= '".$ed."' ");
                  $hist = wsActiveRecord::findByQueryFirstArray("
                      SELECT count(order_history.id) as `ctn`
                      FROM order_history 
                      inner join `ws_articles` on order_history.`article_id` = `ws_articles`.`id`
WHERE order_history.name LIKE  '%Прийом товара с возврата%'
                                                        and `ws_articles`.`category_id` in (".$c.") 
							and order_history.`ctime` >=  '" . $sd."'"
                          . "and order_history.`ctime` <= '".$ed."' ");
                  $rr['x'][] = date("W", strtotime($k->dbeg)).'('.date("d.m", strtotime($k->dbeg)).')';
                  $ost = (int)($k->ctn/((strtotime ($k->dend)-strtotime ($k->dbeg))/(60*60*24)));
                  $prod = (int)$z['suma'];
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
                  $rr['add'][] = (int)($activ['ctn']+$hist['ctn']);

                  $rr['n_0'][] = 1;
                  
                  $rr['n_1'][] = 1;
                  
                  $rr['n_2'][] = 1; 
            }
        
        
        return  self::prognoz($rr);
    }
    
    private static function prognoz($arr = []){
        
        $i = 2;
        $prod = $arr['prod'];
        $ost = $arr['ost'];
        foreach ($prod as $key => $value) {
            $n0 = ceil($ost[$key]*0.3);
            $n1 = ceil($ost[$key]*0.5);
            $n2 = ceil($ost[$key]*1.1);
            if($key>= 2){
                 $sr_p =  $prod[$i]+$prod[$i-1]+$prod[$i-2];
                $sr_ost =  $ost[$i]+$ost[$i-1]+$ost[$i-2];
                if($sr_p <=0){$sr_p = 1; }
                if($sr_ost <=0){$sr_ost = 1; }
                
                 $k = ceil($sr_p/$sr_ost*100);
                 
            if($k <= 30){
                $arr['n_0'][$key] = ceil($n0*0.8);
                $arr['n_1'][$key] = ceil($n1*0.8);
               $arr['n_2'][$key] = ceil($n2*0.8);
            }elseif($k >= 50){
                $arr['n_0'][$key] = ceil($n0*1.2);
                $arr['n_1'][$key] = ceil($n1*1.2);
                $arr['n_2'][$key] = ceil($n2*1.2);
            }else{
                $arr['n_0'][$key] = $n0;
                $arr['n_1'][$key] = $n1;
                $arr['n_2'][$key] = $n2;
            }
            $i++;
            }else{
                $arr['n_0'][$key] = $n0;
                $arr['n_1'][$key] = $n1;
                $arr['n_2'][$key] = $n2;
            }
        }
        $max_p = max($prod);
        $max_d = max($arr['add']);
        $kl = array_search($max_p,$prod);
        
        $count = count($ost);
        $p_ost = ceil(array_sum($ost)/$count);
        $p_prod = ceil(array_sum($prod)/$count);
       // $p_n0 = ceil(array_sum($arr['n_0'])/$count);
       // $p_n1 = ceil(array_sum($arr['n_1'])/$count);
       // $p_n2 = ceil(array_sum($arr['n_2'])/$count);
       $t = $ost[$count-1];
       
       for($i=0; $i<=$count; $i++){
           $arr['sr_ost'][$i] = $p_ost;
           $arr['sr_pr'][$i] = $p_prod;
       }  
                $arr['x'][$count] = 'Средн.';
                $arr['ost'][$count] = $ost[$kl];
                $arr['prod'][$count] = $max_p;
                 $arr['add'][$count] = $max_d;
                $arr['n_0'][$count] = $arr['n_0'][$kl];
                $arr['n_1'][$count] =  $arr['n_1'][$kl];
                $arr['n_2'][$count] = $arr['n_2'][$kl];
        
           $m = $ost[$kl];
           $otk = $m-$t;
           if($otk <0 ){
               $text = '<span style="color:#00e829;font-size: 18px;font-weight: bold;">Перегруз: + '.$otk*(-1).'</span>';
           }else{
               $text = '<span style="color:#ff0a0a;font-size: 18px;font-weight: bold;">Недогруз: - '.$otk.'</span>';
           }
                
         $arr['otkloneniye'] = $text;
        
        return $arr;
    }
    
     public static function getToExcelNorma($post){
         $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz);
                $res = [];
          if($cat->id == 267){
                    $c = '';
                    
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                            foreach ($kid as $k) {
                              //  if($k_kid = $k->getKidsIds()){
                                //    foreach ($k_kid as $q) {
                                //         $res[$q] = self::getQuery($q, $from, $to); 
                                 //   }
                               // }else{
                                     $res[$k] =   self::getQuery($k, $from, $to); 
                                //}
                               
                            }
                        }else{
                            $res[$v->id] =   self::getQuery($v->id, $from, $to); 
                        }
                    }
                   
                }elseif($kid = $cat->getKidsIds()){
                            foreach ($kid as $k) {
                               // if($k_kid = $k->getKidsIds()){
                                //   foreach ($k_kid as $v) {
                                  //       $res[$v] = self::getQuery($v, $from, $to); 
                                   // }
                              //  }else{
                                     $res[$k] = self::getQuery($k, $from, $to); 
                              //  }
                               
                            }
                       
                    
                                 }else{
                                     $res[$cat->id] =   self::getQuery($cat->id, $from, $to); 
                                  } 

                                  
                                  
         return $res;
     }
     private static function getQuery($c, $from, $to){
        
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_category = ".$c." 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
              $rr = [];
            foreach($ok as $k){
                //if($k->ctn){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,0)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` = ".$c." ");

                  $ost = ceil($k->ctn/((strtotime ($k->dend)-strtotime ($k->dbeg))/(60*60*24)));
                  $prod = $z['suma']?ceil($z['suma']):0;
                  
                  $rr['ost'][] = $ost?$ost:0;
                  $rr['prod'][] = $prod;
                //}
            }
        
       $cat =  new Shopcategories((int)$c);
        return ['cat'=>$cat->getRoutez(), 'ost'=> $rr['ost'], 'prod'=>$rr['prod']];//self::prognozExcel();//[ceil($ost), ceil($prod)]; 
    }
    public static function prognozExcel($arr = []){

        $result = [];
        foreach($arr as $k => $v){
            $result[$k]['cat'] = $v['cat'];
            
        if($v['prod']) {
            $max_p = max($v['prod']);
             $kl = array_search($max_p,$v['prod']);
              $m = $v['ost'][$kl];
        }else{
            $max_p = 0;
            
            $m = 0;
        }
        
       // $kl = array_search($max_p,$v['prod']);
        
        $count = count($v['ost']);
        
        $t = $v['ost'][$count-1];
        
       // $m = $v['ost'][$kl];
        
         $otk = $t-$m;
        
        // if($otk > 0 ){
          //   $otk = '+'.$otk;
        // }
           // $text = $otk;
              // $text =  'Перегруз: + '.$otk*(-1);
          // }else{
             //  $text = $otk;
              // $text = 'Недогруз: - '.$otk;
          // }
         
         $result[$k]['prognoz'] = $otk;

            }
            
            return $result;
       
    }
}
