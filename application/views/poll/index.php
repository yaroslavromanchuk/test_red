<button type="button" class="btn btn-primary" 
    <?php if($this->ws->getCustomer()->getIsLoggedIn() and !$this->ws->getCustomer()->getNoPoll()){
         echo 'data-toggle="modal" data-target="#poll_modal" ';
    }elseif(!$this->ws->getCustomer()->getIsLoggedIn()){
        echo 'data-tooltip="tooltip" data-original-title="Для участия в акции, нужно авторизироваться."';
    }else{
         echo 'data-tooltip="tooltip" data-original-title="Вы уже ответили на вопросы, ожидайте розыгрыша 19.12.2019"';
    }
        ?>  >
  Ответить на вопросы
</button>
<div class="modal fade" id="poll_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title"><?=$this->pollTitle?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button> 
		</div>
		<div class="modal-body" id="content_poll">
                  <?=$this->contentPoll?>
                </div>
		<div class="modal-footer">
                    <div class="message_poll_error alert alert-danger" style="display: none"></div>
                    <button class="btn btn-danger"  data-dismiss="modal" aria-hidden="true">Закрыть</button>
			
		</div>
                     
		</div>
	</div>
</div>
<script>
    $(function () {
         $('#poll_modal').on('show.bs.modal', function (e) {
            // var b = e.relatedTarget;
            //  console.log(b);
             
            
         });
         
         $('#poll_modal').on('hide.bs.modal', function (e) {
      // $('#content_poll').load('/poll/form/');
  // do something...
});
    });
    </script>