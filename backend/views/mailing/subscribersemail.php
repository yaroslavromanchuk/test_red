<style>.modal-dialog{width: 730px;}</style>
<div class="row">
<div class="panel panel-info">
<div class="panel-heading"><h3 class="panel-title">Рассылки</h3></div>
<div class="panel-body">
<div id="head" style="text-align: center;">
	<input name="all" type="button" class="btn btn-info" id="all" onclick="return sendPost('all');" value="Общая">
<input name="shop" type="button" class="btn btn-warning" id="shop" onclick="return sendPost('shop');" value="Магазины">
	<input name="men" type="button"  class="btn btn-primary" id="men" onclick="return sendPost('men');" value="Мужская">
<input name="women" type="button"  class="btn btn-danger" id="women" onclick="return sendPost('women');" value="Женская">
	<input name="baby" type="button"  class="btn btn-success" id="baby" onclick="return sendPost('baby');" value="Детская">
	</div>
</div>
</div>
</div>
<div id="view" class="row"></div>
<script>
 function Dell(x) {
            var data = '?preview=dell&id='+x;
           return sendSave(data);
        }

 function Preview(x) {
            var data = '&preview=view&id='+x;
           return sendSave(data);
        }
	function sendPost(dat) {
		console.log(dat);
            $.ajax({
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: '&par='+dat,
                success: function (data) {
				//console.log(data);
				if(data.send){
				$('#view').html(data.result);
				}else{
				alert("error");
				}	
                },
				error: function(e){
				console.log(e);
				}
            });
			return false;

        }	
		
		
function sendSave(dat) {
		console.log(dat);
            $.ajax({
                url: '/admin/subscribersemail/',
                type: 'POST',
                dataType: 'json',
                data: dat,
                success: function (data) {
			//console.log(data);
				if(data.send == 'dell'){
				$('#view').html(data.result);
				}else{ 
				fopen(data.subject, data.result);
				}	
                },
				error: function(data){
				console.log(data);
				}
            });
			return false;

        }
</script>