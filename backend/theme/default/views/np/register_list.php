<div class="card pd-20">
     <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
     <div class="card-body">
         <?php  //echo print_r($this->registers);
         echo $this->registers->success;
         if($this->registers['success'] == 1){
             ?>
         <table class="table table-bordered table-hover">
             <thead>
                 <tr>
                     <td>Дата</td>
                     <td>id</td>
                     <td>Колл.ТТН</td>
                     <td>Статус</td>
                     <td>Действия</td>
                 </tr>
             <thead>
             <tbody>
                 <?php foreach ($this->registers['data'] as $value) { ?>
                  <tr>
                     <td><?=$value['DateTime']?></td>
                     <td><?=$value['Ref']?></td>
                     <td><?=$value['Count']?></td>
                     <td><?=$value['Printed']==1?'Распечатан':'Новый'?></td>
                     <td>
                         <div class="btn-group btn-group-sm m-auto" role="group" aria-label="Basic example">
  <button type="button" onclick="printRegister('<?=$value['Ref']?>'); return false;" class="btn btn-secondary">Печать</button>
  <button type="button" <?php if($value['Printed'] !=1){ ?> onclick="deleteRegister('<?=$value['Ref']?>');" <?php }else{ ?> onclick="alert('Реестр распечатан, удаить нельзя!((('); return false;"<?php } ?> class="btn btn-secondary">Удалить</button> 
</div></td>
                 </tr>
                 <?php } ?>
             </tbody>
         </table>
      <?php   }
         ?>
         
     </div>
</div>

<script>
function printRegister(e){
    window.open("https://my.novaposhta.ua/scanSheet/printScanSheet/refs%5B%5D/"+e+"/type/html/apiKey/920af0b399119755cbca360907f4fa60");

}
function deleteRegister(e){
    //$(this).name;
   // console.log($(this));
    //console.log(e);
     $.ajax({
                url: '/admin/novapochta/?',
                type: 'POST',
                dataType: 'json',
                data: 'metod=delete_register&ref='+e,
                success: function (res) {
                    console.log(res);
		if(res.data){
                    location.reload();
                    
                               }else{
                                    alert('Ошибка');
                                }
                },
                error: function(e){
                    $('<div/>', {  class: 'alert alert-danger alert-dismissible fade show m-2', html: '<strong>Ошибка!</strong> '+e+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' }).appendTo('div.card-header');
                }
            });
    
    return false;
}
</script>

