<?php if($this->polls){
    $poll = null;
	$polls_view = array();
    foreach ($this->polls as $p) {
        $find = null;
        if ($this->user->getId()) {
            $find = wsActiveRecord::useStatic('PollResults')->findFirst(array('customer_id' => $this->user->getId(), 'poll_id' => $p->getId()));

        }
        if ($find) {
            $_SESSION['poll'][$p->getId()] = 1;
        } else {
            $_SESSION['poll'][$p->getId()] = 0;
            $polls_view[] = $p;
        }
    }
    if (count($polls_view)) {
   // l($this->questions);
    ?> 
<style>

label {

        display: inline-block;
    cursor: pointer;

    padding-left: 15px;
    font-size: 13px;


}
input[type=radio] {
	width: 18px;
	height: 18px;

	position: relative;
    top: 5px;
	
}
/*
label:before {
	content: "";
	display: inline-block;

	width: 16px;
	height: 16px;

	margin-right: 10px;
	position: absolute;
	left: 0;
	bottom: 1px;
	background-color: #aaa;
	box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);
}
*/
.radio label:before {
	border-radius: 8px;
}

input[type=radio]:checked + label {
    color: #000000;
    font-weight: bold;
}


</style>
<form action="<?php $this->getPath?>" method="post" name="poll_form" id="poll_form" class="was-validated" onsubmit="return PollSubmit()">  
    <?php  foreach ($polls_view as $poll) { ?>
    
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title text-success"><?=$poll->getName()?></h5>
            <ul class=" card-text list-group list-group-flush radio">
                <?php
				$i=0;
				foreach ($poll->questions as $question){
					echo '';
					if ($question->getType()!=''){ //your variant ?>
						
						<li class="list-group-item">
								<label><?=$question->getName()?>
							<input type="text" name="res" style="width: 30px" id="res" maxlength="255" />
                                                                </label>
							<input type="radio" name="poll[<?=$poll->id?>]" value="<?=$question->getId()?>" />
						    </li>
						
					<?php }else{ ?>
						<li class="list-group-item">
                                                            <input type="radio" name="poll[<?=$poll->id?>]" required id="<?=$question->getId()?>"  value="<?=$question->getId()?>"  />
                                                        <label for="<?=$question->getId()?>"><?=$question->getName()?></label>
						</li>
						<?php
					}

					$i++;
				}
			?>
</ul>
            </div>
        
        </div>
    <?php } ?>
<div class="d-block text-center">
			<button class="btn btn-success btn-lg" type="submit" >Отправить ответы</button>
		</div>
                         </form>

<script>
    function PollSubmit(){
       // console.log($('#poll_form').serializeArray());
       // return false;
		//if ($("form input:radio:checked").length === 10){
                   // return false;
			//$('#poll_submit_button').html('<img src="/images/loader.gif" alt="Loading..." />');
			//var mdata = $('#poll_form').serialize();
			$.ajax({
				type: "POST",
				url: "/poll/form/method/go/",
				data: $('#poll_form').serialize(),
				success: function(msg){
                                    $('#content_poll').html(msg);
                                    $('.focus').focus();
				
			   }
		    });
	/*	}else{
                $('.message_poll_error').html('Нужно ответить на все вопросы').show();
                   setTimeout(function () {

                $('.message_poll_error').hide().html('');

            }, 2000);

                }*/
		return false;
	}

</script>
<?php }else{ echo 'Вы уже участвуете в викторине. Спасибо что ответили на все вопросы. Ожидайте объявление победителя.';
    
if($this->ws->getCustomer()->getId()){
                $this->ws->getCustomer()->setNoPoll(1);
                $this->ws->getCustomer()->save();
            }
}
}else{ echo $this->message;
if($this->ws->getCustomer()->getId()){
                $this->ws->getCustomer()->setNoPoll(1);
                $this->ws->getCustomer()->save();
            }
            
} ?>

