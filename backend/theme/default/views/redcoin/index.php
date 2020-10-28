<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title">Просмотр бонусов клиентов</div>
  <form name="list_order" method="GET"  class="">
      <div class="row">
          <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Статус:</label>
     <select class="form-control" name="status" id="status" >
         <option label="Все"></option>
         <?php if($this->status){
     foreach ($this->status as $s){ ?>
         <option value="<?=$s->id?>" <?php if($_GET['status'] and $_GET['status'] == $s->id){ echo "selected = 'true'";} ?>><?=$s->name?></option>
     <?php }
         } ?>
     </select>
      </div>
          </div>
                    <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Id заказа:</label>
     <input class="form-control" name="order_id_add" value="<?=$_GET['order_id_add']?$_GET['order_id_add']:''?>">
      </div>
          </div>
        <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Id пользователя:</label>
     <input class="form-control" name="customer_id" value="<?=$_GET['customer_id']?$_GET['customer_id']:''?>">
      </div>
          </div>

          <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Дата зачисления:</label>
     <input class="form-control" name="date_add" type="date" value="<?=$_GET['date_add']?$_GET['date_add']:''?>">
      </div>
          </div>
          <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Дата активации:</label>
     <input class="form-control" name="date_actrive" type="date" value="<?=$_GET['date_actrive']?$_GET['date_actrive']:''?>">
      </div>
          </div>
          <div class="col-sm-12 col-md-2">
                <div class="form-group">
     <label class="form-control-label">Дата списания:</label>
     <input class="form-control" name="date_off" type="date" value="<?=$_GET['date_off']?$_GET['date_off']:''?>">
      </div>
          </div>
      </div>
  
  <button type="submit" class="btn btn-primary mb-2">Искать</button>
      </form>
        <div class="message">
            <?php if($this->mess){
     foreach($this->mess as $r){
         echo $r; 
     }
            } ?>
        </div>
</div>

    </div>

<?php if($this->coin){ ?>
<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title">Бонусы по фильтру</div> 
<table class="table  datatable1 table-hover dataTable no-footer">
    <thead>
        <th>Статус</th>
    <th>Заказ</th>
    <th>Пользователь</th>
     <th>Дата начисления</th>
      <th>Дата активации</th>
       <th>Дата списания</th>
        <th>Зачислено</th>
        <th>Использовано</th>
        <th>Остаток</th>
    </thead>
    <tbody>
        <?php foreach ($this->coin as $c){ ?>
        <tr>
            <td><?=$c->status_name->name?></td>
            <td><?=$c->order_id_add?></td>
            <td><?=$c->customer_id?></td>
            <td><?=$c->date_add?></td>
            <td><?=$c->date_active?></td>
            <td><?=$c->date_off?></td>
            <td><?=$c->coin+$c->coin_on?></td>
            <td><?=$c->coin_on?></td>
            <td><?=$c->coin?></td>
        </tr>
       <?php }  ?>
    </tbody>
    
</table>
            </div>
</div>
<?php } ?>


