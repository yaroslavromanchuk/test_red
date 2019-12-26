<div class="card pd-20">
     <h6 class="card-body-title">Редактирование: <?=$this->getCurMenu()->getTitle()?></h6>
     <?php if($this->model){
    // var_dump($this->model);
         ?>
     <form action="" method="post">
		  <table class="table " >
		  <thead class="bg-info">
					<tr>
                                            <td>Название</td>
                                            <td>Рост (cм.)</td>
                                            <td>Обхват груди (cм.)</td>
                                            <td>Талия (cм.)</td>
                                            <td>Бедра (cм.)</td>
					</tr>
		</thead>
                <tbody>
                    <tr>
                        <?php foreach ($this->model as $key=>$value) {
                            if($key == 'id') continue; ?>
                        <td><input tepe="text" class="form-control" value="<?=$value?>" < name="<?=$key?>"></td>
                          <?php  } ?>
                        
                    </tr>
                </tbody>
                  </table>
         <button type="submit" class="btn btn-primary" >Сохранить</button>

     <?php } ?>
</div>

