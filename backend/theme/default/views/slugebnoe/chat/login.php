<?

$error = false;
$login = false;
$tab = 1;

if (isset($_COOKIE['chatName']))
{
	$chatName = $_COOKIE['chatName'];
} else $chatName = 'Gluk';

/*if (isset($_POST['btn0login']))
{*/
	$chat = new Chat;
	$name = $chatName; //$_POST['name'];
	$password = $_POST['password'];

	if (!$error && $chat->UserOnline($name))
		$error = 'This user is already chatting';
	
	if (!$error)
	{
		@$chat->AddUser($name, $password);
		$login = true;
		//$name = $chat->GetName($name);
		$chat->SendSystemMsg('<span class="other" onClick="whisperTo(\''.$name.'\')">'.$name.'</span> entered chat.');
		$chat->ClearUserCache($name);
		$chat->UpdateTime($name);
		//setcookie('chatName', $name, time()+3600*365);
	}