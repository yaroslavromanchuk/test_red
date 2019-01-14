<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if($this->message){ ?>
<table class="table table-hover table-bordered form_history"  >
    <thead class="text-center">
        <tr>
            <th>Заказано<br>товаров</th>
            <th>Сумма<br>без акции</th>
            <th>Сумма<br>по акции</th>
            <th>Разница</th>
            <th>Куплено<br>товаров</th>
            <th>Сумма<br>без акции</th>
            <th>Сумма<br>по акции</th>
            <th>Разница</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td id="all"><?=$this->message['all']?></td>
            <td id="summa_all_no_akciya"><?=$this->message['summa_all_no_akciya']?> грн.</td>
            <td id="summa_all"><?=$this->message['summa_all']?> грн.</td>
            <td id="summa_all_no_akciya_akciya"><?=$this->message['summa_all_no_akciya']-$this->message['summa_all']?></td>
            <td id="fact"><?=$this->message['fact']?></td>
            <td id="summa_fact_no_akciya"><?=$this->message['summa_fact_no_akciya']?> грн.</td>
            <td id="summa_fact"><?=$this->message['summa_fact']?> грн.</td>
            <td id="summa_fact_no_akciya_akciya"><?=$this->message['summa_fact_no_akciya']-$this->message['summa_fact']?> грн.</td>
        </tr>
    </tbody>
</table>
<input type="text" hidden value="<?=$this->id?>" class="id_akciya">
 <?php   
}


