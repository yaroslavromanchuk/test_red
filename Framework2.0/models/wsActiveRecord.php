<?php
require_once('site_config.php');

class wsActiveRecord extends Orm_ActiveRecord
{
	protected static $_block_class = BLOCK_CLASS;
	protected static $_block_type_class = BLOCK_TYPE_CLASS;
	protected static $_block_position_class = BLOCK_POSITION_CLASS;
	protected static $_brand_class = BRAND_CLASS;
	protected static $_shopping_cart_item_class = SHOPPING_CART_ITEM_CLASS;
	protected static $_country_class = COUNTRY_CLASS;
	protected static $_customer_class = CUSTOMER_CLASS;
	protected static $_customer_address_class = CUSTOMER_ADDRESS_CLASS;
	protected static $_customer_machine_class = CUSTOMER_MACHINE_CLASS;
	protected static $_customer_status_class = CUSTOMER_STATUS_CLASS;
	protected static $_customer_type_class = CUSTOMER_TYPE_CLASS;
	protected static $_customer_visit_class = CUSTOMER_VISIT_CLASS;
	protected static $_menu_class = MENU_CLASS;
	protected static $_menu_type_class = MENU_TYPE_CLASS;
	protected static $_menu_filter_class = MENU_FILTER_CLASS;
	protected static $_site_class = SITE_CLASS;
	protected static $_product_class = PRODUCT_CLASS;
	protected static $_product_category_class = PRODUCT_CATEGORY_CLASS;
	protected static $_product_property_class = PRODUCT_PROPERTY_CLASS;
	protected static $_product_property_value_class = PRODUCT_PROPERTY_VALUE_CLASS;
	protected static $_shopping_cart_class = SHOPPING_CART_CLASS;
	protected static $_supplier_class = SUPPLIER_CLASS;
	protected static $_price_class = PRICE_CLASS;
	protected static $_favorite_list_class = FAVORITE_LIST_CLASS;
	protected static $_right_class = RIGHT_CLASS;	
	protected static $_config_class = CONFIG_CLASS;
	protected static $_language_class = LANGUAGE_CLASS;
	protected static $_currency_class = CURRENCY_CLASS;
	protected static $_pricelist_class = PRICELIST_CLASS;
	protected static $_tax_rate_class = TAX_RATE_CLASS;
	protected static $_tax_type_class = TAX_TYPE_CLASS;
	protected static $_file_class = FILE_CLASS;
	protected static $_file_type_class = FILE_TYPE_CLASS;
	protected static $_file_size_class = FILE_SIZE_CLASS;	
	protected static $_product_status_class = PRODUCT_STATUS_CLASS;
	protected static $_product_type_class = PRODUCT_TYPE_CLASS;
	protected static $_distribution_center_class = DISTRIBUTION_CENTER_CLASS;
	protected static $_stock_class = STOCK_CLASS;
	protected static $_order_class = ORDER_CLASS;
	protected static $_order_status_class = ORDER_STATUS_CLASS;
	protected static $_store_class = STORE_CLASS;
	protected static $_order_invoice_class = ORDER_INVOICE_CLASS;
	protected static $_payment_method_class = PAYMENT_METHOD_CLASS;
	protected static $_shipping_method_class = SHIPPING_METHOD_CLASS;
	protected static $_order_item_class = ORDER_ITEM_CLASS;
	protected static $_order_item_status_class = ORDER_ITEM_STATUS_CLASS;
	protected static $_order_log_class = ORDER_LOG_CLASS;
	protected static $_order_payment_class = ORDER_PAYMENT_CLASS;
	protected static $_order_payment_status_class = ORDER_PAYMENT_STATUS_CLASS;
	protected static $_promotion_class = PROMOTION_CLASS;
	protected static $_promotion_type_class = PROMOTION_TYPE_CLASS;
	protected static $_favorite_list_item_class = FAVORITE_LIST_ITEM_CLASS;
	protected static $_log_search_class = LOG_SEARCH_CLASS;
	protected static $_log_class = LOG_CLASS;
	
