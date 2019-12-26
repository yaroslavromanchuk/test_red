<div class="alert alert-success focus" >
    <h4 class="alert-heading"><?php if($this->ok == 10){ echo 'Поздравляю!';}elseif($this->ok > 7){ echo 'Отличный результат!';}elseif($this->ok > 5){ echo 'Хороший результат!';}elseif($this->ok > 3){echo 'Не плохо!';}elseif($this->ok > 0){ echo 'Плохо!';}else{echo 'Ужасно!';} ?></h4>
    <p>Вы правильно ответили на <b><?=$this->ok?></b> из <b><?=$this->all?></b> вопросов.</p>
    <hr>
    <p>Вы набрали <b><?=$this->ok?></b> балов.</p>
</div>
<?php
foreach ($this->result as $pol) { 
    $poll =  wsActiveRecord::useStatic('Poll')->findById($pol['poll']);
    if($poll->id){
    ?>
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title"><?=$poll->getName()?></h5>
            <ul class="card-text list-group list-group-flush">
                <?php
				$i=0;
				foreach ($poll->questions as $question){?>
					
                <li class="list-group-item" 
                    <?php if($pol['question'] == $question->getId() and  $pol['result'] == 1){ echo 'style="color:green;"'; }elseif($pol['question'] == $question->getId() and $pol['result'] == 0){ echo 'style="color:red;"';} ?> >
                                                    <?=$question->getName()?>
						</li>
						<?php
					
				}
			?>
</ul>
            </div>
        
        </div>
    <?php }
}
     ?>
