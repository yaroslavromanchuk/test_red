<?php
if($this->history){ ?>
<table class="table table-hover table-bordered form_history"  >
    <thead class="text-center">
        <tr>
            <th>Дата</th>
            <th>Администратор</th>
            <th>Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->history as $h){ ?>
        <tr>
            <td><?=$h->ctime?></td>
            <td><?=$h->admin->getFullname()?></td>
            <td><?php $info = []; foreach (unserialize($h->info) as $k => $i){
                $info[] = '<span>'.$k.': '.$i.'</span>';
            }
            echo implode('<br>', $info);
            ?></td>
        </tr>
      <?php  }
        ?>
    </tbody>
</table>
 <?php   
}


