<div class="card pd-30 pd-sm-40 form-layout form-layout-4">
              <h6 class="card-body-title">Форма добавления</h6>
              <p class="mg-b-20 mg-sm-b-30">A basic form where labels are aligned in left.</p>
              <div class="row">
                <label class="col-sm-4 form-control-label">Тип размера: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                 <select class="form-control select2" name="type" data-placeholder="Тип">
  <option value="1">Мужское</option>
  <option value="2">Женское</option>
  <option value="8">Детское</option>
  <option value="3">Unisex</option>
</select>
                </div>
              </div><!-- row -->
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Lastname: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" class="form-control" placeholder="Enter lastname">
                </div>
              </div>
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Лого: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                 <label class="custom-file">
                <input type="file" id="file2" name="image" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
                </div>
              </div>
              <div class="row mg-t-20">
                <label class="col-sm-4 form-control-label">Содержимое: <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea rows="2" class="form-control" id="textarea" placeholder="Enter your address"></textarea>
                </div>
              </div>
              <div class="form-layout-footer mg-t-30">
                <button class="btn btn-info mg-r-5">Submit Form</button>
                <button class="btn btn-secondary">Cancel</button>
              </div><!-- form-layout-footer -->
            </div>

<div class="card pd-30 mt-3">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
        <div class="card-body">
            <form name="add_table_size">
                
            </form>
        </div>
<div class="table-wrapper">
    <table class="table display responsive nowrap datatable1">
        <thead class="bg-info">
		<tr>
			<th>Тип</th>
			<th>Категория</th>
			<th>Лого</th>
                        <th>Таблица</th>
		</tr>
        </thead>
        <tbody>
	<?php
        if($this->table){
		foreach($this->table as $sub )
		{ ?>
		<tr>
		<td><a href="<?=$this->path?>size/id/<?=$sub->id?>/"><img src="/img/icons/edit-small.png" alt="Редактирование" /></a>
                        
                 <?php if(!$sub->getArticlesCount()){?>
                    <a href="<?=$this->path?>size/del/<?=$sub->id?>/" onclick="return confirm('Вы действиельно хотите удалить размер?')">
                        <img src="/img/icons/remove-small.png" alt="Удалить" width="24" height="24" />
                    </a>
                <?php } else {?>
                    <?=$sub->getArticlesCount()?> тов.
                <?php } ?>

            </td>
            <td>
                <?=$sub->getSize()?>
            </td>
            <td><?=$sub->category?$sub->category->getName():''?></td>
		</tr>
	<?php
		}
                }
	?>
        </tbody>
    </table>
</div>
</div>