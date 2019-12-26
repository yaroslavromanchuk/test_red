<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HomeAnalitics
 *
 * @author PHP
 */
class HomeAnalitics extends wsActiveRecord{
    /**
     * 
     * @param type $post
     * @param type $get
     * @return type
     */
     public static function sendPost($post = [], $get = []){
         switch ($post->method) {
                case 'ucenka_2': return self::ucenka_2();
                case 'ucenka': return self::ucenka();
                case 'ostatki': return self::ostatki();
                case 'konversiya': return  self::googleanalitics($post->from, $post->to);
                case 'delivery': return  self::swithDelivery($post);
                case 'shop': return  self::swithShop($post);
                case 'order': return  self::orders($post);
                case 'prognoz': return self::prognoz($post);
                case 'prognozBrand': return self::prognozBrand($post);
                case 'oborot_all': return self::oborot($post, 'all');
                case 'oborot_brand': return self::oborot($post, 'brand');
                case 'oborot_root_category': return self::oborot($post, 'root');
                case 'oborot_category': return self::oborot($post, 'category');
                case 'oborot_graid': return self::oborot($post, 'graid');    
                case 'order_bonus': return self::bonus();
                case 'distinct_brand': return self::dictinct_brand_balance($post);
                case 'sredniy_ostatok': return self::sredniy_ostatok($post);
                    
             default:
                 break;
         }
         
     }
     /**
      * 
      * @param type $get
      * @return type
      */
     public static function sendGet($get = []){
         switch ($get->method) {
                case 'balance_brand_in_category_to_excel': return self::balance_brand_in_category_to_excel($get);
                case 'balance_brand_all_to_excel': return self::balance_brand_all_to_excel($get);
                case 'balance_to_excel': return self::balance_to_excel($get);//graid
                case 'balance_brand_to_excel_dey': return self::balance_to_excel_dey($get);
                case 'procent_to_excel': return self::procent_to_excel($get);
             default:
                 break;
         }
         
     }
     /**
      * График сравнения остатков за два периода + оборот по каждому периоду
      * @param type $post
      * @return type
      */
     public static function sredniy_ostatok($post){
         if(isset($post->cat_prognoz)){
            
           //  echo $post->cat_prognoz;
             switch ((int)$post->cat_prognoz){
                 case 999 : $cat = ['parent_id'=>0, 'id not in (106, 85, 267)', 'active'=> 1]; break;
                 case 888 : $cat = [ 'Мужское' => '15,56', 'Женское' => '14,35', 'Детское' => '59,67', 'Unisex' => '54,254,244']; break;
                 default : $cat = ['parent_id'=>$post->cat_prognoz, 'id not in (106, 85, 267)', 'active'=> 1]; break;
             }
 
               //  return  $cat;
         }else{
             $cat = ['parent_id'=>0, 'id not in (106, 85, 267)', 'active'=> 1];
         }
          
        // return $cat;
                
                $d1 = round((strtotime($post->one_date_to)-strtotime($post->one_date_from))/(60*60*24));
                $d2 = round((strtotime($post->two_date_to)-strtotime($post->two_date_from))/(60*60*24));
                  $from1 = date('Y-m-d',strtotime($post->one_date_from));
                  $to1 = date('Y-m-d', strtotime($post->one_date_to));
                  $from2 = date('Y-m-d',strtotime($post->two_date_from));
                  $to2 = date('Y-m-d', strtotime($post->two_date_to));
                
               $r = [];
                 $r['one'] = 0;
                 $r['two'] = 0;  
                 
              if($post->cat_prognoz == 888){
                  foreach ($cat as $k => $value) {
                      $query = ['id in('.$value.')', 'active'=> 1];
                      
                      $c = wsActiveRecord::useStatic('Shopcategories')->findAll($query);
                      
                       $ob = [];
                        $one= 0;
                        $two = 0;
                      foreach ($c as $v) {  
                            if($v->getKidsIds()){
                                $ob[] = implode(',', $v->getKidsIds());
                                    $kid = implode(',', $v->getKidsIds());
                               }else{
                                   $ob[] = $v->id;
                                    $kid = $v->id;
                        }

                        $one += self::category_sredniy_ostatok($kid, $from1, $to1);

                        $two += self::category_sredniy_ostatok($kid, $from2, $to2);        
                 }
                 
                  if($one > 0 || $two > 0){
                      $kid =  implode(',', $ob);
                      $name = $k;
                                $r[$name]['one'] = ($one/$d1);
                                  $r[$name]['two'] = ($two/$d2);
                                  
                                 $prodaz_one =  self::prodazyCategoryPeriod($kid, $from1, $to1);
                                 $prod_one = $prodaz_one?$prodaz_one:1;
                                  $r[$name]['oborot_one'] =  ceil(($r[$name]['one']*$d1)/$prod_one);
                                  
                                $prodaz_two =  self::prodazyCategoryPeriod($kid, $from2, $to2);
                                 $prod_two = $prodaz_two?$prodaz_two:1;
                                $r[$name]['oborot_two'] =  ceil(($r[$name]['two']*$d2)/$prod_two);
                               
                                    $r['one'] += $one;//сумма остатка первого интервала
                                    $r['two'] += $two;//сумма остатка другого интервала
                            }
                  }
              }else{
                    $c = wsActiveRecord::useStatic('Shopcategories')->findAll($cat);
                         foreach ($c as $v) {
                            $name = $v->getRoutez();
                      
                        if($v->getKidsIds()){
                            $kid = implode(',', $v->getKidsIds());
                               }else{
                                   $kid = $v->id;
                        }
                        
                        $one = self::category_sredniy_ostatok($kid, $from1, $to1);

                        $two = self::category_sredniy_ostatok($kid, $from2, $to2);
                            
                            if($one > 0 || $two > 0){
                                $r[$name]['one'] = ($one/$d1);
                                  $r[$name]['two'] = ($two/$d2);
                                  
                                 $prodaz_one =  self::prodazyCategoryPeriod($kid, $from1, $to1);
                                 $prod_one = $prodaz_one?$prodaz_one:1;
                                  $r[$name]['oborot_one'] =  ceil(($r[$name]['one']*$d1)/$prod_one);
                                  
                                $prodaz_two =  self::prodazyCategoryPeriod($kid, $from2, $to2);
                                 $prod_two = $prodaz_two?$prodaz_two:1;
                                $r[$name]['oborot_two'] =  ceil(($r[$name]['two']*$d2)/$prod_two);
                               
                                    $r['one'] += $one;//сумма остатка первого интервала
                                    $r['two'] += $two;//сумма остатка другого интервала
                            }
                 }
     }
                 $res = [];
                 $res['t'] =  $from1.':'.$to1;//интервал  которы сравниваэм
                 $res['s'] =  $from2.':'.$to2;//интервал с которым сравниваэм
                 
                 $r1 = ceil($r['one']/$d1);//средний остаток по интервалу который сравниваем
                    unset($r['one']);
                 $r2 = ceil($r['two']/$d2);//средний остаток по интервалу с которым сравниваем
                    unset($r['two']);
                  $i = 0;
                  $res['a']['name'] =$from1.':'.$to1;
                  $res['a']['type'] = 'spline';
                  $res['b']['name'] =$from2.':'.$to2;
                  $res['b']['type'] = 'spline';
                 
                 foreach ($r as $key => $value) {
                     $res['table'][$i]['one'] = ceil($value['one']);
                     $res['table'][$i]['two'] = ceil($value['two']);
                     $res['table'][$i]['name'] = $key;
                     $res['table'][$i]['oborot_one'] = $value['oborot_one'];
                     $res['table'][$i]['oborot_two'] = $value['oborot_two'];
                      $res['y'][] = $key;
                     $res['a']['data'][] = round((($value['one']/$r1)*100),2);
                     $res['b']['data'][] = round((($value['two']/$r2)*100),2);
                      $i++;
                 }
         return $res;
     }
     /**
      * Сумма остатка за период по определенной категории
      * 
      * @param type $cat - (1,2)
      * @param type $from '2019-01-01'
      * @param type $to '2019-02-01'
      * @return type
      */
     private static function category_sredniy_ostatok($cat, $from, $to){
        $sql = "SELECT sum( `ws_balance_category`.`count` ) AS  `ctn`
                FROM  `ws_balance_category` 
                INNER JOIN  `ws_balance` ON  `ws_balance_category`.`id_balance` =  `ws_balance`.`id` 
                WHERE  `ws_balance`.`date` >=  '".$from."'
                        and `ws_balance`.`date` <= '".$to."'
                        and `ws_balance_category`.`id_category` in (".$cat.")  
                HAVING  `ctn` > 0";
     return  wsActiveRecord::findByQueryFirstArray($sql)['ctn'];
     }
     /**
      * Сумма продаж за период по определенной категории
      * @param type $c - (1,2)
      * @param type $from '2019-01-01'
      * @param type $to '2019-02-01'
      * @return type
      */
     private static function prodazyCategoryPeriod($c, $from, $to){
           return wsActiveRecord::findByQueryFirstArray("SELECT  sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$from."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$to."' 
AND `ws_orders`.`status` not in(17)
and `ws_articles`.`category_id` in (".$c.")")['suma'];  
     }
     
     /**
      * 
      * @param type $post
      * @return ['id','ctn','name']
      */
public static function dictinct_brand_balance($post){
   // return $post->from;
    $from = date('Y-m-d',strtotime($post->from_prognoz_brand));
    $to = date('Y-m-d', strtotime($post->to_prognoz_brand));
        $sql = "SELECT DISTINCT (
 `ws_balance_category`.`id_brand`
) AS  `id` , AVG(  `ws_balance_category`.`count` ) AS  `ctn` ,  `red_brands`.`name` 
FROM  `ws_balance_category` 
INNER JOIN  `ws_balance` ON  `ws_balance_category`.`id_balance` =  `ws_balance`.`id` 
INNER JOIN  `red_brands` ON  `ws_balance_category`.`id_brand` =  `red_brands`.`id` 
WHERE  `ws_balance`.`date` >=  '".$from."'
    and `ws_balance`.`date` <= '".$to."'
GROUP BY  `ws_balance_category`.`id_brand` 
HAVING  `ctn` >5
ORDER BY  `ctn` DESC";
       // return $sql;
     return  wsActiveRecord::findByQueryArray($sql);
    
}
/**
 * Получить все категории
 * @param array post['from','to','b']
 * @return type
 */
public static function dictinct_category_in_brand_balance($post){
   // return $post->from;
    $from = $post['from'];
    $to = $post['to'];
    $b = $post['b'];
        $sql = "SELECT DISTINCT (
 `ws_balance_category`.`id_category`
) AS  `id` ,  `ws_categories`.`name` 
FROM  `ws_balance_category` 
INNER JOIN  `ws_balance` ON  `ws_balance_category`.`id_balance` =  `ws_balance`.`id` 
INNER JOIN  `ws_categories` ON  `ws_balance_category`.`id_category` =  `ws_categories`.`id` 
WHERE  `ws_balance`.`date` >=  '".$from."'
    and `ws_balance`.`date` <= '".$to."'
        and `ws_balance_category`.`id_brand` = ".$b."
GROUP BY  `ws_balance_category`.`id_category` 
ORDER BY  `ws_categories`.`name` DESC";
      //  return $sql;
     return  wsActiveRecord::findByQueryArray($sql);
    
}
     /**
      * 
      * @param type $get
      * @return type
      */
     private static function procent_to_excel($get)
             {
        // l($get);
        // exit();
            $from = date('Y-m-d 00:00:00',strtotime($get->from_procent));
            $to = date('Y-m-d 23:59:59', strtotime($get->to_procent));
        //   $d1 = new DateTime($get->from_procent);
        //    $d2 = new DateTime($get->to_procent);

     //   $interval = date_diff($d2,$d1);

           
        //    $inter = $interval->format('%m');
            
          //   l($inter);
          //  exit();
            $p = [];
        
          require_once('PHPExel/PHPExcel.php');
        $filename = 'otchet_procent';
        
         $sql = "SELECT DISTINCT (
`ws_articles`.`category_id`
) AS  `cat` 
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
INNER JOIN  `ws_articles` ON  `ws_order_articles`.`article_id` =  `ws_articles`.`id` 
WHERE  `ws_orders`.`date_create` >=  '".$from."'
AND  `ws_orders`.`date_create` <=  '".$to."'
AND  `ws_order_articles`.`count` >0
ORDER BY  `ws_articles`.`category_id` ASC ";
         if(true){
             $res = [];
              $merth = date('F', strtotime($get->from_procent));
        foreach(wsActiveRecord::findByQueryArray($sql) as $c){
$sql1 = "SELECT  `ws_order_articles`.`count` as `ctn`,
IF(  `ws_order_articles`.`old_price` >0,  CEILING(`ws_order_articles`.`old_price`) ,  CEILING(`ws_order_articles`.`price`) ) as `start price`,
IF(  `ws_order_articles`.`option_price` >0,   CEILING(`ws_order_articles`.`option_price` * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 )) , IF(  `ws_order_articles`.`old_price` =0,  CEILING(
(
`ws_order_articles`.`price` * ( ( 100 -  `ws_orders`.`skidka` ) /100 ) ) * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 ) ) ,  CEILING(
`ws_order_articles`.`price` * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 )
)
)
) as `price_finish`,
CEILING((1-(IF(  `ws_order_articles`.`option_price` >0,   CEILING(`ws_order_articles`.`option_price` * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 )) , IF(  `ws_order_articles`.`old_price` =0,  CEILING(
(
`ws_order_articles`.`price` * ( ( 100 -  `ws_orders`.`skidka` ) /100 ) ) * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 ) ) ,  CEILING(
`ws_order_articles`.`price` * ( ( 100 -  `ws_order_articles`.`event_skidka` ) /100 )
)
)
)/IF(  `ws_order_articles`.`old_price` >0,  CEILING(`ws_order_articles`.`old_price`) ,  CEILING(`ws_order_articles`.`price`) )))*100) as `proc`
FROM  `ws_order_articles` 
INNER JOIN  `ws_orders` ON  `ws_order_articles`.`order_id` =  `ws_orders`.`id` 
INNER JOIN  `ws_articles` ON  `ws_order_articles`.`article_id` =  `ws_articles`.`id` 
WHERE  `ws_orders`.`date_create` >=  '".$from."'
and `ws_orders`.`date_create` <=  '".$to."'
AND  `ws_order_articles`.`count` >0
and `ws_articles`.`category_id` =".$c->cat." order by `proc` ASC";  

