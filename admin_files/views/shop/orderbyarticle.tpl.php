<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?> </h1>
<?=$this->getCurMenu()->getPageBody(); ?>
    <?php
 $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
$orders = $this->orders;
if ($orders->count()>0){?>
        <p>Заказы:</p>

<table cellspacing="0" cellpadding="4" id="orders" class="table">
    <tr>
        <th>Действие</th>
        <th>Статус</th>
        <th>Размер/Цвет</th>
        <th>Номер</th>
        <th>Дата</th>
         <th>Имя</th>
        <th>Товаров</th>
        <th>Стоимость</th>
        <th>Счет</th>
        <th>Магазин</th>
        <th>Скидка</th>
    </tr>
    <?php $row = 'row2'; foreach ($orders as $order) {
        $order_owner = new Customer($order->getCustomerId());
    $row = ($row == 'row2') ? 'row1' : 'row2';
    ?>
    <tr class="<?php echo $row; ?>" <?php if(wsActiveRecord::useStatic('Shoporderarticles')->findFirst(array('order_id' => $order->getId(), 'article_id'=> $order->getArticleId(),  'size'=> $order->getSize(), 'color' => $order->getColor() ))->getCount() == 0){ echo 'style="background: rgba(255, 10, 10, 0.58);" ';} ?>>
        <td>
            <a href="/admin/shop-orders/edit/id/<?php echo $order->getId(); ?>"><img width="24" height="24" alt="Редактировать" src="/img/icons/edit-small.png"></a>
        </td>
        <td><?php  echo isset($order_status[$order->getStatus()]) ? $order_status[$order->getStatus()] : ""; ?></td>
        <td><?php echo wsActiveRecord::useStatic('Size')->findById($order->getSize())->getSize(). '/'. wsActiveRecord::useStatic('Shoparticlescolor')->findById($order->getColor())->getName(); ?></td>
        <td><?=$order->getId();?></td>
        <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
        <td><?php echo $order->getName(); ?></td>
        <td><?php echo $order->getArticlesCount(); ?></td>
        <td><?php if ($order->getArticlesCount() != 0) {
            echo number_format((double)$order->getTotal('a'), 2, ',', '') . ' грн <br/>' . Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost()), 2) . ' грн';
        } ?></td>
        <td>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/1">Магазин</a><br/>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/2">Укрпочта</a><br/>
        </td>
        <td><?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName() : "&nbsp;"; ?></td>
        <td><?php echo $order_owner->getDiscont();?>%</td>
    </tr>
    <?php } ?>
</table>

<?php }  else {?>
        <p>Нет заказов</p>
        <?php } ?>
	