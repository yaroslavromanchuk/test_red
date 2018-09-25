<?php

class user
{
	
	public static 
		$id,
		$pass,
		$nickname,
		$password,
		$access,
		$timeout,
		$status,
		$ip,
		$agent,
		$conn_time,
		$reg_time,
		$avatar,
		$email,
		$reputation,
		$title,
		$silence
	;
	
	public static function load($pass)
	{
		if ($pass) {
			if ($user = db::selectRow(TB_USERS, '*', '`pass` = "'.$pass.'"')) {
				foreach ($user as $var => $val) {
					if (property_exists('user', $var)) {
						self::$$var = $val;
					}
				}
				return true;
			} else {
				self::drop();
				return false;
			}
		} else {
			return false;
		}
	}
	
	public static function drop()
	{
		$vars = get_class_vars('user');
		
		foreach ($vars as $var => $val) {
			self::$$var = null;
		}
	}
	
	public static function access($access)
	{
		if (self::$access < $access) {
			chat::retAction('PrintErrorMessage', 'message: 6017', true);
		}
	}
	
	public static function banned($nickname, $ip)
	{
		if (db::select(TB_BANS, '*', '`nickname` = "'.$nickname.'" OR `ip` = "'.$ip.'"')) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function connect()
	{
		$pass     = chat::$pass;
		$nickname = util::prepareNickname($_COOKIE['nickname']);
		//$password = util::preparePassword($_POST['password']);
		$ip       = $_SERVER['REMOTE_ADDR'];
		$agent    = $_SERVER['HTTP_USER_AGENT'];
		$update   = false;
		
		// +debug
		//fb('nickname length (normal): '.strlen($nickname));
		//fb('nickname length (decode): '.util::strlen($nickname));
		// -debug
		
		if (empty($nickname) || ! util::checkNickname($nickname)) {
			chat::retAction('Connect', 'success: false, error: 6011', true);
		}
		
		if (util::strlen($nickname) < 2 || util::strlen($nickname) > 16) {
			chat::retAction('Connect', 'success: false, error: 6012', true);
		}
		
		if (self::banned($nickname, $ip)) {
			chat::retAction('Connect', 'success: false, error: 6013', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '*', '`nickname` = "'.$nickname.'"'))) {
			if ($user['password']) {
				if ($user['password'] == $password) {
					$update = true;
				} else {
					chat::retAction('Connect', 'success: false, error: 6014', true);
				}
			} else {
				if ($user['pass'] == $pass) {
					$update = true;
				} else {
					chat::retAction('Connect', 'success: false, error: 6015', true);
				}
			}
		} else {
			if (cfg::$guests_allowed == false) {
				chat::retAction('Connect', 'success: false, error: 6005', true);
			}
		}
		
		if ($users_sel = db::select(TB_USERS, '*', '`nickname` != "'.$nickname.'" AND `timeout` > 0 AND `status` != 3', '`access` DESC, `nickname`')) {
			foreach ($users_sel as $u) {
				$users[] = '{id: '.$u['id'].', nickname: "'.$u['nickname'].'", access: '.($u['access'] ? $u['access'] : '0').', status: '.$u['status'].'}';
			}
			$users = implode(', ', $users);
		} else {
			$users = '';
		}
		
		$pass = util::generatePass();
		
		$data = array(
			'pass'     => $pass,
			'nickname' => $nickname,
			'timeout'  => chat::$time + chat::$act_timeout,
			'status'   => 0,
			'ip'       => $ip,
			'agent'    => $agent,
			'conn_time'=> chat::$time
		);
		
		if ($update) {
			$data['nickname'] = $nickname = $user['nickname'];
			
			if (($r = db::update(TB_USERS, $data, '`id` = '.$user['id'])) == false) {
				chat::retAction('Connect', 'success: false, error: 6016', true);
			}
		} else {
			if (($r = db::insert(TB_USERS, $data)) == false) {
				chat::retAction('Connect', 'success: false, error: 6016', true);
			}
			
			$user['id'] = $r;
			$user['access'] = 0;
		}
		
		chat::addAction('PrintSystemMessage', 'message: 5011, inserts: ["'.$nickname.'"]', 'notuser', $user['id']);
		chat::addAction('AddUser', 'id: '.$user['id'].', nickname: "'.$nickname.'", access: '.$user['access'].', status: 0', 'notuser', $user['id']);
		chat::retAction('Connect', 'success: true, id: '.$user['id'].', pass: "'.$pass.'", nickname: "'.$nickname.'", access: '.$user['access'].', status: 0, users: ['.$users.'], motd: "'.util::getVar('motd').'"');
	}
	
	public static function disconnect()
	{
		if (self::$access) {
			db::update(TB_USERS, array('timeout' => 0), '`id` = '.self::$id);
		} else {
			db::delete(TB_USERS, '`id` = '.self::$id);
		}
		
		chat::addAction('PrintSystemMessage', 'message: 5012, inserts: ["'.self::$nickname.'"]');
		chat::addAction('RemoveUser', 'id: '.self::$id);
		
		chat::retAction('Disconnect');
	}
	
	public static function setStatus()
	{
		$status = $_POST['status'];
		
		if (is_numeric($status) && in_array($status, array(0, 1, 2, 3)) && self::$status != $status) {
			if (self::$status != 3 && $status != 3) {
				chat::addAction('UpdateUser', 'id: '.self::$id.', status: '.$status, 'notself');
			}
			
			if (self::$status != 3 && $status == 3) {
				chat::addAction('RemoveUser', 'id: '.self::$id, 'notself');
			}
			
			if (self::$status == 3 && $status != 3) {
				chat::addAction('AddUser', 'id: '.self::$id.', nickname: "'.self::$nickname.'", access: '.self::$access.', status: '.$status, 'notself');
			}
			
			db::update(TB_USERS, array('status' => $status), '`id` = '.self::$id);
		}
	}
	
}

?>