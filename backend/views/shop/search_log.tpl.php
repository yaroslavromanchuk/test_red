<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1><br/>


<?php if ($this->getSearchs()->count()) { ?>

<table cellspacing="0" cellpadding="4" id="orders">
    <tr>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Искали</th>
    </tr>
    <?php $row = 'row2'; foreach ($this->getSearchs() as $search) {
    $row = ($row == 'row2') ? 'row1' : 'row2';
    ?>
    <tr class="<?php echo $row; ?>">
        <td><?php $d = new wsDate($search->getCtime()); echo $d->getFormattedDateTime(); ?></td>
        <td><?php if($search->getCustomerId()>0) echo '<a href="/admin/user/edit/id/'.$search->getCustomerId().'/">'.$search->customer->getFullname().'</a>'; else echo 'Гость'; ?></td>
        <td><?php echo $search->getSearch(); ?></td>

    </tr>
    <?php } ?>
</table>
<p>
    <label></label>
</p>
    <?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    }
    else {
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
Всего страниц: <?php echo $this->totalPages ?>,  записей: <?php echo $this->count ?>


<?php } else echo 'Нет записей'; ?>
<p>&nbsp;</p>