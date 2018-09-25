<?

// version
$ver = '1.1';

// xajax
require_once('xajax/xajax.inc.php');
$server = new xajax('server.php', 'server');
$server->decodeUTF8InputOn();
$server->waitCursorOff();

$server->registerFunction('SendToAll');
$server->registerFunction('SendWhisper');
$server->registerFunction('Tick');
$server->registerFunction('RemoveAvatar');

// classes
foreach(glob(dirname(__FILE__).'/classes/*.php') as $path) require($path);

?>