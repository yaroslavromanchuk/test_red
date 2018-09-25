<?

require('cfg.php');
require('login.php');

$fcache = 'data/Cache.txt';
$flog = 'data/Log.txt';
$fusers = 'data/Users.txt';
$name = $_COOKIE['chatName'];
fclose(fopen($fcache, 'a+'));
fclose(fopen($flog, 'a+'));
fclose(fopen($fusers, 'a+'));
if (!@file_exists($fcache) || !@file_exists($flog) || !@file_exists($fusers))
{
	$error = 'Bad data files system!';
}

header('Expires: Fri, 25 Dec 1980 00:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/html');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="pragma" content="no-cache">
	<title><?php if (@$name) echo $name.' - '; ?>RED.Chat</title>
	<link rel="stylesheet" href="css/main.css" type="text/css">
	<link rel="stylesheet" href="css/tooltips.css" type="text/css">
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/avatar.js"></script>
	<script type="text/javascript" src="js/tooltips.js"></script>
	<script type="text/javascript" src="js/mel.sound.js"></script>
	<?php $server->printJavascript('xajax/xajax.inc.php'); ?>
</head>
<body onLoad="chatLoad('<?php if (!@$error && @$name) echo $name; ?>');">
	<!--tooltip-->
	<div id="tooltip"></div>
	
	<!--wrapper-->
	<div id="wrapper" align="center">
		<div id="wrap">
			<!--chat box-->
			<div id="chat">
				<div id="chatWrap">
					<div id="chatLog"><?php

					$chat = new Chat;
					$msgs = $chat->ReadLog();
					if (count($msgs))
					{
						foreach($msgs as $m)
						{
							if ($m['type'] == 0) # Public
							{
								$so = ($m['author'] == $name) ? 'self' : 'other';
								$msg = '<p>['.date("H:i", $m['time']).'] <strong class="'.$so.'" onClick="whisperTo(\''.$m['author'].'\')">'.$m['author'].'</strong> : '.$m['msg'].'</p>';
							}
							elseif ($m['type'] == 1) # Whisper
							{
								$author = explode("[to]", $m['author']);
								if($author[0] == $name or $author[1] == $name){
									$whispTo = ($author[0]==$name) ? $author[1] : $author[0];
									$timeLine = '<p>['.date("H:i", $m['time']).'] ';
									$authorLine = '<strong class="whisperTo" onClick="whisperTo(\''.$whispTo.'\')">'.$m['author'].'</strong><strong class="whisper"> whispers</strong> : ';
									$msg = $timeLine.$authorLine.$m['msg'].'</p>';
								}
							}
							elseif ($m['type'] == 3) # System
							{
								$msg = '';
							}

							echo $msg;
						}
					}
					?></div>
					<div id="chatInput">
						<form method="post" onSubmit="addMsg(); return false;">
							<input type="text" id="msg" value="" maxlength="250" onKeyPress="if (checkReWhisper(event)) return false;">
						</form>
					</div>
				</div>
			</div>
			<!--list box-->
			<div id="list">
				<div id="listWrap">
					<div id="listContent">
						<div class="header">Online: <span id="listUsersCount"></span></div>
						<div id="listUsers"></div>
					</div>
					<div id="listActions">
						<div class="left">
							<img src="img/listActions_trash.gif" onClick="clearLog();" title="Clear chat log">
							<img src="img/avatar.gif" onClick="avatarOpen();" title="Avatar controls">
							<img src="img/sndOn.gif" id="sndButton" onClick="sndToggle();" title="Sound on/off">
							<img src="img/play.gif" id="activeButton" onClick="activeToggle();" title="Chat active/pause">
						</div>
						<div class="right">
							<img src="img/pingOffline.gif" id="ping" title="Offline">
						</div>
						<div id="rtime" class="right" style="padding:5px 5px 0px 0px;"></div>
					</div>
				</div>
			</div>
		</div>
		<!--avatar controls-->
		<div id="avatarControls" style="display: none;">
			<div style="float: right;"><img src="img/close.gif" border="0" alt="" style="cursor: pointer;" onClick="avatarClose();"></div>
			<strong>Avatar controls</strong>
			<div id="avatarloading" class="loader" style="display: none;"></div>
			<dl>
				<dt>Current avatar:</dt>
				<dd><img id="avatar" src="img/avatarNull.gif" border="0" alt="" width="50" height="50"></dd>
				<dt>Load avatar:</dt>
				<dd>
					<form id="avatarload" action="avatarload.php" method="post" enctype="multipart/form-data" target="avatarload" onsubmit="$('avatarloading').style.display = 'block'">
						<input name="username" type="hidden" value="<?=$name?>">
						<input name="avatar" type="file"><input name="btn0avatarload" type="submit" value="Load">
					</form>
				</dd>
				<dt><a href="#" onClick="serverRemoveAvatar(author); $('avatarloading').style.display = 'block'; return false">Remove avatar</a></dt>
			</dl>
			<iframe name="avatarload" style="display: none;"></iframe>
		</div>

	</div>

</body>
</html>