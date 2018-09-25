<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if($this->message){
    //echo print_r($this->message);
    ?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Заказано</th>
            <th>Сумма</th>
            <th>Куплено</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?=$this->message['all']?></td>
            <td><?=$this->message['summa_all']?></td>
            <td><?=$this->message['fact']?></td>
            <td><?=$this->message['summa_fact']?></td>
        </tr>
    </tbody>
</table>

    
 <?php   
}


