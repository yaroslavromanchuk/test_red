<div class="card pd-20 mb-3">
     <h5 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h5>
  <div class="card-body">
      <?php if($this->segment){ ?>
      <table class="table">
          <thead>
          <th>id</th>
          <th>Название сегмента</th>
          <th>Колл. клиентов</th>
          <th>Действие</th>
          </thead>
          <tbody>
        <?php  foreach ($this->segment as $s){ ?>
              <tr>
                  <td><?=$s->id?></td>
                  <td><?=$s->name?></td>
                  <td><?=$s->getCountCustomer()?></td>
                  <td><button class="btn btn-success" onclick="this.style.display='none'; return Reload(<?=$s->id?>);">Обновить сегмент</button></td>
              </tr>
         <?php } ?>
              </tbody>
          </table>
    <?php  } ?>
  </div>
  </div>
<script>
function Reload(e){
    //console.log(this);
   $.ajax({
                url: '/admin/customers-segment/',
                type: 'POST',
                dataType: 'json',
                data: {method: 'reload', id: e},
               beforeSend: function( ) {
                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>'}).appendTo('body');
               },
                success: function (data) {
                    console.log(data);
                            alert(data);
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
