<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title">Список фопов</div>
        <?php if($this->fop){ ?>
        <table class="table">
            <thead>
            <th>ID</th>
            <th>Название</th>
            <th>Счёт</th>
            <th>IBAN</th>
            <th>МФО</th>
            <th>Банк</th>
            <th>Колл. Заказов</th>
            <th>Сумма</th>
            </thead>
            <tbody>
   <?php  foreach ($this->fop as $f) { ?>
                <tr>
                    <td><?=$f->id?></td>
                    <td><?=$f->name?></td>
                    <td><?=$f->invoice?></td>
                    <td><?=$f->invoice_ukrpost?></td>
                    <td><?=$f->mfo?></td>
                    <td><?=$f->bank_name?></td>
                    <td><?=$f->countOrder()?></td>
                    <td><?=$f->summOrder()?></td>
                </tr>
     <?php } ?>
             </tbody>
           </table> 
     <?php   } ?>
    </div>
</div>
