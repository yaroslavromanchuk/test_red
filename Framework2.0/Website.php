<?php
class Website
{
        protected $_customer;
	protected static $_language;
	protected static $_site;
    
    public function __construct()
    {
    	// set customer for current visit
    	$this->getCustomer();
    }
    /**
     * 
     * @return type
     */
    public function getCustomer()
    {		
        if ($this->_customer){ return $this->_customer;}

        $customer = null;
        // fetch session hash, or default to null
        $hashVisit = (isset($_SESSION['v']) ? $_SESSION['v'] : null);
        // fetch machine hash, or default to null
        $hashMachine = (isset($_COOKIE['m']) ? $_COOKIE['m'] : null);
        // fetch user hash, or default to null
        $hashUser = (isset($_COOKIE['u']) ? $_COOKIE['u'] : null);
		//$Visit = (isset($_COOKIE['d']) ? $_COOKIE['d'] : null);
		//if($_COOKIE['visit'] == false) $_COOKIE['visit'] = date();
                //
        // define extra info array
	$arrExtraInfo = [
                    'url' => ( 
                                isset($_SERVER['HTTP_HOST']) //  not be set
                                ? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] /* !!!!! GET PORT & HTTP(S) !!!!!*/
                                : ''
                    ),
                    'referrer' => ( 
                            isset($_SERVER['HTTP_REFERER']) // referrer might not be set
                            ? $_SERVER['HTTP_REFERER']
                            : '' // if not, default to an empty string
                            ),
                    'ip' => $_SERVER['REMOTE_ADDR']
                        ];

        $languageId = $this->getLanguageId();
        // check for osadcampaign
        if(isset($_GET['osadcampaign']))
        {
            $osAdCampaign = $_GET['osadcampaign'];
        }
        else
        {
            $osAdCampaign = '';
        }


        if(isset($_GET['debuglevel']) && is_numeric($_GET['debuglevel']))
        {
            $debugLevel = $_GET['debuglevel'];
        }
        else
        {
            $debugLevel = false;//wsConfig::findByCode('debug_level')->getValue();
        }

        if (!$hashVisit)
        {
            $hashVisit = self::generateHash('V');
            
        }

        if (!$hashMachine)
        {
            $hashMachine = self::generateHash('M');
            
        }

        if (!$customerVisit = CustomerVisit::findByHash($hashVisit))
        {
            $customerVisit = new CustomerVisit();
            
        }

        if (!$customerMachine = CustomerMachine::findByHash($hashMachine))
        {
            $customerMachine = new CustomerMachine();
        }

        if ($hashUser)
        {
            $customer = Customer::findByHash($hashUser);
        }

        if (!$customer && !$customerVisit->isNew())
        {
            $customer = $customerVisit->getCustomer();
        }

        if (!$customer)
        {
            $hashUser = null;
            $customer = new Customer();
            $customer->setCustomerTypeId(wsCustomerType::getDefault()->getId());
        }else{
            $customer->setIsLoggedIn(true);
            
        }
        
        // set hashes to current user
        $customer->setHashUser($hashUser);
        $customer->setHashVisit($hashVisit);
        $customer->setHashMachine($hashMachine);
        $customer->setCampaignname($osAdCampaign ? $osAdCampaign : $customerVisit->getCampaignname());
        // update vist and machine data
        if ($customerVisit->isNew())
        {
            //$customerVisit->setTimeCreated(date("Y-m-d H:i:s"));
            $customerVisit->setStartUrl($arrExtraInfo['url']);
            $customerVisit->setReferrerUrl($arrExtraInfo['referrer']);
        }
        //$customerVisit->setSiteId($siteId);
        //$customerVisit->setLangId($languageId);
        $customerVisit->setHashId($hashVisit);
        //$customerVisit->setTimeLastPage(date("Y-m-d H:i:s"));
        $customerVisit->setTotalNumberOfPages($customerVisit->getTotalNumberOfPages() + 1);//количество просмотреных страниц в текущей сесии
        //$customerVisit->setCampaignname($customer->getCampaignname());
        //$customerVisit->setOrderAmount(0);
        //$customerVisit->setOrderCurrencyId(0);
        $customerVisit->setDurationInMinutes(round((time() - ($customerVisit->getCtime() ? $customerVisit->getCtime() : time())) / 60, 0));
        $customerVisit->setEndUrl($arrExtraInfo['url']);
        if ($debugLevel && $debugLevel != $customerVisit->getLoglevel()){ $customerVisit->setLoglevel($debugLevel); }
        //$customerVisit->save();

