<?php

class Shoparticles extends wsActiveRecord
{
    protected $_table = 'ws_articles';
    protected $_orderby = array('sequence' => 'DESC');

    protected $_multilang = ['model' => 'model', 'long_text' => 'long_text', 'sostav'=>'sostav'];

    protected function _defineRelations()
    {
        $this->_relations = array(
	'category' => array(
            'type' => 'hasOne',
            'class' => self::$_shop_categories_class,
            'field' => 'category_id'),
        'color_name' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticlescolor',
                'field' => 'color_id'),
        'sizes' => array(
                'type' => 'hasMany',
                'class' => 'Shoparticlessize',
                'field_foreign' => 'id_article',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'),
        'images' => array(
                'type' => 'hasMany',
                'class' => 'Shoparticlesimage',
                'field_foreign' => 'article_id',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'),
        'offers' => array(
                'type' => 'hasMany',
                'class' => self::$_shop_articles_offer_class,
                'field_foreign' => 'article_id',
                'orderby' => array('id' => 'ASC')),
       'article_brand' => array(
                'type' => 'hasOne',
                'class' => 'Brand',
                'field' => 'brand_id'),
        'label' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticleslabel',
                'field' => 'label_id'),
	'name_status' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticlesstatus',
                'field' => 'status'),
	'name_sezon' => array(
                'type' => 'hasOne',
                'class' => 'Shoparticlessezon',
                'field' => 'sezon'),
        'desires' => [
                'type' => 'hasMany',
                'class' => 'Desires',
                'field_foreign' => 'id_articles',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'],
        'models' =>[
                'type' => 'hasOne',
                'class' => 'Shoparticlesmodel',
                'field' => 'model_id'
            ]
        );
    }
	
    
        public function getTop(){
            $date = date("Y-m-d H:i:s");
          return  wsActiveRecord::useStatic('Shoparticlestop')->findAll([" ctime <= '$date' ", " '$date' <= utime ", 'article_id'=>$this->getId()], ['id'=>'DESC'], ['limit'=>' 0, 1 ']);
            
        }
	/**
         * @return false - товар не участвует в акции
         * или
         * @return параметры акции в которой учавствует товар
         */
	public function getOptions()
	{
           if (!$this->getSkidkaBlock()) { 
	$dat = date('Y-m-d');
	$sql ="SELECT  `ws_articles_option`. * 
FROM  `ws_articles_option` 
JOIN  `ws_articles_options` ON  `ws_articles_option`.`id` =  `ws_articles_options`.`option_id` 
WHERE  `ws_articles_option`.`status` = 1
AND  `ws_articles_option`.`start` <=  '$dat'
AND  `ws_articles_option`.`end` >=  '$dat'
AND (
 `ws_articles_options`.`article_id` = $this->id
OR  `ws_articles_options`.`category_id` = $this->category_id
OR  `ws_articles_options`.`brand_id` = $this->brand_id
)";
	$option = (object)wsActiveRecord::findByQueryFirstArray($sql);

	if ($option){ 
            return $option;
        }
           }
	
	return false;
	}
    /**
     * формирование ссылки на карточку товара
     * @return url "/product/id/(id-товара)/(название товра)"
     */  
    public function getPath()
            {
        $lang = '';
        if(Registry::get('lang') == 'uk'){ $lang = '/uk';}
        return $lang.'/product/id/' . $this->getId() . '/' . $this->_generateUrl($this->model.' '.$this->brand); //model -> getTitle()
        
            }

    public function getDiscount()
    { 
        if (!(int)$this->getOldPrice() || !(int)$this->getPrice()){return 0;}
        
        return 100 - round($this->getPrice() / $this->getOldPrice() * 100);
    }

