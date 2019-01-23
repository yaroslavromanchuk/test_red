<?php

$config = array(
	'type' 			=> 'special', /* date OR time, OR special */
	'cookie' 		=> 1, /* 1 - On, 0 - Off */
	'ip' 			=> 1, /* 1 - On, 0 - Off */
	'repeat' 		=> 1, /* 1 - On, 0 - Off */
	'repeattime' 	=> 86400, /* repeattime cookie or ip (sec) */
	'timezone'	 	=> '+0', /* repeattime cookie or ip (sec) */
	
	'special_type'	=> 1, /* 1 - day, 2 - week, 3 - month */
	'special_time'	=> 86399, /*  */
	'special_day'	=> 'Frider', /*  */
	'special_date'	=> '31', /*  */
	
	'time_left'		=> 86400, /* time type left (sec) */
	'date_left'		=> 1420070400, /* Unix time */
	'template'		=> 'red', /* red, blue, circle, default, techno  */
	'blockvisible' 	=> '0/0/1/1/1', /* w/d/h/i/s */
	'language' 		=> 'Russian', /* y/m/w/d/h/m/s */
	
	'redirect_end'	=> 0, /*  */
	'redirect_url'	=> 'http://spydec.com/', /*  */
	
	'page_html'		=> 0, /*  */
	'page_on'		=> 'index_on.html', /*  */
	'page_off'		=> 'index_off.html', /*  */
	
	'number'		=> 5 /* for cookie name */
);

?>