<?php
if($this->message){ $i=1; ?>
<table class="table table-hover table-bordered form_history" style="font-size: 0.9em;" id="rezultat_akcii" name="rezultat_akcii<?=$this->id?>">
    <thead class="text-center">
        <tr>
            <th>#</th>
            <th>Категория</th>
            <th>Бренд</th>
            <th>Модель</th>
            <th>Гендер</th>
            <th>Колл.</th>
            <th>Цена</th>
            <th>Цена в заказе</th>
            <th>Себестоимость</th>
            <th>Маржа</th>
            <th>Потеряли</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $count = 0;
        $price = 0;
        $price_order = 0;
        $marga = 0;
        $potera = 0;
        foreach($this->message as $a){
            $count+=$a->ctn;
            $price+= $a->article_db->price;
            $price_order+=$a->option_price;
            $marga+=round($a->option_price*$a->ctn - $a->article_db->min_price*$a->ctn, 2); 
            $potera+=round($a->article_db->price*$a->ctn - $a->option_price*$a->ctn, 2);
        } ?>
        <tr style="font-weight: bold;background: #80808087;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=Number::formatFloat($count)?></td>
            <td><?=Number::formatFloat($price)?></td>
            <td><?=Number::formatFloat($price_order)?></td>
             <td></td>
            <td><?=Number::formatFloat($marga)?></td>
            <td><?=Number::formatFloat($potera)?></td>
        </tr>
        <?php foreach($this->message as $a){ ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$a->article_db->category->h1?></td>
            <td><?=$a->article_db->brand?></td>
            <td><?=$a->article_db->model?></td>
            <td><?=$a->article_db->sex->name?></td>
            <td><?php echo $a->ctn;  ?></td>
            <td><?php echo Number::formatFloat($a->article_db->price, 2); ?></td>
            <td><?php echo Number::formatFloat($a->option_price, 2);  ?></td>
            <td><?php echo Number::formatFloat($a->article_db->min_price, 2);  ?></td>
            <td><?php $m = round($a->option_price*$a->ctn - $a->article_db->min_price*$a->ctn, 2); echo Number::formatFloat($m, 2); ?></td>
            <td><?php $p = round($a->article_db->price*$a->ctn - $a->option_price*$a->ctn, 2); echo Number::formatFloat($p, 2); ?></td>
        </tr>
        <?php $i++; } ?>
        
    </tbody>
</table>
<?php }
if(false){ ?>
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


