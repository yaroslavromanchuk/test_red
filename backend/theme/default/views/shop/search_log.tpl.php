<img src="<?=$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1><br/>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Форма поиска</h3>
    </div>
      <form method="get" class="form-horizontal">
    <div class="panel-body">
      
<div class="row m-auto">
    <div class="col-sm-12 col-md-6">
       <div class="form-group">
    <label for="from" class="ct-110 control-label">От:</label>
    <div class="col-xs-8">
        <input type="date" value="<?php if(!empty($_GET['from'])){ echo $_GET['from'];}else{echo date('Y-m-d');}?>" class="form-control input " name="from" id="go_from" placeholder="от" >
    </div>
  </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
    <label for="to" class="ct-110 control-label">До:</label>
    <div class="col-xs-8">
	<input type="date" value="<?php if(!empty($_GET['to'])){ echo $_GET['to'];}else{echo date('Y-m-d');}?>"  class="form-control input " name="to" placeholder="до" >
    </div>
  </div>
    </div>
</div>
    

    </div>
    <div class="panel-footer">
<div class="form-group">
    <div class="col-xs-12" style="text-align:center;">
      <button type="submit" name="go" class="btn btn-primary  btn-lg "><i class="glyphicon glyphicon-search" aria-hidden="true"></i>Искать</button>
  </div>
  </div>
  </div>
    </form>
</div>

<?php if ($this->getSearchs()->count()) { ?>

<table cellspacing="0" cellpadding="4" id="orders" class="table">
    <tr>
        <!--<th>Дата</th>-->
        <th>Искали</th>
        <th>Колл.</th>
    </tr>
    <?php  foreach ($this->getSearchs() as $search) {
    ?>
    <tr >
        <!--<td><?php /* $d = new wsDate($search->getCtime()); echo $d->getFormattedDate(); */?></td>-->
        <td><?=$search->search?></td>
        <td><?=$search->ctn?></td>
    </tr>
    <?php } ?>
</table>
<?php } else {echo 'Нет записей'; }?>