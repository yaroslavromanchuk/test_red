<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Storess
 *
 * @author PHP
 */
class Storess extends Stores {
    
    public function getAkcii(){
              return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'new']);
        }
        public function getTempAkcii(){
              return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'temp']);
        }
    
        
}
