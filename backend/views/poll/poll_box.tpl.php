<?php if (!$this->user->getNoPoll(1)) {
$td = date("Y-m-d"); 
    $polls = wsActiveRecord::useStatic('Poll')->findAll(array('active' => 2, "'$td' < date  "), array('id' => 'ASC'));
	if($polls->count() > 0){
	$poll = null;
	$polls_view = array();
    foreach ($polls as $p) {
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
        ?>
        <div class="fix_box">
            <div class="poll_box">
			<p style="font-size: 12px;">Скоро праздник 8 марта и мы решили создать опрос "Что не стоит дарить женщинам на 8 марта". Для этого просим ответить на несколько вопросов ниже.<br>Статья с самыми интересными ответами выйдет в нашем блоге.</p>
                <?php $j = 0;
                foreach ($polls_view as $poll) {
                    $j++; ?>
                    <div class="ajax_poll <?php if ($j > 1) { ?>hide_poll<?php } ?>">
                        <form action="/poll/ajax/">
                            <input type="hidden" name="id" value="<?php echo $poll->getId() ?>"/>

                            <div class="poll_title" style="height: auto;">
                                <?php echo $poll->getName(); ?>
                            </div>

                            <div class="poll_questions">


                                <?php
                                $i = 0;
                                foreach ($poll->questions as $question) {
                                    echo '';
                                    if ($question->getType()) { //your variant
                                        ?>
                                        <div>
           <input type="radio" <?php if (true){ echo 'checked="checked"'; }?> name="poll_q_id" id="poll_q_id<?=$i;?>" value="<?=$question->getId();?>" />
                                            <label style="margin:0;float:none!important;"
                                                   for="poll_q_id<?=$i?>">
                                                <?=$question->getName()?></label><br>
                                            <input type="text" name="usertype" style="max-width: 100%;" class="form-control input"
                                                   id="usertype" maxlength="300" onclick="Usertype('<?php echo $i; ?>'); return false;"
                                                   />


                                        </div>
                                    <?php
                                    } else {
                                        ?>
                                        <div>
                                            <input type="radio" <?php if (false) echo 'checked="checked"' ?>
                                                   name="poll_q_id" id="poll_q_id<?php echo $i ?>"
                                                   value="<?php echo $question->getId() ?>"/>
                                            <label style="margin:0;float:none!important;"
                                                   for="poll_q_id<?php echo $i ?>">
                                                <?php echo $question->getName() ?></label>


                                        </div>
                                    <?php
                                    }

                                    $i++;
                                }
                                ?>


                            </div>
                            <br/>
<div class="poll_button">
                            <a onclick="PollSubmit($(this)); return false;" href="#" class="btn btn-small btn-default ">Проголосовать</a>
                         
						  <a onclick="$(this).parents('.poll_box').hide(); return false;" href="#"
                               class="next-step button">Закрыть</a> <br/>

                           <!-- <a onclick="NoPoll($(this)); return false;" href="#" class="next-step button">Закрыть и
                                не показывать
                                опросы</a>-->
								
</div>

                        </form>

                    </div>
                <?php } ?>

            </div>
        </div>
    <?php }else{
	
	
	} ?>
    <script type="text/javascript" language="javascript">
	function  Usertype(t){
	$("#poll_q_id"+t).attr('checked', true);
	//alert(t);
	}
	$(document).ready(function(){
	//$("#usertype").onclick = function() {
    //alert( 'Спасибо' );
  //};
	
	});
	//onclick="$(\'#poll_q_id'.$i.'\').attr(\'checked\',true)"
        function PollSubmit(ob) {
            if ($('.poll_box input[type=radio]:checked').length > 0) {


                var nxt = ob.parents('.ajax_poll').hide().next('.ajax_poll:first');
                nxt.removeClass('hide_poll');
                if (!nxt.length) {
                    ob.parents('.poll_box').hide();
                }
				var mdata = ob.parents('form').serialize();
				console.log(mdata);
                $.ajax({
                    type: "POST",
                    url: "/poll/?metod=ajax",
                    data: mdata,
                    success: function (msg) {
						console.log(msg);

                    }
                });
            }
           // return false;
        }
        function NoPoll(ob) {
            ob.parents('.poll_box').hide();
			var mdata = ob.parents('form').serialize();
            $.ajax({
                type: "POST",
                url: "/poll/?metod=no_poll",
                data: mdata,
                success: function (msg) {
                }
            });

        }

    </script>
<?php
}else{
if(true){
$today = date("Y-m-d H:i:s"); 
$all_news = wsActiveRecord::useStatic('News')->findFirst(array("status"=> 1, "start_datetime <= '$today' and '$today' <= end_datetime"));
if(@$all_news){ 
$news = wsActiveRecord::useStatic('NewsViews')->findFirst(array('id_news'=>$all_news->getId(), 'user'=>$this->user->getId()));
//echo $news;
if(!@$news){?>
<div class="fix_box">
            <div class="poll_box">
			<input type="text" hidden id="id" name="id" value="<?=$all_news->getId()?>">
			<div class="poll_title" style="height: auto;"><?=$all_news->getTitle();?></div>
			
			<a  href="#" onclick="ViewPoll($(this)); return false;" class="next-step button">Смотреть</a>
			<a onclick="NoPoll($(this)); return false;" href="#" class="next-step button">Закрыть</a>
			</div>
</div>
<?php
}
}
} ?>
 <script type="text/javascript" language="javascript">
   function NoPoll(ob) {
            ob.parents('.poll_box').hide();
			var mdata = '&id='+$("#id").val();
            $.ajax({
                type: "POST",
                url: "/admin/listnews/?metod=close_news",
                data: mdata,
                success: function (msg) {
                }
            });

        }
		 function ViewPoll(ob){
		 ob.parents('.poll_box').hide();
		 var mdata = '&id='+$("#id").val();
            $.ajax({
                type: "POST",
                url: "/admin/listnews/?metod=view_news",
                data: mdata,
                success: function (msg) {
				if(msg == 'ok')  window.open("/admin/listnews/id/"+$("#id").val());
                }
            });
		 }
		
 </script>
<?php
}
} ?>