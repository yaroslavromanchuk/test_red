<?php

class TelegramsController extends controllerAbstract
{
     private $_files_folder = 'telega';
    public function init()
    {
    // $this->domain = strtolower(str_replace('www', '', $_SERVER['HTTP_HOST']));
       // $this->ws = $this->website = Registry::get('website');
      //  $this->view->user = $this->ws->getCustomer();
        
       // $this->view->ws = $this->ws;
       // $this->view->message = $this->message;
       // $this->view->get = $this->get;
       // $this->view->post = $this->post;

		
       // Registry::set('View', $this->view);
       //  $cache = Registry::get('cache');
	//$cache->setEnabled(true);
       //  $cache_name = 'stores_header';

        //  $this->view->stores_header = $stores_header;
	//$this->view->setRenderPath('developer');
	//$this->_global_template = '/stores/stores.tpl.php';
    }
    
    public function indexAction(){
         require_once("Telega/Telega.php");
         // Add you bot's API key and name
$bot_api_key  = '539849731:AAH9t4G2hWBv5tFpACwfFg3RqsPhK4NrvKI';
$bot_username = 'red_ua';

// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
  //  123,
	404070580,
	396902554,
	526708750,
];

// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Commands/',
];

// Enter your MySQL database credentials

$mysql_credentials = [
   'host'     => 'localhost',
   'user'     => 'telegram',
   'password' => 'Gc9438)L3Ux%',
   'database' => 'telegram',
];

$telegram = new Telega($bot_api_key, $bot_username);

 // Add commands paths containing your custom commands
   $telegram->addCommandsPaths($commands_paths);
//$telegram->isRunCommands()
    // Enable admin users
   $telegram->enableAdmins($admin_users);
  // $telegram->isAdmin(123);
    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);
 // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();

    // Handle telegram webhook request
    $telegram->handle();
    }
}
