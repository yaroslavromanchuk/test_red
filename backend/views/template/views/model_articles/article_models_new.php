<div class="card pd-20">
     <h6 class="card-body-title">Создание: <?=$this->getCurMenu()->getTitle()?></h6>
     
     <?php
     if($this->error){
         echo '<div class="alert alert-danger" role="alert">'.$this->error.'</div>';        
     }
         ?>
     <form action="" method="post">
		  <table class="table " >
		  <thead class="bg-info">
					<tr>
                                            <td>Название</td>
                                            <td>Рост</td>
                                            <td>Обхват груди</td>
                                            <td>Талия</td>
                                            <td>Бедра</td>
					</tr>
		</thead>
                <tbody>
                    <tr>
                        <td><input tepe="text" value="<?=$this->get->name?>"  name="name"></td>
                         <td><input tepe="text" value="<?=$this->get->rost?>"  name="rost"></td>
                          <td><input tepe="text" value="<?=$this->get->grud?>"  name="grud"></td>
                           <td><input tepe="text" value="<?=$this->get->taliya?>"  name="taliya"></td>
                            <td><input tepe="text" value="<?=$this->get->bedra?>"  name="bedra"></td>
                        
                    </tr>
                </tbody>
                  </table>
         <button type="submit" name="save" >Создать</button>

</div>

