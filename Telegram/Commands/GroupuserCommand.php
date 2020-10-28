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
use Longman\TelegramBot\Entities\Groupuser;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;


/**
 * User "/keyboard" command
 *
 * Display a keyboard with a few buttons.
 */
class GroupuserCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'groupuser';

    /**
     * @var string
     */
    protected $description = 'группа пользовательских команд';

    /**
     * @var string
     */
    protected $usage = '/groupuser';

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
	$st = array();
	$mes = $this->getMessage()->getText();
	//if(strpos($mes, 'статус') === 7){
	//	$st = explode(' ', $mes);
	//	$mes = $st[1];
	//}
		$text = 'Что пожелаешь '.$this->getMessage()->getFrom()->getFirstName().'?';
		$chat_id = $this->getMessage()->getChat()->getId();
		$data    = ['chat_id'=> $chat_id];
		$imag = ['chat_id'=> $chat_id];
		switch ($mes) {
		//Раздел
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
	case "Интернет Магазин":
		$data['text'] = 'Что Вас интересует?';
		$data['reply_markup'] = ['keyboard' => [['Сайт','Пункт самовывоза'],['Выбрать раздел']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		/*$data['text'] = 'Жми на ссылку для перехода на сайт?';
        $data['reply_markup'] = new InlineKeyboard([['text' => 'RED.UA', 'url' => 'https://www.red.ua'],'resize_keyboard' => true, 'one_time_keyboard' => true, 'selective' => false]);*/
        break;
		//--Раздел
		//Подразделение
    case "Сайт":
		$data['text'] = 'Что смотрим?';
        $data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
	case "Пункт самовывоза":
            $data['text'] = 'Режим работы: Пн-Вс: 11:00- 21:00'. PHP_EOL .'Телефоны: (068) 605-04-60, (050) 611-82-13';
		//$data['text'] = 'Какой пункт самовывоза интересует?';
       // $data['reply_markup'] = ['keyboard' => [['ул. Строителей, 40','проспект Победы, 98/2'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
			
       break;
	   //--Подразделение
	   //контакты пунктов самовывоза
	   case "ул. Строителей, 40":
		$data['text'] = 'Режим работы: Пн-Вс: 10:00- 20:00'. PHP_EOL .'Телефоны: (063) 010-34-53, (098) 634-26-82';
       // $data['reply_markup'] = ['keyboard' => [['ул. Строителей, 40','проспект Победы, 98/2'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
			
       break;
	   case "проспект Победы, 98/2":
		$data['text'] = 'Режим работы: Пн-Вс: 11:00- 21:00'. PHP_EOL .'Телефоны: (068) 605-04-60, (050) 611-82-13';
       // $data['reply_markup'] = ['keyboard' => [['ул. Строителей, 40','проспект Победы, 98/2'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
			
       break;
		//--контакты пунктов самовывоза
		//обратные переходы
	case "Выбрать раздел":
		$data['text'] = 'Что Вас интересует?';
         $data['reply_markup'] = ['keyboard' => [['Интернет Магазин','Розничные Магазины'],['Выбрать раздел']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		   
        break;
		case "Выбрать подразделение":
		$data['text'] = 'Что Вас интересует?';
        $data['reply_markup'] = ['keyboard' => [['Сайт','Пункт самовывоза'],['Выбрать раздел']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		   
        break;
		case "Выход с категорий":
		$data['text'] = 'Что смотрим?';
       $data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		   
        break;
		//--обратные переходы
		//меню категорий сайта
		case "Контакты":
		$data['text'] = 'Режим работы: Пн-Пт: 09:00-18:00, Сб-Вс: Выходные;'. PHP_EOL .'Телефон: (044) 224-40-00 (063) 809-35-29 (067) 406-90-80;'. PHP_EOL .'Email: market@red.ua';
        $data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		
        break;
		case "Отзывы":
		$data['parse_mode'] = 'HTML';
		$data['text'] = '';
		$html=simplexml_load_file('https://www.red.ua/reviewsxml');
		 foreach ($html->offers->offer as $item) {
		$data['text'] .= $item->pubDate.' : '.$item->name.PHP_EOL.$item->review.PHP_EOL;
		}
       // $data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		
        break;
		case "BLOG":
		$data['parse_mode'] = 'HTML';
		$data['text'] = '';
		$html=simplexml_load_file('https://www.red.ua/rss/');
            foreach ($html->channel->item as $item) {
	     $data['text'] = '<a href="'.$item->link.'">Смотреть</a>';
		 $q = Request::sendMessage($data);
        	}
       // $data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		//return Request::sendMessage($data);
        break;
		case "Товары":
		$data['parse_mode'] = 'HTML';
		$data['text'] = 'Выбери категорию';
        $data['reply_markup'] = ['keyboard' => [['Новинки','Обувь','Аксессуары'],['Женское','Мужское','Детское'],['Выход с категорий']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		
        break;
		case "Акции":
		$data['text'] = 'Для получения статуса заказа, отправте сообщение в формате: "111111 статус" где "111111" - номер заказа.';
		//$html = $this->get_url(array('send'=>'ucenka', 'type'=>1));
		$data['text'] = 'Пока в разработке.';//$html->result;
		$data['reply_markup'] = ['keyboard' => [['Контакты','Отзывы','BLOG'],['Товары','Акции','Соц.сети'],['Выбрать подразделение']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
		//$data['text'] = 'Жми на ссылку для перехода на сайт?';
      /*  $data['reply_markup'] = new InlineKeyboard([
		['text' => 'Узнать статус', 'switch_inline_query_current_chat'=>'Заказ № '],
		'resize_keyboard' => true,
		'one_time_keyboard' => true,
		'selective' => false]);*/
		
		break;
	case "статус1":
		$html=simplexml_load_file('https://www.red.ua/statusorder?id='.$st[0]);
		$data['text'] = $html->status;
		
		break;
	case "Соц.сети":
		$data['parse_mode'] = 'HTML';
		$data['text'] = '<a href="https://www.facebook.com/lifestyle.red.ua/">www.facebook.com</a>';
		Request::sendMessage($data);
		$data['text'] = '<a href="https://www.instagram.com/red_ua/">www.instagram.com</a>';
		Request::sendMessage($data);
		$data['text'] = '<a href="https://www.youtube.com/user/SmartRedShopping">www.youtube.com</a>';
		Request::sendMessage($data);
		break;
		//--меню категорий сайта
		//категории товаров
	case "Новинки":
		$data['parse_mode'] = 'HTML';
            $html = $this->get_url(array('send'=>'tovar', 'type'=>106));
         foreach ($html->array as $a){
            $data['text'] = $a;
            Request::sendMessage($data);
          }
          // $data['text'] = $html->result;
        break;
		case "Обувь":
		$data['parse_mode'] = 'HTML';
                    $html = $this->get_url(array('send'=>'tovar', 'type'=>33));
                  //  $data['text'] = $html->result;
          foreach ($html->array as $a){
            $data['text'] = $a;
           Request::sendMessage($data);
           }
        break;
		case "Аксессуары":
		$data['parse_mode'] = 'HTML';
                      $html = $this->get_url(array('send'=>'tovar', 'type'=>54));
          foreach ($html->array as $a){
            $data['text'] = $a;
            Request::sendMessage($data);
           }

        break;
		case "Женское":
		$data['parse_mode'] = 'HTML';
            $html = $this->get_url(array('send'=>'tovar', 'type'=>14));
          foreach ($html->array as $a){
            $data['text'] = $a;
            Request::sendMessage($data);
           }

        break;
		case "Мужское":
		$data['parse_mode'] = 'HTML';
                    $html = $this->get_url(array('send'=>'tovar', 'type'=>15));
          foreach ($html->array as $a){
            $data['text'] = $a;
            Request::sendMessage($data);
           }
		
        break;
		case "Детское":
		$data['parse_mode'] = 'HTML';
                     $html = $this->get_url(array('send'=>'tovar', 'type'=>59));
          foreach ($html->array as $a){
            $data['text'] = $a;
            Request::sendMessage($data);
           }
        break;
		//--категории товаров
	case "Главная":
		$data['text'] = 'Что будем делать дальше?';
       $data['reply_markup'] = ['keyboard' => [['Интернет Магазин','Розничные Магазины']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
        break;
    default:
		$data['text'] = 'Я не очень умный бот(((. Я понимаю только команды с панели ниже.';
       //$data['reply_markup'] = ['keyboard' => [['Интернет Магазин','Розничные Магазины']], 'resize_keyboard' => true, 'one_time_keyboard' => true, 'selective' => false ];
		
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
