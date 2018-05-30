<?

/*==================================================*\
	Chat
\*==================================================*/

class Chat
{
	var $users = array();
	var $secureString1 = 'qawsed2';
	var $secureString2 = 'azsxdc3';
	
	function Chat()
	{
		$this->GetUsers();
	}
	
	function AddUser($name, $password)
	{
		$_name = strtolower($name);
		if (array_key_exists($_name, $this->users))
		{
			return false;
		}
		else
		{
			$time = time();
			$password = $this->EncodePassword($password);
			$ip = getenv('REMOTE_ADDR');
			$this->users[$_name] = array(
				'name' => $name,
				'password' => $password,
				'updateTime' => 0,
				'silenceTime' => 0,
				'ip' => $ip
			);
			$row = $name.'>'.$password.'>0>0>'.$ip;
			$row = str_replace("\n", '', $row);
			$row .= "\n";
			$d = fopen('data/Users.txt', 'a');
			fwrite($d, $row);
			fclose($d);
			return true;
		}
	}
	
	function GetName($name)
	{
		$_name = strtolower($name);
		return array_key_exists($_name, $this->users) ? $this->users[$_name]['name'] : false;
	}
	
	function EncodePassword($password)
	{
		return md5($this->secureString1.$password.$this->secureString2);
	}
	
	function MatchPassword($name, $password)
	{
		$_name = strtolower($name);
		$password = $this->EncodePassword($password);
		if (array_key_exists($_name, $this->users))
		{
			return ($this->users[$_name]['password'] == $password) ? true : false;
		}
		else
		{
			return false;
		}
	}
	
	function UpdateTime($name)
	{
		$_name = strtolower($name);
		if (array_key_exists($_name, $this->users))
		{
			$this->users[$_name]['updateTime'] = time();
			$this->users[$_name]['ip'] = getenv('REMOTE_ADDR');
			$this->SaveUsers();
		}
	}
	
	function GetUsers()
	{
		$d = file('data/Users.txt');
		if (is_array($d))
		{
			foreach($d as $num => $row)
			{
				$row = str_replace("\r\n", '', $row);
				$u = explode('>', $row);
				$_name = strtolower($u[0]);
				$this->users[$_name] = array(
					'name' => $u[0],
					'password' => $u[1],
					'updateTime' => $u[2],
					'silenceTime' => $u[3],
					'ip' => $u[4]
				);
			}
		}
	}
	
	function SaveUsers()
	{
		$rows = '';
		foreach($this->users as $u)
		{
			$row = implode('>', $u);
			$row = str_replace("\r", '', $row);
			$row = str_replace("\n", '', $row);
			$row .= "\r\n";
			$rows .= $row;
		}
		$d = fopen('data/Users.txt', 'w');
		flock($d, LOCK_EX);
		fwrite($d, $rows);
		fclose($d);
	}
	
	function WriteToCache($str, $mode = 'a')
	{
		$f = fopen('data/Cache.txt', $mode);
		flock($f, LOCK_EX);
		fwrite($f, $str);
		fclose($f);
	}
	
	function WriteToLog($str)
	{
		$f = fopen('data/Log.txt', 'a');
		flock($f, LOCK_EX);
		fwrite($f, $str);
		fclose($f);
	}
	
	function ReadLog($limit=604800)
	{
		$time = time();
		$log = file('data/Log.txt');
		$msgs = array();
		if (count($log))
		{
			foreach($log as $num => $row)
			{
				$m = explode('>', $row);
				if ($m[0] < ($time - $limit)) {
					unset($log[$num]);
				}
				else {
					unset($log[$num]);
					// преобразуем чистые ссылки в сообщении в ссылки в тегах
					$m[3] = $this->UrlReplace($m[3]);
					$msgs[] = array(
						'time' => $m[0],
						'author' => $m[1],
						'msg' => $m[2],
						'type' => $m[3]
					);
				}
			}
		}
		return $msgs;
	}
	
