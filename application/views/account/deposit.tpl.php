<div class="container">
<?php
        if($this->deposit and $this->deposit->count() > 0){
        ?>
<div class="card text-center mb-2">
  <div class="card-body">
    <h5 class="card-title">Текущий депозит: <?=Shoparticles::showPrice($this->user->getDeposit())?> грн.</h5>
    <p class="card-text">
	<table class="table table-hover  table-sm table-responsive-sm ">
            <thead class="thead-dark">
            <th scope="col">Дата</th>
            <th scope="col">Действие</th>
            <th scope="col">Сумма</th>
            <th scope="col">Заказ</th>
            </thead>
    <tbody>
        <?php 
         foreach ($this->deposit as $d) { ?>
             <tr>
                    <td class="info"><?=date('d.m.Y', strtotime($d->ctime))?></td>
                    <td><?=($d->action == '+')?'Зачислено':'Использовано'?></td>
                    <td><?=$d->info?></td>
                    <td><?=$d->orders?$d->orders:''?></td>
    </tr>
        <?php }?>
    </tbody>
</table>
	</p>
  </div>
</div>
        <?php }else{ ?>
<div class="card text-center mb-2">
     <h5 class="card-title">У Вас еще нет истории депозита.</h5>
</div>
        <?php } ?>
</div>