<?php

class chat
{
	
	public static 
		$act_timeout = 30,
		$php_timeout = 30,
		$pass,
		$time,
		$actions
	;
	
	public static function callAction()
	{
		self::$pass = util::preparePass($_POST['pass']);
		self::$time = time();
		
		$action = 'action_'.$_POST['action'];
		
		if (is_callable('self::'.$action)) {
			self::$action();
			self::backAction();
		}
	}
	
	public static function backAction()
	{
		if (sizeof(self::$actions)) {
			exit('{actions: ['.implode(', ', self::$actions).']}');
		}
		exit;
	}
	
	public static function addAction($action, $params = '', $to = 'all', $userid = 0)
	{
		switch ($to) {
			case 'all' :
				$where = ''; break;
			case 'user' :
				$where = ' AND `id` = '.$userid; break;
			case 'notuser' :
				$where = ' AND `id` != '.$userid; break;
			case 'self' :
				$where = ' AND `id` = '.user::$id; break;
			case 'notself' :
				$where = ' AND `id` != '.user::$id; break;
		}
		
		if ($users = db::select(TB_USERS, '`id`', '`timeout` > '.time().$where)) {
			foreach ($users as $u) {
				db::insert(TB_ACTIONS, array(
					'for'     => $u['id'],
					'action'  => $action,
					'params'  => $params,
					'timeout' => time() + chat::$act_timeout
				));
			}
		}
	}
	
	public static function retAction($action, $params = '', $exit = false)
	{
		self::$actions[] = '{action: "'.$action.'", params: {'.$params.'}}';
		
		if ($exit) {
			self::backAction();
		}
	}
	
	public static function clearGarbage()
	{
		// clear actions
		db::delete(TB_ACTIONS, '`timeout` <= '.time());
		
		// clear users
		if ($users = db::select(TB_USERS, '*', '(`timeout` > 0 AND `timeout` <= '.time().') OR (`timeout` = -1)')) {
			foreach ($users as $u) {
				if ($u['timeout'] > 0) {
					self::addAction('PrintSystemMessage', 'message: 5013, inserts: ["'.$u['nickname'].'"]');
				} else {
					self::addAction('PrintSystemMessage', 'message: 5014, inserts: ["'.$u['nickname'].'"]');
				}
				if ($u['access']) {
					db::update(TB_USERS, array('timeout' => 0), '`id` = '.$u['id']);
				} else {
					db::delete(TB_USERS, '`id` = '.$u['id']);
				}
				self::addAction('RemoveUser', 'id: '.$u['id']);
			}
		}
	}
	
	// ACTIONS ================================================================
	
	/*
	 * ACTION:  Connect
	 * -input:  pass, nickname, password
	 * -output: json {success: true, pass, nickname, users: [array of online users]} or {success: false, error}
	 */
	public static function action_Connect()
	{
		sleep(1);
		self::clearGarbage();
		user::connect();
	}
	
	/*
	 * ACTION:  Disconnect
	 * -input:  pass
	 * -output: json {success: true}
	 */
	public static function action_Disconnect()
	{
		if (user::load(self::$pass) == false || user::$timeout < 0) {
			self::retAction('Disconnect', 'message: 6018', true);
		}
		
		user::disconnect();
	}
	