    static public function getListBrands($category = null, $order = 0)
    {
        $orderbuy = '';
        if ($order) { $orderbuy = 'ORDER BY brand'; }
        $category_text = $category ? ((mb_strpos(mb_strtolower($category->getName()), 'new') !== false)
            ? ' AND new = 1 ' : ' AND category_id IN ( ' . implode(', ', $category->getKidsIds()) . ')') : '';
        $q = "SELECT
				brand,
				count(DISTINCT(ws_articles.id)) AS cnt
			FROM
				ws_articles_sizes
				JOIN ws_articles
				ON ws_articles_sizes.id_article = ws_articles.id
			WHERE
				ws_articles_sizes.count > 0
				AND ws_articles.active = 'y'
				$category_text
            GROUP BY
				brand $orderbuy ";
        return wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
    }

    static public function getListPrices($category = null)
    {
        $category_text = $category ? ((mb_strpos(mb_strtolower($category->getName()), 'new') !== false)
            ? ' AND new = 1 ' : ' AND category_id IN ( ' . implode(', ', $category->getKidsIds()) . ')') : '';
        //$q = "SELECT ceil(price/100)*100 AS round_price, count(*) AS cnt FROM ws_articles WHERE stock>0 $category_text AND active='y' GROUP BY round_price";
        $q = "
			SELECT
				ceil(price/100)*100 AS round_price,
				count(*) AS cnt
			FROM
				ws_articles_sizes
				JOIN ws_articles
				ON ws_articles_sizes.id_article = ws_articles.id
			WHERE
				ws_articles_sizes.count > 0
				AND ws_articles.active = 'y'
				$category_text
			GROUP BY
				round_price;
		";

        return wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
    }

    static public function getListColors($category = null)
    {
        $category_text = $category ? ((mb_strpos(mb_strtolower($category->getName()), 'new') !== false)
            ? ' AND ws_articles.new = 1 '
            : ' AND ws_articles.category_id IN ( ' . implode(', ', $category->getKidsIds()) . ')') : '';
        //$category_text = $category ? ' AND ws_articles.category_id = ' . (int)$category->getId() : '';
        $q = "SELECT ws_articles_colors.id as color_id, ws_articles_colors.name as color,
					count(ws_articles_sizes.id_article) AS cnt
				FROM ws_articles_colors INNER JOIN ws_articles_sizes ON ws_articles_colors.id = ws_articles_sizes.id_color
					 INNER JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
				WHERE ws_articles_sizes.count > 0 and ws_articles.active='y' $category_text
				GROUP BY ws_articles_colors.name
				ORDER BY ws_articles_colors.sequence ASC		
		";
        return wsActiveRecord::useStatic('Shoparticlescolor')->findByQuery($q);
    }