	function SendToAll($author, $msg)
	{
		$_author = strtolower($author);
		$msg = htmlspecialchars($msg);
		$time = time();
		$tocache = '';
		foreach($this->users as $_name => $u)
		{
			if ($_name != $_author && $u['updateTime'] > ($time - 15))
			{
				$tocache .= $_name.'>'.$time.'>'.$author.'>'.$msg.'>0'."\r\n";
			}
		}
		$this->WriteToCache($tocache);
		$this->WriteToLog($time.'>'.$author.'>'.$msg.'>0'."\r\n");
	}
	
	function SendWhisper($author, $msg)
	{
		$_author = strtolower($author);
		$msg = htmlspecialchars($msg);
		$time = time();
		$name = preg_replace("/^\/whisper\[([a-z0-9_-]{2,32})\]\s(.+)$/i", "\\1", $msg);
		$msg = preg_replace("/^\/whisper\[([a-z0-9_-]{2,32})\]\s(.+)$/i", "\\2", $msg);
		$_name = strtolower($name);
		$this->WriteToCache($_name.'>'.$time.'>'.$author.'>'.$msg.'>1'."\r\n");
		$this->WriteToLog($time.'>'.$author.'[to]'.$name.'>'.$msg.'>1'."\r\n");
	}
	
	function SendSystemMsg($msg)
	{
		$msg = htmlspecialchars($msg);
		$time = time();
		$tocache = '';
		foreach($this->users as $_name => $u)
		{
			if ($u['updateTime'] > ($time - 15))
			{
				$tocache .= $_name.'>'.$time.'>>'.$msg.'>3'."\r\n";
			}
		}
		$this->WriteToCache($tocache);
		$this->WriteToLog($time.'>>'.$msg.'>3'."\r\n");
	}

	function GetOnlineUsers()
	{
		$time = time();
		$users = array();
		foreach($this->users as $u)
		{
			if ($u['updateTime'] > ($time - 15))
			{
				$_name = strtolower($u['name']);
				$users[$_name] = $u;
			}
		}
		return $users;
	}
	
	function UserOnline($name)
	{
		$time = time();
		$_name = strtolower($name);
		if (array_key_exists($_name, $this->users))
		{
			if ($this->users[$_name]['updateTime'] > ($time - 15))
			{
				return ($this->users[$_name]['ip'] == getenv('REMOTE_ADDR')) ? false : true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function GetNewMsgs($name)
	{
		$time = time();
		$_name = strtolower($name);
		$cache = file('data/Cache.txt');
		$msgs = array();
		if (count($cache))
		{
			foreach($cache as $num => $row)
			{
				$m = explode('>', $row);
				if ($m[1] < ($time - 30))
				{
					unset($cache[$num]);
				}
				elseif ($m[0] == $_name)
				{
					unset($cache[$num]);
					// преобразуем чистые ссылки в сообщении в ссылки в тегах
					$m[3] = $this->UrlReplace($m[3]);
					$msgs[] = array(
						'to' => $m[0],
						'time' => $m[1],
						'author' => $m[2],
						'msg' => $m[3],
						'type' => $m[4]
					);
				}
			}
		}
		$tocache = '';
		if (count($cache))
		{
			foreach($cache as $row)
			{
				$tocache .= $row;
			}
		}
		$this->WriteToCache($tocache, 'w');
		return $msgs;
	}
	
	function ClearUserCache($name)
	{
		$_name = strtolower($name);
		$cache = file('data/Cache.txt');
		if (count($cache))
		{
			foreach($cache as $num => $row)
			{
				$m = explode('>', $row);
				if ($m[0] == $_name)
				{
					unset($cache[$num]);
				}
			}
		}
		$tocache = '';
		if (count($cache))
		{
			foreach($cache as $row)
			{
				$tocache .= $row;
			}
		}
		$this->WriteToCache($tocache, 'w');
	}
	
	function UrlReplace($s)
	{
		return preg_replace("#(https?|ftp):\/\/\S+#i", '<a href="\\0" target="_blank">\\0</a>', $s);
	}
}

?>