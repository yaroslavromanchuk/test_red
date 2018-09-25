<? session_start();
if($_SESSION['admin_pass'])

{
	
	if(strlen($_POST['url_type'])==0 or strlen($_POST['comment_max'])==0 or strlen($_POST['send'])==0 or strlen($_POST['theme'])==0 or strlen($_POST['mail']==0))
	{
		$validation = false;
	}
	
	if(!eregi('^[a-z0-9]+(([a-z0-9_.-]+)?)@[a-z0-9+](([a-z0-9_.-]+)?)+\.+[a-z]{2,4}$',$_POST['mail']))
	{
		$validation = false;
	}
	
	if(!eregi('^[0-9]{1,3}$',$_POST['comment_max']))
	{
		echo "<script Language='JavaScript'>alert('Максимальное количество комментариев на странице может быть от 1 до 999')  </script>";
		echo "<script Language='JavaScript'> history.back() </script>";	
		return;
	}
	else
	{
		$p_url = $_POST['url_type'];
		$c_max = $_POST['comment_max'];
		if($p_url == 1 or $p_url == 2 or $p_url == 3){$p_url = $_POST['url_type'];}else{$p_url = 1;}
		include('../config.php');
		mysql_query("update ".$s_tab." set url_type = '".$p_url."', comment_max = '".$c_max."' where id = '{$id}'") or die ("Error! update");	
						
		echo "<script Language='JavaScript'>location.href = 'index.php'</script>";
	}
}
else
{
	echo "Извините, вам сюда нельзя! Попробуйте авторизоваться заново!";
}
?>