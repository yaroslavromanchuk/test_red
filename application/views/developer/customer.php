<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($this->customer and false){
    $i=0;
    foreach ($this->customer as $c){
       if($c->getCountFactOrder() == 0){
           $c->setBonus(100);
           $c->save();
           echo $i.' : '.$c->getFullname().' - '.$c->getCountFactOrder().'<br>';
           $i++;
       }
       
        
    }
    
}

