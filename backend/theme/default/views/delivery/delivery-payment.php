<div class="card pd-20 mb-2">
    <div class="card-body">
        <div class="card-title"><?=$this->getCurMenu()->getTitle()?></div>
        <?php if ($this->saved) { ?>
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Запись сохранена!</h4> 
</div>
<?php } ?>
	<?php
        if(isset($this->deliveries)){
		foreach($this->deliveries as $delivery )
		{
		
	?>
                    
		
                         <div class="card form-row">
                             <div class="card-body">
                                  <div class="card-title"><?=$delivery->delivery->name?></div>
                     <form name="d-<?=$delivery->id?>" id="d-<?=$delivery->id?>" method="POST" action="<?=$this->path?>deliverypayment/edit/<?=$delivery->delivery->id?>/" >
                        
                             <table class="table">
                                 <tr>
                                     <td>
                           <input type="text"  hidden="true"  name="d[id]"  value="<?=$delivery->id?>">
                           <input type="text"  hidden="true"  name="d[delivery_id]"  value="<?=$delivery->delivery_id?>">
                    
                           
                 <div class="form-group ">
      <label for="name">Способ оплаты</label>
      <select name="d[payment_id]" placeholder="Оплата" class="form-control form-control-sm">
          <?php if(isset($this->payments)){
     foreach ($this->payments as $p) { ?>
          <option value="<?=$p->id?>" <?=($delivery->payment_id == $p->id) ? 'selected' : ''?> ><?=$p->name?></option>
    <?php }
          } ?>
      </select>
     </div>
                               </td>
                           <td>
<div class="form-group ">
      <label for="name">ФОП</label>
      <select name="d[fop]" placeholder="Фоп" class="form-control form-control-sm">
          <?php if(isset($this->fop)){
     foreach ($this->fop as $f) { ?>
          <option value="<?=$f->id?>" <?=($delivery->fop == $f->id) ? 'selected' : ''?> ><?=$f->name?></option>
    <?php }
          } ?>
      </select>
     </div>
			</td>
                         
                           <td>
                               <div class="form-group ">
      <label for="name">Оплата</label>
      <input type="text" class="form-control form-control-sm"  name="d[price]" placeholder="" value="<?=$delivery->price?>">
    </div>
                               </td>
                           <td>
                               <div class="form-group ">
      <label for="name">Макс. Сумма</label>
      <input type="text" class="form-control form-control-sm"  name="d[max_sum]" placeholder="" value="<?=$delivery->max_sum?>">
    </div>
                               </td>
                           <td>
                               <div class="form-group ">
                                    <label for="name">Активность</label>
                        <label class="ckbox">
<input type="checkbox" <?=$delivery->active?'checked':''?> name="d[active]"    class="inputdemo">
<span></span>
</label> 
                                   </div>
                           </td>
                           <td>
                               <div class="text-center">
                                   <input type="submit" class="btn btn-primary btn-sm" name="save_delivery_<?=$delivery->id?>"  value="Сохранить"/>
          </div>
                           </td>
                           </tr>
                           </table>
                            
                  </form>
                                 </div>
                              </div>
              
	<?php
		}
                }
	?>
</div>
    <div class="card-footer">
        <p><a class="btn btn-success" href="<?=$this->path?>delivery_type/edit/id/">Новый способ доставки</a></p>
    </div>
    </div>