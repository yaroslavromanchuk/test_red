<div class="card pd-20 pd-sm-40">
    <a href="/admin/stores-temp/" class="btn btn-outline-primary btn-sm m-2">К записям</a>
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
                        <input type="checkbox" name="active" value="1" <?php if($this->akciya->active == 1){ echo 'checked';}?>><span></span>
              </label>
                </div>
              </div>
              <div class="row mt-2">
                 <input type="text" name="type" class="form-control" hidden value="temp">
                <label class="col-sm-3 form-control-label">Название: <span class="tx-danger">*</span></label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <input type="text" name="name" class="form-control" placeholder="Введите название" value="<?=$this->akciya->name?>" required>
                </div>
              </div>
              <div class="row mt-2">
                <label class="col-sm-3 form-control-label">Картинка: <span class="tx-danger">*</span> (1x1)</label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                  
                  <label class="custom-file">
                <input type="file" id="file2" name="src" class="custom-file-input" >
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
            <?php if($this->akciya->getSrc()){ echo '<br/><br/><img style="max-width:150px;" src="' . $this->akciya->getSrc() . '" />'; }?>
                </div>
              </div>
              <div class="row mt-2">
                <label class="col-sm-3 form-control-label">Описание: </label>
                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                    <textarea name="text" id="text" rows="5"  class="form-control" ><?=$this->akciya->text?></textarea>
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

        // Datepicker
      /*  $('.datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true,
          locale: 'ru',
        dateFormat: 'yy-mm-dd'
        });
        */
        // Summernote editor
        $('#text').summernote({
          height: 150,
          tooltip: false
        })
    });
</script>