<!-- Dialog return -->
	<div class="modal fade comment-form" id="comment-modal_b_ord1" tabindex="-1" role="dialog" aria-labelledby="comment-modal_b_ord1">
		<div class="modal-dialog" role="document" id="f_order1">
	    	<div class="modal-content modal-md">
				<form  action="/page/returnarticles/" method="post" class="disabled-while-empty" name="qo1">
				<div id="hidennn"> 
				<input type="hidden" name="id_tovar" id="id_tovar" value="<?=$this->getShopItem()->getId()?>" />
						<div class="modal-header">
							<h5 class="modal-title"><?=$this->trans->get('Сообщить, когда появится в наличии')?>!</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">						
							<div class="comment-types">
								<div class="comment-type">
								<span class="red">*</span> - <?=$this->trans->get('Поля, обязательные для заполнения')?>
								</div>
							</div>
                                                    <?php if($this->ws->getCustomer()->getIsLoggedIn()){ ?>
                                                        <div class="form-group form-group-sm">
								<label for="name_r"><?=$this->trans->get('Имя')?><span class="red">*</span></label>
								<input class="form-control" name="name_r" id="name_r" required value="<?=$this->ws->getCustomer()->getFirstName()?>"/>							</div>
							<div class="form-group form-group-sm">
							<label for="email_r">e-mail<span class="red">*</span></label>
	<input class="form-control" type="email" name="email_r" id="email_r" placeholder="sample@domen.com" required value="<?=$this->ws->getCustomer()->getEmail()?>" /> 
							</div>
                                                    <?php }else{ ?>
                                                    <div class="form-group form-group-sm">
                                                        <label for="name_r"><?=$this->trans->get('Имя')?><span class="red">*</span></label>
                                                        <input class="form-control" name="name_r" id="name_r" required value=""/>
                                                    </div>
                                                    <div class="form-group form-group-sm">
								<label for="email_r">e-mail<span class="red">*</span></label>
                                                                <input class="form-control" type="email" name="email_r" id="email_r" placeholder="sample@domen.com" required value=""/>
                                                                </div>
                                                    <?php } ?>
							
                                                    
                                <div class="form-group row">
                    <input type="text" hidden value="<?=$this->getShopItem()->color_id?>" name="color_return" id="color_return">
    <label for="size_sel" class="col-sm-4 col-form-label"><?=$this->trans->get('Размер')?>:</label>
    <div class="col-sm-8 pl-0 ">
    <select class="form-control" name="size_return" required id="size_return" >
        <option label="<?=$this->trans->get('Выберите размер')?>"></option>
        <?php foreach ($this->getShopItem()->sizes as $size) {
				if ($size->getCount()==0) {
                                                                    echo '<option value="'.$size->size->id.'">'.$size->size->size.'</option>';
								}
							}
                                                        ?>
    </select>
        </div>
    <span class="error size">
							<i class="arrow-left"></i>
							<span ><?=$this->trans->get('Выберите размер')?></span>
						</span>
  </div>
                                                    <br>
                                                    <span class="sarticle_return"><?=$this->trans->get('Артикул')?>:<span class="red"></span></span>
							<input style="display: none;" class="form-control " type="text" name="articul" id="articul" value=""/>
						</div>
						<div class="modal-footer">
							<input  type="submit" class="btn btn-lg btn-danger" name="return_save" value="Сохранить">
						</div>
						</div>
				</form>
	    	</div>
	  	</div>

	</div>	
	<script>
            $('form[name="qo1"]').on( "submit", function( event ) {
  event.preventDefault();
 // console.log( $( this ).serialize() );
  $.ajax({
					type: 'POST',
					dataType: 'json', 
					url: '/page/returnarticles/',
                                        data: $( this ).serialize(),
					beforeSend: function(){
						//$('#article').css('opacity', '0.1');
						//$('#wait_circle').show();
					},
					success: function (result) {
                                            console.log(result.message);
                                                    $('div#hidennn .modal-body').html(result.message);
                                                    $('div#hidennn .modal-footer').hide();
					},
					error:function(e){
                                             console.log(e);
					}
				});
  return false;
});		
//для напоминалки
		
		$('#size_return').on('change', function() {
			$( ".error.size_return" ).fadeOut();
			var size_id = $('#size_return').val() || 0;
			var color_id = $('#color_return').val() || 0;
			if (color_id > 0 && size_id > 0) {
				getArticleReturn(size_id, color_id);
			}
			if (size_id === '0') {
				window.location.reload(true);
				return(false);
			}
		});
                // для напоминалки
	function getArticleReturn(sizeid, colorid) {
		if (sizeid > 0) {
			if (colorid > 0) {
				$.ajax({
					type: 'GET',
					dataType: 'json', 
					url: '/page/getarticlereturn/&'+"color_id=" + colorid + '&size_id=' + sizeid + '&article_id=' + <?=$this->getShopItem()->getId()?>+'/',
					success: function (result) {
						if (result.type === 'error') {
							//$('#article').css('opacity', '1');
							//$('#wait_circle').hide();
							$('.sarticle_return span').html('соответствия размер - цвет есть в наличии');
							$('.sarticle_return').show();
						}else {
							$('.sarticle_return span').html(result.code);
							$('#articul').val(result.code);
							$('.sarticle_return').show();
						}
					},
					error:function(e){
						$('.sarticle_return span').html('error_ajax');
						$('.sarticle_return').show();
					}
				});
			}
		}
	}
	// выход для напоминалки
	</script>
	<!-- End dialog return -->
