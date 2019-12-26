<style>.modal-dialog{max-width:730px;width: 730px;}</style>
<div class="row">
<div class="panel panel-info">
<div class="panel-heading"><h3 class="panel-title">Рассылки</h3></div>
<div class="panel-body">
	<ul class="nav nav-tabs">
         <?php foreach (Subscriberstype::getListSubscriberType(true) as $k=>$s) { ?>
             <li class="nav-item ">
                 <a class="nav-link" data-toggle="tab" role="tab" href="#tabs-<?=$s->id?>" onclick="return sendPost(<?=$s->id?>);" data-toggle="tab"><?=$s->name?></a>
             </li>
       <?php  } ?>
             <li class="nav-item ">
                 <select class="select2"  onchange="return sendPostSegment(this.value);">
                     <option label="Выберите сегмент">Выберите сегмент</option>>
                     <?php
                     
 foreach (CustomersSegment::getListCustomersSegmentType() as $c){?>
                     <option value="<?=$c->id?>"><?=$c->name?></option>
 <?php }?>
                     </select>
             </li>
</ul>
        <div id="view" style="margin-top: 1px;"></div>
</div>
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
        function sendPostSegment(dat) { 
		console.log(dat);
            $.ajax({
                beforeSend: function(){
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
                },
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: '&segment_customer='+dat,
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