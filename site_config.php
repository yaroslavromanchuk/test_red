<?php
    define('APP_DIR', dirname(__FILE__) . '/application');
    define('WEBSHOP_PATH', 'Framework2.0/');
    $old_dr = $_SERVER['DOCUMENT_ROOT'];
    //if(!$_SERVER['DOCUMENT_ROOT'])
    $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
    define('INPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	//define('FORME', false);
   // define('FORME', (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '127.0.0.2', '91.225.165.62'))));
	//define('FORME', true);
    //define('LOCAL', $_SERVER['REMOTE_ADDR'] == '127.0.0.1');
	
    $sql_host = "localhost";
    $sql_user = "red_site_user";
    $sql_passwd = "hx2H6xQWjsqQcuVsss!";
    $sql_database = "red_site";

    //get current server
    //------------------------------------------
    $server = str_replace('www.', '', $_SERVER["HTTP_HOST"]);
    $url = str_replace($old_dr, '', $_SERVER['DOCUMENT_ROOT']);
    //define('SITE_URL', 'http://' . $server . $url );
    define('SITE_URL', '');

    $config_values = array('default_language' => 'ru', 'is_live' => 1, 'site_id' => '1');
	
	$site = array(
	'from_name' => 'RED.UA', // from (��) ���
	'from_email' => 'market@red.ua', // from (��) email �����
	'smtp_mode' => 'disabled', // enabled or disabled (������� ��� ��������)
	'smtp_host' => 'mail.red.org.ua',
	'smtp_port' => '25',
	'smtp_username' => null,
	'smtp_password' => null,
	'smtp_secure' => "tls" // ssl or tls 
	);
	$pop3 = array(
	'tval' => 10,
	'do_debug' =>'1',
	'host' => 'mail.red.ua',
	'port' => '993',
	'username' => 'php@red.ua',
	'password' => 'KpbEJWMBr0'
	);

	
	
	
    // define additional include paths
    set_include_path(
        $_SERVER['DOCUMENT_ROOT']
            //. PATH_SEPARATOR
            //.'../'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/Framework2.0'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/Framework2.0/libraries'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/Framework2.0/models'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/Framework2.0/packages'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/application/packages'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/application/libraries'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/application/models'
            . PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/application/controllers'
			. PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/application/languages'
			. PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/backend/models'
			. PATH_SEPARATOR
            . $_SERVER['DOCUMENT_ROOT'] . '/backend/controllers'
    //. PATH_SEPARATOR
    //. get_include_path()
    );

    $langs = array('ru' => 1, 'en' => 1, 'uk' => 1);

    //create substitution values for shop models
    $model_substitute = array(
        'block_class' => 'wsBlock',
        'block_type_class' => 'wsBlockType',
        'block_position_class' => 'wsBlockPosition',
        'brand_class' => 'wsBrand',
        'shopping_cart_item_class' => 'wsShoppingCartItem',
        'country_class' => 'wsCountry',
        'customer_class' => 'Customer',
        'customer_address_class' => 'wsCustomerAddress',
        'customer_machine_class' => 'CustomerMachine',
        'customer_status_class' => 'wsCustomerStatus',
        'customer_type_class' => 'wsCustomerType',
        'customer_visit_class' => 'CustomerVisit',
        'menu_class' => 'Menu',
        'product_class' => 'wsProduct',
        'product_category_class' => 'wsProductCategory',
        'product_property_class' => 'wsProductProperty',
        'product_property_value_class' => 'wsProductPropertyValue',
        'shopping_cart_class' => 'wsShoppingCart',
        'supplier_class' => 'wsSupplier',
        'price_class' => 'wsPrice',
        'favorite_list_class' => 'wsFavoriteList',
        'favorite_list_item_class' => 'wsFavoriteListItem',
        'right_class' => 'wsRight',
        'config_class' => 'Config',
        'language_class' => 'wsLanguage',
        'currency_class' => 'wsCurrency',
        'pricelist_class' => 'wsPricelist',
        'tax_rate_class' => 'wsTaxRate',
        'tax_type_class' => 'wsTaxType',
        'file_class' => 'wsFile',
        'file_type_class' => 'wsFileType',
        'product_status_class' => 'wsProductStatus',
        'product_type_class' => 'wsProductType',
        'distribution_center_class' => 'wsDistributionCenter',
        'stock_class' => 'wsStock',
        'order_class' => 'wsOrder',
        'order_status_class' => 'wsOrderStatus',
        'order_invoice_class' => 'wsOrderInvoice',
        'store_class' => 'wsStore',
        'payment_method_class' => 'wsPaymentMethod',
        'shipping_method_class' => 'wsShippingMethod',
        'order_item_class' => 'wsOrderItem',
        'order_log_class' => 'wsOrderLog',
        'order_item_status_class' => 'wsOrderItemStatus',
        'order_payment_class' => 'wsOrderPayment',
        'order_payment_status_class' => 'wsOrderPaymentStatus',
        'site_class' => 'Site',
        'promotion_class' => 'wsPromotion',
        'promotion_type_class' => 'wsPromotionType',
        'log_search_class' => 'wsLogSearch',
        'menu_filter_class' => 'wsMenuFilter',
        'menu_type_class' => 'wsMenuType',
        'file_size_class' => 'wsFileSize',
        'log_class' => 'wsLog',
        'shop_categories_class' => 'Shopcategories',
        'shop_articles_class' => 'Shoparticles',
        'shop_articles_top_class' => 'Shoparticlestop',
        'shop_articles_options_class' => 'Shoparticlesoptions',
        'shop_articles_offer_class' => 'Shoparticlesoffer',
        'shop_orders_class' => 'Shoporders',
        'shop_order_articles_class' => 'Shoporderarticles',
        'shop_order_remarks_class' => 'Shoporderremarks'
    );

    foreach ($model_substitute as $key => $value){
    define(strtoupper($key), $value);
    
    }
    define('DB_SUFFIX', '');
    define('PDO', 0);