	protected static $_shop_categories_class = SHOP_CATEGORIES_CLASS;
	protected static $_shop_articles_class = SHOP_ARTICLES_CLASS;
	protected static $_shop_articles_top_class = SHOP_ARTICLES_TOP_CLASS;
	protected static $_shop_articles_options_class = SHOP_ARTICLES_OPTIONS_CLASS;
	protected static $_shop_articles_offer_class = SHOP_ARTICLES_OFFER_CLASS;
	protected static $_shop_orders_class = SHOP_ORDERS_CLASS;
	protected static $_shop_order_articles_class = SHOP_ORDER_ARTICLES_CLASS;
	protected static $_shop_order_remarks_class = SHOP_ORDER_REMARKS_CLASS;
	//protected static $_shop_order_statuses_class = SHOP_ORDER_STATUSES_CLASS;
	
	//protected static $_amazon_order_articles_class = AMAZON_ORDER_ARTICLES_CLASS;

	public function getTable($new_table = '')
	{
		return DB_SUFFIX . $this->_table;
	}

	public function remove_accent($str)
	{
		$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		return str_replace($a, $b, $str);
	}
	
	protected function _generateUrl($shortcut) 
	{
		$shortcut = $this->_translit(iconv('UTF-8','windows-1251',$shortcut));
		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $this->remove_accent($shortcut)));
	}

	/*
	protected function _generateUrl($shortcut) {
		$shortcut = $this->_translit(iconv('UTF-8','windows-1251',$shortcut));
		return str_replace('--', '-', preg_replace('/[^a-zA-Z0-9_-]+/','-', trim(strtolower($shortcut))));
	}
	*/
	
	public function _translit($str) {
		$transchars =array (
		"E1"=>"A",
		"E2"=>"B",
		"F7"=>"V",
		"E7"=>"G",
		"E4"=>"D",
		"E5"=>"E",
		"B3"=>"Jo",
		"F6"=>"Zh",
		"FA"=>"Z",
		"E9"=>"I",
		"EA"=>"I",
		"EB"=>"K",
		"EC"=>"L",
		"ED"=>"M",
		"EE"=>"N",
		"EF"=>"O",
		"F0"=>"P",
		"F2"=>"R",
		"F3"=>"S",
		"F4"=>"T",
		"F5"=>"U",
		"E6"=>"F",
		"E8"=>"H",
		"E3"=>"C",
		"FE"=>"Ch",
		"FB"=>"Sh",
		"FD"=>"W",
		"FF"=>"X",
		"F9"=>"Y",
		"F8"=>"Q",
		"FC"=>"Eh",
		"E0"=>"Ju",
		"F1"=>"Ja",

		"C1"=>"a",
		"C2"=>"b",
		"D7"=>"v",
		"C7"=>"g",
		"C4"=>"d",
		"C5"=>"e",
		"A3"=>"jo",
		"D6"=>"zh",
		"DA"=>"z",
		"C9"=>"i",
		"CA"=>"i",
		"CB"=>"k",
		"CC"=>"l",
		"CD"=>"m",
		"CE"=>"n",
		"CF"=>"o",
		"D0"=>"p",
		"D2"=>"r",
		"D3"=>"s",
		"D4"=>"t",
		"D5"=>"u",
		"C6"=>"f",
		"C8"=>"h",
		"C3"=>"c",
		"DE"=>"ch",
		"DB"=>"sh",
		"DD"=>"w",
		"DF"=>"x",
		"D9"=>"y",
		"D8"=>"",
		"DC"=>"eh",
		"C0"=>"ju",
		"D1"=>"ja",
		);

		/*
		$str = html_entity_decode($str);
		$str = preg_replace("!<script[^>]{0,}>.*</script>!Uis", "", $str);
		$str = strip_tags($str);
		$str = preg_replace("![^абвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯa-z0-9 ]!i", " ", $str);
		$str = preg_replace("![\s]{2,}!", " ", $str);*/
		$str = trim($str);
		$ns = convert_cyr_string($str, "w", "k");
		$b = '';
		for ($i=0;$i<strlen($ns);$i++)
		{
			$c=substr($ns,$i,1);
			$a=strtoupper(dechex(ord($c)));
			if (isset($transchars[$a])) {
				$a=$transchars[$a];
			} else if (ctype_alnum($c)){
				$a=$c;
			} else if (ctype_space($c)){
				$a='-';
			} else {
				$a='-';
			}
			$b.=$a;
		}
		return $b;
	}	
	
}
?>
