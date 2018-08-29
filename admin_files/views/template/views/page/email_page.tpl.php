<div class="sl-pagebody ">
<div class="card pd-30">
<h6 class="card-body-title">Входящая почта</h6>

</div>
<div class="card pd-30 mg-t-10">
<h6 class="card-body-title">Форма отправки писем</h6>
<form action="" method="POST" class="was-validated" enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
			<div class="col-lg-4 mg-t-40 mg-lg-t-0">
			<div class="form-group">
                  <label class="form-control-label">Тема письма:<span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" name="subject" required placeholder="Тема письма" value="<?=@$this->post->subject?>">
                </div>
            </div>
			<div class="col-lg-4 mg-t-40 mg-lg-t-0">
			<div class="form-group">
                  <label class="form-control-label">Сообщение:<span class="tx-danger">*</span></label>
                  <input type="text" class="form-control" required name="message" placeholder="Введите сообщение" value="<?=@$this->post->message?>">
                </div>
            </div>
			 <div class="col-lg-4 mg-t-40 mg-lg-t-0">
			 <div class="form-group">
			  <label class="form-control-label">Прикрепить файл: </label>
              <label class="custom-file">
                <input type="file" name="file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
			  </div>
            </div>
			
            </div>

            <div class="form-layout-footer">
              <button class="btn btn-info mg-r-5" type="submit" name="send" >Отправить письмо</button>
            </div><!-- form-layout-footer -->
</div>
</form>
</div>

</div>