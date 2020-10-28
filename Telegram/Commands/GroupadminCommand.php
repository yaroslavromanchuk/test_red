<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Groupadmin;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
/**
 * User "/keyboard" command
 *
 * Display a keyboard with a few buttons.
 */
class GroupadminCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'groupadmin';

    /**
     * @var string
     */
    protected $description = 'группа админских команд';

    /**
     * @var string
     */
    protected $usage = '/groupadmin';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
	
	$mes = $this->getMessage()->getText();
		$text = 'Что пожелаешь '.$this->getMessage()->getFrom()->getFirstName().'?';
		$chat_id = $this->getMessage()->getChat()->getId();
		$data    = ['chat_id'=> $chat_id];
		switch ($mes) {
    case "Категории":
		$text = 'Выбери категорию?';
		$data['text'] = $text;
           // 'reply_markup' => $keyboard,
        break;
    case "Розничные Магазины":
	$data['text'] = '- Майдан Незалежности, 1 ТЦ "Глобус", 2 линия, 0 этаж'. PHP_EOL .'Тел: (073) 157-62-60, (073) 157-62-60'. PHP_EOL .'
	- Броварской проспект 17'. PHP_EOL .'Тел: (073) 175 52 58'. PHP_EOL .'
	- ул. Драйзера, 8 Универсам "Сильпо", 2 этаж'. PHP_EOL .'Тел: (044) 546-74-82, (063) 471-36-76'. PHP_EOL .'
	- проспект Героев Сталинграда, 46'. PHP_EOL .'Тел: (044) 428-02-74, (063) 134-35-77'. PHP_EOL .'
	- Проспект Правды, 66 Универсам "Сильпо", 2 этаж'. PHP_EOL .'Тел: (044) 463-05-45, (063) 802-09-95'. PHP_EOL .'
	- ул. Елены Телиги, 13/14 метро "Дорогожичи", напротив остановки "Бабий Яр"'. PHP_EOL .'Тел: (044) 467-20-77, (063) 477-51-95'. PHP_EOL .'
	- ул. Фрунзе (Кирилловская), 127 возле Куреневского рынка'. PHP_EOL .'Тел: (044) 464-18-45, (063) 471-83-65'. PHP_EOL .'
	- проспект Победы, 98/2 между станциями метро "Нивки" и "Святошино"'. PHP_EOL .'Тел: (044) 400-54-84, (063) 515-24-66'. PHP_EOL .'
	- ул.Строителей, 40 ТЦ "DOMA", 2 этаж'. PHP_EOL .'Тел: (044) 594-51-72, (063) 470-43-41'. PHP_EOL .'
	- г. Борисполь, ул. Киевский Шлях, 67. ТЦ "PARK TOWN", 2 этаж.'. PHP_EOL .'Тел: (044) 220-15-05, (063) 746-53-59
		';
     $data['reply_markup'] = ['keyboard' => [['Интернет Магазин','Розничные Магазины']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
	case "Посетить сайт":
		$data['text'] = 'Жми на ссылку для перехода на сайт?';
        $data['reply_markup'] = new InlineKeyboard([['text' => 'RED.UA', 'url' => 'https://www.red.ua'],'resize_keyboard' => true, 'one_time_keyboard' => true, 'selective' => false]);
        break;
	case "Интернет Магазин":
		$data['text'] = 'Что пожелаешь хозяин?';
		$data['reply_markup'] = ['keyboard' => [['Заказы','Товары','Уценка','Бонусы']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Заказы":
		$text = 'Какие заказы показать?';
		$data['text'] = $text;
        $data['reply_markup'] = ['keyboard' => [['Новые заказы','Отправленные заказы','Заказы в магазине'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Новые заказы":
		//$text = 'Новых заказов: ';
		$html = $this->get_url(array('send'=>'orders', 'type'=>1));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Новые заказы','Отправленные заказы','Заказы в магазине'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Заказы в магазине":
		//$text = 'Заказов в магазине: ';
		$html = $this->get_url(array('send'=>'orders', 'type'=>2));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Новые заказы','Отправленные заказы','Заказы в магазине'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Отправленные заказы":
		//$text = 'Заказов в магазине: ';
		$html = $this->get_url(array('send'=>'orders', 'type'=>3));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Новые заказы','Отправленные заказы','Заказы в магазине'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Товары":
		$text = 'Какие товары показать?';
		$data['text'] = $text;
        $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Товары за сегодня":
		//$text = 'За сегодня добалено ';
		$html = $this->get_url(array('send'=>'articles', 'type'=>1));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Товары за вчера":
		//$text = 'За вчера добалено ';
		$html = $this->get_url(array('send'=>'articles', 'type'=>2));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Товары за неделю":
		//$text = 'За неделю добалено ';
		$html = $this->get_url(array('send'=>'articles', 'type'=>3));
		$data['text'] = $html->result;
      $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Неактивные товары":
		//$text = 'Неактивных ';
		$html = $this->get_url(array('send'=>'articles', 'type'=>4));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
		case "Товары в студии":
		//$text = 'Неактивных ';
		$html = $this->get_url(array('send'=>'articles', 'type'=>5));
		$data['text'] = $html->result;
        $data['reply_markup'] = ['keyboard' => [['Товары за сегодня','Товары за вчера'],['Товары за неделю','Товары в студии'],['Неактивные товары','Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
	case "Уценка":
		$html = $this->get_url(array('send'=>'ucenka', 'type'=>1));
		$data['text'] = $html->result;
		$data['reply_markup'] = ['keyboard' => [['Заказы','Товары','Уценка','Бонусы']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Бонусы":
        $text = 'Какие заказы показать?';
		$data['text'] = $text;
        $data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
       break;
    case "Зачислено":
		$html = $this->get_url(array('send'=>'bonus', 'type'=>1));
		$data['text'] = $html->result;
                
		$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Активные":
		$html = $this->get_url(array('send'=>'bonus', 'type'=>2));
		$data['text'] = $html->result;
		$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Использовано":
		$html = $this->get_url(array('send'=>'bonus', 'type'=>3));
		$data['text'] = $html->result;
		$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "Списано":
		$html = $this->get_url(array('send'=>'bonus', 'type'=>4));
		$data['text'] = $html->result;
		$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "dev-close-site":
		$html = $this->get_url(array('send'=>'close', 'type'=>4));
		$data['text'] = $html->result;
		//$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    case "delete-table":
		$html = $this->get_url(array('send'=>'delete-table', 'type'=>4));
		$data['text'] = $html->result;
		//$data['reply_markup'] = ['keyboard' => [['Зачислено','Активные','Использовано','Списано'],['Интернет Магазин']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    default:
		$data['text'] = 'Эта функция еще не готова((( Выберите что-то другое.';
       $data['reply_markup'] = ['keyboard' => [['Заказы','Товары','Уценка','Бонусы']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
}
$data['parse_mode'] = 'HTML';
	return Request::sendMessage($data);

    }
	public function get_url($param){
	$myCurl = curl_init();
curl_setopt_array($myCurl, array(
    CURLOPT_URL => 'https://www.red.ua/telegram/?token=red',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $param
));
$response = curl_exec($myCurl);
curl_close($myCurl);
return json_decode($response);
	}
}
