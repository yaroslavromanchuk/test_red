<!-- !Comment modal -->
	<div class="modal fade comment-form" id="comment-modal_b_ord" tabindex="-1" role="dialog" aria-labelledby="comment-modal_b_ord">
		<div class="modal-dialog" role="document" id="f_order">
	    	<div class="modal-content modal-md"> 
				<form id="qo1"  method="post" class="disabled-while-empty" name="qo">
				<div id="hide">
				<input type="hidden" name="id" id="quik_order-id" value="<?=$this->getShopItem()->getId()?>">
                                <input type="hidden" name="shop_id" id="quik_shop_id" value="<?=$this->getShopItem()->shop_id?>">
						<div class="modal-header">
							<h5 class="modal-title"><?=$this->trans->get('Быстрый заказ')?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
						<div id="qo-result" style="display:none;"></div>						
							<div class="comment-types">
								<div class="comment-type">
								<span class="red">*</span> - <?=$this->trans->get('Поля, обязательные для заполнения')?>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<label for="quik_order-name"><?=$this->trans->get('Имя')?><span class="red">*</span></label>
								<input class="form-control" name="name" id="quik_order-name" required value="<?php
								if (isset($this->basket_contacts['name']) and $this->basket_contacts['name']) {
									echo htmlspecialchars($this->basket_contacts['name']);
								}elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getFirstName();
								}
							?>" />
							</div>
                                                <div class="form-group form-group-sm">
								<label for="quik_order-middle_name"><?=$this->trans->get('Фамилия');?><span class="red">*</span></label>
								<input class="form-control" name="middle_name" id="quik_order-middle_name" required value="<?php
								if (isset($this->basket_contacts['middle_name']) and $this->basket_contacts['middle_name']) {
									echo htmlspecialchars($this->basket_contacts['middle_name']);
								}
								elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getMiddleName();
								}
							?>" />
							</div>
							<div class="form-group form-group-sm">
								<label  for="telephone">Телефон<span class="red">*</span><span style="color:red;" id="leb"></span></label>
<input type="tel" class="form-control phone_form" name="telephone" id="telephone"  placeholder="38(000)000-00-00" maxlength="16"  required  value="<?php
								if (isset($this->basket_contacts['telephone']) and $this->basket_contacts['telephone']) {
									echo htmlspecialchars($this->basket_contacts['telephone']);
								}
								elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getPhone1();
								}
							?>" />
							</div>
							<div class="form-group form-group-sm">
								<label  for="email">e-mail<span class="red">*</span></label>
							<input class="form-control" type="email" name="email" id="email" placeholder="sample@domen.com" required value="<?php
								if (isset($this->basket_contacts['email']) and $this->basket_contacts['email']) {
									echo htmlspecialchars($this->basket_contacts['email']);
								}elseif ($this->ws->getCustomer()->getIsLoggedIn()) {
									echo $this->ws->getCustomer()->getEmail();
								}
							?>" />
							
							</div>
							<div class="form-group">
								<label class="" for="quik_order-comment"><?=$this->trans->get('Комментарий')?></label>
								<textarea class="form-control" name="comment" id="quik_order-comment" rows="3" cols="16" style="max-width: 100%;"></textarea>
							</div>
							
						</div>
						<div class="modal-footer">
<input  type="submit" class="btn btn-danger" value="<?=$this->trans->get('Заказать')?>" />
						</div>
						</div>

				</form>
	    	</div>
	  	</div>

	</div>	
	<!-- End Comment modal -->
