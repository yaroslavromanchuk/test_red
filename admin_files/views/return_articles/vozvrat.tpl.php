<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt=""
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" width="32" class="page-img"
     height="32"/>
<h1>Возрат <?php echo $this->getOrder()->getId(); ?></h1>

<table cellspacing="0" cellpadding="4" id="order-details">


    <tr>
        <td class="column-data">Номер заказа</td>
        <td id='get_order_id'><a
                href="/admin/shop-orders/edit/id/<?php echo $this->getOrder()->getOldOrder(); ?>"><?php echo $this->getOrder()->getOldOrder(); ?></a>
        </td>
    </tr>
    <tr>
        <td class="column-data">Способ возврата</td>
        <td>           <?php
            if ($this->getOrder()->getSposob() == 1) echo 'на депозит';?>
            <?php if ($this->getOrder()->getSposob() == 2) echo 'денежным переводом';?></td>
    </tr>

    <tr>
        <td class="column-data">Дата</td>
        <td><?php $d = new wsDate($this->getOrder()->getDateModify()); echo $d->getFormattedDateTime(); ?></td>
    </tr>
    <form action="" method="post">
        <tr>
            <td class="column-data">Накладная</td>
            <td>
                №<?php echo $this->getOrder()->getNakladna();?>
            </td>
        </tr>
        <tr>
            <td class="column-data">Статус</td>
            <td><label>

                <select name="order_status" onChange="this.form.submit(); return false;">
                    <?php foreach ($this->order_status as $key => $item) { ?>
                    <option value="<?php echo $key; ?>" <?php if ($key == $this->getOrder()->getNewStatus()) echo "selected"; ?>><?php echo $item; ?></option>
                    <?php } ?>
                </select>



            </label></td>
        </tr>
    </form>
</table>

<p><strong>Информация о покупателе</strong></p>
<table cellpadding="4" cellspacing="0" id="order-client"
       <?php
$order_owner = new Customer($this->getOrder()->getCustomerId());
$old_order = new Shoporders($this->getOrder()->getOldOrder());
?>
    <tr>
        <td class="column-data">Компания</td>
        <td><?php echo $this->getOrder()->getCompany() ? $this->getOrder()->getCompany() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Имя</td>
        <td><?php echo ($this->getOrder()->getName() ? $this->getOrder()->getName()
            : "&nbsp;") ?></td>
    </tr>
    <tr>
        <td class="column-data">Адрес</td>
        <td><?php echo $this->getOrder()->getAddress() ? $this->getOrder()->getAddress() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Почтовый код</td>
        <td><?php echo $this->getOrder()->getPc() ? $this->getOrder()->getPc() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Город</td>
        <td><?php echo $this->getOrder()->getCity() ? $this->getOrder()->getCity() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Индекс</td>
        <td><?php echo $this->getOrder()->getIndex() ? $this->getOrder()->getIndex() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Улица</td>
        <td><?php echo $this->getOrder()->getStreet() ? $this->getOrder()->getStreet() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Дом</td>
        <td><?php echo $this->getOrder()->getHouse() ? $this->getOrder()->getHouse() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Квартира</td>
        <td><?php echo $this->getOrder()->getFlat() ? $this->getOrder()->getFlat() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Телефон</td>
        <td><?php echo $this->getOrder()->getTelephone() ? $this->getOrder()->getTelephone() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">E-mail</td>
        <td><?php echo $this->getOrder()->getEmail() ? $this->getOrder()->getEmail() : "&nbsp;"; ?></td>
    </tr>
    <tr>
        <td class="column-data">Комментарий</td>
        <td><?php echo $this->getOrder()->getComments() ? htmlspecialchars($this->getOrder()->getComments())
            : "&nbsp;"; ?></td>
    </tr>
<tr>
    <td class="column-data">Цена товаров</td>
    <td><?php echo Shoparticles::showPrice($this->getOrder()->getRealAmount()); ?> грн</td>
</tr>

<tr>
    <td class="column-data">Стоимость доставки</td>
    <td><?php echo Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?> грн</td>
