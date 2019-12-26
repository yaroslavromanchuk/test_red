<?

require('cfg.php');

function SendToAll($author, $msg)
{
	$r = new xajaxResponse();
	$chat = new Chat;
	$chat->SendToAll($author, $msg);
	return $r;
}



function SendWhisper($author, $msg)
{
	$r = new xajaxResponse();
	$chat = new Chat;
	$chat->SendWhisper($author, $msg);
	return $r;
}



function Tick($author)
{
	# Working timer start
	$start_time = (float) array_sum(explode(' ', microtime()));

	$r = new xajaxResponse();
	$chat = new Chat;
	$chat->UpdateTime($author);
	
	# Get array of online users
	$users = $chat->GetOnlineUsers();
	$listUsers = '';
	$listUsersCount = 0;
	if (count($users))
	{
		ksort($users);
		
		foreach($users as $u)
		{
			$avatar = file_exists('avatars/'.md5($u['name']).'.jpg') ? 'avatars/'.md5($u['name']).'.jpg?'.filesize('avatars/'.md5($u['name']).'.jpg') : 'img/avatarNull.gif';
			if ($u['name'] == $author)
			{
				$listUsers .= '<span class="nameSelf" title="<img src='.$avatar.' border=0 width=50 height=50>">'.$u['name'].'</span><br>';
				$r->addAssign('avatar', 'src', $avatar);
			}
			else
			{
				$listUsers .= '<span class="nameOther" title="<img src='.$avatar.' border=0 width=50 height=50>" onClick="whisperTo(\''.$u['name'].'\')">'.$u['name'].'</span><br>';
			}
			$listUsersCount ++;
		}
	}
	$r->addAssign('listUsers', 'innerHTML', $listUsers);
	$r->addAssign('listUsersCount', 'innerHTML', $listUsersCount);
	
	# Get array of new cached msgs
	$msgs = $chat->GetNewMsgs($author);

	if (count($msgs))
	{
		foreach($msgs as $m)
		{
			if ($m['type'] == 0) # Public
			{
				$msg = '<p>['.date("H:i", $m['time']).'] <strong class="other" onClick="whisperTo(\''.$m['author'].'\')">'.$m['author'].'</strong> : '.$m['msg'].'</p>';
			}
			elseif ($m['type'] == 1) # Whisper
			{
				$timeLine = '<p>['.date("H:i", $m['time']).'] ';
				$authorLine = '<strong class="whisperTo" onClick="whisperTo(\''.$m['author'].'\')">'.$m['author'].'</strong><strong class="whisper"> whispers</strong> : ';
				$msg = $timeLine.$authorLine.$m['msg'].'</p>';
			}
			elseif ($m['type'] == 3) # System
			{
				$m['msg'] = html_entity_decode($m['msg']);
				$msg = '<p>['.date("H:i", $m['time']).'] <strong class="msg_3">*** '.$m['msg'].'</strong></p>';
			}
			
			$r->addAppend('chatLog', 'innerHTML', $msg);
		}
		$r->addScriptCall('scrollLog');
		$r->addScriptCall('playSound', 'snd/msg');
	}

	# Page load timer stop
	#$exec_time = (float) array_sum(explode(' ', microtime())) - $start_time;
	$exec_time = (int) number_format((float) array_sum(explode(' ', microtime())) - $start_time, 3, '', '');

	$r->addScriptCall('pingStop', 'Server work time: '.$exec_time.'ms');
	$r->addScriptCall('titles_init');
	
	return $r;
}


function RemoveAvatar($author)
{
	$r = new xajaxResponse();
	if (@unlink('avatars/'.$author.'.jpg')) {
		$r->addAssign('avatarloading', 'style.display', 'none');
		$r->addAssign('avatar', 'src', 'img/avatarNull.gif');
	}
	else {
		$r->addAssign('avatarloading', 'style.display', 'none');
		$r->addScriptCall('alert', 'Avatar can\'t be removed!');
	}
	return $r;
}

$server->processRequests();

?>