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
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

/**
 * User "/keyboard" command
 *
 * Display a keyboard with a few buttons.
 */
class KeyboardCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'keyboard';

    /**
     * @var string
     */
    protected $description = 'Show a custom keyboard with reply markup';

    /**
     * @var string
     */
    protected $usage = '/keyboard';

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
        //Keyboard examples
        /** @var Keyboard[] $keyboards */
        $keyboards = [];

        //Example 0
        /*$keyboards[0] = new Keyboard(
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
            [' ', '0', ' ']
        );*/
		if($this->isAdmin()){
		//Example 0
        $keyboards[0] = new Keyboard(['Категории', 'Магазины'],['Заказы', 'www.red.ua']);
		}else{
		//Example 0
        $keyboards[0] = new Keyboard(['Розничные Магазины'],['Сайт']);
		}

        //Example 1
        $keyboards[1] = new Keyboard(
            ['Новинки', 'Женское', 'Мужское'],
            ['Обувь', 'Детское', 'Аксессуары'],
            ['Текстиль', 'Sale', 'Бренды']
        );

        //Example 2
        $keyboards[2] = new Keyboard('A', 'B', 'C');

        //Example 3
        /*$keyboards[] = new Keyboard(
            ['text' => 'A'],
            'B',
            ['C', 'D']
        );*/
		//Example 3
        $keyboards[3] = new Keyboard(
            ['Новый', 'Оплачен', 'Cобран'],
            ['Доставлен в магазин', 'В процесе', 'Возвраты'],
			 ['Главная']
        );

        //Example 4 (bots version 2.0)
        $keyboards[4] = new Keyboard([
            ['text' => 'Отправить мои данные', 'request_contact' => true],
            ['text' => 'Отправить мою локацию', 'request_location' => true],
        ]);
		
		 $keyboards[5] = new InlineKeyboard([
            ['text' => 'callback', 'callback_data' => 'identifier'],
            ['text' => 'Открыть red.ua', 'url' => 'https://www.red.ua'],
        ]);
		$mes = $this->getMessage()->getText();
		$text = 'Что пожелаешь '.$this->getMessage()->getFrom()->getFirstName().'?';
		$chat_id = $this->getMessage()->getChat()->getId();
		switch ($mes) {
    case "Сайт":
        $reply =  5;
		
        break;
    case "Категории":
        $reply =  1;
		$text = 'Выбери категорию?';
        break;
    case "Розничные Магазины":
        $reply =  0;
		$text = '- Майдан Незалежности, 1 ТЦ "Глобус", 2 линия, 0 этаж'. PHP_EOL .'Тел: (073) 157-62-60, (073) 157-62-60'. PHP_EOL .'- Броварской проспект 17'. PHP_EOL .'Тел: (073) 175 52 58'. PHP_EOL .'- ул. Драйзера, 8 Универсам "Сильпо", 2 этаж'. PHP_EOL .'Тел: (044) 546-74-82, (063) 471-36-76'. PHP_EOL .'- г. Борисполь, ул. Киевский Шлях, 67. ТЦ "PARK TOWN", 2 этаж.'. PHP_EOL .'Тел: (044) 220-15-05, (063) 746-53-59 
		';
        break;
    case "Заказы":
        $reply =  3;
		$text = 'Какие заказы показать?';
        break;
	case "Главная":
        $reply =  0;
		$text = 'Что будем делать дальше?';
        break;
	case "www.red.ua":
        $reply =  0;
		$text = 'Что будем делать дальше?';
        break;
    default:
        $reply =  0;
}
        //Return a random keyboard.
       // $keyboard = $keyboards[mt_rand(0, count($keyboards) - 1)]
	    $keyboard = $keyboards[$reply]
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);

        
        $data    = [
            'chat_id'      => $chat_id,
            'text'         => $text,
            'reply_markup' => $keyboard,
        ];

        return Request::sendMessage($data);
    }
}
