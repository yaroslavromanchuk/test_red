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
            case '1':  return self::r_1($post);
            case '9':  return self::r_9();
            case '11': return self::r_11($post);
             default:
                 break;
         }
         
         
     }
     /**
      * Товары по категориям
      * @param type $post
      */
     private static function r_1($post)
             {
          
         
     }
     /**
      * Количество товаров по ключевым категориям. Возвращает Категрию и остаток.
      * @return type
      */
     private static function r_9(){
         
         
     }
     /**
      * Заказы по городам(Новая почта и Укр почта). Отображает города, количество заказов, товаров и розделяет по способам доставки, Нова почта и Укр почта
      * @param type $post массив $_POST
      * @return excel file
      */
      private static function r_11($post){
          require_once('PHPExel/PHPExcel.php');
           $from = date('Y-m-d 00:00:00', strtotime($post['order_from']));
            $to = date('Y-m-d 23:59:59', strtotime($post['order_to']));
            $orders = wsActiveRecord::useStatic('Shoporders')->findAll(["delivery_type_id in (4,8,16)", "date_create <='{$to}' and date_create >= '{$from}'"], ['date_create' => 'ASC']);

            $mas = [];
            foreach ($orders as $order) {
                $city = str_replace(array('г.', 'м.', 'c.', 'п.г.т.', 'пгт.', 'с.', 'п.', 'пт.', 'п.т.', 'т.'), '', $order->getCity());
                $city = trim(mb_strtolower($city));

                if ($order->getDeliveryTypeId() == 4) {
                    $mas[$city][1][] = $order;
                }else{
                    $mas[$city][2][] = $order;
                }/* elseif ($order->getDeliveryTypeId() == 9) {
                    $mas[$city][3][] = $order;
                }*/
            }
            ksort($mas);

            $filename = 'otchet_order_' . $post['order_from'] . '_' . $post['order_to'];
            $style = [];
            $style['width']['A'] = 30;
            $style['width']['B'] = 15;
            $style['width']['C'] = 30;
            $style['width']['D'] = 30;
            $style['width']['E'] = 20;
            $style['width']['F'] = 20;
            $parametr = [];
            
            $parametr['header'][0][0] = 'Город';
            $parametr['header'][0][1] = 'Сумма';
            $parametr['header'][0][2] = 'Количество заказов';
            $parametr['header'][0][3] = 'Количество единиц';
            $parametr['header'][0][4] = 'Укрпочта';
            $parametr['header'][0][5] = 'Новая почта';

            $style['font']['A1:F1'] = ['font'=>['bold'=>true], 'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];
            $i = count($parametr['header']);
            foreach ($mas as $k => $m) {
                $kount_a = 0;
                $sum = 0;
                foreach ($m as $kd => $obj) {
                    foreach ($obj as $order) {
                        $kount_a += $order->countArticlesSum();
                        $sum += ($order->getAmount()-$order->getDeliveryCost());
                    }
                }
                $parametr['data'][$i][0] = $k;
                $parametr['data'][$i][1] = $sum;
                $parametr['data'][$i][2] = count(@$m['1']) + count(@$m['2']);
                $parametr['data'][$i][3] = $kount_a;
                $parametr['data'][$i][4] = count(@$m['1']);
                $parametr['data'][$i][5] = count(@$m['2']);
                $i++;
            } 
          return ParseExcel::saveToExcel($filename, [0 => $parametr], $style);
      }
     
     
}
