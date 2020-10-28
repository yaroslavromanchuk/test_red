<?php
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
                            $c_m[] = $kid;
                        }else{
                            $c_m[] = $v->id;
                        }
                    } 
                }elseif($cat->getKidsIds()){
                    $c_m[] = $cat->getKidsIds();
                                 }else{
                    $c_m[] = $cat->id;
                                  }
                                  
                      foreach ($c_m as $value) {
                          $c .=  ','.implode(',', $value); 
                      }
                           $c =  substr($c, 1);
                           
                     if($post->interval_prognoz == 2){
                          return self:: getMassNormaDay($c, $from, $to, true);   
                     }elseif($post->method == 'oborot_all' or $post->method == 'oborot_root_category' or $post->method == 'oborot_category'){
                       
                         return self::getOborot($c, $from, $to);
                     }elseif($post->method == 'oborot_all_monch'){
                         
                         return self::getOborotGRN($c, date('Y-m',strtotime($post->from_prognoz)), date('Y-m', strtotime($post->to_prognoz)));
                     }else{
                         return self::getMassNorma($c, $from, $to);   
                     }      
               // 
              
                                  
    }
    public static function getNormaTable($post){  
        $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz);
                if(!empty($post->interval_prognoz)){
                    switch ($post->interval_prognoz){
                    case '1': return self::getMassNorma($cat->id, $from, $to);
                    case '2': return self:: getMassNormaDay($cat->id, $from, $to, true);   
                    case '3':
                        return self:: getMassNormaMonth($cat->id, date('Y-m',strtotime($post->from_prognoz)), date('Y-m', strtotime($post->to_prognoz)));  
                    default : 
                }
                }else{
                    return self::getMassNorma($cat->id, $from, $to);
                }
                
                
        
    }
    
    public static function getNormaPrognozBrand($post){
        
        $from = date('Y-m-d',strtotime($post->from_prognoz_brand));
                $to = date('Y-m-d', strtotime($post->to_prognoz_brand));
                if($post->brand_prognoz == 111111111){
                   $br =  HomeAnalitics::dictinct_brand_balance($post);
                   $bb = [];
                   foreach ($br as $b) {
                       $bb[] = $b->id;
                   }
                   $b = implode(',', $bb); 
                   
                }else{
                $bb = new Brand((int)$post->brand_prognoz); 
                $b = $bb->id;
                }
               //return $b;
                         return self::getMassNormaBrand($b, $from, $to);   
                         
               // 
              
                                  
    }
    public static function getNormaOborotRoot($post){
$res = [];
                $cc = new Shopcategories((int)$post->cat_prognoz); 
                        if($cc->getKidsIds()){
                               $r = self::getOborot(implode(',', $cc->getKidsIds()), date('Y-m-d',strtotime($post->from_prognoz)), date('Y-m-d', strtotime($post->to_prognoz)));
                        }else{
                            $r = self::getOborot($cc->id, date('Y-m-d',strtotime($post->from_prognoz)), date('Y-m-d', strtotime($post->to_prognoz)));
                        }  
                         $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 21; break;
                                case 21: $min = 21; $max = 28; break;
                                case 28: $min = 28; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            
                            if($r['oborot'][$count-1] < $min or $r['oborot'][$count-1] >= $max){
                                $count = 0;
                            }
                        }
                        if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                           
                      $res = $r;
                       $res['cat'] = $cc->h1;     
                      } 
                }
                        
                        //$res['cat'] = $cc->h1; 
                       
                       return  $res;
                            
    }
    /**
     * Оборот по категориям
     * @param type $post $post->cat_prognoz, post->from_prognoz, post->to_prognoz, post->zone
     * @return type
     */
      public static function getNormaOborotCategory($post){
                if(!empty($post->cat_prognoz)){
               // $cc =  new Shopcategories((int)$post->cat_prognoz);
                $catt = $post->cat_prognoz;//$cc->id;
                }else{
                    $catt = 0;
                }
                
                 $c = wsActiveRecord::useStatic('Shopcategories')->findAll(['parent_id'=>$catt, 'id not in (106, 85, 267, 146)', 'active'=> 1]);
                 $res = [];
                 $i = 0;
                 foreach ($c as $v) {
                     
                 
                        if($v->getKidsIds()){
                               $r = self::getOborot(implode(',', $v->getKidsIds()), date('Y-m-d',strtotime($post->from_prognoz)), date('Y-m-d', strtotime($post->to_prognoz)));
                      
                               }else{
                            $r  = self::getOborot($v->id, date('Y-m-d',strtotime($post->from_prognoz)), date('Y-m-d', strtotime($post->to_prognoz)));
                        } 
                      //  if( $s = count($rr['ost']);//max($rr['ost']);
                        $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 21; break;
                                case 21: $min = 21; $max = 28; break;
                                case 28: $min = 28; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            
                            if($r['oborot'][$count-1] <= $min or $r['oborot'][$count-1] > $max){
                                $count = 0;
                            }
                        }
                        
                if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                           
                      $res[$i] = $r;
                       $res[$i]['cat'] = $v->h1; 
                            $i++;
                            
                            
                      } 
                }
                         
      }
                       return  $res;     
    }
    /**
     * Оборачиваемость брендов за определенный интервал времени
     * @param type $post [from_prognoz,to_prognoz]
     * @return type
     */
    public static function getNormaOborotBrand($post){
               
                $from = date('Y-m-d', strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
        $sql = "SELECT DISTINCT (
 `ws_balance_category`.`id_brand`
) AS  `id` , AVG(  `ws_balance_category`.`count` ) AS  `ctn` ,  `red_brands`.`name` 
FROM  `ws_balance_category` 
INNER JOIN  `ws_balance` ON  `ws_balance_category`.`id_balance` =  `ws_balance`.`id` 
INNER JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` =  `red_brands`.`id` 
WHERE  `ws_balance`.`date` >=  '".$from."'
    and `ws_balance`.`date` <= '".$to."'
GROUP BY  `ws_balance_category`.`id_brand` 
HAVING  `ctn` > 5
ORDER BY  `ctn` DESC";
                 $c = wsActiveRecord::useStatic('BalanceCategory')->findByQuery($sql);
                 $res = [];
                 $i = 0;
                 foreach ($c as $v) {
                            $r  = self::getOborotBrand($v->id, $from, $to);

                      //  if( $s = count($rr['ost']);//max($rr['ost']);
                        $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 21; break;
                                case 21: $min = 21; $max = 28; break;
                                case 28: $min = 28; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            
                            if($r['oborot'][$count-1] <= $min or $r['oborot'][$count-1] > $max){
                                $count = 0;
                            }
                        }
                        
                if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                           
                            foreach ($r['verch'] as $k=> $value) {
                                $r['verch'][$k] = $r['verch_m'];
                            }
                           
                      $res[$i] = $r;
                       $res[$i]['cat'] = $v->name; 
                            $i++;
                            
                            
                      } 
                }
                         
      }
      
    //  echo '<pre>';
    //  print_r($res);
    //  echo '</pre>';
    //  exit();
                      
                       
                       return  $res;
                            
    }
    public static function getNormaOborotGraid($post){
             $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                 $res = [];
                 $i=0;
                $ar = [0 =>'Без грейда', 1=>'Грейд 1', 2=>'Грейд 2', 3=>'Грейд 3', 4=>'Грейд 4', 5=>'Грейд 5' ];
                 
                 foreach ($ar as $k => $v ) {
                            $r  = self::getOborotGraid($k, $from, $to);

                      //  if( $s = count($rr['ost']);//max($rr['ost']);
                        $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 15; break;
                                case 21: $min = 15; $max = 30; break;
                                case 28: $min = 30; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            
                            if($r['oborot'][$count-1] <= $min or $r['oborot'][$count-1] > $max){
                                $count = 0;
                            }
                        }
                        
                if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                           
                            foreach ($r['verch'] as $z=> $value) {
                                $r['verch'][$z] = $r['verch_m'];
                            }
                           
                      $res[$i] = $r;
                       $res[$i]['cat'] = $v; 
                            $i++;
                            
                            
                      } 
                }
                         
      }
    return  $res;                       
    }
    
    public static function getNormaOborotGraidCategory($post){
             $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                 $res = [];
                 $i=0;
                 if(!empty($post->graid)){
                    $ar = [$post->graid => '']; 
                    if(!empty($post->category)){
               // $cc =  new Shopcategories((int)$post->cat_prognoz);
                $catt = $post->category;//$cc->id;
                }else{
                    $catt = 0;
                }
                 $c = wsActiveRecord::useStatic('Shopcategories')->findAll(['parent_id'=>$catt, 'id not in (106, 85, 267, 146)', 'active'=> 1]);
                    foreach ($c as $v) {
                     
                 
                        if($v->getKidsIds()){
                               $r = self::getOborot(implode(',', $v->getKidsIds()), $from, $to, $post->graid);
                      
                               }else{
                            $r  = self::getOborot($v->id, $from, $to, $post->graid);
                        } 
                      //  if( $s = count($rr['ost']);//max($rr['ost']);
                        $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 15; break;
                                case 21: $min = 15; $max = 30; break;
                                case 28: $min = 30; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            
                            if($r['oborot'][$count-1] <= $min or $r['oborot'][$count-1] > $max){
                                $count = 0;
                            }
                        }
                        
                if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                           
                      $res[$i] = $r;
                       $res[$i]['cat'] = $v->h1; 
                            $i++;
                            
                            
                      } 
                }
                         
      }
                 }elseif(!empty($post->category)){
                      $ar = [0 =>'Без грейда', 1=>'Грейд 1', 2=>'Грейд 2', 3=>'Грейд 3', 4=>'Грейд 4', 5=>'Грейд 5' ];
                 }else{
                      $ar = [0 =>'Без грейда', 1=>'Грейд 1', 2=>'Грейд 2', 3=>'Грейд 3', 4=>'Грейд 4', 5=>'Грейд 5' ];
                 
                 
                 
                 foreach ($ar as $k => $v ) {
                            $r  = self::getOborotGraid($k, $from, $to, $cat);
                        $count = count($r['oborot']);
                        if(isset($post->zone) and !empty($post->zone)){
                            $min = 0;
                            $max = 1000;
                            switch ($post->zone) {
                                case 1: $min = 0; $max = 15; break;
                                case 21: $min = 15; $max = 30; break;
                                case 28: $min = 30; $max = 1000; break;
                                default: $min = 0; $max = 1000; 
                                    break;
                            }
                            if($r['oborot'][$count-1] <= $min or $r['oborot'][$count-1] > $max){
                                $count = 0;
                            }
                        }
                if($count > 0){
                        if(array_sum($r['oborot']) > 0){
                            foreach ($r['verch'] as $z=> $value) {
                                $r['verch'][$z] = $r['verch_m'];
                            }
                        $res[$i] = $r;
                        $res[$i]['cat'] = $v; 
                            $i++;  
                      } 
                }           
      }
    }
    return  $res;                       
    }
    
    private static function getMassNormaMonth($c, $from, $to){
         $tost  = wsActiveRecord::findByQueryFirstArray("
SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')
     and `ws_balance_category`.id_category in ({$c}) 
 ")['ctn'];
     
         $rr = [];
         $rr['tost'] = $tost;
          $sql = "
SELECT DATE_FORMAT(`ws_balance`.`date`, '%m-%Y') AS nedelya,
DATE_FORMAT(  `ws_balance`.`date` , '%Y-%m') AS `m`,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m' ) >=  '{$from}'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m' ) <=  '{$to}'
     and `ws_balance_category`.id_category in ({$c}) 
GROUP BY  `nedelya` 
ORDER BY  `m` ASC 
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
          
            foreach($ok as $k){
                  $z = wsActiveRecord::findByQueryFirstArray("
                      SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma
                      FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' )  = '{$k->m}' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` in ({$c})");

                  $activ = wsActiveRecord::findByQueryFirstArray(""
                          . "SELECT SUM(  `count` ) AS ctn FROM  `red_article_log`"
                          . "inner join `ws_articles` on `red_article_log`.`article_id` = `ws_articles`.`id`"
                          . " WHERE `red_article_log`.`type_id` = 4"
                          . " and `ws_articles`.`category_id` in ({$c})  "
                          . " AND  DATE_FORMAT(  `red_article_log`.`ctime` ,  '%Y-%m' ) =  '{$k->m}' ");
                  
                  $hist = wsActiveRecord::findByQueryFirstArray("
                      SELECT count(order_history.id) as `ctn`
                      FROM order_history 
                      inner join `ws_articles` on order_history.`article_id` = `ws_articles`.`id`
WHERE order_history.name LIKE  '%Прийом товара с возврата%'
                            and `ws_articles`.`category_id` in ({$c}) 
			and  DATE_FORMAT(`order_history.`ctime` ,  '%Y-%m' )  =   '{$k->m}'  ");
                    $dn = date('t', strtotime($k->nedelya));//(strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                  $rr['x'][] = $k->nedelya;//.'('.$dn.')';
                 
                      $d = $dn?$dn:1;
                    $rr['d'][] = $d;
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
                  if($ost > 0 and $prod > 0){
                  $rr['oborot'][] =  ceil(($ost*$d)/$prod);
                  }else{ 
                   $rr['oborot'][] = 0; 
                  }
                  $rr['add'][] = (int)($activ['ctn']+$hist['ctn']);
                  
            }
        return  self::prognoz_table($rr);
    }
    
    /**
     * 
     * @param type $c
     * @param type $from
     * @param type $to
     * @return type
     */
    private static function getMassNorma($c, $from, $to){
         $tost  = wsActiveRecord::findByQueryFirstArray("
SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')
     and `ws_balance_category`.id_category in ({$c}) 
 ")['ctn'];
         $rr = [];
         $rr['tost'] = $tost;
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
         
            foreach($ok as $k){
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
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24)+1;
                      $d = $dn?$dn:1;
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
                  if($ost > 0 and $prod > 0){
                  $rr['oborot'][] =  ceil(($ost*$d)/$prod);
                  }else{ 
                   $rr['oborot'][] = 0; 
                  }
                  $rr['add'][] = (int)($activ['ctn']+$hist['ctn']);
                  $rr['n_0'][] = 1;
                  $rr['n_1'][] = 1;
                  $rr['n_2'][] = 1; 
            }
        return  self::prognoz($rr);
    }
    /**
     * 
     * @param type $c
     * @param type $from
     * @param type $to
     * @param type $type
     * @return type
     */
    private static function getMassNormaDay($c, $from, $to, $type){
        
   /*     $sql = "
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
 "; */
        
       $sql = "SELECT  `ws_balance`.`date`,
           SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
AND  `ws_balance_category`.`id_category` IN (".$c." ) 
GROUP BY  `ws_balance`.`date` 
ORDER BY  `ws_balance`.`date` ASC 
LIMIT 0 , 30";
        
            $ok = wsActiveRecord::findByQueryArray($sql);
              $rr = [];
            foreach($ok as $k){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  = '".$k->date."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` in (".$c.")");

                  $sd = $k->date.' 00:00:00';
                  $ed = $k->date.' 23:59:59';
                  
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
                  
                  $rr['x'][] = $k->date;

                  if($k->ctn > 0){
                      $ost = (int)$k->ctn;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
                  $rr['add'][] = (int)($activ['ctn']+$hist['ctn']); 
            }
            if($type){
                return  self::prognozdey($rr);
            }else{
                $s = count($rr['ost']);//max($rr['ost']);
                if($s > 0){
                    if(array_sum($rr['ost']) > 0){
                    return ['st'=>true , 'res'=>['ost'=> $rr['ost'], 'prod'=>$rr['prod'], 'add'=>$rr['add']]];
                    
                    }else{
                         return ['st'=>false];
                    }
              }else{
                    return ['st'=>false];
                }
            }
        
    }
    /**
     * 
     * @param type $c
     * @param type $from
     * @param type $to
     * @return type
     */
    private static function getMassNormaBrand($c, $from, $to){
      //  return $c;
        
        $tost  = wsActiveRecord::findByQueryFirstArray("
SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')
     and `ws_balance_category`.id_brand in ({$c}) 
 ")['ctn'];
     
         $rr = [];
         $rr['tost'] = $tost;
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_brand in( ".$c.") 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
       // return $sql;
            $ok = wsActiveRecord::findByQueryArray($sql);
              
            foreach($ok as $k){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`brand_id` in(".$c.")");

                  $sd = $k->dbeg.' 00:00:00';
                  $ed = $k->dend.' 23:59:59';
                  
                  $activ = wsActiveRecord::findByQueryFirstArray(""
                          . "SELECT SUM(  `count` ) AS ctn FROM  `red_article_log`"
                          . "inner join `ws_articles` on `red_article_log`.`article_id` = `ws_articles`.`id`"
                          . " WHERE `red_article_log`.`type_id` = 4"
                          . " and `ws_articles`.`brand_id` in(".$c." ) "
                          . " AND  `red_article_log`.`ctime` >=  '" . $sd."'"
                          . "and `red_article_log`.`ctime` <= '".$ed."' ");
                  
                  $hist = wsActiveRecord::findByQueryFirstArray("
                      SELECT count(order_history.id) as `ctn`
                      FROM order_history 
                      inner join `ws_articles` on order_history.`article_id` = `ws_articles`.`id`
WHERE order_history.name LIKE  '%Прийом товара с возврата%'
                                                        and `ws_articles`.`brand_id` in(".$c.") 
							and order_history.`ctime` >=  '" . $sd."'"
                          . "and order_history.`ctime` <= '".$ed."' ");
                  
                  $rr['x'][] = date("W", strtotime($k->dbeg)).'('.date("d.m", strtotime($k->dbeg)).')';
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?$dn:1;
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
                  if($ost > 0 and $prod > 0){
                  $rr['oborot'][] =  ceil(($ost*$d)/$prod);
                  }else{ 
                   $rr['oborot'][] = 0; 
                  }
                  $rr['add'][] = (int)($activ['ctn']+$hist['ctn']);
                  $rr['n_0'][] = 1;
                  $rr['n_1'][] = 1;
                  $rr['n_2'][] = 1; 
            }
        return  self::prognoz($rr);
    }
    /**
     * 
     * @param type $c - категория
     * @param type $from - от
     * @param type $to - до
     * @param type $graid - грейд
     * @return int
     */
     private static function getOborot($c, $from, $to, $graid = false){
        // echo $c;
       //  exit();
        //SUM(`ws_balance_category`.`summa`) as summa
         $tost  = wsActiveRecord::findByQueryFirstArray("
SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')
     and `ws_balance_category`.id_category in ({$c}) 
 ")['ctn'];
     
         $rr = [];
         $rr['tost'] = $tost;
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn,
SUM(`ws_balance_category`.`summa`) as summa
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance`";
      if($graid){
          $sql.= " JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`";
      }  
$sql.=" WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_category in (".$c.") ";
    if($graid){
        $sql.=" AND  `red_brands`.`greyd` = ".$graid;
    }    
$sql .= " GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
        
            foreach($ok as $k){
                //SUM((`ws_orders`.`amount`+`ws_orders`.`deposit`+`ws_orders`.`bonus`)) as suma_grn, 
                //SUM(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,0)) as suma,
                $sq = "SELECT 
                      SUM(`ws_order_articles`.`count`) as suma,
                      SUM((`ws_orders`.`amount`+`ws_orders`.`deposit`+`ws_orders`.`bonus`)) as suma_grn
                      FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`" ;
            if($graid){
                $sq.="  inner join  `red_brands` ON  `ws_articles`.`brand_id` = `red_brands`.`id`";
            }    
        
$sq .=" WHERE 
DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
AND DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17, 7, 2)
and `ws_articles`.`category_id` in (".$c.")";   

if($graid){
    $sq.=" and `red_brands`.`greyd` = ".$graid;
}
                  $z = wsActiveRecord::findByQueryFirstArray($sq);


                  $rr['x'][] = date("W", strtotime($k->dbeg)).'('.date("d.m", strtotime($k->dbeg)).')';
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?$dn:1;
                   //  l($d);
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  
                  $prod = (int)$z['suma'];
                  
                  if($ost > 0 and $prod > 0){
                  $rr['oborot'][] =  ceil(($ost*$d)/$prod);
                  }else{ 
                   $rr['oborot'][] = 0; 
                  }
                  
                    if($k->summa > 0){
                        $ost_sum = (int)$k->summa/$d;
                      //$ost_sum = (int)$k->summa;
                    }else{
                        $ost_sum = 0;
                    } 
                  
                  $prod_grn = (int)$z['suma_grn'];
                  
                    if($ost_sum > 0 and $prod_grn > 0){
                        $rr['oborot_grn'][] =  ceil(($ost_sum*$d)/$prod_grn);
                    }else{ 
                        $rr['oborot_grn'][] = 0; 
                    }
                  
                  $rr['niz'][] = 15;
                  $rr['verch'][] = 100;
                  $rr['norma'][] = 30;
            }
        return  $rr;
    }
         private static function getOborotGRN($c, $from, $to){
        $sql = "
SELECT 
DATE_FORMAT( `ws_balance`.`date` , '%Y-%m' ) AS monch,
DATE_SUB( `ws_balance`.`date` , INTERVAL DAYOFMONTH( `ws_balance`.`date` ) -1 DAY ) AS dbeg,
DATE_SUB( DATE_ADD( DATE_SUB( `ws_balance`.`date` , INTERVAL DAYOFMONTH( `ws_balance`.`date` ) -1 DAY ) , INTERVAL 1 MONTH ) , INTERVAL 1 DAY ) AS dend,
SUM( `ws_balance_category`.`count` ) AS ctn,
SUM( `ws_balance_category`.`summa` ) AS summa
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
WHERE DATE_FORMAT( `ws_balance`.`date` , '%Y-%m' ) >= '".$from."'
AND DATE_FORMAT( `ws_balance`.`date` , '%Y-%m' ) <= '".$to."'
and `ws_balance_category`.id_category in (".$c.") 
GROUP BY `monch`
ORDER BY `dbeg` ASC            
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
           
              $rr = [];
            foreach($ok as $k){
                //SUM((`ws_orders`.`amount`+`ws_orders`.`deposit`+`ws_orders`.`bonus`)) as suma_grn, 
                //SUM(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,0)) as suma,
                
                $sql = "SELECT 
                      SUM(`ws_order_articles`.`count`) as suma,
                      SUM(DISTINCT (`ws_orders`.`amount`+`ws_orders`.`deposit`+`ws_orders`.`bonus`)) as suma_grn
                      FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE 
DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
AND DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17, 7, 2)
and `ws_articles`.`category_id` in (".$c.")";
                
                $sql2 = "SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' ) as monch,
                      SUM((`ws_orders`.`amount`+`ws_orders`.`deposit`+`ws_orders`.`bonus`)) as suma_grn
                      FROM  `ws_orders`
WHERE 
DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
AND DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."'
AND `ws_orders`.`status` not in(17, 7, 2)
group by monch";
               // l($sql);
                  $z = wsActiveRecord::findByQueryFirstArray( $sql);

                  $rr['x'][] = date("m.Y", strtotime($k->dbeg));
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?($dn+1):1;
                    //  l($d);  
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  
                  $prod = (int)$z['suma'];
                  
                  if($ost > 0 and $prod > 0){
                  $rr['oborot'][] =  ceil(($ost*$d)/$prod);
                  }else{ 
                   $rr['oborot'][] = 0; 
                  }
                  
                    if($k->summa > 0){
                        $ost_sum = (int)$k->summa/$d;
                      //$ost_sum = (int)$k->summa;
                    }else{
                        $ost_sum = 0;
                    }
                   // l($ost_sum);
                  
                  $prod_grn = (int)$z['suma_grn'];
                 // echo 'prod = '.$z['suma_grn'];
                    if($ost_sum > 0 and $prod_grn > 0){
                        $rr['oborot_grn'][] =  ceil(($ost_sum*$d)/$prod_grn);
                    }else{ 
                        $rr['oborot_grn'][] = 0; 
                    }
                  
                  $rr['niz'][] = 21;
                  $rr['verch'][] = 45;
                  $rr['norma'][] = 28;
            }
        return  $rr;
    }
    /**
     * Оборот по бренду понедельно
     * @param type $c
     * @param type $from
     * @param type $to
     * @return type
     */
    private static function getOborotBrand($c, $from, $to){
        // echo $c;
       //  exit();
         $tost  = wsActiveRecord::findByQueryFirstArray("
SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')
     and `ws_balance_category`.id_brand in ({$c}) 
 ")['ctn'];
     
         $rr = [];
         $rr['tost'] = $tost;
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_brand = ".$c." 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
            $ok = wsActiveRecord::findByQueryArray($sql);
             
              $rr['niz_m'] = 21;
              $rr['norma_m'] = 28;
              $rr['verch_m'] = 60;
            foreach($ok as $k){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  
                      sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma 
                      FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`brand_id` = ".$c);

                  $rr['x'][] = date("W", strtotime($k->dbeg)).'('.date("d.m", strtotime($k->dbeg)).')';
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?$dn:1;
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                
                  if($ost > 0 and $prod > 0){
                      $ob = ceil(($ost*$d)/$prod);
                  $rr['oborot'][]  = $ob;
                  
                   if($ob > $rr['verch_m']){ $rr['verch'][] = $ob+10;
                   $rr['verch_m'] = $ob+10;
                   
                   }else{
                       $rr['verch'][] = 60;
                   }
                  }else{
                      $rr['oborot'][] = 0;
                      $rr['verch'][] = 60;
                  }
                  $rr['niz'][] = $rr['niz_m'];
                  
                 
                 
                  $rr['norma'][] = $rr['norma_m'];
                  
                  
                  
            }
        return  $rr;
    }
    
    /**
     * Оборот грейд
     * @param type $g
     * @param type $from
     * @param type $to
     * @return type
     */
    private static function getOborotGraid($g, $from, $to, $cat = false){
        // echo $c;
       //  exit();

        
         $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
         and `red_brands`.`greyd` = ".$g." 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
        
            $ok = wsActiveRecord::findByQueryArray($sql);
              $rr = [];
              $rr['niz_m'] = 15;
              $rr['norma_m'] = 30;
              $rr['verch_m'] = 45;
            foreach($ok as $k){
                  $z = wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma 
                    FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
inner join  `red_brands` ON  `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
       and `red_brands`.`greyd` = ".$g." 
AND `ws_orders`.`status` not in(17)");

                  $rr['x'][] = date("W", strtotime($k->dbeg)).'('.date("d.m", strtotime($k->dbeg)).')';
                   $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?$dn:1;
                  if($k->ctn > 0){
                      $ost = (int)$k->ctn/$d;
                  }else{
                      $ost = 0;
                  }
                  $prod = (int)$z['suma'];
                
                  if($ost > 0 and $prod > 0){
                      $ob = ceil(($ost*$d)/$prod);
                  $rr['oborot'][]  = $ob;
                  
                   if($ob > $rr['verch_m']){ $rr['verch'][] = $ob+10;
                   $rr['verch_m'] = $ob+10;
                   
                   }else{
                       $rr['verch'][] = 60;
                   }
                  }else{
                      $rr['oborot'][] = 0;
                      $rr['verch'][] = 60;
                  }
                  $rr['niz'][] = $rr['niz_m'];
                  
                 
                 
                  $rr['norma'][] = $rr['norma_m'];     
            }
        return  $rr;
    }
    /**
     * 
     * @param type $arr
     * @return string
     */
    private static function prognoz($arr = []){
        $i = 2;
        $prod = $arr['prod']?$arr['prod']:[0];
        $ost = $arr['ost']?$arr['ost']:[0];
        $oborot = $arr['oborot']?$arr['oborot']:[0];
        $add = $arr['add']?$arr['add']:[0];
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
        $max_d = max($add);
        $kl = array_search($max_p,$prod);
        $count = count($ost);
        $p_ost = ceil(array_sum($ost)/$count);
        $p_prod = ceil(array_sum($prod)/$count);
        $p_oborot = ceil(array_sum($oborot)/$count);
      // $t = $ost[$count-1];
        $t = $arr['tost'];//текущий остаток
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
                $arr['oborot'][$count] = $p_oborot;
        
           $m = $ost[$kl] + $max_p;// остаток на момент максимальных продаж + продажи
           
           $otk = $m-$t;
           $r = $otk;
           if($otk < 0 ){
              $r = $otk*(-1);
               $text = '<span style="color:#00e829;font-size: 18px;font-weight: bold;">Перегруз: + '.$otk*(-1).'</span>';
           }else{
                $r = $otk*(-1);
               $text = '<span style="color:#ff0a0a;font-size: 18px;font-weight: bold;">Недогруз: - '.$otk.'</span>';
           }  
           if($r == 0){$r = '';}
         $arr['otkloneniye'] = $text;
         $arr['raznica'] = $r;
         //$arr['tost'] =  $arr['tost'];
        return $arr;
    }
     private static function prognoz_table($arr = []){
        $prod = $arr['prod']?$arr['prod']:[0];
        $ost = $arr['ost']?$arr['ost']:[0];
        $max_p = max($prod);
        $arr['max_p'] = $max_p;
        
        $kl = array_search($max_p,$prod);
        
       // $count = count($ost);
        //$t = $ost[$count-1];//текущий остаток
       $t =  $arr['tost'];

       
        $m = $ost[$kl] + $max_p;// Средний остаток на момент максимальных продаж + продажи
        $r = ($m-$t)*(-1); 
           if($r == 0){ $r = '';}
         $arr['raznica'] = $r;
        return $arr;
    }
    /**
     * 
     * @param type $arr
     * @return string
     */
    private static function prognozdey($arr = []){
        $i = 2;
        $prod = $arr['prod']?$arr['prod']:[0];
       
        $ost = $arr['ost']?$arr['ost']:[0];
        $gm = [];
        $j = 0;
        foreach ($prod as $k => $v) {
            $gm[$k][0] = $v;
            $gm[$k][1] = $prod[$k+1];
             $gm[$k][2] = $prod[$k+2];
        }
        $reee = [];
        foreach ($gm as $k => $va) {
            if($va[0] < $va[1] && $va[1] < $va[2]){
                $reee[$k] = $gm[$k];
            }
        }
        $c_r = count($reee);
        if($c_r > 1){
            $max = 0;
            $ind = 0;
            foreach ($reee as $k => $v) {
                if(array_sum($v) > $max){
                    $max = array_sum($v);
                    $ind = $k;
                }
            }
            $t = $reee[$ind];
            $reee = [];
            $reee[$ind] = $t;
        }

       $count = count($ost);
       $o_ost = 0;
       $o_add = 0;
       $max_p = 0;
       $otk = 0;
       if(count($reee) == 1){
           $keys = array_keys($reee);
           $firstKey = $keys[0];
           $o_add = $arr['add'][$firstKey]+$arr['add'][$firstKey+1]+$arr['add'][$firstKey+2];//общее количество добавленого товара
           $sd = $arr['add'][$count-1]+$arr['add'][$count-2]+$arr['add'][$count-3];
           $otk = $o_add - $sd;
           $o_ost = ($arr['ost'][$firstKey]+$arr['ost'][$firstKey+1]+$arr['ost'][$firstKey+2])/3;//средний остаток за 3 дня
           $max_p = $reee[$firstKey][2];
            
        }

       
                $arr['x'][$count] = 'Средн.';
                $arr['ost'][$count] = $o_ost;
                $arr['prod'][$count] = $max_p;
                $arr['add'][$count] = $o_add;

           if($otk < 0 ){
               $text = '<span style="color:#00e829;font-size: 18px;font-weight: bold;">Перебор: '.$otk*(-1).'</span>';
           }else{
               $text = '<span style="color:#ff0a0a;font-size: 18px;font-weight: bold;">Добавить: '.$otk.'</span>';
           }     
         $arr['otkloneniye'] = $text;
        return $arr;
    }
    /**
     * Норам продаж в иксель.
     * формирует запросы по категориям ы грейду getQuery($k, $i, $from, $to)
     * @param type $post  post->from_prognoz, post->to_prognoz, post->cat_prognoz
     * @return ['cat']['graid']=['ostatok','prodaji']
     */
     public static function getToExcelNorma($post){ 
         //l($post);
        // exit();
         $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
               // $cat = new Shopcategories((int)$post->cat_prognoz);
               // $i = $post->graid?$post->graid:0;
                $res = [];
                $c = $post->cat;
                 for($i=0; $i<=5;$i++){
                                     $res[$c][$i] = self::getQuery($c, $i, $from, $to); 
                  }
      /*    if($cat->id == 267){
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                            foreach ($kid as $k) {
                                    for($i=0; $i<=5;$i++){
                                     $res[$k][$i] =   self::getQuery($k, $i, $from, $to); 
                                    }
                            }
                        }else{
                            for($i=0; $i<=5;$i++){
                            $res[$v->id][$i] =   self::getQuery($v->id, $i, $from, $to); 
                            }
                        }
                    }
                }elseif($kid = $cat->getKidsIds()){
                            foreach ($kid as $k) {
                               // for($i=0; $i<=5;$i++){
                                     $res[$k][$i] = self::getQuery($k, $i, $from, $to); 
                               // } 
                            }
                                 }else{
                                    // for($i=0; $i<=5;$i++){
                                     $res[$cat->id][$i] = self::getQuery($cat->id, $i, $from, $to); 
                                   //  }
                                  }*/
                                 // l();
         return $res;
     }
     public static function getToExcelNormaListCat($post){
          $cat = new Shopcategories((int)$post->cat_prognoz);
          $res = [];
          if($cat->id == 267){
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                            foreach ($kid as $k) {
                                $res[] = $k;
                            }
                        }else{
                            $res[] = $v->id;
                            
                        }
                    }
                }elseif($kid = $cat->getKidsIds()){
                            foreach ($kid as $k) {
                                $res[] = $k; 
                            }
                                 }else{
                                        $res[] = $v->id;
                                  }
         return $res;
     }
     /**
      * Виборка с БД остатков и продаж (понедельно)
      * @param type $c - категория
      * @param type $b - грейд
      * @param type $from - от
      * @param type $to - до
      * @return  ['ost','prod']
      */
     private static function getQuery($c, $b, $from, $to){
         
           $sq = "SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')";
        

    if($c){  $sq.=" and `ws_balance_category`.id_category  in ({$c}) ";}
  if($b > -1){ $sq.=" and `red_brands`.`greyd`= {$b}  ";}
 //l($sq);
      $tost  = wsActiveRecord::findByQueryFirstArray($sq)['ctn'];
         $rr = [];
         $rr['tost'] = $tost;
        $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '{$from}'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '{$to}'
     and `ws_balance_category`.id_category = {$c} 
         and `red_brands`.`greyd` = {$b} 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";
      // l($sql);
      // exit();
            $ok = wsActiveRecord::findByQueryArray($sql);
            foreach($ok as $k){
                $sql = "SELECT  sum(`ws_order_articles`.`count`) as suma 
                    FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
inner join  `red_brands` ON  `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
       and `red_brands`.`greyd` = ".$b." 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` = ".$c." ";

                  $z = wsActiveRecord::findByQueryFirstArray($sql);
                  
                  if($k->ctn > 0){
                     $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24)+1;
                      $d = $dn?$dn:1;
                      $ost = ceil($k->ctn/$d);
                      $rr['d'][] = $d;
                      $rr['all_ost'][] = $k->ctn;
                  }else{
                     $ost = 0; 
                  }
                  $prod = (int)($z['suma']);
                  
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
            } 
        return $rr;//['ost'=> $rr['ost'], 'prod'=>$rr['prod']];//self::prognozExcel();//[ceil($ost), ceil($prod)]; 
    }
    /**
     * 
     * @param type $arr
     * @return type
     */
    public static function prognozExcel($arr = []){
        $result = [];
        foreach($arr as $k => $v){
        if($v['prod']) {
            $max_p = max($v['prod']);
             $kl = array_search($max_p, $v['prod']);
              $m = $v['ost'][$kl]+$max_p;
        }else{
            $max_p = 0;
            $m = 0;
        }
        
      //  $count = count($v['ost']);
           // $t = $v['ost'][$count-1];
        $t = $v['tost'];
            
                $otk = $t - $m;
                    $result[$k] = $otk;
            }
            return $result;
    }
    /**
     * 
     * @param type $post
     * @return type
     */
     public static function getToExcelNormaDay($post){
         $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz);
                $res = [];
          if($cat->id == 267){
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                            foreach ($kid as $k) {
                                    $r = self::getMassNormaDay($k, $from, $to, false);  
                                    if($r['st']){ $res[$k] = $r['res'];} 
                                  
                            }
                        }else{
                           $r = self::getMassNormaDay($v->id, $from, $to, false);   
                           if($r['st']){ $res[$v->id] =$r['res'];}
                           
                        }
                    }
                }elseif($kid = $cat->getKidsIds()){
                            foreach ($kid as $k) {
                               $r = self::getMassNormaDay($k, $from, $to, false);  
                                  if($r['st']){ $res[$k] = $r['res'];}  
                                
                            }
                                 }else{
                                    $r = self::getMassNormaDay($v->id, $from, $to, false);
                                    if($r['st']){$res[$cat->id] = $r['res'];}
                                      
                                 
                                  }              
         return $res;
     }
     /**
      * В Excel
      * Прогноз догруза по дням с учотом периода з наростом продаж (3 дня)
      * @param type $arr
      * @return boolean
      */
     public static function prognozExceldey($arr = []){
        $prod = $arr['prod'];
        $ost = $arr['ost'];
        $gm = [];
        foreach ($prod as $k => $v) {
            $gm[$k][0] = $v;
            $gm[$k][1] = $prod[$k+1];
            $gm[$k][2] = $prod[$k+2];
        }
        $reee = [];
        foreach ($gm as $k => $va) {
            if($va[0] < $va[1] && $va[1] < $va[2]){
                $reee[$k] = $gm[$k];
            }
        }
        $c_r = count($reee);
        if($c_r > 1){
            $max = 0;
            $ind = 0;
            foreach ($reee as $k => $v) {
                if(array_sum($v) > $max){
                    $max = array_sum($v);
                    $ind = $k;
                }
            }
            $t = $reee[$ind];
            $reee = [];
            $reee[$ind] = $t;
        }

       $count = count($ost);
       $o_ost = 0;
       $o_add = 0;
       $max_p = 0;
       $otk = 0;
       if(count($reee) == 1){
           $keys = array_keys($reee);
           $firstKey = $keys[0];
           $max_p = $reee[$firstKey][2];//
           $o_add = $arr['add'][$firstKey]+$arr['add'][$firstKey+1]+$arr['add'][$firstKey+2];//общее количество добавленого товара за период топовых продаж
           $sd = $arr['add'][$count-1]+$arr['add'][$count-2]+$arr['add'][$count-3];// добалено за последние 3 дня
           
           $otk = $sd - $o_add;
           $o_ost = ($arr['ost'][$firstKey]+$arr['ost'][$firstKey+1]+$arr['ost'][$firstKey+2])/3;//средний остаток за 3 дня за период топовых продаж
           
            
        }
           if($otk < 0 ){
               return ['add'=>$o_add, 'prod'=>$max_p, 'ost'=>(int)$o_ost, 'prognoz'=>$otk*(-1)];
           }else{
               //  return ['add'=>$o_add, 'prod'=>$max_p, 'ost'=>(int)$o_ost, 'prognoz'=>$otk*(-1)];
              return false;
           }     
    }
    /**
     * Возвращает остатки и продажи по всем брендам в категории(с подкатегориями)
     * @param type $post
     * @return []
     */
     public static function getToExcelBrandinCategory($post){
          $from = date('Y-m-d',strtotime($post->from_prognoz));
                $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz);
                $c = '';
               if($cat->getKidsIds()){
                     $c = implode(',', $cat->getKidsIds()); 
               }else{
                   $c = $cat->id;
                }
                  $res = [];     
                  $brand =  self::getBrandBalance($c, $from, $to); 
                  foreach ($brand as $v) {
                     
                      if($v->ctn > 0){
                        $res[$v->id_brand] = self::getQueryBrand($c, $v->id_brand, $from, $to);
                      }
                  }
                  return $res;
     }
     /**
      * Возвращает остатки и продажи по всем брендам
      * @param type $post
      * @return type
      */
      public static function getToExcelAllBrand($post){
          $from = date('Y-m-d',strtotime($post->from_prognoz_brand));
                $to = date('Y-m-d', strtotime($post->to_prognoz_brand));
              
                  $res = [];     
                  $brand =  HomeAnalitics::dictinct_brand_balance($post); 
                 // l($brand);
                 // exit();
                  $cat = [];
                   $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['parent_id'=>0, 'active'=>1, 'id not in(85,106,267)']);
                    foreach ($cc as $v) {
                        if($kid = $v->getKidsIds()){
                            $cat[$v->name] = $kid;
                        }else{
                            $cat[$v->name] = $v->id;
                           
                        }
                    }
                 // l($cat);
                //  exit();
                  
                  foreach ($brand as $v) {
                     
                    //  if($v->ctn > 0){
                       // $res[$v->id] = self::getQueryBrand(false, $v->id, $from, $to);
                        
                       
                        
                       // $cat = HomeAnalitics::dictinct_category_in_brand_balance(['from'=>$from,'to'=>$to,'b'=>$v->id]); 
                       // l($cat);
                      //  exit();
                        foreach ($cat as $k=>$c) {
                           
                         //   $res[$v->id]['name'] = $c->name; 
                             $q = self::getQueryBrand(implode(',', $c), $v->id, $from, $to);
                             if(count($q['ost']) and array_sum($q['ost']) > 0){
                                 $res[$v->id][$k] = $q;
                             }
                        }
                       
                     // }
                  }
                 
                  return $res;
     }
     
     /**
      * Возвращает массив брендов в категории и сумму остатка
      * @param type $c - категории через запетую(1,2,3)
      * @param type $from (2019-05-05)
      * @param type $to (2019-05-10)
      * @return ['id_brand','name','ctn']
      */
      private static function getBrandBalance($c, $from, $to){
              $sql = "
SELECT  
    `ws_balance_category`.`id_brand`,
    `red_brands`.`name`,
    SUM( `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."'
     and `ws_balance_category`.id_category in( ".$c." )
GROUP BY  `ws_balance_category`.`id_brand`
 ";
             $list = wsActiveRecord::findByQueryArray($sql);
          return $list;
      }
      
      
      /**
       * Возвращает массивы остатка и продаж по бренду ( в категории или по всем брендам)
       * @param type $c = false or '1,2,3'
       * @param type $b - id brenda
       * @param type $from
       * @param type $to
       * @return ['ost'=>[],'prod'=>[]]
       */
     private static function getQueryBrand($c = false, $b, $from, $to){
         $sq = "SELECT 
SUM(`ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE  `ws_balance`.`date` =  DATE_FORMAT( NOW(), '%Y-%m-%d')";
        

    if($c){ $sq.=" and `ws_balance_category`.id_category  in (".$c." ) ";}
  if($b){$sq.=" and `ws_balance_category`.id_brand  in (".$b." ) ";}
 
      $tost  = wsActiveRecord::findByQueryFirstArray($sq)['ctn'];
         $rr = [];
         $rr['tost'] = $tost;
              $sql = "
SELECT WEEK(  `ws_balance`.`date` , -1 ) AS nedelya,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dbeg,
ADDDATE(  `ws_balance`.`date` ,INTERVAL( 6 - WEEKDAY(  `ws_balance`.`date` ) ) DAY ) AS dend,
SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) >=  '".$from."'
AND DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) <=  '".$to."' ";
              
  if($c){ $sql.=" and `ws_balance_category`.id_category  in (".$c." ) ";}
  if($b){$sql.=" and `ws_balance_category`.id_brand  in (".$b." ) ";}
  
  $sql.=" and `red_brands`.`id` = ".$b." 