	/*
	 * ACTION:  SendMessage
	 * -input:  pass, message
	 */
	public static function action_SendMessage()
	{
		if (user::load(self::$pass) == false || user::$timeout < 0) {
			self::retAction('Disconnect', 'message: 6018', true);
		}
		
		if (user::$silence > self::$time) {
			// calculate remain silence time
			$remain = floor((user::$silence - self::$time) / 60) + 1;
			self::retAction('PrintErrorMessage', 'message: 6019, inserts: ["'.$remain.'"]', true);
		}
		
		// +debug
		//fb($_POST['message']);
		// -debug
		
		$message = util::prepareMessage($_POST['message']);
		
		// +debug
		//fb($message);
		// -debug
		
		if (strlen($message)) {
			if (preg_match('/^\/[^\/]+/', $message)) {
				list($command, $params) = explode(' ', $message, 2);
				$command = 'command_'.trim($command, '/');
				if (is_callable('self::'.$command)) {
					self::$command($params);
				} else {
					self::retAction('PrintErrorMessage', 'message: 6020');
				}
			} else {
				$time = date('H:i:s', self::$time);
				$from = user::$nickname;
				db::insert(TB_MESSAGES, array('time' => self::$time, 'from' => $from, 'message' => $message));
				self::addAction('PrintMessage', 'time: "'.$time.'", from: "'.$from.'", message: "'.$message.'"', 'notself');
				self::retAction('PrintMessage', 'time: "'.$time.'", from: "'.$from.'", message: "'.$message.'"');
			}
		}
	}
	
	/*
	 * ACTION:  CheckActions
	 * -input:  pass
	 * -output: json {actions: [array of actions]}
	 */
	/*public static function action_CheckActions()
	{
		while (time() < (self::$time + chat::$php_timeout - 1)) {
			if (user::load(self::$pass) == false || user::$timeout < 0) {
				self::retAction('Disconnect', 'message: 6018', true);
			}
			
			db::update(TB_USERS, array('timeout' => time() + chat::$act_timeout), '`id` = '.user::$id);
			
			self::clearGarbage();
			
			if ($actions = db::select(TB_ACTIONS, '*', '`for` = '.user::$id, '`id`')) {
				foreach ($actions as $a) {
					$ids[] = $a['id'];
					self::retAction($a['action'], $a['params']);
				}
				db::delete(TB_ACTIONS, '`id` IN ('.implode(', ', $ids).')');
				self::backAction();
			}
			
			sleep(1);
		}
	}*/
	
	/*
	 * ACTION:  CheckActions
	 * -input:  pass
	 * -output: json {actions: [array of actions]}
	 */
	public static function action_CheckActions()
	{
		$t = new Timer();
		$t->start();
		
		if (user::load(self::$pass) == false || user::$timeout < 0) {
			self::retAction('Disconnect', 'message: 6018', true);
		}
		
		db::update(TB_USERS, array('timeout' => time() + chat::$act_timeout), 'id = '.user::$id);
		
		self::clearGarbage();
		
		if ($actions = db::select(TB_ACTIONS, '*', '`for` = '.user::$id, 'id')) {
			foreach ($actions as $a) {
				$ids[] = $a['id'];
				self::retAction($a['action'], $a['params']);
			}
			db::delete(TB_ACTIONS, 'id IN ('.implode(', ', $ids).')');
			
			fb(self::$actions);
		}
		
		fb('swt: ' . $t->fetch());
	}
	
	/*
	 * ACTION:  SetStatus
	 * -input:  pass, status
	 */
	public static function action_SetStatus()
	{
		if (user::load(self::$pass) == false || user::$timeout < 0) {
			self::retAction('Disconnect', 'message: 6018', true);
		}
		
		user::setStatus();
	}
	
	// COMMANDS ===============================================================
	
	/*
	 * COMMAND: help
	 * -params: -
	 * -access: 0
	 */
	public static function command_help($params)
	{
		user::access(0);
		
		self::retAction('ShowHelp', 'access: '.(user::$access ? user::$access : 0));
	}
	
	/*
	 * COMMAND: motd
	 * -params: message
	 * -access: 0 / 3
	 */
	public static function command_motd($params)
	{
		$message = $params;
		
		if (empty($message)) {
			if ($motd = util::getVar('motd')) {
				self::retAction('PrintSystemMessage', 'message: 5001, inserts: ["'.$motd.'"]');
			} else {
				self::retAction('PrintSystemMessage', 'message: 5002');
			}
		} else {
			user::access(3);
			if ($message == 'clear') {
				util::setVar('motd', '');
				self::retAction('PrintSystemMessage', 'message: 5003');
			} else {
				util::setVar('motd', $message);
				self::retAction('PrintSystemMessage', 'message: 5004');
			}
		}
	}
	
