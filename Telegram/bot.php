<?php 
// Load composer
require_once __DIR__ . "/vendor/autoload.php";
require_once 'init.php';
// �������� ����� � ���� @BotFather
$API_KEY = '539849731:AAH9t4G2hWBv5tFpACwfFg3RqsPhK4NrvKI';
// �������� ���� User ID � ���� @MyTelegramID_bot
$USER_ID = '404070580';
// ���������� ������ ���� ���
$BOT_NAME = "red_ua";

// ������ ���� ������
$mysql_credentials = [
   'host'     => 'localhost',
   'user'     => 'red_site_user',
   'password' => 'hx2H6xQWjsqQcuVsss!',
   'database' => 'telegram',
];
$t = new init();
print_r($t->execute());

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;

try {
	// ������������� ����
	$telegram = new Telegram($API_KEY, $BOT_NAME);
	echo '<pre>';
	echo print_r($telegram->isRunCommands());
	echo '</pre>';
	 /* $result = $telegram->getWebhookUpdates(); //�������� � ���������� $result ������ ���������� � ��������� ������������
    $result = array();
		$text = $result;
    $text = $result["message"]["text"]; //����� ���������
    $chat_id = $result["message"]["chat"]["id"]; //���������� ������������� ������������
    $name = $result["message"]["from"]["username"]; //�������� ������������
    $keyboard = [["��������� ������"],["��������"],["�����"]]; //����������

   if($text and false){
         if ($text == "/start") {
            $reply = "����� ���������� � ����!";
            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
        }elseif ($text == "/help") {
            $reply = "���������� � �������.";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        }elseif ($text == "��������") {
            $url = "https://68.media.tumblr.com/6d830b4f2c455f9cb6cd4ebe5011d2b8/tumblr_oj49kevkUz1v4bb1no1_500.jpg";
            $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "��������." ]);
        }elseif ($text == "�����") {
            $url = "https://68.media.tumblr.com/bd08f2aa85a6eb8b7a9f4b07c0807d71/tumblr_ofrc94sG1e1sjmm5ao1_400.gif";
            $telegram->sendDocument([ 'chat_id' => $chat_id, 'document' => $url, 'caption' => "��������." ]);
        }elseif ($text == "��������� ������") {
            $html=simplexml_load_file('http://netology.ru/blog/rss.xml');
            foreach ($html->channel->item as $item) {
	     $reply .= "\xE2\x9E\xA1 ".$item->title." (<a href='".$item->link."'>������</a>)\n";
        	}
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'text' => $reply ]);
        }else{
        	$reply = "�� ������� \"<b>".$text."</b>\" ������ �� �������.";
        	$telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
        }
    }else{
    	//$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "��������� ��������� ���������." ]);
    }*/


	// ����������� ���� ������
	//$telegram->enableMySQL($mysql_credentials);

	// ���������� ����� commands,
	// � ������� ����� ������ ���� ������ ��������
	//$telegram->addCommandsPath(__DIR__ . "/commands");

	// ���������� �������������� ����
	//$telegram->enableAdmin((int)$USER_ID);

	// ��������� �����
	TelegramLog::initUpdateLog($BOT_NAME . '_update.log');

	// �����������. ����� �� ������ ������� ��������� ������ update,
	// ����� ������� ������ ����� var_dump.
	//$telegram->setCustomInput("");

	// �������� ���������� �������
	//$telegram->handle();


} catch (Longman\TelegramBot\Exception\TelegramException $e) {
	// � ������ ������� ����� �������� ������
	var_dump($e);
}
?>