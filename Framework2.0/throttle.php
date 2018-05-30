<?php
error_reporting(0);
ini_set('display_errors', 0);

if(!defined ( 'THROTTLE_ERROR' ))
	define('THROTTLE_ERROR', 'Temporary banned');
if(!defined ( 'THROTTLE_SPEED' ))
	define('THROTTLE_SPEED', '2,10,50,100');
if(!defined ( 'THROTTLE_INTERVAL' ))
	define('THROTTLE_INTERVAL', 60*60); //1 hour = TTL + old timestamp checking

//cleanup - not really needed. TTL is set up. cleaning is done on each update
/*
foreach (new APCIterator('user', '/^IP_(.*)/') as $counter) {
    echo "Deleting $counter[key]\n";
    $data = @json_decode($counter['value']);
    //clean up
	foreach($data as $key=>$item)
		if($item<time()-THROTTLE_INTERVAL)
		{
			echo 'removing ' . $item;
			unset($data[$key]);
		}    
	//if nothing left - delete
	if(count($data))
		apc_store($counter['key'], json_encode($data), THROTTLE_INTERVAL);
	else
		apc_delete($counter['key']);
}
*/
$name = 'IP_' . @$_SERVER['REMOTE_ADDR'];
$data = json_decode(apc_fetch($name));
if($data === null)	$data = array(); //first time
if(is_object($data)) $data = (array) $data; //in case of errors with keys

//add new record
$data[] = time();

//clean up old entries - let's say 1 hour log
foreach($data as $key=>$item)
{
	$result[$item]++; //sec
	$result[round($item, -1)]++; //10 sec 
	$result[round($item, -2)]++; //100 sec ~ 1.5 minute
	$result[round($item, -3)]++; //1000 sec ~ 17 minutes
	//remove if timestamp too old
	if($item<time()-THROTTLE_INTERVAL)
	{
		unset($data[$key]);
	}
}
$data = array_values($data);
//save
apc_store($name, json_encode($data), THROTTLE_INTERVAL);

//check if banned - only for external IPs
if(@$_SERVER['REMOTE_ADDR'])
{
	$fail = 0;
	list($sec,$ten,$hundred,$k) = explode(',', THROTTLE_SPEED);
	
	//check 2 per second
	if($sec && $result[time()]>$sec) $fail = 1;
	//check 10 per 10 second = 1 per sec
	if($ten && $result[round(time(), -1)]>$ten) $fail = 1;
	//check 50 per 100 second = 1 per 2 sec
	if($hundred && $result[round(time(), -2)]>$hundred) $fail = 1;
	//check 100 per 1000 second = too many request - parser?
	if($k && $result[round(time(), -3)]>$k) $fail = 1;
	
	if($fail)
	{
		//do not cache 503 error
		header( 'Cache-Control: no-store, no-cache, must-revalidate' ); 
		header( 'Cache-Control: post-check=0, pre-check=0', false ); 
		header( 'Pragma: no-cache' );
		//show error
		header('HTTP/1.0 503 Service Unavailable');	
		die(THROTTLE_ERROR);
	}
}