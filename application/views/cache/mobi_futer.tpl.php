<script async  src="/js/common.js?v=1.1"></script>
<footer>
		<div class="w-100 mx-auto text-center ">
				<a href="#" data-evaluation="rss" data-toggle="modal" data-target="#subscribe"  class="btn btn-light" style="background-color: #d3d3d399;font-size: 100%;">
					<img style="margin-top: -7px;width: 20px;margin-right: 10px;"src="/mobil/mimages/rss_email.png" alt="Главная"/>
					<span><?=$this->trans->get('Подписаться на обновления');?></span>
				</a>
		</div>
<div class="mx-auto w-100" >
			<div class="col-md-12">
				<hr>
				<h5 class="text-center"><?=$this->trans->get('Нам важно Ваше мнение');?></h5>
				<div class="evaluate-us">
					<a class="good" data-toggle="modal" href="#" data-target="#saymail"  data-evaluation="good"></a>
					<a class="bad" data-toggle="modal" href="#" data-target="#saymail" data-evaluation="bad"></a>
					<div class="clearfix"></div>
				</div>
				<div class="mt-2"><a href="tel:+380442244000" class="d-block btn btn-light py-1 my-1 "><span>+38(044) 224-40-00</span></a></div>
			</div>
		</div>
		<div class="mx-auto w-100">
			<div class="col-md-12">
				<hr>
				<div class="social-networks">
	    			<a class="social-network social-network-fb" href="http://www.facebook.com/pages/RED-UA/148503625241218?sk=wall" target="_blank">
		    			<div class="social-network-grey"></div>
		    			<div class="social-network-red"></div>
	    			</a><!--
				 --><a class="social-network social-network-instagram" href="http://instagram.com/red_ua" target="_blank">
		    			<div class="social-network-grey"></div>
		    			<div class="social-network-red"></div>
	    			</a><!--
				 --><a class="social-network social-network-youtube" href="https://www.youtube.com/user/SmartRedShopping" target="_blank">
		    			<div class="social-network-grey"></div>
		    			<div class="social-network-red"></div>
					</a><!--
				 --><a class="social-network social-network-blog" href="/blog" target="_blank">
		    			<div class="social-network-grey"></div>
		    			<div class="social-network-red"></div>
					</a>
	    		</div>
				
			</div>
		</div>
		<div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-labelledby="comment-modal-sub" aria-hidden="true">
		<div class="modal-dialog">
	    	<div class="modal-content">
			<div class="modal-header">
							<h4 class="modal-title"><?=$this->trans->get('Новости');?> <span style="color:red;">RED.UA</span></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<form name="comment" id="comment-form" class="disabled-while-empty" method="post" action="/subscribe/">
						<div class="modal-body">
