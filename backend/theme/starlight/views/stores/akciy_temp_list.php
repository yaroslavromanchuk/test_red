<div class="card pd-20 pd-sm-40">
          <a href="/admin/stores-temp/edit/id/" class="p-1 m-2"><i class="icon ion-ios-create-outline">Новая запись</i></a>

        <table class="table table-hover" >
		  <thead class="bg-info">
									<tr>
                                                                        <th></th>
									<th>Действия</th>
									<th>Название</th>
									<th>Картинка</th>
									<th>Содержимое</th>
                                                                        <th>Ссылка</th>
									</tr>
		</thead>
                <tbody id="sortable">
                    <?php
                    if($this->akciya){
                        foreach ($this->akciya as $n) { ?>
                    <tr id="<?=$n->id?>">
                        <td><i class="menu-item-icon icon ion-ios-list-outline tx-22  m-2"></i></td>
                        <td>
                            <a href="/admin/stores-temp/edit/id/<?=$n->id?>/" data-tooltip="tooltip" target="blank" title="" class="d-inline-block" data-original-title="Редактировать">
				  <i class="menu-item-icon icon ion-md-paper tx-22  m-2"></i>
				  </a><a href="/admin/stores-temp/delete/id/<?=$n->id?>/" data-tooltip="tooltip" target="blank" class="d-inline-block"  title="" data-original-title="Удалить">
				  <i class="menu-item-icon icon ion-ios-trash-outline tx-22  m-2"></i>
				  </a>
                        </td>
                        <td><?=$n->name?></td>
                        <td><img src="<?=$n->src?>" style="width: 30px"></td>
                        <td><?=$n->text?></td>
                        <td><a href="<?=$n->getPath()?>" target="_blank"><?=$n->getPath()?></a></td>
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
                    url: '/admin/stores-temp/', // ссылка на обработчик
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