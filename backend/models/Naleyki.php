<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Naleyki
 *
 * @author PHP
 */
class Naleyki extends wsActiveRecord{
   
    public static function getPrint($get = []){
         switch ($get->type) {
            case '1':  return self::magaz($get->ids);
            case '2':  return self::ukr($get->ids);
            case '3': return self::np($get->ids);
            case '4': return self::kur($get->ids);
             default:
                 break;
                            }  
                    }
            private static function magaz($ids)
            {
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(["id in({$ids})"],['id'=>'ASC']);
                return ['order'=>$orders, 'page'=>'order/nakleyki/magaz.tpl.php'];
            }
            private static function ukr($ids)
            {
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(["id in({$ids})"],['id'=>'ASC']);
               // return ['order'=>$orders, 'page'=>'order/nakleyki/ukr.tpl.php'];
                return ['order'=>$orders, 'page'=>'order/nakleyki/np.tpl.php'];
                
            }
            private static function np($ids)
            {
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(["id in({$ids})"],['id'=>'ASC']);
                return ['order'=>$orders, 'page'=>'order/nakleyki/np.tpl.php'];
            }
            private static function kur($ids)
            {
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(["id in({$ids})"],['id'=>'ASC']);
                return ['order'=>$orders, 'page'=>'order/nakleyki/kur_nakl.tpl.php'];
                
            }
}