<input type="radio" name="active" id="radio"  value="1" style="display:none;" checked="checked">						
							<div class="form-group form-group-sm">
								<label for="comment-input-name"><?=$this->trans->get('Имя');?></label>
								<input type="text" class="form-control" id="comment-input-name" name="name" placeholder="<?=$this->trans->get('Имя');?>" required>
							</div>
							<div class="form-group form-group-sm">
								<label for="comment-input-email">e-mail</label>
								<input type="email" class="form-control" id="comment-input-email" name="email" placeholder="example@mail.com" required>
							</div>
							<div class="form-group" >
							<div class="form-group form-group-sm" style="text-align: center;">
							<p><?=$this->trans->get('Выберите категории товаров, которые Вам более интересны');?>.</p>
							<label for="m_new" style="float: left;"><?=$this->trans->get('Мужское');?> <input type="checkbox" name="m_new" id="m_new" class="form-control" style="height: 20px;" value="1" ></label>
							<label for="g_new"><?=$this->trans->get('Женское');?> <input type="checkbox" name="g_new" id="g_new"  class="form-control" style="height: 20px;" value="1" ></label>
							<label for="d_new" style="float: right;"><?=$this->trans->get('Детское');?> <input type="checkbox" name="d_new" id="d_new" class="form-control" style="height: 20px;" value="1" ></label>
							</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->trans->get('Отмена');?></button>
							<button type="submit" class="btn btn-default" disabled="disabled"><?=$this->trans->get('Подписаться');?></button>
						</div>	
				</form>
	    	</div>
	  	</div>
	</div>	
	<!-- End Comment modal subscribe -->
		<!-- !Comment modal -->
	<div class="modal fade" id="saymail" tabindex="-1" role="dialog" aria-labelledby="saymail" aria-hidden="true">
		<div class="modal-dialog">
	    	<div class="modal-content">
			<div class="modal-header">
							<h4 class="modal-title"><?=$this->trans->get('Оцените нашу работу');?></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
		    	<form name="comment" id="comment-form" class="disabled-while-empty" method="post" action="/page/saymail/">
						<div class="modal-body">						
							<div class="comment-types"><div class="comment-type">
									<input type="radio" name="comment-type" id="comment-radio-good" value=":)Поблагодарить">
									<label for="comment-radio-good"><?=$this->trans->get('Хорошо');?></label>
								</div>
								<div class="comment-type">
									<input type="radio" name="comment-type" id="comment-radio-bad" value=":(Пожаловаться">
									<label for="comment-radio-bad"><?=$this->trans->get('Плохо');?></label>
								</div>
								
							</div>
							<div class="form-group form-group-sm">
								<label for="comment-input-name"><?=$this->trans->get('Имя');?></label>
								<input type="text" class="form-control" id="comment-input-name" name="name" placeholder="<?=$this->trans->get('Имя');?>" required>
							</div>
							<div class="form-group form-group-sm">
								<label for="comment-input-tel"><?=$this->trans->get('Телефон');?></label>
								<input type="tel" class="form-control" id="comment-input-tel" name="phone" placeholder="+xx (xxx) xxx-xx-xx" required>
							</div>
							<div class="form-group form-group-sm">
								<label for="comment-input-email">e-mail</label>
								<input type="email" class="form-control" id="comment-input-email" name="email" placeholder="example@mail.com" required>
							</div>
							<div class="form-group">
								<label class="" for="comment-text"><?=$this->trans->get('Комментарий');?></label>
								<textarea class="form-control" rows="3" id="comment-text" name="comments" style="max-width: 100%;"></textarea>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->trans->get('Отмена');?></button>
							<button type="submit" class="btn btn-default" disabled="disabled"><?=$this->trans->get('Отправить');?></button>
						</div>
				</form>
	    	</div>
	  	</div>
	</div>	
	<!-- End Comment modal -->	
	</footer>
	
<script>
    /* <![CDATA[ */
    var google_conversion_id = 1005381332;
    var google_conversion_label = "1vqdCJyg5gMQ1M2z3wM";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script   src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript><div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1005381332/?value=0&amp;label=1vqdCJyg5gMQ1M2z3wM&amp;guid=ON&amp;script=0"/>
</div></noscript>

<script async src='//uaadcodedsp.rontar.com/rontar_aud_async.js'></script>
<script>
/*widget*/
(function() {
var widgetId = 'a977b0023f4594ba63190bf5ca00d6ba';
var s = document.createElement('script');
s.type = 'text/javascript';
s.charset = 'utf-8';
s.async = true;
s.src = '//callme.voip.com.ua/script/widget/'+widgetId;
var ss = document.getElementsByTagName('script')[0];
ss.parentNode.insertBefore(s, ss);}
)();

function setUk(l) {
var s = '<?=$_SESSION['lang']?>';
if(l.name != s){
      $.ajax({
         type: "POST",
         url: "/ajax/setlang/",
         data: "&lang="+l.name,
         success: function(res){location.reload();}
          });
		  }
          return false;
}
function setCooki(e) {
document.cookie = "mobil =" + e;
location.reload();
          return false;
}
</script>