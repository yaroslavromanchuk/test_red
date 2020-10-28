<?php
class wsLog extends wsActiveRecord
{
    protected $_table = 'ws_log';
    protected $_orderby = array('id' => 'ASC');


	public static function add($message, $type = 'INFO')
	{
		$logger = new wsLog();
		$logger->setHashVisit(isset($_SESSION['v']) ? $_SESSION['v'] : '');
		$logger->setUrl(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
		$logger->setReffererUrl(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
		$logger->setTimestamp(date('Y-m-d H:i:s'));
		$logger->setPriorityName($type);
		$logger->setPriority(1);
		$logger->setMessage($message);
		$logger->save();
                
                if($type == 'EMERG'){
                   Telegram::sendMessageTelegram(404070580, 'ERROR RED.UA!'.PHP_EOL.'URL:'.$_SERVER['REQUEST_URI'].PHP_EOL.'Message:'.$message);
                }
	}
        
        
}
