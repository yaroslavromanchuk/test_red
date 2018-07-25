<img src="<?php echo SITE_URL.$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1><br/>
<form action="/admin/shop-orders/" method="get">
	<style type="text/css">
		#search td {vertical-align: middle; padding: 1px; font-size: 16px;}
		#search tr:nth-child(even) { background: #F8F8F8; }
		label {cursor: pointer;}
	</style>
	<table border="0"  style="margin: auto;" class="table" cellpadding="0" cellspacing="0" id="search">
		<tr>
			<td colspan="4" align="center"><strong>Поиск:</strong></td>
		</tr>
		<tr>
			<td>Номер заявки:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['order']; ?>" name="order"/></td>
			<td>Телефон:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['phone']; ?>" name="phone"/></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['email']; ?>" name="email"/></td>
			<td>Имя:</td>
			<td><input type="text" class="form-control input" value="<?php echo @$_GET['uname']; ?>" name="uname"/></td>
		</tr>
		<tr>
			<td colspan="4" align="center">Дата создания   от <input type="date"  class="form-control input" name="create_from" size="9" />
			до <input type="date"  class="form-control input" name="create_to" size="9" /></td>
			
		</tr>
		<tr>
			<td colspan="4" align="center">Дата отправки  от <input type="date"  class="form-control input" name="go_from" size="9" />
			до <input type="date"  class="form-control input" name="go_to" size="9" /></td>
			
		</tr>
		<tr>
			<td colspan="4" align="center">Стоимость: <input type="text" class="form-control input" value="<?php echo @$_GET['price']; ?>" name="price"/> +- 3 грн.</td>
		</tr>
		<tr>
			<td colspan="4" align="center"><input type="submit" class="button" value="Найти" style="padding: 5px 100px; cursor: pointer;" /></td>
		</tr>
	</table>
</form>

<script type="text/javascript">

    $(document).ready(function () {
        $('#masrintblank').click(function () {
            if ($('.order-item:checked').val()) {
                id = '';
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
                        id += ',' + $(this).attr('name').substr(5);
                    } else {
                        id += $(this).attr('name').substr(5);
                    }
                    i++;
                });
                window.open ( '/admin/masgenerateblank/ids/' + id , '_blank');

            }
        });
    });
