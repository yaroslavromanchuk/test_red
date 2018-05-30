<script type="text/javascript" src="/js/accordion/jqueryui.custom.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/js/accordion/jqueryui.custom.css"/>
<h1><?php echo $this->getCurMenu()->getName();?></h1>
	<?php echo $this->getCurMenu()->getPageBody();?>
	<br>
	<br>
<script type="text/javascript">
$("#accordion").accordion({active:false, autoHeight:false,collapsible:true});
</script>

