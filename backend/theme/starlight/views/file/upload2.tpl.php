<script type="text/javascript" src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/mootools.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/Swiff.Uploader.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/Fx.ProgressBar.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/FancyUpload2.js"></script>

<style>
#demo-status
{
	background-color:		#F9F7ED;
	padding:				10px 15px;
	width:					420px;
}
#demo-status .progress
{
	background:				white url(<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/progress-bar/progress.gif) no-repeat;
	background-position:	+50% 0;
	margin-right:			0.5em;
}
#demo-status .progress-text
{
	font-size:				84%;
	font-weight:			bold;
}
#demo-list
{
	list-style:				none;
	width:					450px;
	margin:					0;
}
 
#demo-list li.file
{
	border-bottom:			1px solid #eee;
	background:				url(<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/file.png) no-repeat 4px 4px;
}
#demo-list li.file.file-uploading
{
	background-image:		url(<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/uploading.png);
	background-color:		#D9DDE9;
}
#demo-list li.file.file-success
{
	background-image:		url(<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/success.png);
}
#demo-list li.file.file-failed
{
	background-image:		url(<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/failed.png);
}
 
#demo-list li.file .file-name
{
	font-size:				84%;
	margin-left:			44px;
	display:				block;
	clear:					left;
	line-height:			40px;
	height:					40px;
	font-weight:			bold;
}
#demo-list li.file .file-size
{
	font-size:				84%;
	line-height:			18px;
	float:					right;
	margin-top:				2px;
	margin-right:			6px;
}
#demo-list li.file .file-info
{
	display:				block;
	margin-left:			44px;
	font-size:				84%;
	line-height:			20px;
	
}
#demo-list li.file .file-remove
{
	clear:					right;
	float:					right;
	line-height:			18px;
	margin-right:			6px;
}
.overall-title, .file-title, .current-title, .current-text {
	font-size: 84%;
	font-weight: normal;
}
</style>
<script>
window.addEvent('load', function() {
 
	var swiffy = new FancyUpload2($('demo-status'), $('demo-list'), {
		'url': $('form-demo').action,
		'fieldName': 'file',
		'path': '<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/Swiff.Uploader.swf',
		'onLoad': function() {
			$('demo-status').removeClass('hide');
			$('demo-fallback').destroy();
		},
                        'target': 'demo-browse-images',
		'typeFilter': <?php if ($this->file_type==2) { echo " {'Files (*.pdf)': '*.pdf'},"; } else { echo " {'Images (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png'}, "; } ?>
		
	});
 
	/**
	 * Various interactions
	 */
 
	$('demo-browse-images').addEvent('click', function() {
		swiffy.browse();
		return false;
	});

	$('demo-upload').addEvent('click', function() {
		swiffy.upload();
		return false;
	});
 
});
</script>

<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<form action="<?php echo $this->path;?>upload/?file_type_id=<?php echo $this->file_type;?>&<?php echo session_name() .'='. session_id();?>" method="post" enctype="multipart/form-data" id="form-demo">
	<input type="hidden" name="file_type_id" value="<?php echo $this->file_type;?>"/>
	<fieldset id="demo-fallback">
		<legend><?php echo $this->getCurMenu()->getTitle();?></legend>
		<label for="demo-photoupload">
			Upload files:
			<input type="file" name="file" id="demo-photoupload" />
		</label>
	</fieldset>
 
	<div id="demo-status" class="hide">
		<p>
			<a href="#" id="demo-browse-images">Bladeren...</a> |
			<a href="#" id="demo-upload">Upload</a>
		</p>
		<div>
			<strong class="overall-title">Status alle bestanden</strong><br />
			<img src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/progress-bar/bar.gif" class="progress overall-progress" />
		</div>
		<div>
			<strong class="current-title">Status upload</strong><br />
			<img src="<?php echo SITE_URL;?><?php echo $this->files;?>scripts/fancyupload/assets/progress-bar/bar.gif" class="progress current-progress" />
		</div>
		<div class="current-text"></div>
	</div>
 
	<ul id="demo-list"></ul>
 
</form>