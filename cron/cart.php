<?php

//каждый час с 09:00 до 22:00
require_once('cron_init.php');

//clearUserCart();  //удалить товары с корзин которых нет в наличии
emait_to_Cart();
