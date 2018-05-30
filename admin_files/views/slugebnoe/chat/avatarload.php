<?

require('cfg.php');

if (isset($_POST['btn0avatarload']))
{
	$error = false;
	$user = $_POST['username'];
	$avatar = $_FILES['avatar'];

	if (!$error && empty($avatar['tmp_name'])) {
		$error = 'Select avatar for load!';
	}
	if (!$error && !preg_match("/\.(jpg|gif|png)$/i", $avatar['name'])) {
		$error = 'Select JPG, GIF or PNG image!';
	}
	if (!$error && $avatar['size'] > 30000) {
		$error = 'Size of image is too big!';
	}
	
	if (!$error) {
		@mkdir('avatars', 0777);
		@chmod('avatars', 0777);
		$moveto = 'avatars/'.md5($user).'.jpg';
		move_uploaded_file($avatar['tmp_name'], $moveto);
		@chmod($moveto, 0644);
		echo "
			<script type=\"text/javascript\">
				parent.$('avatarloading').style.display = 'none';
				parent.$('avatarload').reset();
				parent.$('avatar').src = 'avatars/".md5($user).".jpg?".time()."';
			</script>
		";
	}
	else {
		echo "
			<script type=\"text/javascript\">
				parent.$('avatarloading').style.display = 'none';
				alert('".$error."');
			</script>
		";
	}
}

/*
	else {
		$ext = preg_replace("/(.+)\.(jpg|jpeg|gif|png)$/i", "\\2", $name);
		$ext = strtolower($ext);
	}
	*/

/*
	if (@file_exists($imgsrc) && $status=="0") {
		//mysql_query("INSERT INTO ".tb_images." (name, cat, title) VALUES('$name', '$cat', '$title')");
		//$id = mysql_insert_id();
		//$src = "uploads/image_".$id.".".$ext;
		//move_uploaded_file($imgsrc, $src);
		//@chmod($src, 0644);
		//$tsize = explode("x", $tsize);
		//$tw = $tsize[0];
		//$th = $tsize[1];
		//$thumb = makethumb($src, "uploads/image_".$id."_thumb.".$ext, $tw, $th, 0, $ext);
		//@chmod($thumb, 0644);
		//mysql_query("UPDATE ".tb_images." SET src='$src', thumb='$thumb' WHERE id='$id'");
		//$status = "Картинка загружена!";
		echo '
			<script type="text/javascript">
				parent.image_clear();
				parent.show(\'btn0image_add\');
				parent.xajax.$(\'image_info\').innerHTML = \''.$status.'\';
				parent.parent.image_view();
				parent.xajax_stats();
			</script>
		';
	}
	else {
		echo '
			<script type="text/javascript">
				parent.show(\'btn0image_add\');
				parent.xajax.$(\'image_info\').innerHTML = \''.$status.'\';
			</script>
		';
	}
}
*/

?>