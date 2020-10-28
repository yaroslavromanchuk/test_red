<div class="container">
    <!--<div class="row">
        <div class="col-sm-12 p-3 card">
            <div class="card-body text-justify">
                <p>В связи введением карантина на территории всей Украины, установленного Постановлением Кабинета Министров Украины # 211 от 11.03.2020 года, просим Вас учитывать возможные задержки в подтверждениях заказов и их доставке. С учетом вышеперечисленного, отправка посылок будет осуществляться в срок 3-5 дней с дня оформления. Также просим Вас отнестись с пониманием к возможным сбоям в работе сайта и службы поддержки. Служба поддержки будет работать только по электронной почте market@red.ua. В любом случае, все заказы и наши обязательства будут осуществлены в ближайшее время после завершения карантина.</p>
            </div>
</div>
    </div>-->
    <?php
    if($this->delivery){ ?>
    <div class="row m-auto">
        <div class="col-sm-12 text-center p-3">
            <h3><?=$this->trans->get('Варианты доставки')?>:</h3>
        </div>
       <?php foreach ($this->delivery as $d) { ?>
        <div class="col-sm-12 mb-2" >
          
            <div class="card">
            <div class="row">
                 
                <div class="col-sm-12 col-md-2 text-center">
                    <img class="card-img-top px-2" src="<?=$d->img?>" alt="<?=$d->getName()?>">
                    <h5 class="card-title"><?=strip_tags($d->getName())?></h5>
                     <a name="<?=$d->id?>">
                </div>
                </a>
                <div class="col-sm-12 col-md-10 card-body">
                    <table class="table">
<tbody>
<tr>
<th><?=$this->trans->get('Стоимость')?></th>
<td><?=$d->getPrices()?></td>
</tr>
<tr>
<th><?=$this->trans->get('Сроки')?></th>
<td><?=$d->getTime()?></td>
</tr>
<tr>
<th><?=$this->trans->get('Извещение')?></th>
<td><?=$d->getNotice()?></td>
</tr>
<tr>
<th><?=$this->trans->get('Примечание')?></th>
<td><?=$d->getNote()?></td>
</tr>
<tr>
<th><?=$this->trans->get('Адрес')?></th>
<td><?=$d->getAdress()?></td>
</tr>
</tbody>
</table>
                </div>
            </div>
            </div>
        </div>
      <?php  } ?>
        </div>
   <?php }?>
    <?=$this->getCurMenu()->getPageBody()?>
</div>