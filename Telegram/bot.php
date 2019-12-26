<?php 
// Load composer
require_once __DIR__ . "/vendor/autoload.php";
require_once 'init.php';
// Получите токен у бота @BotFather
$API_KEY = '539849731:AAH9t4G2hWBv5tFpACwfFg3RqsPhK4NrvKI';
// Получите свой User ID у бота @MyTelegramID_bot
$USER_ID = '404070580';
// Придумайте своему боту имя
$BOT_NAME = "red_ua";

// Данные базы данных
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
	// Инициализация бота
	$telegram = new Telegram($API_KEY, $BOT_NAME);
	echo '<pre>';
	echo print_r($telegram->isRunCommands());
	echo '</pre>';
	 /* $result = $telegram->getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
    $result = array();
		$text = $result;
    $text = $result["message"]["text"]; //Текст сообщения
    $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
    $name = $result["message"]["from"]["username"]; //Юзернейм пользователя
    $keyboard = [["Последние статьи"],["Картинка"],["Гифка"]]; //Клавиатура

   if($text and false){
         if ($text == "/start") {
            $reply = "Добро пожаловать в бота!";
            $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => false ]);
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup' => $reply_markup ]);
        }elseif ($text == "/help") {
            $reply = "Информация с помощью.";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        }elseif ($text == "Картинка") {
            $url = "https://68.media.tumblr.com/6d830b4f2c455f9cb6cd4ebe5011d2b8/tumblr_oj49kevkUz1v4bb1no1_500.jpg";
            $telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Описание." ]);
        }elseif ($text == "Гифка") {
            $url = "https://68.media.tumblr.com/bd08f2aa85a6eb8b7a9f4b07c0807d71/tumblr_ofrc94sG1e1sjmm5ao1_400.gif";
            $telegram->sendDocument([ 'chat_id' => $chat_id, 'document' => $url, 'caption' => "Описание." ]);
        }elseif ($text == "Последние статьи") {
            $html=simplexml_load_file('http://netology.ru/blog/rss.xml');
            foreach ($html->channel->item as $item) {
	     $reply .= "\xE2\x9E\xA1 ".$item->title." (<a href='".$item->link."'>читать</a>)\n";
        	}
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'text' => $reply ]);
        }else{
        	$reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
        	$telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
        }
    }else{
    	//$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
    }*/


	// Подключение базы данных
	//$telegram->enableMySQL($mysql_credentials);

	// Добавление папки commands,
	// в которой будут лежать ваши личные комманды
	//$telegram->addCommandsPath(__DIR__ . "/commands");

	// Добавление администратора бота
	//$telegram->enableAdmin((int)$USER_ID);

	// Включение логов
	TelegramLog::initUpdateLog($BOT_NAME . '_update.log');

	// Опционально. Здесь вы можете указать кастомный объект update,
	// чтобы поймать ошибки через var_dump.
	//$telegram->setCustomInput("");

	// Основной обработчик событий
	//$telegram->handle();


} catch (Longman\TelegramBot\Exception\TelegramException $e) {
	// В случае неудачи будет выведена ошибка
	var_dump($e);
}
?>