</tr>
<tr>
    <td class="column-data">Депозит</td>
    <td><?php echo Shoparticles::showPrice($this->getOrder()->getDeposit()); ?> грн</td>
</tr>

    <tr>
        <td class="column-data">Сумма</td>
        <td><b><?php echo Shoparticles::showPrice($this->getOrder()->getAmount()); ?> грн</b></td>
    </tr>

    <tr>
        <td colspan="2">
            <a href="/admin/user/edit/id/<?php echo $this->getOrder()->getCustomerId();?>&order_id=<?php echo $this->getOrder()->getId();?>">Редактировать
                клиента</a>
        </td>
    </tr>
</table>

<p><strong>Товары</strong></p>

<table cellpadding="4" cellspacing="0" id="order-articles">
    <tr>
        <td colspan="3" class="kolomicon"><strong>Действие</strong></td>
        <td class="column-article"><strong>Кол./Товар</strong></td>
        <td class="column-article"><strong>Размер/Цвет</strong></td>
        <td><strong>Старая цена</strong></td>
        <td><strong>Скидка</strong></td>
        <td colspan="2"><strong>Цена</strong></td>
    </tr>
    <form method="post" action="">
        <?php $t_price = 0.00; $t_option = 0.00;
        if ($this->getOrder()->getArticles()->count()) {
            ?>
            <?php $bool = false;
            foreach ($this->getOrder()->getArticles() as $main_key => $article_rec) {
                $article = new Shoparticles($article_rec->getArticleId());
                $order_article = new Shoporderarticles($article_rec->getOldArticle());
                $real_order = new Shoporders($this->getOrder()->getOldOrder());
                ?>
                <tr>
                    <td class="kolomicon">
                    </td>

                    <td class="kolomicon">
                        <?php if (!in_array($this->getOrder()->getNewStatus(), array(2, 3))) { ?>
                        <a
                                href="<?php echo $this->path;?>vozrat/id/<?php echo $this->getOrder()->getId(); ?>/del/<?php echo $article_rec->getId();?>/"
                                onclick="return confirm('Удалить?');"><img
                                src="<?php echo SITE_URL; ?>/img/icons/remove-small.png"
                                alt="Удалить" width="24" height="24"/></a>
                        <?php } ?>
                    </td>
                    <td class="kolomicon">

                        <img class="prev" rel="#miesart<?=$article->getId();?>"
                             src="<?php echo $article->getImagePath('small_basket'); ?>"
                             alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/>

                        <div class="simple_overlay" id="miesart<?=$article->getId();?>"
                             style="min-height: 300px; padding:10px 80px">
                            <img src="<?php echo $article->getImagePath('detail'); ?>"
                                 alt="<?php echo htmlspecialchars($article_rec->getTitle()); ?>"/>

                        </div>
                        <div class="previ"
                             style="position: absolute; display: none; margin-left: 600px; margin-top: -150px;">
                            <img src="<?php echo $article->getImagePath('detail'); ?>"
                                 alt="<?php echo htmlspecialchars($article->getTitle()); ?>"/></div>

                    </td>

                    <td class="column-article">


                        <?php echo 'Возврат: '.$article_rec->getCount().' - ';?>
                        <select name="count_<?php echo $article_rec->getId();?>">
                            <?php for ($i = 1; $i <= $order_article->getCount(); $i++) { ?>
                            <option value="<?php echo $i?>" <?php if ($article_rec->getCount() == $i) { ?>selected="selected"<?php } ?>><?php echo $i?></option>
                            <?php } ?>
                        </select>
                        - <a href="<?php echo $article->getPath(); ?>"
                             target="_blank"><?php echo $article_rec->article_db->category->getRoutez() . ' : '; echo $article_rec->getTitle();  if (strlen($article_rec->getCode()) > 0) echo '(КОД: ' . $article_rec->getCode() . ')';?></a>
                    </td>
                    <td class="column-euro">
                        <input type="hidden" class="hidden" value="<?=$article->getId()?>">
                        <?=wsActiveRecord::useStatic('Size')->findById($article_rec->getSize())->getSize();?>
                        /<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article_rec->getColor())->getName();?>


                    </td>
                    <td><?php $price_real = (int)$article_rec->getOldPrice() ? $article_rec->getOldPrice() : $article_rec->getPrice();
                        echo $price_real; ?></td>
                    <td>
                        <?php echo ceil((1 - ($article_rec->getPrice() / $price_real)) * 100);?> %
                        <?php if ($article_rec->getEventSkidka()) { ?>
                        +<?php echo $article_rec->getEventSkidka(); ?>%
                        <?php } ?>
                    </td>
                    <td>
                        <?php $tmp = $article_rec->getPrice() * (1 - ($article_rec->getEventSkidka() / 100)) * $article_rec->getCount(); $t_price += $tmp; echo Shoparticles::showPrice($tmp); ?>
                        грн
                    </td>
                    <td>

                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
        <tr>
            <td colspan="3"></td>
            <td>
                <?php if (!in_array($this->getOrder()->getNewStatus(), array(2, 3))) { ?>
                <input type="submit" name="edit" id="edit" value="Изменить"/>
                <?php } ?>
            </td>
            <td colspan="2"></td>
        </tr>


    </form>


    <tr>
        <td colspan="6" class="tussenrij">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Скидка</strong></td>
        <td class="column-euro"><strong></strong></td>
        <td><strong><?php

            echo $order_owner->getDiscont($old_order->getId()); ?>%</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Всего</strong></td>
        <td class="column-euro"><strong></strong></td>
        <td><strong><?php echo Shoparticles::showPrice($t_price); ?> грн</strong></td>
    </tr>

    <?php if (!in_array($this->getOrder()->getNewStatus(), array(2, 3))) { ?>
    <tr>
        <td colspan="6"><b>Добавить к возврату</b></td>
    </tr>

    <form method="post" action="">

        <?php foreach ($real_order->articles as $article) {
        if (!ShoporderarticlesVozrat::isArticleVozvat($article->getId())) {
            $article_r = new Shoparticles($article->getArticleId());
            ?>
            <tr>
                <td>
                    <input type="checkbox" name="adda_<?php echo $article->getId()?>"/>
                </td>
                <td></td>
                <td>
                    
                    <img class="prev" rel="#miesart<?=$article_r->getId();?>"
                             src="<?php echo $article_r->getImagePath('small_basket'); ?>"
                             alt="<?php echo htmlspecialchars($article_r->getTitle()); ?>"/>
                    <div class="simple_overlay" id="miesart<?=$article->getId();?>"
                             style="min-height: 300px; padding:10px 80px">
                            <img src="<?php echo $article_r->getImagePath('detail'); ?>"
                                 alt="<?php echo htmlspecialchars($article_r->getTitle()); ?>"/>

                        </div>
                        <div class="previ"
                             style="position: absolute; display: none; margin-left: 600px; margin-top: -150px;">
                            <img src="<?php echo $article_r->getImagePath('detail'); ?>"
                                 alt="<?php echo htmlspecialchars($article_r->getTitle()); ?>"/></div>
                </td>
                <td>
                    <select name="addcount_<?php echo $article->getId();?>">
                        <?php for ($i = 1; $i <= $article->getCount(); $i++) { ?>
                        <option value="<?php echo $i?>"><?php echo $i?></option>
                        <?php } ?>
                    </select>
                    <a href="<?php echo $article_r->getPath(); ?>"
                       target="_blank"><?php echo $article->article_db->category->getRoutez() . ' : '; echo $article->getTitle();  if (strlen($article->getCode()) > 0) echo '(КОД: ' . $article->getCode() . ')';?></a>

                </td>
                <td>
                    <?=wsActiveRecord::useStatic('Size')->findById($article->getSize())->getSize();?>
                    /<?=wsActiveRecord::useStatic('Shoparticlescolor')->findById($article->getColor())->getName();?>
                </td>

                <td><?php $price_real = (int)$article->getOldPrice() ? $article->getOldPrice() : $article->getPrice();
                    echo $price_real; ?></td>
                <td>
                    <?php echo ceil((1 - ($article->getPrice() / $price_real)) * 100);?> %
                    <?php if ($article->getEventSkidka()) { ?>
                    +<?php echo $article->getEventSkidka(); ?>%
                    <?php } ?>
                </td>
                <td>
                    <?php $tmp = $article->getPrice() * (1 - ($article->getEventSkidka() / 100)) * $article->getCount(); $t_price += $tmp; echo Shoparticles::showPrice($tmp); ?>
                    грн
                </td>
            </tr>
            <?php }
    } ?>
        <tr>
            <td colspan="6">
                <input value="Добавить" name="add" type="submit"/>
            </td>
        </tr>
    </form>
    <?php } ?>

</table>


<script type="text/javascript">
$(document).ready(function () {
    $('.prev').hover(function () {
        $(this).parent().find('div.previ').show();
    }, function () {
        $(this).parent().find('div.previ').hide();
    });
    var color_end = 0;
    var size_end = 0;
    $('.color').change(function () {
        var color_id = $(this).val();
        color_end = color_id;
        var size = $(this).parent().find('.size');
        var size_id = size.val();
        if (color_id == '0') {
            window.location.reload(true);
            return(false);
        }
        size.attr('disabled', true);
        size.html('<option>загрузка...</option>');
        var url = '/page/getsize/';
        $.get(
                url,
                "color_id=" + color_id + '&article_id=' + $(this).parent().find('.hidden').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.size).each(function () {
                            option = '';
                            if (size_id == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        size.html(options);
                        size.attr('disabled', false);
                    }
                },
                "json"
        );
        if (color_end != 0 && size_end != 0) {
            $(this).parent().parent().find('.chek_edit').removeAttr("checked");
            var count_item = $(this).parent().parent().find('.count');
            var count = count_item.val();
            count_item.attr('disabled', true);
            count_item.html('<option>загрузка...</option>');
            var url = '/page/getcount/';
            $.get(
                    url,
                    "color_id=" + color_end + '&size_id=' + size_end + '&article_id=' + $(this).parent().find('.hidden').val(),
                    function (result) {
                        if (result.type == 'error') {
                            alert('error');
                            return(false);
                        }
                        else {
                            var options = '';
                            var option = '';
                            $(result.count).each(function () {
                                option = '';
                                if (count == $(this).attr('id')) option = 'selected="selected"';
                                options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                            });
                            count_item.html(options);
                            count_item.attr('disabled', false);
                        }
                    },
                    "json"
            );
        }
    });


    $('.size').change(function () {
        var size_id = $(this).val();
        size_end = size_id;
        var color = $(this).parent().find('.color');
        var color_id = color.val();
        if (size_id == '0') {
            window.location.reload(true);
            return(false);
        }
        color.attr('disabled', true);
        color.html('<option>загрузка...</option>');
        var url = '/page/getcolor/';
        $.get(
                url,
                "size_id=" + size_id + '&article_id=' + $(this).parent().find('.hidden').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.color).each(function () {
                            option = '';
                            if (color_id == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '"' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        color.html(options);
                        color.attr('disabled', false);
                    }
                },
                "json"
        );
        if (color_end != 0 && size_end != 0) {
            $(this).parent().parent().find('.chek_edit').removeAttr("checked");
            var count_item = $(this).parent().parent().find('.count');
            var count = count_item.val();
            count_item.attr('disabled', true);
            count_item.html('<option>загрузка...</option>');
            var url = '/page/getcount/';
            $.get(
                    url,
                    "color_id=" + color_end + '&size_id=' + size_end + '&article_id=' + $(this).parent().find('.hidden').val(),
                    function (result) {
                        if (result.type == 'error') {
                            alert('error');
                            return(false);
                        }
                        else {
                            var options = '';
                            var option = '';
                            $(result.count).each(function () {
                                option = '';
                                if (count == $(this).attr('id')) option = 'selected="selected"';
                                options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                            });
                            count_item.html(options);
                            count_item.attr('disabled', false);
                        }
                    },
                    "json"
            );
        }
    });
    $('.color_id').change(function () {
        var color_id = $(this).val();
        var size_id = $('.size_id').val();
        if (color_id == '0') {
            window.location.reload(true);
            return(false);
        }
        $('.size_id').attr('disabled', true);
        $('.size_id').html('<option>загрузка...</option>');
        var url = '/page/getsize/';
        $.get(
                url,
                "color_id=" + color_id + '&article_id=' + $('#article_id').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.size).each(function () {
                            option = '';
                            if (size_id == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '" ' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        $('.size_id').html(options);
                        $('.size_id').attr('disabled', false);
                    }
                },
                "json"
        );
    });


    $('.size_id').change(function () {
        var size_id = $(this).val();
        var color_id = $('.color_id').val();
        if (size_id == '0') {
            window.location.reload(true);
            return(false);
        }
        $('.color_id').attr('disabled', true);
        $('.color_id').html('<option>загрузка...</option>');
        var url = '/page/getcolor/';
        $.get(
                url,
                "size_id=" + size_id + '&article_id=' + $('#article_id').val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        var option = '';
                        $(result.color).each(function () {
                            option = '';
                            if (color_id == $(this).attr('id')) option = 'selected="selected"';
                            options += '<option value="' + $(this).attr('id') + '"' + option + '>' + $(this).attr('title') + '</option>';
                        });
                        $('.color_id').html(options);
                        $('.color_id').attr('disabled', false);
                    }
                },
                "json"
        );
    });
    $('#article_id').change(function () {
        $('.color_id').attr('disabled', true);
        $('.color_id').html('<option>загрузка...</option>');
        $('.size_id').attr('disabled', true);
        $('.size_id').html('<option>загрузка...</option>');
        var url = '/page/getcolorandsize/';
        $.get(
                url,
                '&article_id=' + $(this).val(),
                function (result) {
                    if (result.type == 'error') {
                        alert('error');
                        return(false);
                    }
                    else {
                        var options = '';
                        $(result.color).each(function () {
                            options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                        });
                        $('.color_id').html(options);
                        $('.color_id').attr('disabled', false);
                        var options = '';
                        $(result.size).each(function () {
                            options += '<option value="' + $(this).attr('id') + '" >' + $(this).attr('title') + '</option>';
                        });
                        $('.size_id').html(options);
                        $('.size_id').attr('disabled', false);
                    }
                },
                "json"
        );

    });
});

