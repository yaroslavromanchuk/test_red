<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>


<form action="" method="get">
    <p>Поиск</p>
    <span> Email </span><input type="text" name="email" value="<?= @$_GET['email']; ?>"/><input type="submit"
                                                                                                value="Найти"/>
</form>
<a href="#" onclick="$('.add').slideToggle(); return false;">Добавить</a>
<div class="add"  style="display: none">
    <form action="" method="post">
        <textarea name="add" style="width: 200px; height: 200px"></textarea>
        <input type="submit" value="Добавить"/>
    </form>
</div>

<p>Всего: <?php echo $this->count; ?></p>
<?php if($this->add>0){?>
    <p>Добавлено: <?php echo $this->add;?></p>
<?php } ?>
<?php if($this->find>0){?>
    <p>Найденно: <?php echo $this->find;?></p>
<?php } ?>
<?php if($this->error>0){?>
    <p>Ошибок: <?php echo $this->error;?></p>
<?php } ?>

<table id="pageslist" cellpadding="2" cellspacing="0" width="980">
    <?php
    $row = 'row1';
    foreach ($this->getSubscribers() as $sub) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
        <tr class="<?php echo $row; ?>">
            <td class="kolomicon"><a href="<?php echo $this->path; ?>blacklist/del/<?php echo $sub->getId(); ?>/" onclick="return confirm('Удалить Email из черного списка?');"><img
                        src="<?php echo SITE_URL; ?>/img/icons/remove-small.png" alt="Удалить"/></a></td>
            <td class="c-projecttitle"><?php echo $sub->getEmail(); ?></td>
            <td class="c-projecttitle"><?php echo date('d-m-Y', strtotime($sub->getCtime())); ?></td>
        </tr>
    <?php
    }
    ?>
</table>

<?php $url = explode('?', $_SERVER['REQUEST_URI']);
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

   ?>