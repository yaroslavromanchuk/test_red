<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php
header("Content-Type: text/html; charset=utf-8");
// Файл firstsql.php
$host='localhost'; // имя хоста (уточняется у провайдера)
$database='red_site'; // имя базы данных, которую вы должны создать
$user='red_site_user'; // заданное вами имя пользователя, либо определенное провайдером
$pswd='hx2H6xQWjsqQcuVsss!'; // заданный вами пароль

$dbh = mysql_connect($host, $user, $pswd) or die("Не могу соединиться с MySQL.");
mysql_select_db($database) or die("Не могу подключиться к базе.");
?>