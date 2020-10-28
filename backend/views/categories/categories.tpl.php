
    <div class="card card pd-20 ">
    <h6 class="card-body-title"><img src="<?=$this->getCurMenu()->getImage()?>"  class="page-img" /><?=$this->getCurMenu()->getTitle()?></h6>
    <table   class="table  datatable1 table-hover">
		
        <thead>
            <tr>
			<th>Действия</th>
			<th>Название</th>
                        <th>Товаров</th>
			<th>Путь</th>
		</tr>
        </thead>
        <tbody>
                
	<?php
		foreach($this->categories as $category){
                    
                   $count =  $category->getActiveProductCount();
	?>
		<tr>
		<td>
                <a href="<?=$this->path?>shop-categories/edit/id/<?=$category->getId()?>/"><i class="icon ion-ios-create-outline btn-lg"></i></a>
                <?php if($count){ ?>
                <i class="icon ion-ios-close-circle btn-lg"></i>
                    <?php }else{ ?>
                <a href="<?=$this->path?>shop-categories/delete/id/<?=$category->getId()?>/" onclick="return confirm('Удалить категорию?')">
                   <i class="icon ion-ios-close-circle-outline btn-lg"></i>
                </a>
                    <?php  } ?>
		<a href="<?=$this->path?>shop-categories/moveup/id/<?=$category->getId()?>/"><i class="icon ion-ios-arrow-up btn-lg"></i></a>
		<a href="<?=$this->path?>shop-categories/movedown/id/<?=$category->getId()?>/"><i class="icon ion-ios-arrow-down btn-lg"></i></a>
                 </td>
		<td><?=$category->getName()?></td>
                <td><?=$count?></td>
		<td><?=$category->getRoutez()?></td>
		</tr>
	
                <?php  } ?>
        </tbody>
    </table>
</div>
