<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
	
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $this->getMessage()->getFrom()->getFirstName().', я очень рад с тобой познакомиться. Вот твои персональные данные:'. PHP_EOL. PHP_EOL;//.' ' . PHP_EOL . 'Type /help to see all commands!';
		
		$text .= 'Your ID: ' .  $chat_id . PHP_EOL;
                    $text .= 'Name: ' . $message->getFrom()->getFirstName() . ' ' .$message->getFrom()->getLastName() . PHP_EOL;
					
					$username = $message->getFrom()->getUsername();
                    if ($username !== null && $username !== '') {
                        $text .= 'Username: ' . $username . PHP_EOL. PHP_EOL;
                    }
		$text1 = 'New User: ' . PHP_EOL;
		$text1 .= 'User ID: ' .  $chat_id . PHP_EOL;
                    $text1 .= 'Name: ' . $message->getFrom()->getFirstName() . ' ' .$message->getFrom()->getLastName() . PHP_EOL;
					
					$username = $message->getFrom()->getUsername();
                    if ($username !== null && $username !== '') {
                        $text1 .= 'Username: @' . $username . PHP_EOL. PHP_EOL;
                    }
					
		$text .='Я не очень умный бот и я не обладаю искусственным интелектом, но я очень быстро учусь)'. PHP_EOL;
		$text .='На панели внизу представлены все команды которые я понимаю.'. PHP_EOL;
		$data1 = ['chat_id' => 404070580];
		$data1['text'] = $text1;
        $data = ['chat_id' => $chat_id];
        $data['text'] = $text;
		$data['reply_markup'] = ['keyboard' => [['Интернет Магазин','Розничные Магазины']], 'resize_keyboard' => true, 'one_time_keyboard' => false, 'selective' => false ];
	
	Request::sendMessage($data1);
		
        return Request::sendMessage($data);
    }
}