$c_n = wsActiveRecord::useStatic('Shopcategories')->findById($c->cat)->getRoutez();
          $res[$c_n] = self::to_procent(wsActiveRecord::findByQueryArray($sql1));
            
        }

        ksort($res);   
            $style = [];
            $style['width']['A'] = 50;
           // $style['width']['B:U'] = 15;
            
            $parametr = [];
           
            $parametr['header'][0][0] = 'Категории';
            $parametr['header'][0][1] = '0%';  
            $parametr['header'][0][2] = '';
            $parametr['header'][0][3] = '5-11%';
            $parametr['header'][0][4] = '';
            $parametr['header'][0][5] = '12-16%';
            $parametr['header'][0][6] = '';
            $parametr['header'][0][7] = '17-21%';
            $parametr['header'][0][8] = '';
            $parametr['header'][0][9] = '22-31%';
            $parametr['header'][0][10] = '';
            $parametr['header'][0][11] = '32-51%';
            $parametr['header'][0][12] = '';
            $parametr['header'][0][13] = '42-51%';
            $parametr['header'][0][14] = '';
            $parametr['header'][0][15] = '52-60%';
            $parametr['header'][0][16] = '';
            $parametr['header'][0][17] = '60<%';
            $parametr['header'][0][18] = '';
            $parametr['header'][0][19] = 'Общее';
                
                
                
                $parametr['header'][1][0] = '';
                $parametr['header'][1][1] = 'Колл';
                $parametr['header'][1][2] = 'Сумма';
                
                $parametr['header'][1][3] = 'Колл';
                $parametr['header'][1][4] = 'Сумма';
                
                $parametr['header'][1][5] = 'Колл';
                $parametr['header'][1][6] = 'Сумма';
                
                $parametr['header'][1][7] = 'Колл';
                $parametr['header'][1][8] = 'Сумма';
                
                $parametr['header'][1][9] = 'Колл';
                $parametr['header'][1][10] = 'Сумма';
                
                $parametr['header'][1][11] = 'Колл';
                $parametr['header'][1][12] = 'Сумма';
                
                $parametr['header'][1][13] = 'Колл';
                $parametr['header'][1][14] = 'Сумма';
                
                $parametr['header'][1][15] = 'Колл';
                $parametr['header'][1][16] = 'Сумма';
                
                $parametr['header'][1][17] = 'Колл';
                $parametr['header'][1][18] = 'Сумма';
                
                $parametr['header'][1][19] = 'Колл';
                $parametr['header'][1][20] = 'Сумма';
                   
            $style['merge']['A1:A2'] = true;
            $style['merge']['B1:C1'] = true;
            $style['merge']['D1:E1'] = true;
            $style['merge']['F1:G1'] = true;
            $style['merge']['H1:I1'] = true;
            $style['merge']['J1:K1'] = true;
            $style['merge']['L1:M1'] = true;
            $style['merge']['N1:O1'] = true;
            $style['merge']['P1:Q1'] = true;
            $style['merge']['R1:S1'] = true;
            $style['merge']['T1:U1'] = true;
            
            $style['font']['A1:U1'] = ['font'=>['bold'=>true],'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER], 'numberformat'=>['code'=>'@']];//
            $style['font']['A2:U2'] = ['font'=>['bold'=>true],'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];
            $style['font']['T3:T100'] = ['font'=>['bold'=>true, 'color'=>['rgb'=>'ff0000']]];
            $style['font']['U3:U100'] = ['font'=>['bold'=>true, 'color'=>['rgb'=>'ff0000']]];
            $i =  count($parametr['header']);
           
            foreach ($res as $kay => $val) {
                    $parametr['data'][$i][0] = $kay;
                    $parametr['data'][$i][1] = $val[0]['ctn'];
                    $parametr['data'][$i][2] = $val[0]['sum'];
                    $parametr['data'][$i][3] = $val[10]['ctn'];
                    $parametr['data'][$i][4] = $val[10]['sum'];
                    $parametr['data'][$i][5] = $val[15]['ctn'];
                    $parametr['data'][$i][6] = $val[15]['sum'];
                    $parametr['data'][$i][7] = $val[20]['ctn'];
                    $parametr['data'][$i][8] = $val[20]['sum'];
                    $parametr['data'][$i][9] = $val[30]['ctn'];
                    $parametr['data'][$i][10] = $val[30]['sum'];
                    $parametr['data'][$i][11] = $val[40]['ctn'];
                    $parametr['data'][$i][12] = $val[40]['sum'];
                    $parametr['data'][$i][13] = $val[50]['ctn'];
                    $parametr['data'][$i][14] = $val[50]['sum'];
                    $parametr['data'][$i][15] = $val[60]['ctn'];
                    $parametr['data'][$i][16] = $val[60]['sum'];
                    $parametr['data'][$i][17] = $val[70]['ctn'];
                    $parametr['data'][$i][18] = $val[70]['sum'];
                    $parametr['data'][$i][19] = $val[0]['ctn']+$val[10]['ctn']+$val[15]['ctn']+$val[20]['ctn']+$val[30]['ctn']+$val[40]['ctn']+$val[50]['ctn']+$val[60]['ctn']+$val[70]['ctn'];
                    $parametr['data'][$i][20] = $val[0]['sum']+$val[10]['sum']+$val[15]['sum']+$val[20]['sum']+$val[30]['sum']+$val[40]['sum']+$val[50]['sum']+$val[60]['sum']+$val[70]['sum'];
                    $i++;
                } 
                
                $parametr['title'] = $merth;
                $p[] = $parametr;
             }
               // $p[] = $parametr;
         return ['name' =>$filename, 'parametr'=>$p, 'style'=>$style];
         
            }
    /**
     * 
     * @param type $param
     * @return type
     */        
    private static function to_procent($param) {
                $r = [];
                $r[0] = ['ctn' => 0,'sum' => 0];
                $r[10] = ['ctn' => 0,'sum' => 0];
                $r[15] = ['ctn' => 0,'sum' => 0];
                $r[20] = ['ctn' => 0,'sum' => 0];
                $r[30] = ['ctn' => 0,'sum' => 0];
                $r[40] = ['ctn' => 0,'sum' => 0];
                $r[50] = ['ctn' => 0,'sum' => 0];
                $r[60] = ['ctn' => 0,'sum' => 0];
                $r[70] = ['ctn' => 0,'sum' => 0];
               
                foreach ($param as  $v) {
                    if($v->proc < 5){
                        $r[0]['ctn'] = $r['0']['ctn']+$v->ctn;
                        $r[0]['sum'] = $r['0']['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc >= 5 and $v->proc <= 11){
                        $r[10]['ctn'] = $r[10]['ctn']+$v->ctn;
                        $r[10]['sum'] = $r[10]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 11 and $v->proc <= 16){
                        $r[15]['ctn'] = $r[15]['ctn']+$v->ctn;
                        $r[15]['sum'] = $r[15]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 16 and $v->proc <= 21){
                        $r[20]['ctn'] = $r[20]['ctn']+$v->ctn;
                        $r[20]['sum'] = $r[20]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 21 and $v->proc <= 31){
                        $r[30]['ctn'] = $r[30]['ctn']+$v->ctn;
                        $r[30]['sum'] = $r[30]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 31 and $v->proc <= 41){
                        $r[40]['ctn'] = $r[40]['ctn']+$v->ctn;
                        $r[40]['sum'] = $r[40]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 41 and $v->proc <= 51){
                        $r[50]['ctn'] = $r[50]['ctn']+$v->ctn;
                        $r[50]['sum'] = $r[50]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 51 and $v->proc <= 60){
                        $r[60]['ctn'] = $r[60]['ctn']+$v->ctn;
                        $r[60]['sum'] = $r[60]['sum']+$v->price_finish * $v->ctn;
                    }elseif($v->proc > 60){
                        $r[70]['ctn'] = $r[70]['ctn']+$v->ctn;
                        $r[70]['sum'] = $r[70]['sum']+$v->price_finish * $v->ctn;
                    } 
                }
                return $r;
            }
            /**
             * 
             * @param type $post
             * @return type
             */
    private static function prognoz($post) {
         return Bufer::getNorma($post);
     }
     private static function prognozBrand($post) {
         return Bufer::getNormaPrognozBrand($post);
     }
     
     /**
      * 
      * @param type $post
      * @param type $type
      * @return type
      */
     private static function oborot($post, $type = '') {
         switch ($type) {
            case 'all': return Bufer::getNorma($post);
            case 'brand':  return Bufer::getNormaOborotBrand($post);
            case 'graid':  return Bufer::getNormaOborotGraid($post);
            case 'root': return Bufer::getNormaOborotCategory($post);
            case 'category': return Bufer::getNormaOborotCategory($post);

            default:
                  return Bufer::getNormaOborotCategory($post);
         }
     }
     
     private static function ucenka_2(){
         $sql = "SELECT  `ws_articles`.`ucenka` , SUM(  `ws_articles_sizes`.`count` ) AS ctn
FROM  `ws_articles` 
INNER JOIN  `ws_articles_sizes` ON  `ws_articles`.`id` =  `ws_articles_sizes`.`id_article` 
WHERE  `ws_articles_sizes`.`count` > 0
and `ws_articles`.status  = 3
GROUP BY  `ws_articles`.`ucenka` ";
$ucenka = wsActiveRecord::findByQueryArray($sql);
$s = 0;
foreach($ucenka as $c){
$result[$c->ucenka] = $c->ctn;
$s+=$c->ctn;
}
$result['sum'] = $s;

	
         return $result;
     }
     private static function ucenka(){
         
         $sql="SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat
FROM  `ucenka_history` 
WHERE DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 60 DAY ) 
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ";
$mas = [];
$i=0;
foreach(wsActiveRecord::findByQueryArray($sql) as $c){
$sql = "SELECT  `proc` , SUM(  `koll` ) AS ctn FROM   `ucenka_history` WHERE  DATE_FORMAT( `ctime` ,  '%Y-%m-%d' ) =  '".$c->dat."' GROUP BY  `proc` ";
$mas[$i]['x'] = $c->dat;

foreach(wsActiveRecord::findByQueryArray($sql) as $t){
$mas[$i][$t->proc] = $t->ctn?$t->ctn:0;
}
$i++;
}
return $mas;
     }
     private static function ostatki(){
         $type = 0;
          $ok = wsActiveRecord::findByQueryArray("
                SELECT DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) AS dat, SUM(  `ws_balance_category`.`count` ) AS ctn
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 30 
DAY ) 
GROUP BY  `ws_balance`.`id` 
ORDER BY  `dat` ASC   ");
		   foreach($ok as $k){
                       $r_ok['x'][] = $k->dat;
                       $r_ok['y'][] = (int)$k->ctn;
		   // $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn);
		   }
		    return $r_ok;
     }
     private static function visit(){
          $res = [];
                $ok2 = wsCustomerVisit::findByQueryArray("SELECT COUNT(  `id` ) AS  `visit` , SUM(  `total_number_of_pages` ) AS  `page` , SUM( IF(  `total_number_of_pages` =1, 1, 0 ) ) AS otkaz, DATE_FORMAT(  `ctime` ,  '%d.%m' ) AS  `dat` , DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS  `dat_p` 
FROM  `ws_visits` 
WHERE (customer_id NOT IN(1,2993,7341,7668,24150,26187,34608,34655,35971,36149,36431,37449,8005,24148,29397,36213,37075,22844,23029,27804,36399,37183,37307,22832,33929,20336,37484) OR  `customer_id` IS NULL
)
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' )");
                foreach ($ok2 as $key => $value) {
                    $res[$value->dat]['visit'] = $value->visit; 
                    $res[$value->dat]['page'] = $value->page; 
                    $res[$value->dat]['glubina'] = round(($value->page/$value->visit), 2); 
                    $res[$value->dat]['otkaz'] = round((($value->otkaz/$value->page)*100), 2);
                   // $dat = date('Y-m-d', strtotime($value->dat.'.2018'))$value->dat.
                $s_z =    OrderHistory::findByQueryFirstArray("SELECT count(`order_id`) as `ctn` FROM  `order_history` WHERE  `name` LIKE  '%Заказ оформлен%' and DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) = '$value->dat_p' ")['ctn'];
                $res[$value->dat]['konvers'] = round((($s_z/$value->visit)*100) , 2);
                }
                
                return $res;
     }
      private static function googleanalitics($from = '', $to = ''){
       // return $from;
        if($from == ''){$from = date('Y-m-d'); }
        if($to == ''){$to = date('Y-m-d'); }
       // $from = (string)"'".$from."'";
       // $to  = (string)"'".$to."'";
        require_once('Google/HelloAnalytics.php');
        
        $analytics = initializeAnalytics();
        
        $results = $analytics->data_ga->get('ga:57394917', $from, $to, 'ga:sessions, ga:users , ga:newUsers, ga:bounceRate,  ga:pageviews, ga:pageviewsPerSession');
        
        if (count($results->getRows()) > 0) {
    // Get the entry for the first entry in the first row.
    $res = $results->getRows()[0];
    $text['sessions'] = $res[0];
    $text['users'] = $res[1];
    $text['newUsers'] = $res[2];
    $text['otkaz'] = round($res[3], 2);
    $text['pageviews'] = $res[4];
    $text['pageviewsPerSession'] = round($res[5], 2);
    $sql = "SELECT count(`order_id`) as `ctn` FROM  `order_history` WHERE  `name` LIKE  '%Заказ оформлен%' and DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) >= '$from'  and  DATE_FORMAT(`ctime` ,  '%Y-%m-%d' ) <= '$to' ";
   // var_dump($sql, false);
    $k = OrderHistory::findByQueryFirstArray($sql)['ctn'];
 $text['konvers'] = round(($k/$res[0])*100, 2);
 
 
 $now =  strtotime($from); // текущее время (метка времени)
$your_date = strtotime($to); // какая-то дата в строке (1 января 2017 года)
$datediff = $your_date - $now; // получим разность дат (в секундах)
$text['dney'] = floor($datediff / (60 * 60 * 24))+1;
    $result = $text;
    //echo print_r($result);
    //$sessions = $rows[0][0];
  } else {
    $result = "No results found.\n";
  }
        return $result;
    } 
    
   private static function  swithDelivery($post){
        
       switch ($post->type) {
           case '35' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 3, 5 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                      
                    case '4' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` =4
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                    case '816' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 8, 16 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
                    case '9' :
                        $ok = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` = 9
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
			return $ok;
        default:
             $ok_m = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 3, 5 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
             $ok_up = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` =4
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
             $ok_np = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` 
IN ( 8, 16 ) 
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
              $ok_k = wsActiveRecord::findByQueryArray("
SELECT `id` , COUNT(  `id` ) AS ctn, 
DATE_FORMAT(  `date_create` ,  '%a %d.%m' ) AS  `date` , 
AVG( TIMEDIFF(  `order_go` ,  `date_create` ) ) / ( 24 *60 *60 ) AS time
FROM  `ws_orders` 
WHERE  `order_go` !=  '0000-00-00 00:00:00'
AND  `delivery_type_id` = 9
AND  `date_create` <=  '".date("Y-m-d 23:59:59", strtotime($this->post->to))."'
AND  `date_create` >=  '".date("Y-m-d 00:00:00", strtotime($this->post->from))."'
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `ws_orders`.`id` ASC  ");
            
            return ['m'=>$ok_m, 'up'=>$ok_up, 'np'=>$ok_np, 'k'=>$ok_k];
       }
   
    }
    
    private static function  swithShop($post){
        
        switch ($post->type) {
	case 'h_a' ://
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count` =0, 1,`ws_order_articles`.`count`)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 

GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND `ws_orders`.`status`  in(2,7,15)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[(int)$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[(int)$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[(int)$k->dat] ? $p[(int)$k->dat] : 0, 'ret'=>$re[(int)$k->dat] ? $re[(int)$k->dat] : 0 );
		   }
		   
			return $r_ok;
			case 'n_d_a' :
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
        case 'n_h_a':
            $ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
            
            return $r_ok;
                        
			case 'm_d_a' :
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
        case 'm_h_a':
            $ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`delivery_type_id` in(3,5)
AND `ws_orders`.`status`  in(2,7)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$p = array();
$re = array();
foreach($pay as $r){
$p[$r->dat] = $r->suma;
}
foreach($ret as $r){
$re[$r->dat] = $r->suma;
}
			foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->suma, $type => 0, 'pay'=>$p[$k->dat] ? $p[$k->dat] : 0, 'ret'=>$re[$k->dat] ? $re[$k->dat] : 0 );
		   }
		   
			return $r_ok;
	default : 
 
 
			return $r_ok;
        }
    }
    private static function orders($post) {
        switch ($post->type) {
            case 'h' : 
			$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn FROM  `ws_orders` WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) AND status NOT IN (5,7,17) GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		   $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
            case 'm_h' : 
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
			 case 'm_d' : 
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
		     return $r_ok;
			case 'n_h' :
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
		
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat.':00', 'y' =>(int)$k->ctn, $type => 0 );
		   }
			 return $r_ok;
			 case 'n_d' :
			$ok = 	wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status NOT IN (5,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
		   foreach($ok as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
			 return $r_ok;
		case 'dely':
		$days_arr_dely = array();
	$days_dely = wsActiveRecord::findByQueryArray("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`delivery_type_id` 
FROM  `ws_orders` 
WHERE  STATUS IN ( 100, 9, 15,16 ) 
AND  `ws_orders`.`delivery_type_id` !=0 and `ws_orders`.`delivery_type_id` !=12
GROUP BY  `ws_orders`.`delivery_type_id` 
ORDER BY  `ctn` ASC");
$mas = array(3=>'Победа', 4=>'Укр.Почта', 5=>'Строителей', 8=>'НП:ОО', 9=>'Курьер', 16=>'НП:НП');
	foreach($days_dely as $d){
	$days_arr_dely['name'][] = $mas[$d->delivery_type_id];
	$days_arr_dely['koll'][] = (int)$d->ctn;
}
	return $days_arr_dely;
	case 'status':
	$days_arr_status = array();
	$days_status = wsActiveRecord::findByQueryArray("SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`status` 
FROM  `ws_orders` 
WHERE `ws_orders`.`status` IN (100,1,9,15,16) and `ws_orders`.`from_quick` != 1
GROUP BY  `ws_orders`.`status` 
ORDER BY  `ws_orders`.`status` ASC");
        
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($days_status as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	return $days_arr_status;
	case 'quick':
	//$quick_arr = array();
	$quick = wsActiveRecord::findByQueryArray("SELECT  `status` , COUNT(  `id` ) AS  `ctn` 
FROM  `ws_orders` 
WHERE  `from_quick` = 1
AND  `status` != 17 
AND  `quick` = 1
AND DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
GROUP BY  `status` ");
            
$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(0=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($quick as $d){
	$days_arr_status[] = array('label'=>$mas[$d->status], data=>array(1,(int)$d->ctn), 'color'=>$color[$d->status]);
}
	
	return $days_arr_status;
            default : 
			$ok = "SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) AS dat, COUNT(  `id` ) AS ctn
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) 
AND status NOT IN (5,7,17) 
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d %H' ) 
ORDER BY  `dat` ASC ";
			
		   
		   foreach(wsActiveRecord::findByQueryArray($ok) as $k){
		    $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn, $type => 0 );
		   }
		    return $r_ok;
		   }
        
    }
    public static function createOrder(){
       
             /**
            * оформленные заказы
            */        
	  $days_arr = [];
          $week_arr =[];
          $month_arr = [];
          $year_arr = [];
          
	$days = wsActiveRecord::findByQueryArray(""
        . "SELECT DATE_FORMAT(  `ctime` ,  '%H' ) AS dat, SUM(  `sum_order` ) AS money "
        . "FROM  `order_history`  "
        . "WHERE DATE_FORMAT(  `ctime` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) "
        . "AND `name` LIKE  'Заказ оформлен'"
        . "GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d %H' ) "
        . "ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($days as $d){
            $s += $d->money;
	$days_arr['koll'][] = $d->money;
}
$days_arr['summa'] = $s;
	
	$week = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%d' ) AS dat, SUM(  `sum_order` ) AS money
FROM   `order_history` 
WHERE  DATE_FORMAT(  `ctime` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND `name` LIKE  'Заказ оформлен' 
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
 $s = 0;
	foreach($week as $d){
            $s += $d->money;
	$week_arr['koll'][] = $d->money;
}
$week_arr['summa'] = $s;

$month = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) AS dat, SUM(  `sum_order` ) AS money
FROM   `order_history` 
WHERE DATE_FORMAT(  `ctime` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `name` LIKE  'Заказ оформлен'
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($month as $d){
            $s += $d->money;
	$month_arr['koll'][] = $d->money;
}
$month_arr['summa'] = $s;

$year = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ctime` ,  '%Y-%m' ) AS dat, SUM(  `sum_order` ) AS money
FROM  `order_history`  
WHERE DATE_FORMAT(  `ctime` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND `name` LIKE  'Заказ оформлен'
GROUP BY DATE_FORMAT(  `ctime` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
        $s = 0;
	foreach($year as $d){
            $s += $d->money;
	$year_arr['koll'][] = $d->money;
}
$year_arr['summa'] = $s;
return  ['year'=>$year_arr, 'month' =>$month_arr, 'week'=> $week_arr, 'days'=> $days_arr];
    }
    
    public static function paymentOrder(){
                /**
         * оплеченые заказы
         */
$days_arr_op = [];       
$week_arr_op = [];
$month_arr_op = [];
$year_arr_op = [];



$days_op = wsActiveRecord::findByQueryArray(""
        . "SELECT DATE_FORMAT(  `admin_pay_time` ,  '%H' ) AS dat,  SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit "
        . "FROM  `ws_orders`  "
        . "WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) = DATE_FORMAT( NOW( ) ,  '%Y%m%d' ) "
        . " AND status IN (8,14) "
        . "GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d %H' ) "
        . "ORDER BY  `dat` ASC ");

foreach($days_op as $d){
	$days_arr_op['am'][$d->dat] = $d->money;
	$days_arr_op['dep'][$d->dat] = $d->deposit;
	$days_arr_op['koll'][] = $d->deposit+$d->money;
}

$week_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 
DAY ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($week_op as $d){
	$week_arr_op['am'][$d->dat] = $d->money;
	$week_arr_op['dep'][$d->dat] = $d->deposit;
	$week_arr_op['koll'][] = $d->deposit+$d->money;
}

$month_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($month_op as $d){
	$month_arr_op['am'][$d->dat] = $d->money;
	$month_arr_op['dep'][$d->dat] = $d->deposit;
	$month_arr_op['koll'][] = $d->deposit+$d->money;
}

$year_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND status IN (8,14) 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
	foreach($year_op as $d){
	$year_arr_op['am'][$d->dat] = $d->money;
	$year_arr_op['dep'][$d->dat] = $d->deposit;
	$year_arr_op['koll'][] = $d->deposit+$d->money;
}

        return  ['year'=>$year_arr_op, 'month' =>$month_arr_op, 'week'=> $week_arr_op, 'days'=> $days_arr_op];
    }
    
    public static function comment(){
     return  wsActiveRecord::findByQueryArray("
    SELECT `ws_orders`.* 
    FROM  `ws_orders` 
    WHERE  `ws_orders`.`status` = 100
    AND  `id` NOT IN (
        SELECT  `ws_order_remarks`.`order_id` 
        FROM  `ws_order_remarks`
                    )
    AND  `customer_id` NOT IN (
            SELECT  `id` 
            FROM  `ws_customers` 
            WHERE  `customer_type_id` >1
                                )
    AND  `comments` !=  ''
    ORDER BY  `ws_orders`.`date_create` ASC");
     
    }
    public static function chek(){
        $ok = wsActiveRecord::findByQueryArray("
    SELECT DATE_FORMAT(  `date_create` ,  '%d.%m' ) AS dat, count(`id`) as `ctn`, SUM(  `amount`+ `deposit`) AS `summ`, id
    FROM  `ws_orders` 
    WHERE DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 30 DAY ) 
AND status != 17 
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `id` ASC ");
$m = [];
foreach ($ok as $value) {
    $m['label'][] = (int)($value->summ/$value->ctn);
    $m['date'][] = $value->dat;
}
return $m;
        
    }
    public static function bonus(){
    
        $sql_c = "SELECT COUNT(  `id` ) AS  `ctn` 
FROM  `ws_customers` 
WHERE  `segment_id` 
IN ( 1, 2, 4, 5, 6 )";
$customer = wsActiveRecord::findByQueryFirstArray($sql_c);

$result['c_ctn'] = (int)$customer['ctn'];
$result['c_summ'] = (int)($customer['ctn']*100);
        
         $sql = "SELECT COUNT(  `ws_orders`.`id` ) AS  `ctn` , SUM(  `ws_orders`.`bonus` +  `ws_orders`.`deposit` +  `ws_orders`.`amount` ) AS  `summa` 
FROM  `ws_orders` 
INNER JOIN  `ws_customers` ON  `ws_orders`.`customer_id` =  `ws_customers`.`id` 
WHERE  `ws_customers`.`segment_id` 
IN ( 1, 2, 4, 5, 6 ) 
AND  `ws_orders`.`bonus` >0
AND  `ws_orders`.`date_create` >  '2019-05-01 00:00:00'
AND  `ws_orders`.`status` NOT 
IN ( 2, 5, 7, 17 )";
$orders = wsActiveRecord::findByQueryFirstArray($sql);

$result['r_ctn'] = (int)$orders['ctn'];
$result['r_summ'] = (int)$orders['summa'];


//$result['sum'] = $s;

	
         return $result;
     
        
    }
    public static function brandsubscribe(){
        $sql = "SELECT COUNT( `id` ) AS ctn
FROM `red_brands_subscribe_customer`
WHERE `active` = 1";
        
        $sql2 = "SELECT *
FROM `red_brands_subscribe_customer`
WHERE `active` = 1
GROUP BY `brand_id`";
        
        $list =  wsActiveRecord::useStatic('BrandSubscribeCustomer')->findByQuery($sql2);
        $c = 0;
        foreach ($list as $b){
                $c += $b->brandsub->getCountsub();
        }
        
        return [
            'sub'=>wsActiveRecord::findByQueryFirstArray($sql)['ctn'],
            'order'=> $c
            ];
    }
    /**
     * Баланс брендов в категории в иксель файл
     * @param type $get
     * @return type
     */
    private static function balance_brand_in_category_to_excel($get){
       
        
             $res = Bufer::getToExcelBrandinCategory($get);
       
                 $br = [];
                foreach($res as $k => $v){
                    $br[$k] =  Bufer::prognozBrand($v);
                }
                
                $p = [];
                $style['width']['A'] = 30;
                $style['width']['B'] = 12;
                $style['font']['A1:B1'] = ['font'=>['bold'=>true]];
                $p['header'][0][0] = 'Бренд';
                $p['header'][0][1] = 'Буфер';
                 $i = 1;
                foreach ($br as $ke => $value) {
                    if($value[0] != 0){
                    $p['data'][$i][0] = wsActiveRecord::useStatic('Brand')->findById($ke)->name;
                    $p['data'][$i][1] = $value[0];
                    $i++;
                    }
                }
                
                 $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style];
    }
    /**
     * 
     * @param type $get
     * @return type
     */
    private static function balance_brand_all_to_excel($get){
      
             $res = Bufer::getToExcelAllBrand($get);
             //l($res);
            // exit();
                 $br = [];
                foreach($res as $k => $v){
                    // l($k.'=>');
                   //  l($v);
                   foreach ($v as $key => $value) {
                       $br[$k][$key] =  Bufer::prognozBrand($value);
                   }
                    
                }
                
               
                
                $p = [];
                $style['width']['A'] = 30;
                $style['width']['B'] = 30;
                $style['width']['C'] = 12;
                $style['font']['A1:C1'] = ['font'=>['bold'=>true]];
                $p['header'][0][0] = 'Бренд';
                $p['header'][0][1] = 'Категория';
                $p['header'][0][2] = 'Буфер';
                 $i = 1;
                foreach ($br as $ke => $value) {
                    
                    $p['data'][$i][0] = wsActiveRecord::useStatic('Brand')->findById($ke)->name;
                    $i++;
                    foreach ($value as $key => $c) {
                        if($c[0] != 0){
                        $p['data'][$i][0] = '';
                        $p['data'][$i][1] = $key; 
                        $p['data'][$i][2] = $c[0];
                        $i++;
                        }
                    }
                  //  $i++;
                    
                }
                $p['title'] = date('d.m.Y',strtotime($get->from_prognoz_brand)).'_'.date('d.m.Y', strtotime($get->to_prognoz_brand));
               // l($p);
              //  exit();
                
                // $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>'Прогноз по брендам_'.date('d.m.Y',strtotime($get->from_prognoz_brand)).'_'.date('d.m.Y', strtotime($get->to_prognoz_brand)), 'parametr'=>[0=>$p], 'style'=>$style];
    }
    /**
     * Скачать отчет продаж по грейдам
     * @param type $get
     * @return type
     */
    private static function balance_to_excel($get){
         $res = Bufer::getToExcelNorma($get);
         
          $style['width']['A'] = 50;
            $style['width']['B'] = 15;
            $style['width']['C'] = 12;
            $style['width']['D'] = 12;
             $style['width']['E'] = 12;
              $style['width']['F'] = 12;
               $style['width']['G'] = 12;
                $style['width']['H'] = 12;
                 $style['font']['A1:H1'] = ['font'=>['bold'=>true]];
                $p = [];
                $p['header'][0][0] = 'Категория';
                $p['header'][0][1] = 'Без грейда';
                $p['header'][0][2] = 'Грейд 1';
                $p['header'][0][3] = 'Грейд 2';
                $p['header'][0][4] = 'Грейд 3';
                $p['header'][0][5] = 'Грейд 4';
                $p['header'][0][6] = 'Грейд 5';
                $p['header'][0][7] = 'Общее';
                
                $gr = [];
                foreach($res as $k => $v){$gr[$k] =  Bufer::prognozExcel($v);}
                $i = 1;
                foreach($gr as $k => $v){
                    $cat = new Shopcategories($k);
                    $p['data'][$i][0] = $cat->getRoutez();
                    $p['data'][$i][1] = $v[0];
                    $p['data'][$i][2] = $v[1];
                    $p['data'][$i][3] = $v[2];
                    $p['data'][$i][4] = $v[3];
                    $p['data'][$i][5] = $v[4];
                    $p['data'][$i][6] = $v[5];
                    $p['data'][$i][7] = ($v[0]+$v[1]+$v[2]+$v[3]+$v[4]+$v[5]);
                    $i++;
                }
                 $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style];
    }
    
    private static function balance_to_excel_dey($get){
         $res = Bufer::getToExcelNormaDay($get);
        //   echo '<pre>';
        // print_r($res);
         // echo '</pre>';
        // exit();
         $p = [];
                $p['header'][0][0] = 'Категория';
                $p['header'][0][1] = 'Добавлено';
                $p['header'][0][2] = 'Макс.продано';
                $p['header'][0][3] = 'Ср.Остаток';
                $p['header'][0][4] = 'Нужно догрузить';
                
                $gr = [];
                foreach($res as $k => $v){
                    $r = Bufer::prognozExceldey($v);
                   if(count($r) > 1){ $gr[$k] =  $r;}
                }
          //       echo '<pre>';
       //  print_r($gr);
       //   echo '</pre>';
       //  exit();
                $i = 1;
                foreach($gr as $k => $v){
                  
                    $cat = new Shopcategories($k);
                    $p['data'][$i][0] = $cat->getRoutez();
                    $p['data'][$i][1] = $v['add'];
                    $p['data'][$i][2] = $v['prod'];
                    $p['data'][$i][3] = $v['ost'];
                    $p['data'][$i][4] = $v['prognoz'];
                    $i++;
                    
                }
                 $style = [];
            $style['width']['A'] = 50;
            $style['width']['B'] = 12;
            $style['width']['C'] = 15;
            $style['width']['D'] = 12;
            $style['width']['E'] = 20;
             $style['font']['A1:E1'] = ['font'=>['bold'=>true]];
                 $cat = wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
                return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style];
    }
}
