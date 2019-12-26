<div class="card pd-20 pd-sm-40">
    <?php
$iskat = $this->trans->get('Искать');
?>

<form action="/admin/shop-orders/" method="get" class="form">
    <div class="d-flex align-items-center justify-content-center bg-gray-100 ht-md-80 bd pd-x-20">
            <div class="d-md-flex pd-y-20 pd-md-y-0">
                <label class="col-sm-4 m-auto form-control-label"><?=$this->trans->get('Поиск заказа №')?>: </label>
              <input type="text" name="order" class="form-control" placeholder="Заказ">
              <button type="submit" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0"><?=$iskat?></button>
            </div>
          </div>
</form>

<form action="/admin/viewOrder/" method="get" class="form">
     <div class="d-flex align-items-center justify-content-center bg-gray-100 ht-md-80 bd pd-x-20">
            <div class="d-md-flex pd-y-20 pd-md-y-0">
                <label class="col-sm-4 m-auto form-control-label"><?=$this->trans->get('Просмотр заказа №');?>: </label>
              <input type="text" name="order" class="form-control" placeholder="Заказ">
              <button type="submit" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0"><?=$iskat?></button>
            </div>
          </div>
</form>

<form action="/admin/viewOrder/metod/edit/" method="get" class="form">
    <div class="d-flex align-items-center justify-content-center bg-gray-100 ht-md-80 bd pd-x-20">
            <div class="d-md-flex pd-y-20 pd-md-y-0">
                <label class="col-sm-4 m-auto form-control-label"><?=$this->trans->get('Редактирование заказа №');?>: </label>
              <input type="text" name="order" class="form-control" placeholder="Заказ">
              <button type="submit" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0"><?=$iskat?></button>
            </div>
          </div>

</form>
<form action="/admin/viewOrder/metod/view/" method="get" class="form">
    <div class="d-flex align-items-center justify-content-center bg-gray-100 ht-md-80 bd pd-x-20">
            <div class="d-md-flex pd-y-20 pd-md-y-0">
                <label class="col-sm-4 m-auto form-control-label"><?=$this->trans->get('Просмотр товара по артикулу №');?>: </label>
              <input type="text" name="articul"class="form-control" placeholder="Заказ">
              <button type="submit" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0"><?=$iskat?></button>
            </div>
          </div>
</form>
</div>



