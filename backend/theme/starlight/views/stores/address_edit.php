<div class="card pd-20 pd-sm-40">
    <a href="/admin/stores-address/" class="btn btn-outline-primary btn-sm m-2">К списку магазинов</a>
          <h6 class="card-body-title">Акция</h6>
           <?php if($this->error){
     foreach ($this->error as $e) {?>
         <div class="alert alert-danger" role="alert">
  <?=$e?>
</div>
    <?php }   
          } ?>
          <form  action="" method="post" enctype="multipart/form-data">
               <div class="row">
                <label class="col-sm-3 form-control-label">Активность: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <label class="ckbox">
                        <input type="checkbox" name="active" value="1" <?php if($this->address->active == 1){ echo 'checked';}?>><span></span>
              </label>
                </div>
              </div>
              <div class="row  mt-2">
                 <input type="text" name="type" class="form-control" hidden value="new">
                <label class="col-sm-3 form-control-label">Магазин: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" name="name" class="form-control" placeholder="Введите название" value="<?=$this->address->name?>">
                </div>
              </div>
              <div class="row mt-2">
                <label class="col-sm-3 form-control-label">Адресс: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">

                    <textarea name="adress"  rows="5" id="adress" class="form-control" ><?=$this->address->adress?></textarea>
                </div>
              </div>
              <div class="row mt-2">
                <label class="col-sm-3 form-control-label">Карта: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <textarea name="google_map"  rows="5"  class="form-control" ><?=$this->address->google_map?></textarea>
                </div>
              </div>
              <div class="row mt-2">
                  <div class="col-sm-12">
                      <div class="form-layout-footer">
                          <button class="btn btn-info mg-r-5" type="submit">Сохранить</button>
                          <button class="btn btn-secondary" type="reset">Очистить</button>
            </div>
                  </div>

              </div>
          </form>
</div>
<script>
      $(function(){
        'use strict';

        // Inline editor
        //var editor = new MediumEditor('.editable');

        // Summernote editor
        $('#adress').summernote({
          height: 150,
          tooltip: false
        })
      });
    </script>