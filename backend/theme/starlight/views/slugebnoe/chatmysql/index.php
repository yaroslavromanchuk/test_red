<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>RED.Chat</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/png" href="img/favicon.png" />
		<link rel="stylesheet" type="text/css" href="lib/mel.sound.css" />
		<link rel="stylesheet" type="text/css" href="lib/ext/css/ext-all.css" />
		<link rel="stylesheet" type="text/css" href="lib/ext/css/xtheme-gray.css" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="lib/jquery.js"></script>
		<script type="text/javascript" src="lib/jquery.cookie.js"></script>
		<script type="text/javascript" src="lib/mel.focusto.js"></script>
		<script type="text/javascript" src="lib/mel.popup.js"></script>
		<script type="text/javascript" src="lib/mel.sound.js"></script>
		<script type="text/javascript" src="lib/mel.tipper.js"></script>
		<script type="text/javascript" src="lib/ext/ext-jquery-adapter.js"></script>
		<script type="text/javascript" src="lib/ext/ext.js"></script>
		<script type="text/javascript" src="js/init.js"></script>
		
	</head>
	
	<body>
		
		<div id="frameLogin">
			<div class="frame1_top"></div>
			<div class="frame1_body">
				<p>
					<span lang="1102"><!--Nickname--></span>:<br />
					<input id="inpNickname" type="hidden" />
				</p>
				<div id="btnPassword" class="ctrl ctrl_password"></div>
				<p style="display: none">
					<span lang="1103"><!--Password--></span>:<br />
					<input id="inpPassword" type="password" />
				</p>
				<p align="center">
					<button id="btnConnect" class="btn"><div><div><div lang="1209"><!--Connect--></div></div></div></button>
				</p>
				<p align="center" style="display: none">
					<span class="icon_loading" lang="1210"><!--Connecting...--></span>
				</p>
				<!-- <p align="center">
					<a href="javascript:;" id="btnLangEn"><img src="img/langs/en.png" border="0" /></a>
					<a href="javascript:;" id="btnLangRu"><img src="img/langs/ru.png" border="0" /></a>
				</p> -->
			</div>
			<div class="frame1_bottom"></div>
		</div>
		
		<div id="frameMain">
			<div id="main">
				<div id="log">
					<!--Messages Log Here-->
				</div>
				<div id="input">
					<input id="inpMessage" type="text" />
				</div>
			</div>
			<div id="list">
				<div id="self">
					<div id="nickname"><span class="icon_status_0"><!--Nickname--></span></div>
					<div id="status"><span class="icon_none" lang="3000"><!--Online--></span></div>
					<div id="status_label"><span class="icon_none" lang="1211"><!--Select status--></span>:</div>
				</div>
				<div id="statuses">
					<div status="0"><span class="icon_status_0" lang="3000"><!--Online--></span></div>
					<div status="1"><span class="icon_status_1" lang="3001"><!--Away--></span></div>
					<div status="2"><span class="icon_status_2" lang="3002"><!--Busy--></span></div>
					<div status="3"><span class="icon_status_3" lang="3003"><!--Invisible--></span></div>
				</div>
				<div id="users">
					<!--Users List Here-->
				</div>
				<div id="ctrls">
					<div id="btnSendMessage" class="ctrl ctrl_send"></div>
					<div id="btnClearMessage" class="ctrl ctrl_clearm"></div>
					<div id="btnSmiles" class="ctrl ctrl_smiles"></div>
					<div id="btnSound" class="ctrl ctrl_sound1"></div>
					<div id="btnClear" class="ctrl ctrl_clear"></div>
					<div id="btnAbout" class="ctrl ctrl_about"></div>
					<div id="btnDisconnect" class="ctrl ctrl_disconnect"></div>
				</div>
				<div id="smiles">
					<!--Smiles Pack Here-->
				</div>
			</div>
		</div>
		
		<div id="tplAbout" class="hidden">
			<p class="t"><a href="http://melnaron.net/projects/melchat" target="_blank">RED.Chat</a></p>
			<p><span lang="1201"><!--Copyright--></span> &copy; 2009 <a href="http://melnaron.net/" target="_blank">Melnaron</a></p>
			<p class="b"><span lang="1202"><!--Version--></span>: %VERSION%</p>
		</div>
		
		<div id="tplShowUsers" class="hidden">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr style="font-weight: bold">
					<td><span lang="1102"><!--Nickname--></span>:</td>
					<td><span lang="1104"><!--Access--></span>:</td>
					<td><span lang="1106"><!--IP--></span>:</td>
					<td><span lang="1107"><!--Agent--></span>:</td>
					<td><span lang="1108"><!--Conn. Time--></span>:</td>
					<td><span lang="1109"><!--Reg. Time--></span>:</td>
				</tr>
			</table>
		</div>
		
		<div id="tplShowBans" class="hidden">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr style="font-weight: bold">
					<td width="50%"><span lang="1206"><!--Nicknames--></span>:</td>
					<td width="50%"><span lang="1207"><!--IPs--></span>:</td>
				</tr>
			</table>
		</div>
		
	</body>
	
</html>