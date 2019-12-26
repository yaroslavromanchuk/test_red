	<div class="col-sm-12 bg-white">
            <div class="row m-auto">
                <div class="col-sm-12 col-md-4 col-xl-3 text-center">
                         <img class="align-self-center mr-3" style="max-width: 100%" src="<?=$this->getBrand()->getImage();?>">
                </div>
                <div class="col-sm-12 col-md-8 col-xl-9">
                      <div class="media-body">
    <p><?=$this->getBrand()->getText()?></p>
  </div>
                </div>
            </div>
	</div>
<div class="col-sm-12 text-center p-2">
<?php
if($this->ws->getCustomer()->getIsLoggedIn()){
    if($this->getBrand()->getIsSubscribe($this->ws->getCustomer()->getId())){ ?>
    <a href="#" class="btn btn-danger sub"  data-method="dell" data-id="<?=$this->getBrand()->id?>" ><?=$this->trans->get('Отписаться от обновлений бренда')?></a>
   <?php  }else{ ?>
       <a href="#" class="btn btn-danger sub" data-method="add"  data-id="<?=$this->getBrand()->id?>" ><?=$this->trans->get('Подписаться на обновления бренда')?></a>
   <?php } ?>
<?php }else{ ?>
       <div style="margin: auto; display: inline-block">
       <form class="form-inline was-validated" id="sub_brand" name="sub_brand">
           <input type="text" hidden=""  name="subscribe"  value="<?=$this->getBrand()->id?>">
             <input type="text" hidden=""  name="method"  value="sub">
  <div class="form-group mb-2">
    <label for="email" class="sr-only">Email</label>
    <input type="text"  class="form-control" required="true" id="email" name="email"  placeholder="email@example.com" value="">
  </div>
  <button type="submit" class="btn btn-danger mb-2"><?=$this->trans->get('Подписаться на обновления бренда')?></button>
</form>
           </div>
       <div id="mess_sub" style="display: none;" class="alert alert-danger"></div>
<?php } 
?>   
</div>
<div class="col-sm-12">
<?=$this->articlesHtml;?>
    </div>
<?php
$count = $this->getBrand()->getCount();
if($count > 4){ ?>
<div class="col-sm-12 text-center">
<a href="<?=$this->getBrand()->getPathFind()?>" class="btn btn-danger"><?=$this->trans->get('Показать все товары этого бренда')?> (<?=$count?>) </a>
</div>
<?php } ?>

<script>
    
    $(".sub").click(function(е)   { 
    Add($(this).data("id"), $(this).data("method"), $(this));
     return false;
    });
    function Add(id, method, e){
         $.ajax({
                url:'/brands/subscribe/'+id+'/',
                type: 'POST',
               // dataType: 'json',
                data: {method: method, subscribe: id},
                success: function(res){
                    console.log(res);
                    e.text(res);
                },
        error: function(e, t){
            console.log(t);
            console.log(e.responseText);
            
        },
                complete:function(res){
                    e.removeClass('sub');
                    setTimeout(function(){e.hide();}, 3000);
        }
    });
        return false;
    }
     
    $('#sub_brand').on('submit', function(e){
        e.preventDefault();
        Sub($(this).serialize());
        return false;
    });
    function Sub(f){
    console.log('tut');
            $.ajax({
                url:'/brands/subscribe/1/',
                type:'POST',
               // dataType:'json',
                data: f,
                success:function(res){
                    console.log(res);
                    $('#mess_sub').html(res);
                    $('#mess_sub').fadeIn();
                },
        error: function(e){
            console.log(e);
            
        },
                complete:function(res){
                //e.removeClass('sub');
               // e.hide();
                    setTimeout(function(){$('#mess_sub').fadeOut();}, 3000);
        }
    });
return false;
    }
 </script>   