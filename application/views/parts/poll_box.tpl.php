<?php if (!$this->ws->getCustomer()->getNoPoll(1)) {
    $poll = null;
    $polls = wsActiveRecord::useStatic('Poll')->findAll(array('active' => 2), array('id' => 'ASC'));
    $polls_view = array();
    foreach ($polls as $p) {
        $find = null;
        if ($this->ws->getCustomer()->getId()) {
            $find = wsActiveRecord::useStatic('PollResults')->findFirst(array('customer_id' => $this->ws->getCustomer()->getId(), 'poll_id' => $p->getId()));

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
        <div class="fix_box ">
            <div class="poll_box">
                <h1>Опрос:</h1>
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
                                            <input type="radio" <?php if ($i == 0) echo 'checked="checked"' ?>
                                                   name="poll_q_id" id="poll_q_id<?php echo $i ?>"
                                                   value="<?php echo $question->getId() ?>"/>
                                            <label style="margin:0;float:none!important;"
                                                   for="poll_q_id<?php echo $i ?>">
                                                <?php echo $question->getName() ?></label>
                                            <input type="text" name="usertype" style="width: 100px" class="formfields2"
                                                   id="usertype" maxlength="255"
                                                   onclick="$(\'#poll_q_id'.$i.'\').attr(\'checked\',true)"/>


                                        </div>
                                    <?php
                                    } else {
                                        ?>
                                        <div>
                                            <input type="radio" <?php if ($i == 0) echo 'checked="checked"' ?>
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

                            <a onclick="PollSubmit($(this)); return false;" href="#" class="next-step new_button">Проголосовать</a>
                            <a onclick="$(this).parents('.poll_box').hide(); return false;" href="#"
                               class="next-step new_button">Закрыть</a> <br/>
                            <br/>

                            <a onclick="NoPoll($(this)); return false;" href="#" class="next-step new_button">Закрыть и
                                не показывать
                                опросы</a>


                        </form>

                    </div>
                <?php } ?>

            </div>
        </div>
    <?php } ?>
    <script type="text/javascript" language="javascript">
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
} ?>