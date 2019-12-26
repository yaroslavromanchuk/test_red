<div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title">Адреса</h6>

        <table class="table table-hover" >
		  <thead class="bg-info">
									<tr>
                                                                             <th></th>
									<th>Действия</th>
									<th>Магазин</th>
									<th>Контакты</th>
									
									</tr>
		</thead>
                <tbody  id="sortable">
                    <?php
                    if($this->address){
                        foreach ($this->address as $n) { ?>
                    <tr id="<?=$n->id?>">
                        <td><i class="menu-item-icon icon ion-ios-list-outline tx-22  m-2"></i></td>
                        <td>
                            <a href="/admin/stores-address/edit/id/<?=$n->id?>/" data-tooltip="tooltip" target="blank" class="d-inline-block" title="" data-original-title="Редактировать">
				  <i class="menu-item-icon icon ion-md-paper tx-22  m-2"></i>
				  </a><a href="/admin/stores-address/delete/id/<?=$n->id?>/" data-tooltip="tooltip" class="d-inline-block" target="blank" title="" data-original-title="Удалить">
				  <i class="menu-item-icon icon ion-ios-trash-outline tx-22  m-2"></i>
				  </a>
                        </td>
                        <td><?=$n->name?></td>
                        <td><?=$n->adress?></td>

                    </tr>
                      <?php  } 
                    }
                    ?>
                </tbody>
        </table>
        </div>
<script>
  $( function() {
    $( "#sortable" ).sortable({
             cursor: 'move',
            update: function() {
                $.ajax({
                    url: '/admin/stores-akciya/', // ссылка на обработчик
                    type: "POST",
                    data: { id: $('#sortable').sortable("toArray"), metod: 'sort' },
                    success: function (data) {
                        console.log(data);
                        // обработка если надо
                    },
                    error: function (e) {
                        console.log(e);
                        // обработка если надо
                }
            });
            }
        });
        $("#sortable").disableSelection();
        });
  </script>