</script>
<!--
<br/>
<a href="/admin/edit-quick-order/id/">
<img src="<?php //echo SITE_URL; ?><?php //echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img" height="32"/>Новая заявка
</a>
<br/>-->
<br/>
<?php if ($this->getOrders()->count()) { ?>
    <script type="text/javascript">
        var clik_ok = 0;
        function chekAll() {
            if (!clik_ok) {
                $('.cheker').attr('checked', true);
                clik_ok = 1;
            } else {
                $('.cheker').attr('checked', false);
                clik_ok = 0;
            }
            return false;
        }
    </script>

	<table cellspacing="0" cellpadding="4" id="orders" class="table">
        <tr>
            <th>Действия</th>
            <th>Номер</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Имя</th>
            <th>Товаров</th>
            <th>Стоимость</th>
            <th>Скидка</th>
            <th>Комментарии</th>
			<th>Связь</th>
        </tr>
        <?php $row = 'row2'; foreach ($this->getOrders() as $order) {
            $row = ($row == 'row2') ? 'row1' : 'row2';
            $order_owner = new Customer($order->getCustomerId());
		?>
            <tr class="<?php echo $row; ?>"
                <?php if ($order_owner->getAdminComents()) { ?>style="background: #ff6666;" <?php
                } else {
					if ($order->getNowaMail() != '0000-00-00 00:00:00' and $order->getDeliveryTypeId() == 8 and strtotime($order->getNowaMail()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")) and false)
							echo "style='background: #ff9900'";
						elseif ($order->getStatus() == 11 and $order->getDelayToPay() and $order->getDelayToPay() != '0000-00-00' and strtotime($order->getDelayToPay()) <= mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")))
							echo "style='background: #ff9900'";
						elseif($order_owner->getBlockM() == 1)
								echo 'style="background: #d77de7;" ';
				
            }
		?>>
                <td class="kolomicon">
					<input type="checkbox" class="order-item cheker" name="item_<?php echo $order->getId(); ?>" style="vertical-align: top;"/>
					<a href="<?php echo $this->path; ?>edit-quick-order/id/<?php echo $order->getId(); ?>/">
						<img src="<?php echo SITE_URL; ?>/img/icons/edit-small.png" alt="Редактировать" width="24" height="24"/>
					</a>
					<?php if ($this->user->isSuperAdmin()) { ?>
						<a target="_blank" href="/admin/orderhistory/id/<?php echo $order->getId(); ?>">
							<img height="24" width="24" alt="История" src="/img/icons/histori.png">
						</a>
<?php
					}
?>
                </td>
                <td><?php echo $order->getQuickNumber(); ?></td>
                <td><?=$order->getStat()->getName()?></td>
                <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
                <td><?php echo $order->getName() . ' ' . $order->getMiddleName(); ?></td>
                <td><?php echo $order->getArticlesCount(); ?></td>
                <td><?php
					if ($order->getArticlesCount() != 0) {
                        $sttt = '';
                        $sttt2 = '';
                        if ($order->isUcenArticle()) {
                            $sttt = '<span style="color:#a51515">';
                            $sttt2 = '</span>';
                        }
                        $price_1 = number_format((double)$order->getTotal('a'), 2, ',', '');
                        $price_2 = $order->getAmount();
                        echo  $price_1 . ' грн <br/>' . $sttt . $price_2 . ' грн' . $sttt2;
                    }
				?></td>

                <td><?php  if ($order->getSkidka() != '') {
                        echo $order->getSkidka();
                    } else {
                        $order->save();
                        echo $order->getSkidka();
                    } ?>%
                </td>

				<td>
				<?php if ($order->getRemarks()->count() or $order->getComments()) {
					$remar = array();
					foreach ($order->getRemarks() as $remark) {
					$rem = $remark->getRemark()."-".$remark->getName();
                            //$remar[] = $remark->getRemark();
							$remar[] = $rem;
					}
					?>
						<?php if ($order->getRemarks()->count()) { ?>
							<div style="background: #C0FFD4; border: 1px solid #000; padding: 5px; margin: 5px;">
							<b>Внутренний комментарий :</b><br/>
							<?php echo implode(';', $remar); ?>
							</div>
<?php
							}
?>
						<?php if ($order->getComments()) { ?>
							<div style="background: #ffff33; border: 1px solid #000; padding: 5px; margin: 5px;">
							<b>Комментарий клиента :</b><br/>
							<?php echo $order->getComments(); ?>
							</div>
						<?php } ?>
				<?php } ?>
				</td>
				
				<td>
				<?php if ($order->getCallMail() == '0000-00-00 00:00:00') {
                                ?>
<span id="<?php echo $order->getQuickNumber();?>"> <a href="#" onclick="PhoneMail(<?php echo $order->getId();?>,$(this)); return false;"
                                          class="nowa_mail1">Отправить письмо</a></span>
                            <?php } else { ?>
<span id="<?php echo $order->getQuickNumber();?>"> письмо отправлено: <?php echo date('d-m-Y', strtotime($order->getCallMail()));?>
<a href="#" onclick="PhoneMail(<?php echo $order->getId();?>,$(this)); return false;"
                                       class="nowa_mail1"> отправить повторно</a></span>
                            <?php } ?>
                        
				</td>
            </tr>


        <?php } ?>

    </table>

    <?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
    $paginator = '&nbsp;&nbsp;';
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
    }
    $start = 1;
    $end = $this->totalPages;
    if ($this->page > $limitLeft) {
        $paginator .= '...&nbsp;';
        $start = $this->page - $limitLeft;
    }
    if (($this->page + $limitRight) < $this->totalPages) {
        $end = $this->page + $limitRight;
    }
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
    }
    echo $paginator;

    ?><br/>
    Всего страниц: <?php echo $this->totalPages; ?>,  <?php echo $this->count; ?> записей!


<?php
	}
	else {
		echo 'Нет записей';
	}
?>
<p>&nbsp;</p>
  <script type="text/javascript">
function PhoneMail(id, object) {
var getcall = 'getcall';
$.post('/admin/nowamail/', {
id: id,
metod: getcall
}, function (data) {
            object.parent().html(data);
        });
}

	  </script>