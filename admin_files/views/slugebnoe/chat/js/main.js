<!--

/* ID */
function byId(id) {
	return document.getElementById(id);
}

function $(id) {
	return document.getElementById(id);
}

/* Login tab */
function selectTab(tabNo)
{
	if (tabNo == 1)
	{
		byId('loginTab2').style.display = 'none';
		byId('loginTab1').style.display = 'block';
	}
	else if (tabNo == 2)
	{
		byId('loginTab1').style.display = 'none';
		byId('loginTab2').style.display = 'block';
	}
}

/* chat load */
var author = 0;

function chatLoad(a)
{
	if (a)
	{
		author = a;
		startTick();
		byId('msg').focus();
	}
}

/* Log */
function scrollLog()
{
	byId('chatLog').scrollTop = 65535;
}
function clearLog()
{
	byId('chatLog').innerHTML = '';
}

/* Spam control */
var spamTime = 1000;
var spamTimer = null;

function spamOn()
{
	spamTimer = setTimeout('spamOff()', spamTime);
}
function spamOff()
{
	spamTimer = null;
}

/* URL Replace in string */
function urlreplace(str)
{
    return str.replace(/((https?|ftp):\/\/\S*)/gi, '<a href="$1" target="_blank">$1</a>');
}

/* Message print */
function printMsg(msg, type)
{
	var time = new Date();
	var hours = time.getHours();
	var minutes = time.getMinutes();
	if (hours < 10) hours = '0'+hours;
	if (minutes < 10) minutes = '0'+minutes;
	// убираем хтмл теги если они есть
	msg = msg.replace(/&/g,'&amp;').replace(/>/g,'&gt;').replace(/</g,'&lt;'); //.replace(/'/g,'&#39;')
	// преобразуем ссылки
	msg = urlreplace(msg);
	
	if (!type)
	{
		var printMsg = '<p>['+hours+':'+minutes+'] <strong class="self">'+author+'</strong> : '+msg+'</p>';
	}
	else if (type == 'whisper')
	{
		var to = msg.slice(msg.indexOf('[')+1, msg.indexOf(']'));
		var whisper = msg.slice(msg.indexOf('] ')+2);
		var timeLine = '<p>['+hours+':'+minutes+'] ';
		var authorLine = '<strong class="whisper">'+author+' to </strong><strong class="whisperTo" onClick="whisperTo(\''+to+'\')">'+to+'</strong> : ';
		var printMsg = timeLine+authorLine+whisper+'</p>';
	}
	else // error
	{
		var printMsg = '<p>['+hours+':'+minutes+'] <strong class="'+type+'">*** '+msg+'</strong></p>';
	}
	
	byId('chatLog').innerHTML += printMsg;
	scrollLog();
}

/* Message add */
function addMsg()
{
	var msg = byId('msg').value;
	if (msg)
	{
		if (online == 0)
			printMsg('You are disconnected. Press Refresh button', 'error');
		else if (spamTimer)
			printMsg('You can not send more than once in one second', 'error');
		else
		{
			if (msg.indexOf('/whisper') == 0)
			{
				if (msg.indexOf('[') > 0 && msg.indexOf(']') > 0)
				{
					serverSendWhisper(author, msg);
					printMsg(msg, 'whisper');
				}
				else
				{
					printMsg('Wrong command', 'error');
				}
			}
			else if (msg.indexOf('/') == 0)
			{
				printMsg('Wrong command', 'error');
			}
			else
			{
				serverSendToAll(author, msg);
				printMsg(msg);
			}
			spamOn();
			byId('msg').value = '';
		}
	}
}

/* Whisper to */
var lastWhisperName = null;

function whisperTo(name)
{
	var oldMsg = byId('msg').value;
	if (oldMsg.indexOf('/whisper') >= 0)
		oldMsg = '';
	byId('msg').focus();
	byId('msg').value = '/whisper['+name+'] '+oldMsg;
	lastWhisperName = name;
}

/* Check re-whisper */
function checkReWhisper(e)
{
	var ev = null;
	window.event ? ev = window.event : ev = e;
	if (ev && ev.keyCode == 9)
	{
		if (lastWhisperName)
		{
			var oldMsg = byId('msg').value;
			if (oldMsg.indexOf('/whisper') >= 0)
				oldMsg = '';
			byId('msg').value = '/whisper['+lastWhisperName+'] '+oldMsg;
		}
		return true;
	}
}

/* Tick */
var tickTime = 2000; // ms
var tickTimer = null;
var online = 1;

function startTick()
{
	tickTimer = setInterval('tick()', tickTime);
	tick();
}
function stopTick()
{
	clearInterval(tickTimer);
}
function tick()
{
	pingStart();
	serverTick(author);
}

/* Sound on/off swith */
var sndOn = 1;

function sndToggle()
{
	var b = byId('sndButton');
	
	if (sndOn)
	{
		b.src = 'img/sndOff.gif';
		sndOn = 0;
		disableSound();
	}
	else
	{
		b.src = 'img/sndOn.gif';
		sndOn = 1;
		//playSound('snd/on');
		enableSound();
	}
}

/* Active Toggle */
function activeToggle()
{
	if (online)
	{
		byId('ping').src = 'img/pingOffline.gif';
		byId('ping').title = 'Disconnected';
		online = 0;
		stopTick();
		titles_init();
		byId('rtime').innerHTML = '';
		byId('activeButton').src = 'img/play.gif';
	}
	else
	{
		online = 1;
		pc = 0;
		startTick();
		byId('activeButton').src = 'img/pause.gif';
	}
}

/* Ping timer start / stop, ping counter */
// responce time
var rt = 0;
// ping counter
var pc = 0;

function pingStart()
{
	//byId('ping').src = 'img/pingAnim.gif';
	d = new Date();
	rt = d.getTime();
	// увеличиваем пинг каунтер на 1
	pc ++;
	// проверка пинг каунтера, если больше 5, то дисконнектим
	if (pc > 5)
	{
		//byId('ping').src = 'img/pingOffline.gif';
		byId('ping').title = 'Disconnected';
		online = 0;
		stopTick();
		titles_init();
		byId('rtime').innerHTML = '';
		byId('activeButton').src = 'img/play.gif';
	}
}
function pingStop(newTitle)
{
	if (online)
	{
		byId('ping').src = 'img/pingOnline.gif';
		byId('ping').title = newTitle;
		d = new Date();
		rtms = d.getTime()-rt;
		byId('rtime').innerHTML = rtms+'ms';
		// обнуляем пинг каунтер
		pc = 0;
		byId('activeButton').src = 'img/pause.gif';
	}
}

-->