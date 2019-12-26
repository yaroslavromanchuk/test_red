<div class="card pd-20 mb-3">
     <h5 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h5>
  <div class="card-body">
      <?php if($this->segment){ ?>
      <table class="table">
          <thead>
          <th>id</th>
          <th>Название сегмента</th>
          <th>Колл. клиентов</th>
          </thead>
          <tbody>
        <?php  foreach ($this->segment as $s){ ?>
              <tr>
                  <td><?=$s->id?></td>
                  <td><?=$s->name?></td>
                  <td><?=$s->getCountCustomer()?></td>
              </tr>
         <?php } ?>
              </tbody>
          </table>
    <?php  } ?>
  </div>
  </div>