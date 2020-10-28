<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subscribers
 *
 * @author PHP
 */
class Subscribers  extends Emailpost
{
    /**
     * Список сохраненных рассылок по сегментно
     * @param type $segment_id
     * @return type
     */
    public function ListSaveSubscriders($segment_id) {
       return  wsActiveRecord::useStatic('Emailpost')->findAll(['segment_id'=>$segment_id], ['id'=>'DESC'], [0, 40]);
    }
    public function ListSaveSubscridersSegment($segment_id) {
       return  wsActiveRecord::useStatic('Emailpost')->findAll(['segment_customer'=>$segment_id], ['id'=>'DESC'], [0, 40]);
    }
    /**
     * Сохранить рассылку
     * @param type $id
     * @param type $post
     * @return type
     */
    public static function saveSubscribe($id = false, $post = []){
        if(count($post) > 0){
            if($id){
                $s = new Emailpost($id);
            }else{
                $s = new Emailpost();
            }
        $s->import($post);
        $s->save();
        if($s->id){
            $m = 'Рассылка сохранена!';
        }else{
            $m = 'Ошибка';
        }
        return json_encode(['status' => 'send', 'message'=>$m, 'id'=>$s->id]);
        }else{
            return json_encode(['status' => 'send', 'message'=>'Ошибка сохранения! Попробуйте снова.']);
        }
        
    }
    /**
     * Отправка рассылки
     * @param type $param = []
     * @return boolean
     */
    public static function gomail($param) {
        
        return true;
        
    }
    
    public static function gotestmail($param) {
        
        return true;
        
    }
}
