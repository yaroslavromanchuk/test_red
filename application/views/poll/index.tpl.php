<?php if($this->errors){?>
	<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
            <ul><?php 	foreach($this->errors as $error){?>
	            <li><?php echo $error;?></li>
			<?php } ?>
            </ul>
		</div>
<?php }elseif ($this->questions){?>

<form action="<?php $this->getPath?>" method="post" name="poll_form" id="poll_form" onsubmit="return PollSubmit()">
<table class="poll_main" cellpadding="0" cellspacing="0">
    <tr><td style=" border-bottom: 1px solid #DDDDDD; padding:5px; ">
	<div class="poll_title" style=""><?php echo $this->pollTitle?>
    <input type="submit" value="Проголосовать" style="display: block; float: right;"/>
    </div>
    </td>
        </tr>
    <tr>
    <td style="padding:5px 5px 0 5px;"><div class="">


			<?php
				$i=0;
				foreach ($this->questions as $question){
					echo '';
					if ($question->getType()!=''){//your variant
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
    </table>
</form>


<?php } ?>
<script type="text/javascript" language="javascript">
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
