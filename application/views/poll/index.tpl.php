<?php if($this->errors){?>
	<div id="errormessage"><img src="/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
            <ul><?php 	foreach($this->errors as $error){?>
	            <li><?php echo $error;?></li>
			<?php } ?>
            </ul>
		</div>
<?php }elseif ($this->questions){ ?>
<div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#poll_modal" style="position: absolute;
    top: 20%;
    left: 5%;">
  Викторина
</button></div>
<div class="modal fade" id="poll_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title"><?=$this->pollTitle?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
		</div>
                     <form action="<?php $this->getPath?>" method="post" name="poll_form" id="poll_form" onsubmit="return PollSubmit()">
		<div class="modal-body">
                  
<table class="poll_main" cellpadding="0" cellspacing="0">
    <tr>
    <td style="padding:5px 5px 0 5px;">
        <div class="">


			<?php
				$i=0;
				foreach ($this->questions as $question){
					echo '';
					if ($question->getType()!=''){ //your variant
						echo '
						<div class="poll_ansver">
								<label style="margin:0;float:none!important;" for="poll_q_id'.$i.'">'.$question->getName().'</label>
							<input type="text" name="usertype" style="width: 30px" id="usertype" maxlength="255" onclick="$(\'#poll_q_id'.$i.'\').attr(\'checked\',true)"/>
							<input type="radio" name="poll_q_id" id="poll_q_id'.$i.'" value="'.$question->getId().'" />
						    </div>
						';
					}else{
						echo '
						<div class="poll_ansver">
							<label style="margin:0;float:none!important;" for="poll_q_id'.$i.'">'.$question->getName().'</label>
							<input type="radio" name="poll_q_id" id="poll_q_id'.$i.'" value="'.$question->getId().'"  />
						</div>
						';
					}

					$i++;
				}
			?>




</div></td>
</tr>
    </table>

                </div>
		<div class="modal-footer">
                    <button class="btn btn-danger"  data-dismiss="modal" aria-hidden="true">Закрыть</button>
			<button class="btn btn-secondary" type="submit" >Проголосовать</button>
		</div>
                         </form>
		</div>
	</div>
</div>


<script >
   $(function () {
         $('#poll_modal').on('show.bs.modal', function (e) {
        console.log(e);
        console.log($(this));
  // do something...
});
    });
   
	function PollSubmit(){
		if ($('#poll_form input[@type=radio]:checked').length > 0){
			$('#poll_submit_button').html('<img src="/images/loader.gif" alt="Loading..." />');
			var mdata = $('#poll_form').serialize();
			$.ajax({
				type: "POST",
				url: "/poll/&"+mdata,
				data: mdata,
				success: function(msg){
				 $('#poll_div').html('<?php echo addslashes('<img src="/img/golos_ok.png" width="499" height="75" alt="senk" />')?>'+msg);
			   }
		    });
		}
		return false;
	}

</script>
<?php } ?>

