<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminSection
 *
 * @author PHP
 */
class Norma extends wsActiveRecord
{
   
    public static function getNorma($arr){
        $i = 2;
       // $j = 1;
        //$g = 0;
        
        $prod = $arr['prod'];
        $ost = $arr['ost'];
       // $no = $arr['n_0'];
        //$nn = $arr['n_1'];
        //$nb = $arr['n_2'];
       // $z = 0;
        foreach ($prod as $key => $value) {
            
           
            $n0 = ceil($ost[$key]*0.3);
            $n1 = ceil($ost[$key]*0.5);
            $n2 = ceil($ost[$key]*1.1);
            if($key>= 2){
                
                 $sr_p =  $prod[$i]+$prod[$i-1]+$prod[$i-2];
                $sr_ost =  $ost[$i]+$ost[$i-1]+$ost[$i-2];
                
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
          // $j++;
          // $g++;
            }else{
                $arr['n_0'][$key] = $n0;
                $arr['n_1'][$key] = $n1;
                $arr['n_2'][$key] = $n2;
            }
            
           
        }
        $max_p = max($prod);
        $max_d = max($arr['add']);
        $kl = array_search($max_p,$prod);
        
        $count = count($ost);
        $p_ost = ceil(array_sum($ost)/$count);
        $p_prod = ceil(array_sum($prod)/$count);
       // $p_n0 = ceil(array_sum($arr['n_0'])/$count);
       // $p_n1 = ceil(array_sum($arr['n_1'])/$count);
       // $p_n2 = ceil(array_sum($arr['n_2'])/$count);
        
       for($i=0; $i<=$count; $i++){
           $arr['sr_ost'][$i] = $p_ost;
           $arr['sr_pr'][$i] = $p_prod;
           
       }  
        
                $arr['x'][$count] = 'Max';
                $arr['ost'][$count] = $ost[$kl];
                $arr['prod'][$count] = $max_p;
                 $arr['add'][$count] = $max_d;
                $arr['n_0'][$count] = $arr['n_0'][$kl];
                $arr['n_1'][$count] =  $arr['n_1'][$kl];
                $arr['n_2'][$count] = $arr['n_2'][$kl];
        
         
        
        return  $arr;//['no'=> $no, 'nn'=> $nn, 'nb'=> $nb];
    }
}