GROUP BY  `nedelya` 
ORDER BY  `dbeg` ASC 
 ";

            $ok = wsActiveRecord::findByQueryArray($sql);
           
           
            foreach($ok as $k){
                $sql = "SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma 
                    FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
inner join  `red_brands` ON  `ws_articles`.`brand_id` = `red_brands`.`id` 
and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$k->dbeg."' 
and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$k->dend."' 
       and `red_brands`.`id` = ".$b." 
AND `ws_orders`.`status` not in(17)";
if($c){ $sql.= " and `ws_articles`.`category_id` in(".$c.")";}
if($b){ $sql.= " and `ws_articles`.`brand_id` = ".$b;}


                  $z = wsActiveRecord::findByQueryFirstArray($sql);
                  if($k->ctn > 0){
                      $dn = (strtotime($k->dend)-strtotime($k->dbeg))/(60*60*24);
                      $d = $dn?$dn:1;
                      $ost = (int)$k->ctn/$d;
                  }else{
                    $ost = 0; 
                  }
                  $prod = (int)($z['suma']);
                  
                  $rr['ost'][] = ceil($ost);
                  $rr['prod'][] = ceil($prod);
            }
            
         return ['ost'=> $rr['ost'], 'prod'=>$rr['prod'], 'tost' => $rr['tost']];
     }
     
     /**
      * Прогноз по текущим остаткам и по максимальным продажам
      * @param type $arr
      * @return type
      */
     public static function prognozBrand($arr){
        $result = [];
        $prod = $arr['prod']?$arr['prod']:[0];
        $ost = $arr['ost']?$arr['ost']:[0];
        $max_p = max($prod);
        $kl = array_search($max_p,$prod);
        $t = $arr['tost'];
       /* $v = $arr;
         if(count($v['prod'])) {
            
            
            $max_p = max($v['prod']);
            
             $kl = array_search($max_p, $v['prod']);
             
              $m = $v['ost'][$kl]+$max_p; 
        }else{
            $max_p = 0;
            
            $m = 0;
        }*/
        $m = $ost[$kl] + $max_p;// остаток на момент максимальных продаж + продажи
           
       // $otk = $m-$t;
        
       // $count = count($v['ost']);
        
        //$v['ost'][$count-1];

         $otk = $t - $m;

         $result[] = $otk;

            return $result;
       
    }
    
}
