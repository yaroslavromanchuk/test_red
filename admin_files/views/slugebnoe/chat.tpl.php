<script>
$('#center-content').css({'background':'#505050', 'border':'1px solid #BBB'});
</script>

<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img" height="32" style="padding: 0 0 0 60px;"/>
<h1 style="color:#DDD;">
	<?php echo $this->getCurMenu()->getTitle();?>
	<span style="font-weight:normal"></span>
</h1>
<div align="center">
<iframe src="/admin_files/views/slugebnoe/demo/index.php?name=<?php echo $this->user->getFirstName();?>&email=<?php echo $this->user->getEmail(); ?>" width="600" height="580" align="center" frameborder="0"></iframe>
</div>