        if ($customerMachine->isNew())
        {
            $customerMachine->setHashId($hashMachine);
            //$customerMachine->setVisitCreatedId($customerVisit->getId());
            $customerMachine->setVisitCreated($customerVisit);
            $customerMachine->setIpCreated($arrExtraInfo['ip']);
            //$customerMachine->setTimeCreated(date("Y-m-d H:i:s"));
            $customerMachine->setHostnameCreated($arrExtraInfo['url']);
            $customerMachine->setTotalNumberOfVisits(1);
        }
        
        $customerMachine->setTotalNumberOfVisits(
            ($customerMachine->getVisitLastId() && $customerMachine->getVisitLastId() != $customerVisit->getId()) ?
            $customerMachine->getTotalNumberOfVisits() + 1 : $customerMachine->getTotalNumberOfVisits()
            );
        //$customerMachine->setSiteId($siteId);
        $customerMachine->setLangId($languageId);
        $customerMachine->setVisitLast($customerVisit);
        //$customerMachine->setTimeLastVisit(date("Y-m-d H:i:s"));
        $customerMachine->setIpLastVisit($arrExtraInfo['ip']);
        $customerMachine->setHostnameLastVisit($arrExtraInfo['url']);
        $customerMachine->save();
       // $customerVisit->save();

        //$customer->setTimeLastVisit(date("Y-m-d H:i:s"));
	    $customer->setMachineLastVisit($customerMachine);
	    $customer->setVisitLast($customerVisit);
            
	    if ($customer->getVisitLastId() != $customerVisit->getId()){ $customer->setTotalNumberOfVisits($customer->getTotalNumberOfVisits() + 1); }
	    if (!$customer->getMachineCreatedId()){$customer->setMachineCreated($customerMachine);}
	    if (!$customer->getVisitCreatedId()){$customer->setVisitCreated($customerVisit);}

        if (!$customer->isNew())
        {
        	$customer->addToVisits($customerVisit);
            $customer->save();
        }

        $customerVisit->setMachine($customerMachine);
        
        $customerVisit->save();
		

        $this->_customer = $customer;

        $this->updateHashes();

        return $this->_customer;
    }

    /**
     * 
     */
    public function updateHashes()
    {
        $customer = $this->_customer;
        // set session hash id
        $_SESSION['v'] = $customer->getHashVisit();
        setcookie('hash', $customer->getHashUser(), strtotime('+5 day'),'/');
        // expiry time (+1 year from now)
        $expiryTimes['u'] = strtotime('+1 year');
        $expiryTimes['m'] = strtotime('+1 year');
	$expiryTimes['d'] = strtotime('+1 year');
	$expiryTimes['s'] = strtotime('+1 day');
        
        // set the cookie values to false if they should be deleted
        if (!$customer->getHashUser())
        {
            $customer->setHashUser(null);
            $expiryTimes['u'] = strtotime('-1 year');
        }
        if (!$customer->getHashMachine())
        {
            $customer->setHashMachine(null);
            $expiryTimes['m'] = strtotime('-1 year');
        }
        
        // set user cookie
        setcookie('u', $customer->getHashUser(), $expiryTimes['u'],'/');

        // set machine cookie
        setcookie('m', $customer->getHashMachine(), $expiryTimes['m'],'/');
	// set date visit cookie
	if(!isset($_COOKIE['d'])) { setcookie('d',  $this->generateDate(date("Y-m-d")), $expiryTimes['d'],'/'); }
	// proverca poslednego visita and set new date visit
	if(isset($_COOKIE['d']) and $_COOKIE['d'] !=  $this->generateDate(date("Y-m-d"))) {
	setcookie('s', $_COOKIE['d'], $expiryTimes['s'],'/');
	setcookie('d',  $this->generateDate(date("Y-m-d")), $expiryTimes['d'],'/');
	}
    }
	
	static public function getSite()
	{
		if(!self::$_site)
                {
                    self::$_site = Site::getThisSite($_SERVER['HTTP_HOST']);
                    
                }

		if(!self::$_site)
                {
                    self::$_site = Site::getDefault();
                    
                }

		return self::$_site;
	}
	
	public function getLanguageId()
	{
		if (!self::$_language)
                {
                    self::$_language = Registry::get('lang_id')?Registry::get('lang_id'):1;//wsLanguage::getDefaultLang()->getId();
                    
                }
	
		return self::$_language;
	}	
	
	public function getCacheSuffix($params = '')
	{
		return '_site' . $this->getSite()->getId() . '_lng' . $this->getLanguageId() . $params;
	}

    static function generateHash($hashType)
    {
        return md5(uniqid(mt_rand(),true));
    }
    
	public function generateDate($dat)
    {
	$newstr = '';
	$key = 'coderedua';
	$string=base64_encode($dat);// base64
$arr=array();
$x=0;
while ($x++< strlen($string)) {
$arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//
$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//
}
	return $newstr;
    }
}