	/*
	 * COMMAND: nick
	 * -params: nickname
	 * -access: 0
	 */
	public static function command_nick($params)
	{
		user::access(0);
		
		$nickname = util::prepareNickname($params);
		
		if (empty($nickname) || ! util::checkNickname($nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6011', true);
		}
		
		if (util::strlen($nickname) < 2 || util::strlen($nickname) > 16) {
			chat::retAction('PrintErrorMessage', 'message: 6012', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '`id`', '`nickname` = "'.$nickname.'"')) && $user['id'] != user::$id) {
			self::retAction('PrintErrorMessage', 'message: 6015', true);
		}
		
		db::update(TB_USERS, array('nickname' => $nickname), '`id` = '.user::$id);
		self::addAction('PrintSystemMessage', 'message: 5015, inserts: ["'.user::$nickname.'", "'.$nickname.'"]', 'notself');
		self::addAction('UpdateUser', 'id: '.user::$id.', nickname: "'.$nickname.'"', 'notself');
		self::retAction('PrintSystemMessage', 'message: 5016, inserts: ["'.$nickname.'"]');
		self::retAction('ChangeNickname', 'nickname: "'.$nickname.'"');
	}
	
	/*
	 * COMMAND: whisper
	 * -params: to, message
	 * -access: 0
	 */
	public static function command_whisper($params)
	{
		user::access(0);
		
		list($to, $message) = explode(' ', $params, 2);
		
		if (strtolower($to) == strtolower(user::$nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6021', true);
		}
		
		if (($to = db::selectRow(TB_USERS, '*', '`nickname` = "'.$to.'" AND `timeout` > 0')) == false) {
			self::retAction('PrintErrorMessage', 'message: 6022', true);
		}
		
		if (! strlen($message)) {
			self::retAction('PrintErrorMessage', 'message: 6023', true);
		}
		
		$time = date('H:i:s', self::$time);
		$from = user::$nickname;
		$tonm = $to['nickname'];
		
		db::insert(TB_MESSAGES, array('time' => self::$time, 'from' => $from, 'to' => $tonm, 'message' => $message));
		self::addAction('PrintWhisper', 'time: "'.$time.'", from: "'.$from.'", to: "'.$tonm.'", message: "'.$message.'"', 'user', $to['id']);
		self::retAction('PrintWhisper', 'time: "'.$time.'", from: "'.$from.'", to: "'.$tonm.'", message: "'.$message.'"');
	}
	
	/*
	 * COMMAND: reg
	 * -params: password
	 * -access: 0
	 */
	public static function command_reg($params)
	{
		user::access(0);
		
		$password = $params;
		
		if (user::$access) {
			self::retAction('PrintErrorMessage', 'message: 6024', true);
		}
		
		if (empty($password)) {
			self::retAction('PrintErrorMessage', 'message: 6025', true);
		}
		
		if (strpos($password, ' ') !== false) {
			self::retAction('PrintErrorMessage', 'message: 6026', true);
		}
		
		if (($users = db::select(TB_USERS, '`id`', '`access` = 3')) == false) {
			$access = 3;
			self::retAction('PrintSystemMessage', 'message: 5017');
		} else {
			$access = 1;
			self::retAction('PrintSystemMessage', 'message: 5018');
		}
		
		db::update(TB_USERS, array('password' => util::preparePassword($password), 'access' => $access, 'reg_time' => self::$time), '`id` = '.user::$id);
		self::addAction('UpdateUser', 'id: '.user::$id.', access: '.$access, 'notself');
		self::retAction('ChangeAccess', 'access: '.$access);
	}
	
	/*
	 * COMMAND: password
	 * -params: old_password, new_password
	 * -access: 1
	 */
	public static function command_password($params)
	{
		user::access(1);
		
		list($old_password, $new_password) = explode(' ', $params, 2);
		
		if (empty($old_password) || empty($new_password)) {
			self::retAction('PrintErrorMessage', 'message: 6027', true);
		}
		
		if (util::preparePassword($old_password) != user::$password) {
			self::retAction('PrintErrorMessage', 'message: 6028', true);
		}
		
		if (strpos($new_password, ' ') !== false) {
			self::retAction('PrintErrorMessage', 'message: 6026', true);
		}
		
		db::update(TB_USERS, array('password' => util::preparePassword($new_password)), '`id` = '.user::$id);
		self::retAction('PrintSystemMessage', 'message: 5019');
	}
	
	/*
	 * COMMAND: unreg
	 * -params: password
	 * -access: 1
	 */
	public static function command_unreg($params)
	{
		user::access(1);
		
		$password = $params;
		
		if (user::$access > 1) {
			self::retAction('PrintErrorMessage', 'message: 6029', true);
		}
		
		if (empty($password)) {
			self::retAction('PrintErrorMessage', 'message: 6030', true);
		}
		
		if (user::$password != util::preparePassword($params)) {
			self::retAction('PrintErrorMessage', 'message: 6014', true);
		}
		
		db::update(TB_USERS, array('password' => '', 'access' => 0, 'reg_time' => 0), '`id` = '.user::$id);
		self::addAction('UpdateUser', 'id: '.user::$id.', access: 0', 'notself');
		self::retAction('PrintSystemMessage', 'message: 5020');
		self::retAction('ChangeAccess', 'access: 0');
	}
	
	/*
	 * COMMAND: echo
	 * -params: message
	 * -access: 2
	 */
	public static function command_echo($params)
	{
		user::access(2);
		
		$message = $params;
		
		if (empty($message)) {
			self::retAction('PrintErrorMessage', 'message: 6023', true);
		}
		
		self::addAction('PrintEchoMessage', 'message: "'.$message.'"', 'notself');
		self::retAction('PrintEchoMessage', 'message: "'.$message.'"');
	}
	
	/*
	 * COMMAND: messages
	 * -params: number of messages / clear
	 * -access: 2 / 3
	 */
	public static function command_messages($params)
	{
		if (strtolower($params) == 'clear') {
			user::access(3);
			db::delete(TB_MESSAGES);
			self::retAction('PrintSystemMessage', 'message: 5021', true);
		}
		
		user::access(2);
		
		$count = db::selectCount(TB_MESSAGES);
		$limit = 500;
		
		if ($params = intval($params)) {
			$limit = ($params > $count) ? $count : (($params > 500) ? 500 : $params);
		}
		
		if ($limit > $count) {
			$limit = $count;
		}
		
		$start = $count - $limit;
		
		if ($messages = db::select(TB_MESSAGES, '*', null, '`id` LIMIT '.$start.', '.$limit)) {
			foreach ($messages as $m) {
				$msgs[] = '
					{
						id: '.$m['id'].',
						time: "'.date('Y-m-d H:i:s', $m['time']).'",
						from: "'.$m['from'].'",
						to: "'.$m['to'].'",
						message: "'.$m['message'].'"
					}
				';
			}
		}
		
		self::retAction('ShowMessages', 'messages: '.(isset($msgs) ? '['.implode(', ', $msgs).']' : '[]'));
	}
	
	/*
	 * COMMAND: users
	 * -params: -
	 * -access: 2
	 */
	public static function command_users($params)
	{
		user::access(2);
		
		if ($users = db::select(TB_USERS, '*', null, '`id`, `access` DESC')) {
			foreach ($users as $u) {
				$usrs[] = '
					{
						id: "'.$u['id'].'",
						nickname: "'.$u['nickname'].'",
						access: "'.$u['access'].'",
						ip: "'.$u['ip'].'",
						agent: "'.$u['agent'].'",
						regtime: "'.(($u['reg_time']) ? date('Y-m-d H:i:s', $u['reg_time']) : '').'",
						conntime: "'.date('Y-m-d H:i:s', $u['conn_time']).'"
					}
				';
			}
		}
		
		self::retAction('ShowUsers', 'users: '.(isset($usrs) ? '['.implode(', ', $usrs).']' : '[]'));
	}
	
	/*
	 * COMMAND: bans
	 * -params: -
	 * -access: 2
	 */
	public static function command_bans($params)
	{
		user::access(2);
		
		if ($bans = db::select(TB_BANS, '*', null, '`nickname`, `ip`')) {
			foreach ($bans as $b) {
				if ($b['nickname']) {
					$nns[] = $b['nickname'];
				}
				if ($b['ip']) {
					$ips[] = $b['ip'];
				}
			}
		}
		
		$nns = isset($nns) ? '["'.implode('", "', $nns).'"]' : '[]';
		$ips = isset($ips) ? '["'.implode('", "', $ips).'"]' : '[]';
		self::retAction('ShowBans', 'bans: {nns: '.$nns.', ips: '.$ips.'}');
	}
	
	/*
	 * COMMAND: kick
	 * -params: nickname
	 * -access: 2
	 */
	public static function command_kick($params)
	{
		user::access(2);
		
		$nickname = $params;
		
		if (empty($nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6031', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '*', '`nickname` = "'.$nickname.'" AND `timeout` > 0')) == false) {
			self::retAction('PrintErrorMessage', 'message: 6022', true);
		}
		
		if ($user['access'] >= user::$access) {
			self::retAction('PrintErrorMessage', 'message: 6034', true);
		}
		
		db::update(TB_USERS, array('timeout' => -1), '`id` = '.$user['id']);
		//self::retAction('PrintSystemMessage', 'message: 5022, inserts: ["'.$user['nickname'].'"]');
	}
	
	/*
	 * COMMAND: ban
	 * -params: nickname
	 * -access: 2
	 */
	public static function command_ban($params)
	{
		user::access(2);
		
		$nickname = $params;
		
		// если с указанным именем есть оператор или администратор,
		// то это имя нельзя банить.
		if (db::select(TB_USERS, '*', '`nickname` = "'.$nickname.'" AND `access` >= 2')) {
			self::retAction('PrintErrorMessage', 'message: 6039', true);
		}
		
		db::insert(TB_BANS, array('nickname' => $nickname));
		db::update(TB_USERS, array('timeout' => -1), '`nickname` = "'.$nickname.'"');
		self::retAction('PrintSystemMessage', 'message: 5023, inserts: ["'.$nickname.'"]');
	}
	
	/*
	 * COMMAND: unban
	 * -params: nickname
	 * -access: 2
	 */
	public static function command_unban($params)
	{
		user::access(2);
		
		$nickname = $params;
		
		db::delete(TB_BANS, '`nickname` = "'.$nickname.'"');
		self::retAction('PrintSystemMessage', 'message: 5024, inserts: ["'.$nickname.'"]');
	}
	
	/*
	 * COMMAND: banip
	 * -params: ip
	 * -access: 2
	 */
	public static function command_banip($params)
	{
		user::access(2);
		
		$ip = $params;
		
		// если с указанным ip-адресом есть оператор или администратор,
		// то этот ip-адрес нельзя банить.
		if (db::select(TB_USERS, '*', '`ip` = "'.$ip.'" AND `access` >= 2')) {
			self::retAction('PrintErrorMessage', 'message: 6040', true);
		}
		
		db::insert(TB_BANS, array('ip' => $ip));
		db::update(TB_USERS, array('timeout' => -1), '`ip` = "'.$ip.'"');
		self::retAction('PrintSystemMessage', 'message: 5025, inserts: ["'.$ip.'"]');
	}
	
	/*
	 * COMMAND: unbanip
	 * -params: ip
	 * -access: 2
	 */
	public static function command_unbanip($params)
	{
		user::access(2);
		
		$ip = $params;
		
		db::delete(TB_BANS, '`ip` = "'.$ip.'"');
		self::retAction('PrintSystemMessage', 'message: 5026, inserts: ["'.$ip.'"]');
	}
	
	/*
	 * COMMAND: silence
	 * -params: nickname, time
	 * -access: 2
	 */
	public static function command_silence($params)
	{
		user::access(2);
		
		list($nickname, $time) = explode(' ', $params, 2);
		
		if (is_numeric($time) == false || $time < 1) {
			self::retAction('PrintErrorMessage', 'message: 6032', true);
		}
		
		if (strtolower(user::$nickname) == strtolower($nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6033', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '*', '`nickname` = "'.$nickname.'" AND `timeout` > 0')) == false) {
			self::retAction('PrintErrorMessage', 'message: 6022', true);
		}
		
		if ($user['access'] >= user::$access) {
			self::retAction('PrintErrorMessage', 'message: 6034', true);
		}
		
		$time = floor($time);
		$silence = self::$time + $time * 60;
		
		db::update(TB_USERS, array('silence' => $silence), '`nickname` = "'.$nickname.'"');
		self::addAction('PrintErrorMessage', 'message: 6035, inserts: ["'.$time.'"]', 'user', $user['id']);
		self::retAction('PrintSystemMessage', 'message: 5027, inserts: ["'.$user['nickname'].'", "'.$time.'"]');
	}
	
	/*
	 * COMMAND: op
	 * -params: nickname
	 * -access: 3
	 */
	public static function command_op($params)
	{
		user::access(3);
		
		$nickname = $params;
		
		if (empty($nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6003', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '*', '`nickname` = "'.$nickname.'"')) == false) {
			self::retAction('PrintErrorMessage', 'message: 6022', true);
		}
		
		if ($user['access'] != 1) {
			self::retAction('PrintErrorMessage', 'message: 6037', true);
		}
		
		db::update(TB_USERS, array('access' => 2), '`id` = '.$user['id']);
		
		self::addAction('PrintSystemMessage', 'message: 5028', 'user', $user['id']);
		self::addAction('ChangeAccess', 'access: 2', 'user', $user['id']);
		self::addAction('UpdateUser', 'id: '.$user['id'].', access: 2', 'notself');
		
		self::retAction('PrintSystemMessage', 'message: 5029, inserts: ["'.$user['nickname'].'"]');
		self::retAction('UpdateUser', 'id: '.$user['id'].', access: 2');
	}
	
	/*
	 * COMMAND: unop
	 * -params: nickname
	 * -access: 3
	 */
	public static function command_unop($params)
	{
		user::access(3);
		
		$nickname = $params;
		
		if (empty($nickname)) {
			self::retAction('PrintErrorMessage', 'message: 6003', true);
		}
		
		if (($user = db::selectRow(TB_USERS, '*', '`nickname` = "'.$nickname.'"')) == false) {
			self::retAction('PrintErrorMessage', 'message: 6022', true);
		}
		
		if ($user['access'] != 2) {
			self::retAction('PrintErrorMessage', 'message: 6038', true);
		}
		
		db::update(TB_USERS, array('access' => 1), '`id` = '.$user['id']);
		
		self::addAction('PrintSystemMessage', 'message: 5030', 'user', $user['id']);
		self::addAction('ChangeAccess', 'access: 1', 'user', $user['id']);
		self::addAction('UpdateUser', 'id: '.$user['id'].', access: 1', 'notself');
		
		self::retAction('PrintSystemMessage', 'message: 5031, inserts: ["'.$user['nickname'].'"]');
		self::retAction('UpdateUser', 'id: '.$user['id'].', access: 1');
	}
	
	/*
	 * COMMAND: userdelete (BETA)
	 * -params: nickname
	 * -access: 3
	 */
	public static function command_userdelete($params)
	{
		user::access(3);
		$nickname = $params;
		db::delete(TB_USERS, '`nickname` = "'.$nickname.'"');
		self::retAction('PrintSystemMessage', 'message: "BETA CMD: User \''.$nickname.'\' deleted."');
	}
	
}

?>