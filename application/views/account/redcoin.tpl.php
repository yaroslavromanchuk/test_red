<div class="container">
<?php
        if($this->coin){
        ?>
<div class="card text-center mb-2">
  <div class="card-body">
    <h5 class="card-title">Доступно на <?=date("d.m.Y")?>: <?=$this->user->getSummCoin()?> redcoin.</h5>
    <p class="card-text">
	<table class="table table-hover  table-sm table-responsive-sm ">
           <thead class="thead-dark">
        <th>Статус</th>
    <th>Заказ</th>
     <th>Дата начисления</th>
      <th>Дата активации</th>
       <th>Дата списания</th>
        <th>Зачислено</th>
        <th>Использовано</th>
        <th>Остаток</th>
    </thead>
    <tbody>
        <?php 
         foreach ($this->coin as $c){ ?>
            <tr>
            <td><?=$c->status_name->name?></td>
            <td><?=$c->order_id_add?$c->order_id_add:''?></td>
            <td><?=$c->date_add?></td>
            <td><?=$c->date_active?></td>
            <td><?=$c->date_off?></td>
            <td><?=$c->coin+$c->coin_on?></td>
            <td><?=$c->coin_on?></td>
            <td><?=$c->coin?></td>
        </tr>
        <?php }?>
    </tbody>
</table>
	</p>
  </div>
</div>
        <?php }else{ ?>
<div class="card text-center mb-2">
     <h5 class="card-title">У Вас еще нет истории бонусов.</h5>
</div>
        <?php } ?>
</div>