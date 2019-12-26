<div class="card pd-20">
     <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
     <?php if($this->models){?>
     <a href="<?=$this->path?>articlemodels/new/1/" data-toggle="tooltip"  title="Добавить модель">Добавить модель</a>
         <div class="table-wrapper">
		  <table class="table display responsive nowrap datatable1" >
		  <thead class="bg-info">
									<tr>
                                            <td>Действия</td>
                                            <td>Название</td>
                                            <td>Рост</td>
                                            <td>Обхват груди</td>
                                            <td>Талия</td>
                                            <td>Бедра</td>
									</tr>
		</thead>
                <tbody>
                    <?php foreach ($this->models as $k => $value) { ?>
                            
                       
                    <tr>
                        <td><a href="<?=$this->path?>articlemodels/edit/<?=$value->getId()?>/" data-toggle="tooltip"  title="Редактировать">
				  <i class="menu-item-icon icon ion-ios-paper-outline tx-22 pd-5" ></i>
				  </a></td>
                        <?php foreach ($value as $key=>$val) {
                            if($key == 'id') continue;?>
                        <td><?=$val?></td>
                          <?php  } ?>
                        
                    </tr>
                    <?php  } ?>
                </tbody>
                  </table>
         </div>
   <?php  }?>
</div>

