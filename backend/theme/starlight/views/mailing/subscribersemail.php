<style>.modal-dialog{max-width:750px;}</style>

<div class="card pd-20 mb-3">
 <h5 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h5>
 <div class="card-header">
     <ul class="nav nav-tabs">
         <?php foreach (Subscriberstype::getListSubscriberType(true) as $k=>$s) { ?>
             <li class="nav-item ">
                 <a class="nav-link" data-toggle="tab" role="tab" href="#tabs-<?=$s->id?>" onclick="return sendPost(<?=$s->id?>);" data-toggle="tab"><?=$s->name?></a>
             </li>
       <?php  } ?>
</ul>
 </div>
<div class="card-body tab-content" id="view">

</div>
</div>
<script>
 function Dell(x) {
           $.ajax({
               beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: {dellete: 'dell', id: x},
                success: function (data) {
                   // console.log(data);
		$('#view').html(data.result);	
                $('#foo').detach();
                },
		error: function(data){
		console.log(data);
		}
            });
	return false;
        }

 function Preview(x) {
          $.ajax({
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: {preview: 'view', id: x},
                success: function (data) {
			//console.log(data);
		fopen(data.subject, data.result);
					
                },
		error: function(data){
			console.log(data);
                         $('#foo').detach();
		}
            });
	return false;  
}
        
	function sendPost(dat) { 
		console.log(dat);
            $.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: '&segment='+dat,
                success: function (data) {
				//console.log(data);
				if(data.send){
				$('#view').html(data.result);
				}else{
				alert("error");
				}
                                $('#foo').detach();
                },
				error: function(e){
				console.log(e);
                                $('#foo').detach();
				}
            });
			return false;

        }	
</script>