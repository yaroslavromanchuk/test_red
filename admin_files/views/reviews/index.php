<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img">
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?php echo $_GET['views']; ?>
<style>
.size14{font-size: 14px;}
</style>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">
  <ul class="nav nav-pills" role="tablist">
  <li role="presentation" <?php if(@$this->type and $this->type == 1) echo ' class="active"'; ?>>
  <a href="/admin/reviews-edit/views/1" class="size14">Новые отзывы
 <?php if(@$this->c_rew['otziw_new']) echo '<span class="badge">'.$this->c_rew['otziw_new'].'</span>'; ?> 
  </a>
  </li>
  <li role="presentation" <?php if(@$this->type and $this->type == 2) echo ' class="active"'; ?>>
  <a href="/admin/reviews-edit/views/2" class="size14">Одобренные отзывы
  <?php if(@$this->c_rew['otziw_ok']) echo '<span class="badge">'.$this->c_rew['otziw_ok'].'</span>'; ?> 
  </a>
  </li>
  <li role="presentation" <?php if(@$this->type and $this->type == 3) echo ' class="active"'; ?>>
  <a href="/admin/reviews-edit/views/3" class="size14">Новые ответы <?php if(@$this->c_rew['otvet_new']) echo '<span class="badge">'.$this->c_rew['otvet_new'].'</span>'; ?> 
  </a>
  </li>
  <li role="presentation" <?php if(@$this->type and $this->type == 4) echo ' class="active"'; ?>>
  <a href="/admin/reviews-edit/views/4" class="size14">Одобренные ответы <?php if(@$this->c_rew['otvet_ok']) echo '<span class="badge">'.$this->c_rew['otvet_ok'].'</span>'; ?> 
  </a>
  </li>
  <li role="presentation" <?php if(@$this->type and $this->type == 5) echo ' class="active"'; ?>>
  <a href="/admin/reviews-edit/views/5" class="size14">Скрытые <?php if(@$this->c_rew['hide']) echo '<span class="badge">'.$this->c_rew['hide'].'</span>'; ?> 
  </a>
  </li>
</ul>
  </div>
  <div class="panel-body">
  </div>

  <!-- List group -->
  <ul class="list-group">
  <?php if(@$this->comments){
  foreach($this->comments as $r){ ?>
  <li class="list-group-item" style="font-size: 90%;">
<b><span><?=$r->name.' | '.$r->mail.' |	'.$r->date_add?></span></b> <?php if(@$this->type and ($this->type == 1 or $this->type == 3 or $this->type == 5)) echo '<img src="/admin_files/views/reviews/good.gif"/><a href="?good_comment='.$r->id.'">Одобрить</a> | '; ?>
<?php if(@$this->type and $this->type != 5) echo '<img src="/admin_files/views/reviews/delete.gif"/><a href="?hide_comment='.$r->id.'">Скрыть</a> | '; ?><img src="/admin_files/views/reviews/delete.gif"/><a href="?delete_comment=<?=$r->id?>" >Удалить</a></br></br>
<?=$r->text?>
</li>
  
  <?php }
  } ?>
  </ul>
</div>
<script type="text/javascript">
function view_reviews(e){
$.get('/admin/reviews-edit/metod/view/id/'+e,function (data) {

 $('.list-group').html(data);
 
 });
}
</script>