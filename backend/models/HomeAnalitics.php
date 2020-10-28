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
                case 'prognoz_table': return Bufer::getNormaTable($post); 
                case 'prognozBrand': return self::prognozBrand($post);
                case 'oborot_all': return self::oborot($post, 'all');
                case 'oborot_all_monch': return self::oborot($post, 'oborot_all_monch');
                case 'oborot_brand': return self::oborot($post, 'brand');
                case 'oborot_root_category': return self::oborot($post, 'root');
                case 'oborot_category': return self::oborot($post, 'category');
                case 'oborot_graid': return self::oborot($post, 'graid');
                case 'oborot_graid_category': return self::oborot($post, 'graid_category');
                case 'order_bonus': return self::bonus();
                case 'distinct_brand': return self::dictinct_brand_balance($post);
                case 'sredniy_ostatok': return self::sredniy_ostatok($post);
                case 'realization': return self::realization($post);
                case 'order_gryde_period': return self::order_gryde_period($post);
                case 'top_cat': return self::TopCat($post);
                case 'list_category': return self::ListCategory($post);
                case 'balance_to_excel': return self::balance_to_excel($post);//graid
                case 'balance_to_excel_list_cat': return Bufer::getToExcelNormaListCat($post);//graid
                    case 'asb_ostatok': return self::getABCostatok($post);//
                    case 'asb_order': return self::getABCorder($post);//
                
                    
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
                case 'top_cat': return self::TopCatExcel($get);
                case 'marga_period': return self::getMargaPeriod($get);
                    
             default:
                 break;
         }
         
     }
      public function getABCorder($post){
          $from = $post->from;
          $to = $post->to;
          $type = $post->type;
          $select = "";
          $inner = "";
          $where = "";
          $order_by = " proc_m DESC ";
          switch ($post->group){
              case 'tovar': $group_by = "a.`id`"; $select = "a.`id` , concat(a.`model`, ' ', a.brand) as model, "; break;
              case 'model': $group_by = "a.`model`"; $select = " a.`model`, "; break;
              case 'category': $group_by = "a.`category_id`"; $select ="c.`h1`, "; $inner="inner join `ws_categories` as c ON c.`id` = a.`category_id`"; break;
              case 'brand': $group_by = "a.`brand_id`"; $select ="a.`brand`, ";  break;      
              case 'graid': $group_by = "b.`greyd`";  $select ="g.`name`, "; $inner="inner join `red_brands` as b ON b.`id` = a.`brand_id` inner join `red_brands_greyd` as g ON g.`greyd_id` = b.`greyd`"; break;
                
              default: $group_by = "a.`id`"; $select = "a.`id` , a.`model`, "; 
          }
          switch ($post->interval){
            case 'all':  $having = ""; break;
            case 'a': $having = "having proc_m > 60"; break;
            case 'b': $m1 = 40; $m2 = 60; $having = "having proc_m > 40 and proc_m <= 60"; break;
            case 'c': $m1= -100; $m2 = 40; $having = "having  proc_m <= 40"; break;
            default:  $having = "";
        }
         

          $sql = "SELECT {$select} 
              SUM( ra.`count` ) AS `prod_shtuk` ,
              SUM( IF( ra.`option_price` , ra.`option_price` , ra.`price` ) * ra.`count` ) AS `prod_grn` ,
              SUM( IF( a.`min_price` , a.`min_price` , 0 ) * ra.`count`) AS `sb_price` ,
              SUM( IF( ra.`option_price` , ra.`option_price` , ra.`price` ) * ra.`count` - IF( a.`min_price` , a.`min_price` , 0 ) * ra.`count`) AS `marga` ,
              ROUND( (
SUM( IF( ra.`option_price` , ra.`option_price` , ra.`price` ) * ra.`count` ) - SUM( IF( a.`min_price` , a.`min_price` , 0 ) * ra.`count` ) ) / SUM( IF( ra.`option_price` , ra.`option_price` , ra.`price` ) * ra.`count` ) *100
) AS `proc_m`
FROM `ws_order_articles` AS ra
INNER JOIN `ws_orders` AS r ON r.`id` = ra.`order_id`
AND r.`status` NOT
IN ( 7, 2, 17 )
AND r.`date_create` >= '{$from} 00:00:00' and r.`date_create` <= '{$to} 23:59:59' 
INNER JOIN `ws_articles` AS a ON a.`id` = ra.`article_id`
{$inner}
WHERE ra.`count` >0 and a.`min_price` > 1
{$where}
GROUP BY {$group_by}
{$having}
ORDER BY {$order_by}";
          
         $res =  wsActiveRecord::findByQueryArray($sql);
         $t = "";
         if($res){
             $flag = false;
if($type == 'view'){
    $flag = true;
}
$data = "";
if($flag){ $data = "-data"; }
             $t .= "<table class='table' id='abc-order-result-table{$data}'>";
    $t .= '<thead>';
    $t .= "<tr>";
            switch ($post->group){
              case 'tovar': $t.="<th>ID</th><th>Название</th>"; break;
              case 'model':  $t.="<th>Модель</th>"; break;
              case 'category':  $t.="<th>Категория</th>"; break;
              case 'brand':  $t.="<th>Бренд</th>";  break;      
              case 'graid':  $t.="<th>Грейд</th>"; break;
                
              default:  $t.="<th>ID</th><th>Название</th>";
          }
            $t .= "<th>Колл.</th>"
            . "<th>Продажи грн.</th>"
            . "<th>СС</th>"
            . "<th>Маржа</th>"
            . "<th>%</th>";
            $t .= "</tr>";
     $t .= '</thead>';
      $t .= '<tbody>';
      $prod_shtuk = 0;
      $prod_grn = 0;
      $sb_price = 0;
      $marga = 0;
      foreach ($res as $a){
          $prod_shtuk +=$a->prod_shtuk;
          $prod_grn += $a->prod_grn;
          $sb_price += $a->sb_price;
          $marga += $a->marga;
           $t .= "<tr>";
            switch ($post->group){
              case 'tovar': $t.="<td>{$a->id}</td><td>{$a->model}</td>"; break;
              case 'model':  $t.="<td>{$a->model}</td>"; break;
              case 'category':  $t.="<td>{$a->h1}</td>"; break;
              case 'brand':  $t.="<td>{$a->brand}</td>";  break;      
              case 'graid':  $t.="<td>{$a->name}</td>"; break;
                
              default:   $t.="<td>{$a->id}</td><td>{$a->model}</td>";
          }
          $t .= "<td>".Number::formatExcel($a->prod_shtuk)."</td>";
          $t .= "<td>".Number::formatExcel($a->prod_grn)."</td>";
          $t .= "<td>".Number::formatExcel($a->sb_price)."</td>";
          $t .= "<td>".Number::formatExcel($a->marga)."</td>";
          $t .= "<td>".Number::formatExcel($a->proc_m)."</td>";
            $t .= "</tr>";

      }
       $t .= "</tbody>";
    $t.="<tfoot>";
     $t .= "<tr>";
    switch ($post->group){
              case 'tovar': $t.="<td></td><td></td>"; break;
              case 'model':  $t.="<td></td>"; break;
              case 'category':  $t.="<td></td>"; break;
              case 'brand':  $t.="<td></td>";  break;      
              case 'graid':  $t.="<td></td>"; break;
                
              default:   $t.="<td></td><td></td>";
          }
           $t .= "<td>".Number::formatExcel($prod_shtuk)."</td>";
          $t .= "<td>".Number::formatExcel($prod_grn)."</td>";
          $t .= "<td>".Number::formatExcel($sb_price)."</td>";
          $t .= "<td>".Number::formatExcel($marga)."</td>";
             $pr = ceil(($prod_grn-$sb_price)/$prod_grn *100);
          $t .= "<td>".Number::formatExcel($pr)."</td>";
           $t .= "</tr>";
     $t.="</tfoot>";
    $t .="</table>";
      
         }
          
        //  $arr = [$m1, $m2, $from, $to, $type, $group];
           return $t;
         // return $t;
      }
     public function getABCostatok($post){
         $t = "";
         list($from, $to) = explode('-', $post->interval);
         
          $type = $post->type;
           $inner = "";
            $where = "a.`stock` NOT LIKE '0' AND a.`status` = 3";
          $order_by = " `oborot` DESC ";
          switch ($post->group){
              case 'tovar': $group_by = "a.`id`"; /**/
                  $select = "SUM(s.`coming`) as pr,  sum(if(ar.`count`, ar.`count`, 0)) as prod, count(distinct(voz.id)) as vozrat, sum(distinct(IF(voz.count,voz.count,0 ))) as ret,   a.`id` , a.`brand` , a.`model` , a.`price` , a.`min_price` , (a.`price` - a.`min_price`) AS `marga` , a.`stock` , DATEDIFF( CURDATE( ) , a.`data_new` ) AS oborot, a.`views`, a.`sezon`, a.`size_type`, a.`image`,  ROUND((a.`price` - a.`min_price`)/a.`price`*100) as m_proc ";
                  $inner="inner join `ws_articles_sizes` as s ON s.`id_article` = a.id left join ws_order_articles as ar ON ar.article_id = a.id left join ws_order_articles_vozrat as voz ON voz.article_id  = a.id";
                  $having = "oborot > {$from} AND oborot <= {$to}"; break; //
              case 'model': 
                  $group_by = "a.`model`";
                  $select = "distinct(SUM(s.`coming`)) as pr, sum(if(ar.`count`, ar.`count`, 0)) as prod, count(voz.id) as vozrat, sum(IF(voz.count,voz.count,0 )) as ret, a.`model`, SUM(a.`price`) as price , SUM(a.`min_price`) as min_price , SUM(a.`price` - a.`min_price`) AS `marga` ,  SUM(a.`stock`) as stock, SUM(a.`views`) as views, ROUND((SUM(a.`price`) - SUM(a.`min_price`))/SUM(a.`price`)*100) as m_proc ";
                  $inner="inner join `ws_articles_sizes` as s ON s.`id_article` = a.id left join ws_order_articles as ar ON ar.article_id = a.id  left join ws_order_articles_vozrat as voz ON voz.article_id  = a.id";
                  $order_by = " `marga` DESC ";
                  $having = "m_proc  > {$from} AND m_proc  <= {$to}";
                  break;
              case 'category': $group_by = "a.`category_id`"; $select ="c.`h1`, "; $inner="inner join `ws_categories` as c ON c.`id` = a.`category_id`"; break;
              case 'brand': $group_by = "a.`brand_id`"; $select ="a.`brand`, ";  break;      
              case 'graid': $group_by = "b.`greyd`";  $select ="g.`name`, "; $inner="inner join `red_brands` as b ON b.`id` = a.`brand_id` inner join `red_brands_greyd` as g ON g.`greyd_id` = b.`greyd`"; break;
                
              default: $group_by = "a.`id`";  $select = "a.`id` , a.`brand` , a.`model` , a.`price` , a.`min_price` , (a.`price` - a.`min_price`) AS `marga` , a.`stock` , DATEDIFF( CURDATE( ) , a.`data_new` ) AS oborot, a.`views`, a.`sezon`, a.`size_type`, a.`image`"; $having = "oborot > {$from} AND oborot <= {$to}";
          }
          
         
          
          
    $sql = "SELECT {$select}
        FROM `ws_articles` as a
        {$inner}
        WHERE {$where}
          group by  {$group_by}
        HAVING {$having}
        ORDER BY {$order_by}
   
";
//Limit 0, 10
//l($sql);
$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
//l($articles);
if($articles){
    $prihod = 0;
$ostatok = 0;
$ostatok_grn = 0;
$sebestoimost = 0;
$marga = 0;
    $data = "";
    $flag = false;
if($type == 'view' && $post->group == 'tovar'){
    $flag = true;
}
        if($flag){ $data = "-data"; }
    $t .= "<table class='table' id='abc-result-table{$data}'>";
    $t .= '<thead>';
    $t .= "<tr>"
            . "<th>id</th>"

            . "<th>Гендер</th>"
            . "<th>Сезон</th>"
            . "<th>Бренд</th>"
             . "<th>Модель</th>"
             . "<th>Цена</th>"
             . "<th>СС</th>"
             . "<th>Маржа</th>"
             . "<th>Приход</th>"
            . "<th>Остаток</th>"
            . "<th>Д/С</th>"
            . "<th>Просмотры</th>"
            . "<th>Продажи</th>"
            . "<th>Возвраты</th>";
               if($flag){ $t .= "<th>Фото</th>";}
            $t .= "</tr>";
     $t .= '</thead>';
      $t .= '<tbody>';
    foreach ($articles as $a){
        $ostatok_grn+=$a->price;
        $price = Number::formatExcel($a->price);
        
        $cc = Number::formatExcel($a->min_price);
        $sebestoimost+=$a->min_price;
        $marg = Number::formatExcel($a->marga);
        $marga+=$a->marga;
        if($flag){  
        $img = $a->getImagePath('small_basket');
        $orig_img = $a->getImagePath();
        }
        $pr =  $a->pr;
          $prihod+=$pr;
     //  foreach ($a->sizes as $s){
          //  $pr+=$s->coming;
         //   $prihod+=$s->coming;
      //  }
        $prod = $a->prod;
        //foreach ($a->orders as $o){
        //    $prod+=$o->count;
      //  }
        $ret = $a->vozrat;
       // foreach ($a->returns as $r){
         //  $ret+=$r->count; 
       // }
        $ost = $a->stock+$a->ret;
        $ostatok+=$ost;
        $t .= "<tr>"
                . "<td>{$a->id}</td>"
        
                . "<td>{$a->sex->name}</td>"
                        . "<td>{$a->name_sezon->name}</td>"
                        . "<td>{$a->brand}</td>"
                        . "<td>{$a->model}</td>"
                        . "<td>{$price}</td>"
                        . "<td>{$cc}</td>"
                        . "<td>{$marg}</td>"
                        . "<td>{$pr}</td>"
                        . "<td>{$ost}</td>"
                        . "<td>{$a->oborot}</td>"
                        . "<td>{$a->views}</td>"
                        . "<td>{$prod}</td>"
                        . "<td>{$a->returns->count()}</td>";  
                             if($flag){  $t .= "<td class='galery'><a href='{$orig_img}'><img  data-src=\"{$orig_img}\" src='{$img}'></a></td>";}
               $t .= "</tr>";
    }
    $t .= "</tbody>";
    $t.="<tfoot>";
       $t.="<tr>"
               . "<td></td>"
               . "<td></td>"
               . "<td></td>"
               . "<td></td>"
               . "<td></td>"
               . "<td><b>".Number::formatExcel($ostatok_grn)."</b></td>"
               . "<td><b>".Number::formatExcel($sebestoimost)."</b></td>"
               . "<td><b>".Number::formatExcel($marga)."</b></td>"
               . "<td><b>".Number::formatExcel($prihod)."</b></td>"
               . "<td><b>".Number::formatExcel($ostatok)."</b></td>"
               . "<td></td>"
               . "<td></td>"
               . "<td></td>"
               . "<td></td>"
               . "</tr>";
    $t.="</tfoot>";
    $t .="</table>";
}
         return $t;
     }


     public function getMargaPeriod($post){
        // l($post);
         //exit();
         $from = $post->from;
         $to = $post->to;
          $style = [];
         switch($post->group_by){
                    case '1':  $group_by = "`ws_articles`.`id`"; $name = "tovar"; break;//tovar
                    case '2':  $group_by = "`ws_articles`.`brand_id`"; $name = "brand"; $style['width']['A'] = 0; break;//brand
                    case '3': $group_by = "`ws_articles`.`model`"; $name = "model"; $style['width']['B'] = 0;  break;//model
                    case '4': $group_by = "`ws_articles`.`brand_id`, `ws_articles`.`model`"; $name = "brand_model"; break;//brand+model
                default : $group_by = "`ws_articles`.`id`"; $name = "tovar"; break;
                }
 require_once('PHPExel/PHPExcel.php');
 
        $filename = 'marga_'.$name.'_'.$from.'_'.$to;
        
           // $style['width']['A:C'] = 20;
            //$style['width']['D:M'] = 12;
            $style['width']['C'] = 20;
            
            $parametr = [];
           
            $parametr['header'][0][0] = 'Модель';
            $parametr['header'][0][1] = 'Бренд';  
            $parametr['header'][0][2] = 'Грейд';
            $parametr['header'][0][3] = 'Остаток';
            $parametr['header'][0][4] = 'Дней на сайте';
            $parametr['header'][0][5] = 'Расход';
            $parametr['header'][0][6] = 'В вале шт';
            $parametr['header'][0][7] = 'Сумма';
            $parametr['header'][0][8] = 'Себестоимость';
            $parametr['header'][0][9] = 'Маржа';
            $parametr['header'][0][10] = 'В вале М';
            $parametr['header'][0][11] = 'с/с ед';
            $parametr['header'][0][12] = 'маржа на ед';
            $parametr['header'][0][13] = 'маржа на ед/день';
          
            
            $style['font']['A1:N1'] = ['font'=>['bold'=>true],'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];//
            $i =  count($parametr['header']);
            $gride =[
                0 => [
                    'name' =>'Без грейда',
                   // 'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ],
               1 => [
                    'name' =>'Грейд 1',
                   // 'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ],
                2 => [
                    'name' =>'Грейд 2',
                   // 'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ],
                3 => [
                    'name' =>'Грейд 3',
                  //  'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ],
                4 => [
                    'name' =>'Грейд 4',
                   // 'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ],
                5 => [
                    'name' =>'Грейд 5',
                    //'x' =>0,
                    'py' =>5,
                    'my' => 9,
                ]
            ];
            $all_ostatok = 0;
            $all_prod = 0;
            $all_summa = 0;
            $all_sob = 0;
            $all_marga = 0;
            
            
$res2 = [];
           foreach($gride as $k => $g){
               $sql = "SELECT `ws_articles`.`id` AS `id` ,
             `ws_articles`.`model` ,
             `ws_articles`.`brand` ,
g.name AS greyd,
SUM(DISTINCT `ws_articles`.`stock`) AS `ostatok` ,
SUM(if(`ws_articles`.`stock`>0,DATEDIFF('{$to}', `ws_articles`.`data_new`),DATEDIFF(`ws_orders`.`date_create`, `ws_articles`.`data_new`))) as `d_site`,
SUM( or_ar.`count` ) AS `prod` ,
SUM( IF( or_ar.`option_price` , or_ar.`option_price`, or_ar.`price` ) * or_ar.`count` )  AS `prod_grn` ,
SUM(IF( `ws_articles`.`min_price` , `ws_articles`.`min_price` , 0 ))  AS `sb_price` ";
$sql .= ", SUM( IF( or_ar.`option_price` , or_ar.`option_price`, or_ar.`price` ) - IF( `ws_articles`.`min_price` , `ws_articles`.`min_price` , 0 )) as `marga`";
$sql .= "FROM `ws_articles`
LEFT JOIN (SELECT * from `ws_order_articles` where `count` > 0 ORDER BY `ws_order_articles`.`order_id` DESC) as or_ar ON or_ar.`article_id` = `ws_articles`.`id` 
INNER JOIN `ws_orders` ON `ws_orders`.`id` = or_ar.`order_id` 
INNER JOIN `red_brands` AS b ON b.`id` = `ws_articles`.`brand_id`
INNER JOIN `red_brands_greyd` AS g ON g.greyd_id = b.`greyd`
WHERE `ws_orders`.`date_create` >= '{$from} 00:00:00'
AND `ws_orders`.`date_create` <= '{$to} 23:59:59'
and `ws_articles`.`status` = 3 and  b.`greyd` = {$k}
GROUP BY $group_by order by `ws_articles`.`brand_id`, `ws_articles`.`model` asc";
//l($sql);
//exit();
               $res = wsActiveRecord::findByQueryArray($sql);
               if($post->ostatok == 'true'){
               $ids = [];
               foreach ($res as $a){
                   $ids[] = $a->id;
               }
               $str_ids = implode(',',$ids);
      $sql2 = "SELECT `ws_articles`.`id` AS `id` ,
          `ws_articles`.`model` ,
          `ws_articles`.`brand` ,
          g.name AS greyd,
          SUM( DISTINCT `ws_articles`.`stock` ) AS `ostatok` ,
          SUM( DATEDIFF( NOW(), `ws_articles`.`data_new` ) ) AS `d_site` ,
          0 AS `prod` ,
          0 AS `prod_grn` ,
          SUM( IF( `ws_articles`.`min_price` , `ws_articles`.`min_price` , 0 ) ) AS `sb_price` ,
          0 AS `marga`
FROM `ws_articles`
INNER JOIN `red_brands` AS b ON b.`id` = `ws_articles`.`brand_id`
INNER JOIN `red_brands_greyd` AS g ON g.greyd_id = b.`greyd`
WHERE `ws_articles`.`stock` NOT LIKE '0'
AND `ws_articles`.`status` =3
AND b.`greyd` = {$k}
AND `ws_articles`.`id` NOT IN ({$str_ids})
GROUP BY $group_by order by `ws_articles`.`brand_id`, `ws_articles`.`model` asc";    
               $res2 = wsActiveRecord::findByQueryArray($sql2);
           }
             //  l($res);
              //  l($res2);
             //   l(array_merge($res,$res2));
              //  exit();
               $sum_ostatok = 0;
               $sum_prod = 0;
               $sum_prod_grn = 0;
               $sum_sb_price = 0;
               $sum_marga = 0;
               $sum_marga_prod = 0;
               $c_g = 0;
            foreach (array_merge($res,$res2) as  $val) {
                
                    $parametr['data'][$i][0] = $val->model;
                    $parametr['data'][$i][1] = $val->brand;
                    $parametr['data'][$i][2] = $val->greyd;
                        //$graid = $val['greyd'];
                    $parametr['data'][$i][3] = $val->ostatok;
                $sum_ostatok+=$parametr['data'][$i][3];
                    $parametr['data'][$i][4] = $val->d_site;
                    $parametr['data'][$i][5] = $val->prod;
                $sum_prod+= $parametr['data'][$i][5];
                    $parametr['data'][$i][6] = '';
                    $parametr['data'][$i][7] = $val->prod_grn;
                $sum_prod_grn+=$parametr['data'][$i][7];
                    $parametr['data'][$i][8] = $val->sb_price;
                $sum_sb_price+=$parametr['data'][$i][8];
                    $parametr['data'][$i][9] = $val->marga;
                     //$parametr['data'][$i][9] = round($parametr['data'][$i][5]>0?$parametr['data'][$i][7]/$parametr['data'][$i][5]-$parametr['data'][$i][8]:0,2);
                $sum_marga+=$parametr['data'][$i][9];
                    $parametr['data'][$i][10] = '';
                    $parametr['data'][$i][11] = round($parametr['data'][$i][8]/($parametr['data'][$i][5]>0?$parametr['data'][$i][5]:1),2);
                    $parametr['data'][$i][12] = round($parametr['data'][$i][9]/($parametr['data'][$i][5]>0?$parametr['data'][$i][5]:1),2);
                    $parametr['data'][$i][13] = round($parametr['data'][$i][4]>0?$parametr['data'][$i][9]/$parametr['data'][$i][4]:$parametr['data'][$i][9],2);
                        $sum_marga_prod +=  $parametr['data'][$i][13];
                    $parametr['data'][$i][14] = $val->id;
                    $i++;
                    $c_g++;
                }
                $parametr['data'][$i][0] = '';
                $parametr['data'][$i][1] = '';
                $parametr['data'][$i][2] = $g['name'].' Итог';
                $parametr['data'][$i][3] = $sum_ostatok; 
                $parametr['data'][$i][4] = ''; 
                $parametr['data'][$i][5] = $sum_prod;
                $parametr['data'][$i][6] = ''; 
                $parametr['data'][$i][7] = $sum_prod_grn;
                $parametr['data'][$i][8] = $sum_sb_price;
                $parametr['data'][$i][9] = $sum_marga;
                $parametr['data'][$i][10] = ''; 
                $parametr['data'][$i][11] =  round($parametr['data'][$i][8]/$parametr['data'][$i][5]?$parametr['data'][$i][5]:1,2);
                $parametr['data'][$i][12] =  round($parametr['data'][$i][9]/$parametr['data'][$i][5]?$parametr['data'][$i][5]:1,2); 
                $parametr['data'][$i][13] = round($sum_marga_prod/$c_g, 2);
                $gride[$k]['x'] = $i;
                    $all_ostatok += $parametr['data'][$i][3];
                    $all_prod += $parametr['data'][$i][5];
                    $all_summa += $parametr['data'][$i][7];
                    $all_sob += $parametr['data'][$i][8];
                    $all_marga += $parametr['data'][$i][9];
                    $i++;
                    $style['font']['A'.$i.':N'.$i] = ['font'=>['bold'=>true]];//
                
           }
          // $i++;
          
           $parametr['data'][$i][0] = '';
           $parametr['data'][$i][1] = ''; 
           $parametr['data'][$i][2] = 'Общий итог';
           $parametr['data'][$i][3] = $all_ostatok; 
           $parametr['data'][$i][4] = ''; 
            $parametr['data'][$i][5] = $all_prod;
            $parametr['data'][$i][6] = ''; 
            $parametr['data'][$i][7] = $all_summa;
            $parametr['data'][$i][8] = $all_sob;
            $parametr['data'][$i][9] = $all_marga;
            $parametr['data'][$i][11] = $parametr['data'][$i][8]/$parametr['data'][$i][5];
            $parametr['data'][$i][12] = $parametr['data'][$i][9]/$parametr['data'][$i][5];
             $style['font']['A'.$i.':N'.$i] = ['font'=>['bold'=>true]];//
           // l($parametr['data']);
          $c=  count($parametr['data']);
            foreach ($parametr['data'] as $k => &$d){
                if($k==$c){ break;}
                $d[6] =  round(($d[5]/$all_prod*100),2).'%';
                $d[10] =  round(($d[9]/$all_marga*100),2).'%';
               // l($d);
            }
           // l($parametr['data']);
           //  exit();
            
           // foreach ($gride as $gg){
            //    if(isset($gg['x'])){
            //    $parametr['data'][$gg['x']][6] = ceil($parametr['data'][$gg['x']][$gg['py']]/$all_prod*100).' %';
             //   $parametr['data'][$gg['x']][10] = ceil($parametr['data'][$gg['x']][$gg['my']]/$all_marga*100).' %';
             //   }
           // }
                
                $parametr['title'] = 'МАРЖА';
                $p[] = $parametr;
         return ['name' =>$filename, 'parametr'=>$p, 'style'=>$style];
     }

     public function ListCategory($post){
         $res = [];
        // $from = date('Y-m-d',strtotime($post->from_prognoz));
           //     $to = date('Y-m-d', strtotime($post->to_prognoz));
                $cat = new Shopcategories((int)$post->cat_prognoz);
          if($cat->id == 267){
                    $cc = wsActiveRecord::useStatic('Shopcategories')->findAll(['id in (33,14,15,54,59,146)']);
                    foreach ($cc as $v) {
                       // $res[$v->id] = $v->getRoutez(); 
                        if($kid = $v->getKids()){
                            foreach ($kid as $k) {
                                     $res[$k->id] = $k->getRoutez(); 
                                     if($kk = $k->getKids()){
                                         foreach ($kk as $d) {
                                     $res[$d->id] = $d->getRoutez();
                                         }
                                     }
                            }
                        }
                    }
                }elseif($kid = $cat->getKids()){
                   // $res[$cat->id] = $cat->getRoutez(); 
                            foreach ($kid as $k) {
                                     $res[$k->id] = $k->getRoutez(); 
                                     if($kk = $k->getKids()){
                                         foreach ($kk as $d) {
                                     $res[$d->id] = $d->getRoutez();
                                         }
                                     }

                            }
                                 }else{
                                     $res[$cat->id] = $cat->getRoutez(); 
                                 }
             asort($res);
             $ress = [];
             foreach ($res as $k=> $r){
                 $ress[] =[
                     'id'=> $k,
                     'name' => $r
                 ];
             }
         return $ress;
     }
     
     public function TopCatExcel($post)
             {
         $limit = "LIMIT 0 , 10";
         $from = $post->from;
         $to = $post->to;
        // $order_by = " ORDER BY `suma` DESC";
        // $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
       //  $group_by = "`ws_categories`.`id`";
       //  $type = " SELECT `ws_categories`.`id` , `ws_categories`.`h1` AS `name`";
         switch($post->type){
             case '1':
                 $group_by = "`ws_categories`.`id`";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                  $type = " SELECT `ws_categories`.`id` , `ws_categories`.`h1` AS `name`";
                  $inner = "INNER JOIN `ws_categories` ON `ws_articles`.`category_id` = `ws_categories`.`id`";
                  break;
             case '2':
                $group_by = "`ws_articles`.`brand_id` ";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                 $type = "SELECT `ws_articles`.`brand_id` as id , `ws_articles`.`brand` AS `name`";
                 $inner = "";
                 break;
             default: 
                  $group_by = "`ws_categories`.`id`";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                  $type = " SELECT `ws_categories`.`id` , `ws_categories`.`h1` AS `name`";
                  $inner = "INNER JOIN `ws_categories` ON `ws_articles`.`category_id` = `ws_categories`.`id`";
                 break;
         }
                 if(!empty($post->limit)){
                    $limit = "LIMIT 0 , ".(int)$post->limit; 
                    
                 }
                 if(!empty($post->ed)){
                     
                     switch ($post->ed){
                         case '1':
            $order_by = " ORDER BY `suma` DESC";
            $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                             break;
                         case '2':
            $order_by = " ORDER BY `suma` DESC";
            $select = ",SUM(IF(`ws_order_articles`.`option_price`>0,`ws_order_articles`.`option_price`, `ws_order_articles`.`price`)) as `suma`";
                             break;
                         case '3':
            $order_by = " ORDER BY `suma` DESC";
            $select = ", (SUM(IF(`ws_order_articles`.`option_price`>0,`ws_order_articles`.`option_price`, `ws_order_articles`.`price`) * `ws_order_articles`.`count`)/(SUM( `ws_order_articles`.`count` ))-IF( `ws_articles`.`min_price` , `ws_articles`.`min_price` , 0 )) as `suma`";
                             break;
                     }
                 }
                 $sql = "
                    {$type}
                    {$select}
FROM `ws_order_articles`
INNER JOIN `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
INNER JOIN `ws_articles` ON `ws_order_articles`.`article_id` = `ws_articles`.`id`
{$inner}
WHERE DATE_FORMAT( `ws_orders`.`date_create` , '%Y%m%d' ) >= DATE_FORMAT( '{$from}' , '%Y%m%d' )
and DATE_FORMAT( `ws_orders`.`date_create` , '%Y%m%d' ) <= DATE_FORMAT( '{$to}' , '%Y%m%d' )
AND `ws_orders`.`status` NOT IN ( 17 )
GROUP BY {$group_by}
{$order_by}
{$limit}  
";
         $ok = wsActiveRecord::findByQueryArray($sql);
     $dn = (strtotime($to)-strtotime($from))/(60*60*24);
    $d = $dn?$dn:1;
    $res = [];
    $bal_select = "";
    $bal_where = " WHERE `date` >= '{$from}' and `date` <= '{$to}'";
     switch($post->type){
                 case '1': $bal_select = "SELECT SUM(`ws_balance_category`.`count`) as avg FROM `ws_balance` "
                         . "inner join ws_balance_category ON ws_balance_category.id_balance = ws_balance.id and ws_balance_category.id_category = ";
                         break;
                 case '2': $bal_select = "SELECT SUM(`ws_balance_category`.`count`) as avg FROM `ws_balance` "
                         . "inner join ws_balance_category ON ws_balance_category.id_balance = ws_balance.id and ws_balance_category.id_brand = ";
                         break;
             }
         foreach ($ok as $c){
             $res[$c->id]['name'] = $c->name;
             $res[$c->id]['suma'] = ceil($c->suma);
             ////
             $bal = $bal_select.$c->id.$bal_where;
            
$res[$c->id]['sr'] = ceil(wsActiveRecord::findByQueryFirstArray($bal)['avg']/$d);
         }
          require_once('PHPExel/PHPExcel.php');
        $filename = 'otchet_top_cat_'.date('Y-m-d');
         $style = [];
            $style['width']['A'] = 50;
            $style['width']['B:C'] = 15;
            
            $parametr = [];
           
            $parametr['header'][0][0] = 'Категории';
            $parametr['header'][0][1] = 'Куплено';  
            $parametr['header'][0][2] = 'Средний остаток';
          
            
            $style['font']['A1:C1'] = ['font'=>['bold'=>true],'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];//
            $i =  count($parametr['header']);
           
            foreach ($res as  $val) {
                    $parametr['data'][$i][0] = $val['name'];
                    $parametr['data'][$i][1] = $val['suma'];
                    $parametr['data'][$i][2] = $val['sr'];
                    $i++;
                } 
                
                $parametr['title'] = 'TOP_category';
                $p[] = $parametr;
         return ['name' =>$filename, 'parametr'=>$p, 'style'=>$style];
     }
     
     
     public function TopCat($post){
         $limit = "LIMIT 0 , 10";
         $from = $post->from;
         $to = $post->to;
        switch($post->type){
             case '1':
                 $group_by = "`ws_categories`.`id`";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                  $type = " SELECT `ws_categories`.`id` , `ws_categories`.`h1` AS `name`";
                  $inner = "INNER JOIN `ws_categories` ON `ws_articles`.`category_id` = `ws_categories`.`id`";
                  $bal_select = "SELECT SUM(`ws_balance_category`.`count`) as avg FROM `ws_balance` "
                         . "inner join ws_balance_category ON ws_balance_category.id_balance = ws_balance.id and ws_balance_category.id_category = ";
                  break;
             case '2':
                $group_by = "`ws_articles`.`brand_id` ";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                 $type = "SELECT `ws_articles`.`brand_id` as id , `ws_articles`.`brand` AS `name`";
                 $inner = "";
                 $bal_select = "SELECT SUM(`ws_balance_category`.`count`) as avg FROM `ws_balance` "
                         . "inner join ws_balance_category ON ws_balance_category.id_balance = ws_balance.id and ws_balance_category.id_brand = ";
                 break;
             default: 
                  $group_by = "`ws_categories`.`id`";
                  $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                 $order_by = " ORDER BY `suma` DESC";
                  $type = " SELECT `ws_categories`.`id` , `ws_categories`.`h1` AS `name`";
                  $inner = "INNER JOIN `ws_categories` ON `ws_articles`.`category_id` = `ws_categories`.`id`";
                  $bal_select = "SELECT SUM(`ws_balance_category`.`count`) as avg FROM `ws_balance` "
                         . "inner join ws_balance_category ON ws_balance_category.id_balance = ws_balance.id and ws_balance_category.id_category = ";
                 break;
         }
                 if(!empty($post->limit)){
                    $limit = "LIMIT 0 , ".(int)$post->limit; 
                    
                 }
                 if(!empty($post->ed)){
                     
                     switch ($post->ed){
                         case '1':
            $order_by = " ORDER BY `suma` DESC";
            $select = ",SUM( IF( `ws_order_articles`.`count` >0, `ws_order_articles`.`count` , 1 ) ) AS `suma`";
                             break;
                         case '2':
            $order_by = " ORDER BY `suma` DESC";
            $select = ",SUM(IF(`ws_order_articles`.`option_price`>0,`ws_order_articles`.`option_price`, `ws_order_articles`.`price`) * `ws_order_articles`.`count`) as `suma`";
                             break;
                         case '3':
            $order_by = " ORDER BY `suma` DESC";
            $select = ", (SUM(IF(`ws_order_articles`.`option_price`>0,`ws_order_articles`.`option_price`, `ws_order_articles`.`price`) * `ws_order_articles`.`count`)/(SUM( `ws_order_articles`.`count` ))-IF( `ws_articles`.`min_price` , `ws_articles`.`min_price` , 0 )) as `suma`";
                             break;
                     }
                 }
                 $sql = "
                     {$type}
                     {$select}
FROM `ws_order_articles`
INNER JOIN `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
INNER JOIN `ws_articles` ON `ws_order_articles`.`article_id` = `ws_articles`.`id`
{$inner}
WHERE DATE_FORMAT( `ws_orders`.`date_create` , '%Y%m%d' ) >= DATE_FORMAT( '{$from}' , '%Y%m%d' )
    and DATE_FORMAT( `ws_orders`.`date_create` , '%Y%m%d' ) <= DATE_FORMAT( '{$to}' , '%Y%m%d' )
AND `ws_orders`.`status` NOT
IN ( 17 ) and `ws_articles`.`status` = 3
GROUP BY {$group_by}
{$order_by}
{$limit}  
";

         $ok = wsActiveRecord::findByQueryArray($sql);
     $date = [];
     $date['labels'] = [];
     $date['data'] = [];
     $date['data2'] = [];
     $date['data3'] = [];
     $dn = (strtotime($to)-strtotime($from))/(60*60*24);
    $d = $dn?$dn:1;
    
    $bal_where = " WHERE `date` >= '{$from}' and `date` <= '{$to}'";
         foreach ($ok as $c){
             $date['labels'][] = $c->name;
             $date['data'][] = $c->suma;
             
 $bal = $bal_select.$c->id.$bal_where;
$date['data3'][] = ceil(wsActiveRecord::findByQueryFirstArray($bal)['avg']/$d);
         }
         return $date;
     }

     public function order_gryde_period($post){
         $flag = false;
         if($post->type == 2) {$flag = true;}
         $g = $post->gryde;
        $d1 = round((strtotime($post->one_date_to_gryde)-strtotime($post->one_date_from_gryde))/(60*60*24));
        $d2 = round((strtotime($post->two_date_to_gryde)-strtotime($post->two_date_from_gryde))/(60*60*24));
        
                  $from1 = date('Y-m',strtotime($post->one_date_from_gryde));
                  $to1 = date('Y-m', strtotime($post->one_date_to_gryde));
                  $from2 = date('Y-m', strtotime( '-1 year', strtotime($post->one_date_from_gryde)));
                 // $from2 = date('Y-m',strtotime($post->two_date_from_gryde));
                  $to2 = date('Y-m', strtotime('-1 year', strtotime($post->one_date_to_gryde)));
                  //$to2 = date('Y-m', strtotime($post->two_date_to_gryde));
                  
                  $z = wsActiveRecord::findByQueryArray("
                        SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%m' ) as monch,
                        SUM(IF(`ws_order_articles`.`option_price`,(`ws_order_articles`.`option_price`*`ws_order_articles`.`count`),(`ws_order_articles`.`price`*`ws_order_articles`.`count`))) as suma,
                        SUM( `ws_order_articles`.`count` ) AS ctn
                        FROM  `ws_orders`
                        inner join `ws_order_articles` ON `ws_orders`.`id` = `ws_order_articles`.`order_id`
                        inner join `ws_articles` ON `ws_order_articles`.`article_id` = `ws_articles`.`id`
                        inner join `red_brands` ON `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE 
DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' )  >= '".$from1."' 
AND DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' )  <= '".$to1."' 
AND `ws_orders`.`status` not in(17, 7, 2)
AND `red_brands`.`greyd` = ".$g."
AND `ws_order_articles`.`count` > 0
group by monch
");
                  $sql = "
                        SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%m' ) as monch,
                        SUM(IF(`ws_order_articles`.`option_price`,(`ws_order_articles`.`option_price`*`ws_order_articles`.`count`),(`ws_order_articles`.`price`*`ws_order_articles`.`count`))) as suma,
                        SUM( `ws_order_articles`.`count` ) AS ctn
                        FROM  `ws_orders`
                        inner join `ws_order_articles` ON `ws_orders`.`id` = `ws_order_articles`.`order_id`
                        inner join `ws_articles` ON `ws_order_articles`.`article_id` = `ws_articles`.`id`
                        inner join `red_brands` ON `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE 
DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' )  >= '".$from2."' 
AND DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m' )  <= '".$to2."' 
AND `ws_orders`.`status` not in(17, 7, 2)
AND `red_brands`.`greyd` = ".$g."
AND `ws_order_articles`.`count` 
group by monch
";
                  
                  $y = wsActiveRecord::findByQueryArray($sql);
                  $c = 1;
                  $c1 = count($z);
                  $c2 = count($y);
                  if($c2 > $c1){
                      $c = 2;
                  }
                  
                  $r_ok = [];
                  $res = [];
                   $res['a']['name'] =$from1.':'.$to1;
                  $res['a']['type'] = 'spline';
                  $res['b']['name'] =$from2.':'.$to2;
                  $res['b']['type'] = 'spline';
                 
		   foreach($z as $k){
                       if($c == 1){
                        $res['y'][] = (int)$k->monch;
                       }
                       
                       $r_ok['x'][] = $k->monch;
                        if($flag){
                           $r_ok['y'][] = (int)$k->ctn;
                           $res['a']['data'][] = (int)$k->ctn;
                       }else{
                           $r_ok['y'][] = (int)$k->suma;
                           $res['a']['data'][] = (int)$k->suma;
                       }
                      // $r_ok['z'][] = (int)$k->summ;
		   // $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn);
		   }
                   foreach ($y as $yy) {
                       if($c == 2){
                        $res['y'][] = (int)$yy->monch;
                       }
                        if($flag){
                           $r_ok['z'][] = (int)$yy->ctn;
                           $res['b']['data'][] = (int)$yy->ctn;
                       }else{
                           $r_ok['z'][] = (int)$yy->suma;
                           $res['b']['data'][] = (int)$yy->suma;
                       }
                       //$r_ok['z'][] = (int)$yy->suma;
                   }
                   
		    //return $r_ok;
                    return $res;
   
     }

     public static function realization($post){
            $from = date('Y-m',strtotime($post->one_date_from_r));
            $to = date('Y-m', strtotime($post->one_date_to_r));
            $sq = "SELECT
                 DATE_FORMAT(  `ws_orders`.`admin_pay_time` ,  '%Y-%m' ) as monch,
                      SUM(`ws_orders`.`amount`) as suma
                      FROM  `ws_orders`
WHERE DATE_FORMAT(  `ws_orders`.`admin_pay_time` ,  '%Y-%m' )  >= '".$from."' 
    and DATE_FORMAT(  `ws_orders`.`admin_pay_time` ,  '%Y-%m' )  <= '".$to."' 
AND `ws_orders`.`status` = 8
group by monch";
          //  l($sq);
             $z = wsActiveRecord::findByQueryArray($sq);
             
            
            // $type = 0;
         $r_ok = [];

		   foreach($z as $k){
                       $r_ok['x'][] = $k->monch;
                       $r_ok['y'][] = (int)$k->suma;
                      // $r_ok['z'][] = (int)$k->summ;
		   // $r_ok[] = array('x'=> $k->dat, 'y' =>(int)$k->ctn);
		   }
		    return $r_ok;
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
                 case 777 : $cat = [0 =>'Без грейда', 1=>'Грейд 1', 2=>'Грейд 2', 3=>'Грейд 3', 4=>'Грейд 4', 5=>'Грейд 5' ];break;
                 default : $cat = ['parent_id'=>$post->cat_prognoz, 'id not in (106, 85, 267)', 'active'=> 1]; break;
             }
 
               //  return  $cat;
         }else{
             $cat = ['parent_id'=>0, 'id not in (106, 85, 267)', 'active'=> 1];
         }
          
        // return $cat;
                
                $d1 = round((strtotime($post->one_date_to)-strtotime($post->one_date_from))/(60*60*24)+1);
                $d2 = round((strtotime($post->two_date_to)-strtotime($post->two_date_from))/(60*60*24)+1);
                
                  $from1 = date('Y-m-d',strtotime($post->one_date_from));
                  $to1 = date('Y-m-d', strtotime($post->one_date_to));
                  $from2 = date('Y-m-d',strtotime($post->two_date_from));
                  $to2 = date('Y-m-d', strtotime($post->two_date_to));
               // echo $d1.'<br>';
               // echo $d2;
               $r = [];
                 $r['one'] = 0;
                 $r['two'] = 0;  
                 $r['one_p'] = 0;
                 $r['two_p'] = 0;
                 
              if($post->cat_prognoz == 777){ //graid
                  foreach ($cat as $k => $value) {
                      // $ob = [];
                        $one= 0;
                        $two = 0;
                         $c = wsActiveRecord::useStatic('Brand')->findAll(['greyd'=>$k, 'hide'=>1]);
                         $br = [];
                         foreach ($c as $v) {  
                             $br[] = $v->id;
                              }
                             // foreach ($c as $v) {
                                   //$ob[] = $v->id;
                                   // $kid = $v->id;
                        
                         $o = self::gride_sredniy_ostatok(implode(',', $br), $from1, $to1);
                         $t = self::gride_sredniy_ostatok(implode(',', $br), $from2, $to2);
                        // if($o or $t){
                             $one += $o;

                            $two +=  $t;
                            // $ob[] = $v->id;
                             $ob = $br;
                       //  }
                              
                // }
                // l($one);
             // echo $d1;
            // echo $one.'<br>';
                 if($one > 0 || $two > 0){
                      $kid =  implode(',', $ob);
                      $name = $value;
                                $r[$name]['one'] = ($one/$d1);
                                  $r[$name]['two'] = ($two/$d2);
                                  
                                 $prodaz_one =  self::prodazyGraidPeriod($k, $from1, $to1);
                                 $prod_one = $prodaz_one?$prodaz_one:0;
                                        $r[$name]['one_prod'] = $prod_one;
                                  $r[$name]['oborot_one'] =  ceil(($r[$name]['one']*$d1)/($prod_one?$prod_one:1));
                                  
                                $prodaz_two =  self::prodazyGraidPeriod($k, $from2, $to2);
                                 $prod_two = $prodaz_two?$prodaz_two:0;
                                  $r[$name]['two_prod'] = $prod_two;
                                $r[$name]['oborot_two'] =  ceil(($r[$name]['two']*$d2)/($prod_two?$prod_two:1));
                               
                                    $r['one'] += $one;//сумма остатка первого интервала
                                    $r['two'] += $two;//сумма остатка другого интервала
                                    $r['one_p'] += $prod_one;//сумма продаж первого интервала
                                    $r['two_p'] += $prod_two;//сумма продаж другого интервала
                            }
                 
                  }
                    // l($r);
                 //die();
              }elseif($post->cat_prognoz == 888){ //gender
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
                                $r[$name]['one_prod'] = ($prod_one/$d1);
                                $r[$name]['oborot_one'] =  ceil(($r[$name]['one']*$d1)/$prod_one);
                                  
                                $prodaz_two =  self::prodazyCategoryPeriod($kid, $from2, $to2);
                                $prod_two = $prodaz_two?$prodaz_two:1;
                                $r[$name]['two_prod'] = ($prod_two/$d2);
                                $r[$name]['oborot_two'] =  ceil(($r[$name]['two']*$d2)/$prod_two);
                               
                                    $r['one'] += $one;//сумма остатка первого интервала
                                    $r['two'] += $two;//сумма остатка другого интервала
                                     $r['one_p'] += $prod_one;//сумма продаж первого интервала
                                    $r['two_p'] += $prod_two;//сумма продаж другого интервала
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
                               // l($d1);
                               // l($d2);
                               
                                $r[$name]['one'] = ($one/$d1);
                                  $r[$name]['two'] = ($two/$d2);
                                //  l($r[$name]['one']);
                                 //  exit();
                                 $prodaz_one =  self::prodazyCategoryPeriod($kid, $from1, $to1);
                                 $prod_one = $prodaz_one?$prodaz_one:1;
                                 $r[$name]['one_prod'] = ($prod_one/$d1);
                                  $r[$name]['oborot_one'] =  ceil(($r[$name]['one']*$d1)/$prod_one);
                                  
                                $prodaz_two =  self::prodazyCategoryPeriod($kid, $from2, $to2);
                                 $prod_two = $prodaz_two?$prodaz_two:1;
                                 $r[$name]['two_prod'] = ($prod_two/$d2);
                                $r[$name]['oborot_two'] =  ceil(($r[$name]['two']*$d2)/$prod_two);
                               
                                    $r['one'] += $one;//сумма остатка первого интервала
                                    $r['two'] += $two;//сумма остатка другого интервала
                                     $r['one_p'] += $prod_one;//сумма продаж первого интервала
                                    $r['two_p'] += $prod_two;//сумма продаж другого интервала
                            }
                 }
     }
                 $res = [];
                 $res['t'] =  $from1.':'.$to1;//интервал  которы сравниваэм
                 $res['s'] =  $from2.':'.$to2;//интервал с которым сравниваэм
                 
                 $r1 = ceil($r['one']/$d1);//средний остаток по интервалу который сравниваем
                 if($r1 <= 0){ $r1 = 1;} unset($r['one']);
                 $r2 = ceil($r['two']/$d2);//средний остаток по интервалу с которым сравниваем
                 if($r2 <= 0){ $r2 = 1;} unset($r['two']);
                    
                $r3 = $r['one_p'];//средний остаток по интервалу который сравниваем
                 if($r3 <= 0){ $r3 = 1;}  unset($r['one_p']);
                 $r4 = $r['two_p'];//средний остаток по интервалу с которым сравниваем
                 if($r4 <= 0){ $r4 = 1;} unset($r['two_p']);
                  $i = 0;
                  $res['a']['name'] = $from1.':'.$to1.'(остатки)';
                  $res['a']['type'] = 'spline';
                  $res['b']['name'] = $from2.':'.$to2.'(остатки)';
                  $res['b']['type'] = 'spline';
                  
                  $res['c']['name'] = $from1.':'.$to1.'(продажи)';
                  $res['c']['type'] = 'spline';
                  $res['d']['name'] = $from2.':'.$to2.'(продажи)';
                  $res['d']['type'] = 'spline';
                 
                 foreach ($r as $key => $value) {
                     $res['table'][$i]['one'] = ceil($value['one']);
                     $res['table'][$i]['one_prod'] = ceil($value['one_prod']);
                     $res['table'][$i]['two'] = ceil($value['two']);
                     $res['table'][$i]['two_prod'] = ceil($value['two_prod']);
                     $res['table'][$i]['name'] = $key;
                     $res['table'][$i]['oborot_one'] = $value['oborot_one'];
                     $res['table'][$i]['oborot_two'] = $value['oborot_two'];
                     $res['y'][] = $key;
                     $res['a']['data'][] = round((($value['one']/$r1)*100),2);
                     $res['b']['data'][] = round((($value['two']/$r2)*100),2);
                     
                    $res['c']['data'][] = round((($value['one_prod']/$r3)*100),2);
                     $res['d']['data'][] = round((($value['two_prod']/$r4)*100),2);
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
                        and `ws_balance_category`.`count` > 0
                        and `ws_balance_category`.`id_category` in (".$cat.")";
       // l($sql);
       // exit();
     return  wsActiveRecord::findByQueryFirstArray($sql)['ctn'];
     }
     /**
      * Сумма остатка за период по грейдам
      * 
      * @param type $cat - (1,2)
      * @param type $from '2019-01-01'
      * @param type $to '2019-02-01'
      * @return type
      */
     private static function gride_sredniy_ostatok($cat, $from, $to){
        $sql = "SELECT sum( `ws_balance_category`.`count` ) AS  `ctn`
                FROM  `ws_balance_category` 
                INNER JOIN  `ws_balance` ON  `ws_balance_category`.`id_balance` =  `ws_balance`.`id` 
                WHERE  `ws_balance`.`date` >=  '".$from."'
                        and `ws_balance`.`date` <= '".$to."'
                        and `ws_balance_category`.`count` > 0
                        and `ws_balance_category`.`id_brand` in (".$cat.")";
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
      * Сумма продаж за период по определенной брендам
      * @param type $c - (1,2)
      * @param type $from '2019-01-01'
      * @param type $to '2019-02-01'
      * @return type
      */
     private static function prodazyBrandPeriod($c, $from, $to){
           return wsActiveRecord::findByQueryFirstArray(
                   "SELECT  sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` on `ws_order_articles`.`article_id` = `ws_articles`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$from."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$to."' 
AND `ws_orders`.`status` not in(17, 7, 2) and `ws_order_articles`.`count` > 0
and `ws_articles`.`brand_id` in (".$c.")")['suma'];  
     }
     private static function prodazyGraidPeriod($g, $from, $to){
           return wsActiveRecord::findByQueryFirstArray(
                   "SELECT  sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
inner join `ws_articles` ON `ws_order_articles`.`article_id` = `ws_articles`.`id`
inner join `red_brands` ON `ws_articles`.`brand_id` = `red_brands`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  >= '".$from."' 
    and DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' )  <= '".$to."' 
AND `ws_orders`.`status` not in(17, 7, 2) and `ws_order_articles`.`count` > 0
AND `red_brands`.`greyd` = {$g}")['suma'];  
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
            case 'oborot_all_monch': return  Bufer::getNorma($post); 
            case 'graid_category': return Bufer::getNormaOborotGraidCategory($post);

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
$result['uc'][] = ['label'=> $c->ucenka.'%', 'value'=>$c->ctn];
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
        // $type = 0;
         $r_ok = [];
          $ok = wsActiveRecord::findByQueryArray("
                SELECT DATE_FORMAT(  `ws_balance`.`date` ,  '%Y-%m-%d' ) AS dat,
                SUM(  `ws_balance_category`.`count` ) AS ctn,
                SUM(`ws_balance_category`.`summa`) as summ
FROM  `ws_balance` 
JOIN  `ws_balance_category` ON  `ws_balance`.`id` =  `ws_balance_category`.`id_balance` 
WHERE DATE_FORMAT(  `ws_balance`.`date` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 30 
DAY ) 
GROUP BY  `ws_balance`.`id` 
ORDER BY  `dat` ASC   ");
		   foreach($ok as $k){
                       $r_ok['x'][] = $k->dat;
                       $r_ok['y'][] = (int)$k->ctn;
                       $r_ok['z'][] = (int)$k->summ;
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
$ok = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma 
    FROM  `ws_order_articles`
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
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY )
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
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
$ok = wsActiveRecord::findByQueryArray("
    SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(IF(`ws_order_articles`.`count`>0,`ws_order_articles`.`count`,1)) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y-%m-%d' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
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
AND `ws_orders`.`status` not in(17)
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");
$pay = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, sum(`ws_order_articles`.`count`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
AND `ws_orders`.`status` = 8
GROUP BY DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) 
ORDER BY  `dat` ASC ");

$ret = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `ws_orders`.`date_create` ,  '%H' ) AS dat, count(`ws_order_articles`.`id`) as suma FROM  `ws_order_articles`
inner join `ws_orders` ON `ws_order_articles`.`order_id` = `ws_orders`.`id`
WHERE DATE_FORMAT(  `ws_orders`.`date_create` ,  '%Y%m' ) = DATE_FORMAT( NOW( ) ,  '%Y%m' ) 
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
	$days_status = wsActiveRecord::findByQueryArray("
            SELECT COUNT(  `ws_orders`.`id` ) AS ctn,  `ws_orders`.`status` , `ws_order_statuses`.`name`
FROM  `ws_orders` 
INNER JOIN `ws_order_statuses` ON `ws_order_statuses`.id = `ws_orders`.`status`
WHERE `ws_orders`.`status` IN (100,9,15,16) and `ws_orders`.`from_quick` != 1 and is_admin = 0
GROUP BY  `ws_orders`.`status` 
ORDER BY  `ws_orders`.`status` ASC");
        
//$mas = array(100=>'Новый', 1=>'В процесе', 2=>'Отменён', 8=>'Оплачен', 9=>'Собран', 10=>'Продлён клиентом', 12=>'Ждёт возврат', 15=>'Собран 2', 16=>'Собран 3' );
$color = array(100=>'#4c4fd2',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');

	foreach($days_status as $d){
	$days_arr_status['st'][] = ['label'=>$d->name, 'value'=>$d->ctn];
        $days_arr_status['color'][] = $color[$d->status];
}
	
	return $days_arr_status;
	case 'quick':
	//$quick_arr = array();
	$quick = wsActiveRecord::findByQueryArray("SELECT  `ws_orders`.`status` , COUNT(  `ws_orders`.`id` ) AS  `ctn` , `ws_order_statuses`.`name`
FROM  `ws_orders` 
INNER JOIN `ws_order_statuses` ON `ws_order_statuses`.id = `ws_orders`.`status`
WHERE  `from_quick` = 1 and is_admin = 0
AND  `status` != 17 
AND  `quick` = 1
AND DATE_FORMAT(  `date_create` ,  '%Y%m%d' ) > DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
GROUP BY  `status` ");     
$color = array(100=>'#4c4fd2',1=>'#d7da5c',2=>'#677489',8=>'#5B93D3',9=>'#37da3d', 15=>'#37c2da', 16=>'#dac037');
	foreach($quick as $d){
            $days_arr_status['st'][] = ['label'=>$d->name, 'value'=>$d->ctn];
        $days_arr_status['color'][] = $color[$d->status];
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
        . "WHERE DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) = DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) "
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
WHERE  DATE_FORMAT(  `ctime` ,  '%Y-%m-%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
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
WHERE DATE_FORMAT(  `ctime` ,  '%Y-%m' ) = DATE_FORMAT( NOW( ) ,  '%Y-%m' ) 
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
     public static function activeOrder(){
                /**
         * оплеченые заказы
         */
$days_arr_op = [];       
$week_arr_op = [];
$month_arr_op = [];
$year_arr_op = [];



$days_op = wsActiveRecord::findByQueryArray("
    SELECT DATE_FORMAT( `date_create` , '%H' ) AS dat, SUM( `amount` ) AS money, SUM( `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM `ws_orders`
WHERE DATE_FORMAT( `date_create` , '%Y-%m-%d' ) = DATE_FORMAT( NOW( ) , '%Y-%m-%d' )
AND status not in (2,7,17)
GROUP BY DATE_FORMAT( `date_create` , '%Y-%m-%d %H' )
ORDER BY `dat` ASC ");

foreach($days_op as $d){
	$days_arr_op['am'][$d->dat] = $d->money;
	$days_arr_op['dep'][$d->dat] = $d->deposit;
        $days_arr_op['bon'][$d->dat] = $d->bonus;
	$days_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$week_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND status not in (2,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($week_op as $d){
	$week_arr_op['am'][$d->dat] = $d->money;
	$week_arr_op['dep'][$d->dat] = $d->deposit;
        $week_arr_op['bon'][$d->dat] = $d->bonus;
	$week_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$month_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y-%m' ) = DATE_FORMAT( NOW( ) ,  '%Y-%m' ) 
AND status not in (2,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($month_op as $d){
	$month_arr_op['am'][$d->dat] = $d->money;
	$month_arr_op['dep'][$d->dat] = $d->deposit;
        $month_arr_op['bon'][$d->dat] = $d->bonus;
	$month_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$year_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `date_create` ,  '%Y-%m' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `date_create` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND status not in (2,7,17)
GROUP BY DATE_FORMAT(  `date_create` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
	foreach($year_op as $d){
	$year_arr_op['am'][$d->dat] = $d->money;
	$year_arr_op['dep'][$d->dat] = $d->deposit;
        $year_arr_op['bon'][$d->dat] = $d->bonus;
	$year_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

        return  ['year'=>$year_arr_op, 'month' =>$month_arr_op, 'week'=> $week_arr_op, 'days'=> $days_arr_op];
    }
    public static function paymentOrder(){
                /**
         * оплеченые заказы
         */
$days_arr_op = [];       
$week_arr_op = [];
$month_arr_op = [];
$year_arr_op = [];



$days_op = wsActiveRecord::findByQueryArray("
    SELECT DATE_FORMAT( `admin_pay_time` , '%H' ) AS dat, SUM( `amount` ) AS money, SUM( `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM `ws_orders`
WHERE DATE_FORMAT( `admin_pay_time` , '%Y-%m-%d' ) = DATE_FORMAT( NOW( ) , '%Y-%m-%d' )
AND STATUS =8
GROUP BY DATE_FORMAT( `admin_pay_time` , '%Y-%m-%d %H' )
ORDER BY `dat` ASC ");

foreach($days_op as $d){
	$days_arr_op['am'][$d->dat] = $d->money;
	$days_arr_op['dep'][$d->dat] = $d->deposit;
        $days_arr_op['bon'][$d->dat] = $d->bonus;
	$days_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$week_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) >= DATE_SUB( CURRENT_DATE, INTERVAL 6 DAY ) 
AND status = 8
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($week_op as $d){
	$week_arr_op['am'][$d->dat] = $d->money;
	$week_arr_op['dep'][$d->dat] = $d->deposit;
        $week_arr_op['bon'][$d->dat] = $d->bonus;
	$week_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$month_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) = DATE_FORMAT( NOW( ) ,  '%Y-%m' ) 
AND status = 8 
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m-%d' ) 
ORDER BY  `dat` ASC ");
	foreach($month_op as $d){
	$month_arr_op['am'][$d->dat] = $d->money;
	$month_arr_op['dep'][$d->dat] = $d->deposit;
        $month_arr_op['bon'][$d->dat] = $d->bonus;
	$month_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
}

$year_op = wsActiveRecord::findByQueryArray("SELECT DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) AS dat, SUM(  `amount` ) AS money, SUM(  `deposit` ) AS deposit, SUM( `bonus` ) AS bonus
FROM  `ws_orders` 
WHERE DATE_FORMAT(  `admin_pay_time` ,  '%Y' ) = DATE_FORMAT( NOW( ) ,  '%Y' ) 
AND status = 8
GROUP BY DATE_FORMAT(  `admin_pay_time` ,  '%Y-%m' ) 
ORDER BY  `dat` ASC ");
	foreach($year_op as $d){
	$year_arr_op['am'][$d->dat] = $d->money;
	$year_arr_op['dep'][$d->dat] = $d->deposit;
        $year_arr_op['bon'][$d->dat] = $d->bonus;
	$year_arr_op['koll'][] = $d->deposit+$d->money+$d->bonus;
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
          //return $res;
     //l($res);
     //   exit();
         $style = [];
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
                foreach($res as $k => $v){
                    $cat = new Shopcategories($k);
                    $gr['name'] = $cat->getRoutez();
                    $gr['res'] =  Bufer::prognozExcel($v);
                }
             // l($gr);
              //  exit();
                $i = 1;
                return $gr;
                
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
               return  ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style];
                // l(['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style]);
               //  exit();
                 //return
    }
    
    private static function balance_to_excel_dey($get){
         $res = Bufer::getToExcelNormaDay($get);
        //   echo '<pre>';
        // l($res);
         // echo '</pre>';
       //exit();
         $p = [];
                $p['header'][0][0] = 'Категория';
                $p['header'][0][1] = 'Добавлено';
                $p['header'][0][2] = 'Макс.продано';
                $p['header'][0][3] = 'Ср.Остаток';
                $p['header'][0][4] = 'Нужно догрузить';
                
                $gr = [];
                foreach($res as $k => $v){
                    $r = Bufer::prognozExceldey($v);
                   if(count($r) > 1){ 
                       $gr[$k] =  $r;
                       
                   }
                }
                $i = 1;
                foreach($gr as $k => $v){
                  
                    $cat = new Shopcategories($k);
                    $p['data'][$i][0] = $cat->h1;//getRoutez();
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
                 $cat = $get->cat_prognoz;//wsActiveRecord::useStatic('Shopcategories')->findById((int)$get->cat_prognoz)->getRoutez();
               return ['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style];
               //  l(['name' =>$cat.'_'.date('d.m.Y',strtotime($get->from_prognoz)).'_'.date('d.m.Y', strtotime($get->to_prognoz)), 'parametr'=>[0=>$p], 'style'=>$style]);
              //   exit();
                 
    }
}
