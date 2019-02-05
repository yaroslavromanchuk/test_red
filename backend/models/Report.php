<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Формирование отчетов в Excel
 * @author Yaroslav Romanchuk
 */
class Report extends wsActiveRecord{
    /**
     * метод переопеределения по типу отчёта
     *
     * @param type $get массив $_GET
     * @param type $post массив $_POST
     * @return excel file download
     */
     public static function toExcel($get = [], $post = []){
         switch ($get->type) {
            case '1': return  self::r_1($post);
            case '9': return self::r_9();
             default:
                 break;
         }
         
         
     }
     private static function r_1($post)
             {
           ini_set('memory_limit', '1024M');

                $day = strtotime($post['day']);
                $mast = array();
                $q = 'SELECT ws_order_articles.* FROM ws_order_articles
inner JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
WHERE ws_orders.date_create  > DATE_FORMAT(DATE_ADD("' . date('Y-m-d', $day) . ' 00:00:00", INTERVAL -' . ($post['type'] - 1) . ' DAY), "%Y-%m-%d 00:00:00")
        AND ws_orders.date_create <= "' . date('Y-m-d', $day) . ' 23:59:59"';

    $articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);


                foreach ($articles as $article) {

                    //   d($article->category->getRoutez());
                    $cat = explode(' : ', $article->article_db->category->getRoutez());
                    if ($cat[0] == 'РАСПРОДАЖА') {
                        $cat[0] = $cat[1];
                        /*
							if ($cat[0] == 'Женская одежда') $cat[0] = 'Женское';
							if ($cat[0] == 'Мужская одежда') $cat[0] = 'Мужское';*/

                        if ($cat[0] == 'Детям') $cat[0] = 'Детская : ' . (@$cat[2]);
                        if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[2]);
                        if ($cat[0] == 'Белье') $cat[0] = @$cat[2];
                    }
                    if ($cat[0] == 'Белье') $cat[0] = @$cat[1];
                    if ($cat[1] == 'Детское белье') $cat[0] = 'Детям : ' . (@$cat[1]);
                    if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[1]);
                    /* $mast[$cat[0]][] = $article;*/
                    $a_price = $article->getPerc($article->order->getAllAmount(), 0);
                    if (@$mast[$cat[0]]['count']) {
                        $mast[$cat[0]]['count'] += $article->getCount();
                        $mast[$cat[0]]['price'] += $a_price['price'];
                        $mast[$cat[0]]['minus'] += $a_price['minus'];
                    } else {
                        $mast[$cat[0]]['count'] = $article->getCount();
                        $mast[$cat[0]]['price'] = $a_price['price'];
                        $mast[$cat[0]]['minus'] = $a_price['minus'];
                    }
                }
                ksort($mast);

                require_once('PHPExel/PHPExcel.php');
                $name = 'otchet_all_order_by_cat_' . $post['day'];
                
                
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(30);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(15);
                $aSheet->getColumnDimension('D')->setWidth(15);

                $aSheet->setCellValue('A1', 'С даты:');
                $aSheet->setCellValue('B1', date('d-m-Y', $day));
                if ($_POST['type'] > 1) {
                    $aSheet->setCellValue('C1', 'за ' . $_POST['type'] . ' дней');
                } else {
                    $aSheet->setCellValue('C1', 'за ' . $_POST['type'] . ' день');
                }

                $aSheet->setCellValue('A2', 'Товары');
                $aSheet->setCellValue('B2', 'К-во ед.');
                $aSheet->setCellValue('C2', 'Сумма');
                $aSheet->setCellValue('D2', 'Скидка');


                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A2')->applyFromArray($boldFont);
                $aSheet->getStyle('B2')->applyFromArray($boldFont);
                $aSheet->getStyle('B1')->applyFromArray($boldFont);
                $aSheet->getStyle('C2')->applyFromArray($boldFont);
                $aSheet->getStyle('D2')->applyFromArray($boldFont);
                $aSheet->getStyle('E2')->applyFromArray($boldFont);
                $aSheet->getStyle('F2')->applyFromArray($boldFont);
                $i = 3;
                foreach ($mast as $kay => $val) {
                    $aSheet->setCellValue('A' . $i, $kay);
                    $aSheet->setCellValue('B' . $i, $val['count']);
                    $aSheet->setCellValue('C' . $i, $val['price']);
                    $aSheet->setCellValue('D' . $i, $val['minus']);
                    $i++;
                }

                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

               // header("Content-type: application/x-msexcel");
                $objWriter->save('php://output');
         
     }
     private static function r_9(){
         
         ini_set('memory_limit', '1024M');
            $i = 0;
            $parametr = [];

            $q1 = "SELECT t1.id, t1.parent_id, t1.name, SUM( t3.stock ) AS 'ostatok'
				FROM  `ws_categories` t1
				INNER JOIN  `ws_articles` t3 ON t1.id = t3.category_id
                                
				GROUP BY t1.id 
				ORDER BY  t1.name ASC";

            $artucles = wsActiveRecord::useStatic('Shopcategories')->findByQuery($q1);
                
            
            $parametr[$i][0] = 'Категория';
            $parametr[$i][1] = 'Остаток';
            $i++;
            
            
            $filename = 'otchet_tov_group_' . date("Y-m-d_H:i:s");
            $mascat = array();
            foreach ($artucles as $cat) {
                $mascat[$cat->getRoutez()] = $cat;
            }
            ksort($mascat);
             
            foreach ($mascat as $val => $article) {
                if($article->getOstatok() > 0){
                    $parametr[$i][0] = $val;
                    $parametr[$i][1] = $article->getOstatok();
       /*         $q_in = "SELECT SUM( t4.`count` ) as rashod
						FROM  `ws_categories` t1
						RIGHT JOIN  `ws_categories` t2 ON t1.id = t2.parent_id
						RIGHT JOIN  `ws_articles` t3 ON t2.id = t3.category_id
						INNER JOIN `ws_order_articles` t4 ON t3.id = t4.article_id
						WHERE t2.`id` =" . $article->getId() . "
						GROUP BY t2.id";
               $rashod = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q_in);
//if (!(@$rashod[0]->getRashod())) var_dump(@$rashod[0]);

                $rasho = (@$rashod[0]) ? $rashod[0]->getRashod() : 0;
*/
                $i++;
                }
            }

            return ParseExcel::saveToExcel($filename, $parametr);
         
     }
     
     
}
