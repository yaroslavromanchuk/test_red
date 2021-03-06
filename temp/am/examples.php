<?php

//require('vendor/autoload.php');

require_once 'AmazonAPI.php';
require_once 'AmazonUrlBuilder.php';

// Setup a new instance of the AmazonUrlBuilder with your keys
$urlBuilder = new AmazonUrlBuilder('AKIAI6LYCSFCOWKAZOYQ', '8x5kBJytSq38Kszua/yj5N3Q9h1sDQ9PYR02mo47', 'AKIAI6LYCSFCOWKAZOYQ', 'de');

// Setup a new instance of the AmazonAPI with your keys
$amazonAPI = new AmazonAPI($urlBuilder, 'simple');

// Need to avoid triggering Amazon API throttling
$sleepTime = 1.5;
/*
// Item Search:
// Harry Potter in Books, sort by featured
$items = $amazonAPI->ItemSearch('harry potter', 'Books');
print('>> Harry Potter in Books, sort by featured');
var_dump($items);

sleep($sleepTime);

// Harry Potter in Books, sort by price low to high
$items = $amazonAPI->ItemSearch('harry potter', 'Books', 'price');
print('>> Harry Potter in Books, sort by price low to high');
var_dump($items);

sleep($sleepTime);

// Harry Potter in Books, sort by price high to low
$items = $amazonAPI->ItemSearch('harry potter', 'Books', '-price');
print('>> Harry Potter in Books, sort by price high to low');
var_dump($items);
*/
sleep($sleepTime);

// Amazon echo, lookup only with Amazon as a seller
$items = $amazonAPI->ItemLookUp('B01FYMB0HK', true);
//echo print_r($items);
//echo $items[0]['title'];
//print('>> Look up specific ASIN\n');
var_dump($items);
/*
sleep($sleepTime);

// Amazon echo, lookup with incorrect ASIN array
$asinIds = array('INVALID', 'INVALIDASIN', 'NOTANASIN');
$items = $amazonAPI->ItemLookUp($asinIds, true);
print('>> Look up specific ASIN\n');
var_dump($items);
*/
//var_dump($amazonAPI->GetErrors());
?>
