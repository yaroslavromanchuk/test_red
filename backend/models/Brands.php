<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Brands
 *
 * @author PHP
 */
class Brands extends Brand{
    
    
    public function getBrands(){
        return wsActiveRecord::useStatic('Brand')->findFirst(['id'=>$this->id], ['name' => 'ASC']);
    }
    public function getAllBrands($limit = 1000){
        $alf = [
            1 => 'S',//P,Q,V
            36431 => 'M',//M,N,U,Z Наташа
            33929 => 'K',//J,K,T Саша
            7668 => 'H',//G,H,S,X Олеся
            34608 => 'R',//D,E,R,W Аня
           // 8005 => 'J'
        ];
        $id = $this->user->id;
        return wsActiveRecord::useStatic('Brand')->findAll(["greyd is NULL"], ['name' => 'ASC'], [0,$limit]);
    }
    /**
     * Редактирование бренда
     * @param type $id - бренда
     * @param type $param - массив с полями которые нужно изменить
     * @return boolean
     */
    public function BrandEdit($id, $param = []){
         $brand = new Brand((int)$id);
         $brand->import($param);
         $brand->save();
        
        return true;
    }
    
    
}