    static public function getListSizes($category = null)
    {
        $category_text = $category ? ((mb_strpos(mb_strtolower($category->getName()), 'new') !== false)
            ? ' AND ws_articles.new = 1 '
            : ' AND ws_articles.category_id IN ( ' . implode(', ', $category->getKidsIds()) . ')') : '';
        //$category_text = $category ? ' AND ws_articles.category_id = ' . (int)$category->getId() : '';
        $q = "SELECT ws_sizes.id as size_id, ws_sizes.size as name,
					count(ws_articles_sizes.id_article) AS cnt
				FROM ws_sizes INNER JOIN ws_articles_sizes ON ws_sizes.id = ws_articles_sizes.id_size
					 INNER JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
				WHERE ws_articles_sizes.count > 0 and ws_articles.active='y' $category_text
				GROUP BY ws_sizes.id
				ORDER BY ws_sizes.sequence ASC		
		";
        return wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q);
    }

    static public function getArticlesByArticul($articul = null)
    {
        $q = "SELECT ws_articles.*
					FROM ws_articles_sizes INNER JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
					WHERE ws_articles_sizes.code LIKE  '%$articul%' 
					ORDER BY  `ws_articles_sizes`.`utime` DESC 
		";
        return wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
    }

    static public function getSearchPath($raw = array())
    {
        $data = [];

        if ($raw['brand'])
        {
            $data[] = 'brand/' . urlencode(str_replace('&', '_', $raw['brand']));  
        }
        if ($raw['price'])
        {
            $data[] = 'price/' . (int)$raw['price'];   
        }
        if ($raw['color'])
        {
            $data[] = 'color/' . (int)$raw['color'];  
        }
        if ($raw['size'])
        {
            $data[] = 'size/' . (int)$raw['size'];
        }
        if ($raw['s'])
        {
            $data[] = 's/' . $raw['s'];  
        }
        if ($raw['sort'])
        { 
            $data[] = 'sort/' . $raw['sort'];
        }
        if ($raw['category'])
        {
            return '/category/id/' . $raw['category']->getId() . '/' . implode('/', $data) . '/';
        }else{
            return '/search/' . implode('/', $data) . '/';
        }
    }

    public function findLastSequenceRecord()
    {
        return $this->findFirst(array(), array('sequence' => 'DESC'));
    }

    public function deleteCurImages()
    {
        $this->unlink_file();
        /*$filename = INPATH."files/i1/".$this->getImage();
          if (is_file($filename))
              @unlink($filename);
          $filename = INPATH."files/i2/".$this->getImage();
          if (is_file($filename))
              @unlink($filename);
          $filename = INPATH."files/i3/".$this->getImage();
          if (is_file($filename))
              @unlink($filename);*/
    }

    public function deleteCurPdf()
    {
        $filename = INPATH . "files/pdf/" . $this->getPdf();
        if (is_file($filename))
        {
            unlink($filename);
        }
    }

    public function getTitle()
    {
        return $this->getBrand() . ' (' . $this->getModel() . ')';
    }

    public function getRealPrice($price = false)
    {
       // $tmp = ($price === false) ? ((($offer = $this->getOffer()) && $offer->getId()) ? $offer->getPrice() : $this->getPrice()) : $price;
	   $tmp = ($price === false) ?  $this->getPrice() : $price;
        return $tmp;
    }
	public function getFirstPrice(){
	return ($this->getOldPrice() > 0)?$this->getOldPrice():$this->getPrice();
	}

    public function getPriceSkidka()//цена товара с доп скидкой
    {
    /*  $s = Skidki::getActiv($this->getId());
		$z = false;// Skidki::getActivCat($this->getCategoryId());
        if($z){
		  return $this->getRealPrice() * ((100 - $z->getValue()) / 100);
		}elseif($s){
            return $this->getRealPrice() * ((100 - $s->getValue()) / 100);
        }*/
		
		return $this->getRealPrice();
    }

    public function getRealPriceExBTW($price = false)
    {
        $btw = BTW / 100;
        $tmp = ($price === false) ? $this->getRealPrice() : $price;
        return $tmp / (1 + $btw);
    }

    public function getImagePath($type = 1)
    {
        switch ($type) {
            case 'small_basket':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/36/height/36/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            case 'homepage':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/396/height/365/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            case 'listing':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/155/height/155/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            case 'detail':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/360/height/360/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            case 'small_preview':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/800/height/600/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            case 'card_product':
                return SITE_URL . Mimeg::getrealpath('/mimage/type/1/width/600/height/600/crop/false/fill/true/fill_color/255_255_255/filename/' . $this->getImage());
            default:
                return SITE_URL . Mimeg::getrealpath('/mimage/original/1/filename/' . $this->getImage());
        }
        //return SITE_URL . '/files/i' . $type . '/' . $this->getImage();
    }

    public function _beforeDelete(){
        $folder = $_SERVER['DOCUMENT_ROOT'] . '/files/org/';
        $name = explode('.', $this->getImage());
        if (file_exists($folder . $name[0] . '_w155_h132_cf_ft_fc255_255_255.' . $name[1])){unlink($folder . $name[0] . '_w155_h132_cf_ft_fc255_255_255.' . $name[1]);}
        if (file_exists($folder . $name[0] . '_w70_h70_cf_ft_fc255_255_255.' . $name[1])){unlink($folder . $name[0] . '_w70_h70_cf_ft_fc255_255_255.' . $name[1]);}
        if (file_exists($folder . $name[0] . '_w155_h155_cf_ft_fc255_255_255.' . $name[1])){unlink($folder . $name[0] . '_w155_h155_cf_ft_fc255_255_255.' . $name[1]);}
        if(file_exists($folder . $this->getImage())){ unlink($folder . $this->getImage()); }
        
        return true;
    }


    public function getPdfPath(){
        return SITE_URL . '/files/pdf/' . $this->getPdf();
    }

    public function getPdfFileSize($del = 1){
        $file = INPATH . '/files/pdf/' . $this->getPdf();
        $size = ($this->getPdf() && file_exists($file)) ? filesize($file) : 0;
        return (int)($size / $del);
    }

    public function getSArticles(){
        $sa = new Orm_Collection();
        if ($this->sug_article_id_1 && ($a = wsActiveRecord::useStatic('Shoparticles')->findById($this->sug_article_id_1)) && $a->getId() && strcasecmp($a->getActive(), 'y') == 0)
        {$sa->add($a);}
        if ($this->sug_article_id_2 && ($a = wsActiveRecord::useStatic('Shoparticles')->findById($this->sug_article_id_2)) && $a->getId() && strcasecmp($a->getActive(), 'y') == 0)
        { $sa->add($a);}
        if ($this->sug_article_id_3 && ($a = wsActiveRecord::useStatic('Shoparticles')->findById($this->sug_article_id_3)) && $a->getId() && strcasecmp($a->getActive(), 'y') == 0)
        {$sa->add($a);}
        return $sa;
    }

   /* public function getRealOptions($delfirst = false){
        $tmp = parent::__call('getOptions', null);
        if (isset($tmp[0]))
            $tmp[0]->setNumber(1);
        if (isset($tmp[1]))
            $tmp[1]->setNumber(2);
        if ($delfirst && isset($tmp[0]))
            $tmp->del(0);
        return $tmp;
    }*/

    /**
     * 
     * @param type $count - количество товара, поумолчанию = 1
     * @param type $size - id розмера
     * @param type $color - id цвета
     * @param type $option_id - id акции на скидку
     * @param type $flag -  1 = быстрый заказ, корзина очищается, поумолчанию = 0
     * @param type $art - артикул товара
     * @param type $skidka_block - если заблокирован от скидок = 1, поумолчанию = 0
     * @return boolean
     */
    public function addToBasket($size, $color, $art, $flag = 0){
        $res = [];
        
        if (strcasecmp($this->getActive(), 'y') != 0) {
            $res['status'] = false;
            $res['message'] = 'Этот товар еще не активен! Попробуйте заказать пожже.';
            return $res;
            }
    $basket = & $_SESSION['basket'];

	if($flag == 1) { $basket = []; } //если 1, очистить корзину, это быстрый заказ
        
        $was_added = false;
        $count_card = 0;
        foreach ($basket as $key => $item){
            if (!$was_added && $item['article_id'] == $this->getId() && $item['size'] == $size && $item['color'] == $color) {
                    //$basket[$key]['count'] += $count;
                    $was_added = true;
            $res['status'] = false;
            $res['message'] = Translator::get('error_add_card_article');
            }
            $count_card += $item['count'];
			}
                        
    if (!$was_added) {
        $price = $this->getPrice();
        $option_id = 0;
        $option_price = 0;
        
        if($this->getOptions()){
                    switch ($this->getOptions()->type){
                        case 'final':
                         $option_id = $this->getOptions()->id;
                         $option_price = $price - ($price * ($this->getOptions()->value/100));  
                           break;
                        case 'dop':
                            $option_id = $this->getOptions()->id;
                            break;
                        default: $option_id = 0; break;
                    }
                    
                }
            $basket[] = [
                'article_id' => $this->getId(),
                'price' => $this->getRealPrice(),
                'count' => 1,//$count,
                'option_id' => $option_id,
                'option_price' => $option_price,
                'size' => $size,
                'color' => $color,
		'artikul' => $art,
		'category' =>$this->getCategoryId(),
		'skidka_block' =>$this->getSkidkaBlock()
            ];
            $res['status'] = true;
            $res['count_card'] = $count_card+1;
            return $res;
       }else{
           return $res;
       }
        return true;
    }

    public function getOffer(){
        $tmp = $this->getOffers();
        if ($tmp && $tmp->count()){return $tmp[0];}
        else
        { return null;}
    }

    static public function showPrice($price){
        return number_format((double)$price, 2, ',', '');
    }

    static public function showPriceBTW($price){
        $btw = BTW / 100;
        $tmp = ($price * $btw) / (1 + $btw);
        return self::showPrice($tmp);
    }

    static public function showPriceExBTW($price){
        $btw = BTW / 100;
        $tmp = $price / (1 + $btw);
        return self::showPrice($tmp);
    }

    public function getSystemPath($type = NULL, $filename = NULL){
        if ($type === NULL)
        {$type = $this->type;}
        if ($filename === NULL)
        {$filename = $this->filename;}

        $path = '';
        switch (strtolower($type)) {
            case 1:
            case 2:
            case 3: $path = INPATH . "files/i" . ((int)$type) . "/{$filename}"; break;

            default: $path = INPATH . "files/org/{$filename}";
                if (!is_file($path))
                {$path = INPATH . "files/i3/{$filename}";}
				break;
        }

        return $path;
    }

    public function unlink_file($type = NULL, $filename = NULL)
            {
        if ($type === NULL)
        { $type = $this->type;}
        if ($filename === NULL)
        {$filename = $this->filename;}
        switch ($type) {
            default:
                $file = $this->getSystemPath($type, $filename);
                $filename_wout_ext = substr($file, 0, strrpos($file, '.'));
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (is_file($file) && $filename_wout_ext) {
                    $files = glob("{$filename_wout_ext}*.{$ext}");
                    if (strtolower($ext) == 'flv')
                        $files = array_merge($files, glob("{$filename_wout_ext}*.jpeg"));
                    if ($files)
                        foreach ($files as $file_)
                            if (is_file($file_))
                                @unlink($file_);
                }
                break;
        }
    }

    public function getProcent($all_orders_amount, $skidka = 0)
            {
        if ($skidka != 0) {
            return $skidka . '%';
        }
        if ($all_orders_amount <= 700) {
            return '0,00%';
        } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
            return '5,00%';
        } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
            return '10,00%';
        } elseif ($all_orders_amount > 12000) { //15%
            return '15,00%';
        }

    }
    
    /**
     * @Shoparticles::getPerc() - стоимость товара
     * 
     * @param type $all_orders_amount - сумма всех придведущих заказов
     * @param type $count - количество товара в корзине
     * @param type $skidka - скидка клиента
     * @param type $event_skidka - дополнительная скидка
     * @param type $kupon - промокод
     * @param type $sum_order - сумма текущего заказа
     * 
     * @return array ['option_id','minus','price','option_price','comment','skidka', 'skidka_block']
     * option_id - ид акции в которой учавствует товар
     * option_price - стоимость товара по условиям акции
     * minus - сумма скидки на товар
     * price - цена товара за которую покупает клиент
     * comment - коментарий к товару
     * skidka - процент скидки на данный товар
     * skidka_block - уведомляет что этот товар заблокироован на скидки
     */
    public function getPerc($all_orders_amount, $count = 1, $skidka = 0, $event_skidka = 0, $kupon = '', $sum_order = 0)
    {
        /*
        * сумма скидки на товар
        */
        $minus = 0.00;
        /*
         * стоимость товара в заказе
         */
        $price = $this->getPrice() * $count;
        /**
         * коментарий к товару
         */
	$coment = '';
        /**
        * скидка на товар %
        */
	$pr_skidka = 0;
        /**
         * доп. скидка
         * @$dop_ck
         */
        $dop_ck  = $event_skidka;
	
            if (!$this->getSkidkaBlock()) {   
                if($this->getOptions()){
                    switch ($this->getOptions()->type){
                        
                        case 'final':
                         $mas['option_id'] = $this->getOptions()->id;
                         $mas['minus'] = $price * ($this->getOptions()->value/100);
                         $mas['price'] = ($price - $mas['minus']);
                         $mas['option_price'] = $mas['price'];  
                         $mas['comment'] = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;font-size: 10px;">'.$this->getOptions()->option_text.'</div>';
                            return $mas;
                        case 'dop':
                            $mas['option_id'] = $this->getOptions()->id; 
                            $dop_ck = $this->getOptions()->value;
                            $coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;font-size: 10px;">'.$this->getOptions()->option_text.'</div>';
                            break;
                    }
                    
                }
		$kod = false;
		
	if($kupon !=''){
	$kod = wsActiveRecord::useStatic('Other')->findFirst(array("cod"=>$kupon));
	if($kod->count_order){ // esli est ogranichenie po koll zakazov
	$k = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $this->ws->getCustomer()->getId(), "kupon LIKE  '".$kod->cod."' ") );
	if($k){
	if((int)$k >= (int)$kod->count_order){
	$kod = false;
	$coment = '<div class="alert alert-danger" style="padding: 5px;margin-top: 10px;">Вами превышен лимит использования этого промокода.</div>';
	}
	}

	}
	if($kod->category_id and $kod->category_id != $this->category_id){ // kod deistvuet na opredelennu kategoriyu 
	$kod = false;
	$coment = '<div class="alert alert-danger" style="padding: 5px;margin-top: 10px;">На этот товар промокод не распространяется.</div>';
	}
	}
		
                if ((int)$this->getOldPrice() == 0) {
                    if ($skidka != 0) {
                        $minus = (($price / 100) * ($skidka + $dop_ck));
                        $price -= $minus;
						$pr_skidka +=$skidka+$dop_ck;
						//
					if($kod and $kod->new_cust_plus == 1){
						if($sum_order >= $kod->min_sum){
						$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						$coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка по промокоду.</div>';
						$pr_skidka += $kod->skidka;
						//kupon
										}
							}
//
                    }else{
                        if ($all_orders_amount <= 700) {
							$minus = (($price / 100) * $dop_ck);
                            $price -= $minus;
							$pr_skidka += $dop_ck;
                        } elseif ($all_orders_amount > 700 && $all_orders_amount <= 5000) { //5%
                            $minus = (($price / 100) * (5+$dop_ck));
                            $price -= $minus;
							$pr_skidka += 5+$dop_ck;
                        } elseif ($all_orders_amount > 5000 && $all_orders_amount <= 12000) { //10%
                            $minus = (($price / 100) * (10+$dop_ck));
                            $price -= $minus;
							$pr_skidka += 10+$dop_ck;
                        } elseif ($all_orders_amount > 12000) { //15%
                            $minus = (($price / 100) * (15+$dop_ck));
                            $price -= $minus;
							$pr_skidka += 15+$dop_ck;
                        }
						//
					if($kod and $kod->new_sum_plus == 1){
					if($sum_order >= $kod->min_sum){
					$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						$coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка по промокоду.</div>';
						$pr_skidka += $kod->skidka;
						//kupon
}
}
//
						
                    }
                }else{
				$minus += ($this->getOldPrice() - $this->getPrice());

                        if($dop_ck){
					$m = (($price / 100) * $dop_ck);
						$minus += $m;
						$price -= $m;
						//$coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка по промокоду.</div>';
						$pr_skidka += $dop_ck;
					}
				
				//kupon
			if($kod and $kod->ucenka == 1 and $sum_order >= $kod->min_sum){
				$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
						$coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка по промокоду.</div>';
						$pr_skidka += $kod->skidka;
						//kupon
			}
//
 
                }

					if($kod and $kod->all == 1 and $sum_order >= $kod->min_sum){
						$m = (($price / 100) * $kod->skidka);
						$minus += $m;
						$price -= $m;
				$coment = '<div class="alert alert-info" style="padding: 5px;margin-top: 10px;">На этот товар действует скидка по промокоду.</div>';
				$pr_skidka += $kod->skidka;
						//kupon
					}
		
				
     
			
		}else{
		$mas['skidka_block'] = 1;
		}
        $mas['price'] = $price; 
        $mas['minus'] = $minus;
		$mas['comment'] = $coment;
		$mas['skidka'] = $pr_skidka;
		
        return $mas;
    }
	

    static public function getSimilar($id)
    {
        $params['dataset'] = 'red_views';
        $params['values'] = array($id);
        $params['support'] = 10; //% from item occurance
        $params_url = http_build_query($params);

        //cURL HTTPS POST
        $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "http://shop.webunion.kiev.ua/api/assoc/a/find/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        $result = json_decode($response);
        curl_close($ch);
        $ads = array();
        if ($result->error == 0 && is_array($result->data)) {
            foreach ($result->data as $r) {
                if (is_array($r)) {
                    foreach ($r as $k) {
                        $ads[] = $k;
                    }
                }
            }
            if (count($ads)) {

                $where = "
                  FROM ws_articles_sizes
                  JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                  WHERE ws_articles_sizes.count > 0 AND ws_articles.active = 'y' and ws_articles.id IN  (" . implode(',', $ads) . ")
                 ";

                $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' . $where . ' ORDER BY ctime DESC LIMIT 10';
                return wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
            }else{
            }
        }else{
            return '';
        }
    }

    public function getSmallBlockCachedHtml($rewrite = true, $page = false)
            {
        $cache = Registry::get('cache');
	$cache->setEnabled(true);
        $view = Registry::get('View');
        $cache_name = 'one_product_item_' . $this->getId() . '_REDUA_site_'.$_SESSION['lang'];
        $article_item = $cache->load($cache_name);
        if (!$article_item || $rewrite) {
            //prepare to view
            $view->article = $this;
            $label = false;
			if($this->getLabelId() != 0)
                            {
                                $label = $this->label->getImage();
                            }
            $view->label = $label;

                                $article_item = $view->render('/cache/small_item_block.tpl.php');

            $cache->save($article_item, $cache_name, array($cache_name), false);
       }
        return $article_item;
    }

    public function _afterSave()
    {
        $this->getCachedHtml(true);
        return true;
    }

    public function ArtycleBuyCount()
    {
        return wsActiveRecord::useStatic('Shoporderarticles')->count(array('article_id' => $this->getId()));
    }

    public function getSpecNakl()
    {
	if($this->getSkidkaBlock() == 1){
       if($this->getOldPrice() != 0 and $this->getLabelId() != 21){
	   $this->setLabelId(21);
       $this->save();
       $this->getSmallBlockCachedHtml(true);
	   }
	   return true;
	   }
	   	 $item_time = strtotime($this->getCtime());
        $day = (time() - $item_time) / (24 * 60 * 60);
		//$day = date("Y-m-d", strtotime("- 6 days", strtotime($this->getDataNew())));
        if ($day <= 5 and $this->getOldPrice() == 0){
		if($this->getLabelId() != 13){
                $this->setLabelId(13);
                $this->save();
                $this->getSmallBlockCachedHtml(true);
				}
				return true;
        }
		
	   if ($this->getLabelId() != 19) {
            $day = date('Y-m-d', (strtotime($this->getCtime()) + (24 * 60 * 60)));
            if ($day == date('Y-m-d')) {
                $q = 'SELECT SUM(ws_order_articles.count) as counti FROM ws_order_articles
               JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
               WHERE ws_order_articles.article_id = ' . $this->getId() . ' AND ws_orders.date_create > "' . $day . ' 00:00:00" AND ws_orders.date_create < "' . $day . ' 23:59:59"';
                $res = wsActiveRecord::useStatic('Shoporderarticles')->findByQueryArray($q)[0]->counti;
                $count = @$res;//->at(0)->counti;
                if ($count > 4) {
                    $this->setLabelId(19);
                    $this->save();
                    $this->getSmallBlockCachedHtml(true);
					return true;
                }
            }
        }

        if ($this->getLabelId() != 16) {
            if ($this->getOldPrice() and ($this->getOldPrice() > $this->getPrice())) {
                $this->setLabelId(16);
                $this->save();
                $this->getSmallBlockCachedHtml(true);
            }
        }

        if ($this->getLabelId() != 18 and $this->getLabelId() != 20) {
            $q = 'SELECT SUM(ws_articles_sizes.count) as counti FROM ws_articles_sizes
                    WHERE ws_articles_sizes.id_article =' . $this->getId();
            $res = wsActiveRecord::useStatic('Shoparticlessize')->findByQueryArray($q)[0]->counti;
            $count = @$res;//->at(0)->counti;
            if ($count == 1) {
                $cat_par = $this->category->getParents(1);
                $find = 0;
                foreach ($cat_par as $par) {
                    if ($par->getId() == 33) {
                        $find = 1;
                    }
                }
                if ($find) {
                    $this->setLabelId(18);
                } else {
                    $this->setLabelId(20);
                }
                $this->save();
                $this->getSmallBlockCachedHtml(true);
				return true;

            }
        }elseif($this->getLabelId() == 18 or $this->getLabelId() == 20){
		$q = 'SELECT SUM(ws_articles_sizes.count) as counti FROM ws_articles_sizes
                    WHERE ws_articles_sizes.id_article =' . $this->getId();
            $res = wsActiveRecord::useStatic('Shoparticlessize')->findByQueryArray($q)[0]->counti;
            $count = @$res;//->at(0)->counti;
			if ($count != 1) {
			$this->setLabelId(null);
			$this->save();
                $this->getSmallBlockCachedHtml(true);
			return true;
			}
		
		}
	
    }

    public function getCountByDate($from, $to)
    {
        $count = 0;

        $q = 'SELECT sum(ws_articles_sizes.count) as cnt FROM ws_articles_sizes
                                   WHERE ws_articles_sizes.id_article =' . $this->getId();
        $counts = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
        if ($counts->at(0)) {
            $count += $counts->at(0)->getCnt();
        }

        $q = 'SELECT sum(ws_order_articles.count) as cnt FROM ws_order_articles
            JOIN ws_orders on ws_orders.id =ws_order_articles.order_id
            WHERE ws_order_articles.article_id = ' . $this->getId() . '
            AND ws_orders.date_create <="' . $to . ' 23:59:59"
            AND ws_orders.date_create >= "' . $from . ' 00:00:00"';
        $counts = wsActiveRecord::useStatic('Shoparticles')->findByQuery($q);
        if ($counts->at(0)) {
            $count += $counts->at(0)->getCnt();
        }
        return $count;
    }

    public static function findByIds($ids = array(),$limit = 15)
            {
        if(count($ids)){
            $query = 'SELECT distinct(ws_articles.id), ws_articles.*,DATE_FORMAT(ws_articles.data_new,"%Y-%m-%d") as orderctime
            FROM ws_articles_sizes
            JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
            WHERE ws_articles_sizes.count > 0
            AND ws_articles.active = "y"
            AND ws_articles.stock > 0
            AND ws_articles.id IN ('.implode(',',$ids).')
            ORDER BY orderctime DESC, model ASC LIMIT 0,'.$limit;
 


            $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($query);

            return $articles;

        }

        return array();
    }
	public function getCountArticles()
                {
   return wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT SUM(  `count` ) AS ctn FROM  `ws_articles_sizes` WHERE `id_article` =".$this->getId())->at(0)->getCtn();

	}
	public function decode($encoded, $key)
                {//расшифровываем
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
			$x=0;
			while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
			}
			return base64_decode($encoded);//Вертаем расшифрованную строку
			}

}