</script>
<script type="text/javascript">

    function loadArticles(category_id) {
        var data_to_post = new Object();
        data_to_post.id = category_id;
        data_to_post.getarticles = '1';
        $.post('<?php echo $this->path . "shop-orders/"; ?>', data_to_post, function (data) {
            createSelectList(data);
        }, 'json');
        $('#article_id').html('');
        $('#option_id').html('');
    }

    function loadOptions(article_id) {
        var data_to_post = new Object();
        data_to_post.id = article_id;
        data_to_post.getoptions = '1';
        data_to_post.delivery_cost = '<?php echo Shoparticles::showPrice($this->getOrder()->getDeliveryCost()); ?>';
        data_to_post.articles_count = '<?php echo $this->getOrder()->count(); ?>';
        $.post('<?php echo $this->path . "shop-orders/"; ?>', data_to_post, function (data) {
            createSelectList(data);
        }, 'json');
        $('#option_id').html('');
    }

    function createSelectList(data) {
        if ('done' == data.result) {
            out = '';
            himg = '';
            for (var i = 0; i < data.data.length; i++) {
                if (data.data[i].img) {
                    himg += '<img style="display: none;" id ="aih_' + data.data[i].id + '" src="' + data.data[i].img + '"  />';
                }
                out += '<option value="' + data.data[i].id + '">' + data.data[i].title + himg + '</option>';
            }
            if ('articles' == data.type) {
                out = '<option value="0" selected>Выберите товар...</option>' + out;
                $('#article_id').html(out);
                $('#aih_box').html(himg);
            } else {
                out = '<option value="0" selected>Selecteer een optie...</option>' + out;
                $('#option_id').html(out);
            }
        }
    }
    $('#article_id').mousemove(function () {
        $('#aih_box img').hide();
        $('#aih_box #aih_' + $(this).attr('value')).show();
    }).mouseout(function () {
                $('#aih_box img').hide();
            });

</script>

