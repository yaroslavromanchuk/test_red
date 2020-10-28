<?php

class ReportController extends controllerAbstract
{

    public function init()
    {
        
    }
     protected function _postAction($content)
    {
        return $content;
    }
    
    public function otchetsAction()
    {
         if ($this->get->type) {
             switch ($this->get->type) {
                 case 1: return $this->r1();
                 case 2: return $this->r2();
                 case 3: return $this->r3();
                 case 4: return $this->r4();
                 case 44: return $this->r44();
                 case 45: return $this->r45();
                 case 5: return $this->r5();
                 case 6: return $this->r6();
                 case 7: return $this->r7();
                 case 8: return $this->r8();
                 case 9: return $this->r9();
                case 10: return $this->r10();
                     default:
                     break;
             }
         }
        exit();
    }
    function r1(){
      //   ini_set('memory_limit', '1024M');

                $day = strtotime($this->post['day']);
                $mast = [];
                $q = 'SELECT ws_order_articles.* FROM ws_order_articles
inner JOIN ws_orders ON ws_order_articles.order_id = ws_orders.id
WHERE ws_orders.date_create  > DATE_FORMAT(DATE_ADD("' . date('Y-m-d', $day) . ' 00:00:00", INTERVAL -' . ($this->post['type'] - 1) . ' DAY), "%Y-%m-%d 00:00:00")
        AND ws_orders.date_create <= "' . date('Y-m-d', $day) . ' 23:59:59"';

    $articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);


                foreach ($articles as $article) {
                    $cat = explode(' : ', $article->article_db->category->getRoutez());
                    if ($cat[0] == 'РАСПРОДАЖА') {
                        $cat[0] = $cat[1];
                        /*
							if ($cat[0] == 'Женская одежда') $cat[0] = 'Женское';
							if ($cat[0] == 'Мужская одежда') $cat[0] = 'Мужское';*/

                        if ($cat[0] == 'Детям') $cat[0] = 'Детская : ' . (@$cat[2]);
                        if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[2]);
                        if ($cat[0] == 'Белье') $cat[0] = @$cat[2];
                    }
                    if ($cat[0] == 'Белье') $cat[0] = @$cat[1];
                    if ($cat[1] == 'Детское белье') $cat[0] = 'Детям : ' . (@$cat[1]);
                    if ($cat[0] == 'Обувь') $cat[0] = 'Обувь : ' . (@$cat[1]);
                    /* $mast[$cat[0]][] = $article;*/
                    $a_price = $article->getPerc($article->order->getAllAmount(), 0);
                    if (@$mast[$cat[0]]['count']) {
                        $mast[$cat[0]]['count'] += $article->getCount();
                        $mast[$cat[0]]['price'] += $a_price['price'];
                        $mast[$cat[0]]['minus'] += $a_price['minus'];
                    } else {
                        $mast[$cat[0]]['count'] = $article->getCount();
                        $mast[$cat[0]]['price'] = $a_price['price'];
                        $mast[$cat[0]]['minus'] = $a_price['minus'];
                    }
                }
                ksort($mast);

                require_once('PHPExel/PHPExcel.php');
                $filename = 'otchet_all_order_by_cat_' . $this->post['day'];
                
                
               // $filename = $name . '.xls';
                $style = [];
            $style['width']['A'] = 30;
            $style['width']['B'] = 15;
            $style['width']['C'] = 15;
            $style['width']['D'] = 15;

                $parametr = [];
                
                $parametr['header'][0][0] = 'С даты:';
                $parametr['header'][0][1] = date('d-m-Y', $day);
                $parametr['header'][0][2] = 'за ' . $_POST['type'] . ' дн.';
                //$parametr['header'][0][3] = [];
                $style['merge']['C1:D1'] = true;
                $parametr['header'][1][0] = 'Товары';
                $parametr['header'][1][1] = 'К-во ед.';
                $parametr['header'][1][2] = 'Сумма';
                $parametr['header'][1][3] = 'Скидка';
                    $style['font']['A1:D1'] = ['font'=>['bold'=>true]];
                    $style['font']['A2:D2'] = ['font'=>['bold'=>true]];

                $i = count($parametr['header']);
                foreach ($mast as $kay => $val) {
                    $parametr['data'][$i][0] = $kay;
                    $parametr['data'][$i][1] = $val['count'];
                    $parametr['data'][$i][2] = $val['price'];
                    $parametr['data'][$i][3] = $val['minus'];
                    $i++;
                }

                 return ParseExcel::saveToExcel($filename, [0 => $parametr], $style);
    }
    function r2(){
        // ini_set('memory_limit', '1024M');
                $from = strtotime($_POST['order_from']);
                $to = strtotime($_POST['order_to']);
               // if (@$_POST['no_new']) {
                //    $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('status not in (100, 17)', 'date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"'), array('date_create' => 'ASC'));
               // } else {
                 //    $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"', ' status != 17'), array('date_create' => 'ASC'));
		//}
					  
		$orders = "
                                            SELECT
						DATE_FORMAT( order_history.ctime,  '%d-%m-%Y' ) AS dat, SUM(  `sum_order` ) AS suma, COUNT(  `id` ) AS ctn, SUM(  `count_article` ) AS ctn_ar
                                            FROM
						order_history
                                            WHERE
						order_history.name LIKE  'Заказ оформлен'
						and ctime >= '" . date('Y-m-d', $from) . " 00:00:00'
						and ctime <= '" . date('Y-m-d', $to) ." 23:59:59'
                                            group by dat
					";
		$orders = wsActiveRecord::useStatic('OrderHistory')->findByQuery($orders);
                
                require_once('PHPExel/PHPExcel.php');
                $filename  = 'otchet_order_' . $_POST['order_from'] . '_' . $_POST['order_to'];
                
                $style = [];
            $style['width']['A'] = 10;
            $style['width']['B'] = 10;
            $style['width']['C'] = 20;
            $style['width']['D'] = 20;
            
            $style['width']['E'] = 12;
            $style['width']['F'] = 15;
            $style['width']['G'] = 17;
            $style['width']['H'] = 28;

                $parametr = [];
                
                $parametr['header'][0][0] = 'Дата';
                $parametr['header'][0][1] = 'Сумма';
                $parametr['header'][0][2] = 'Количество заказов';
                $parametr['header'][0][3] = 'Количество единиц';
                $parametr['header'][0][4] = 'Товар';
                $style['merge']['E1:H1'] = true;
                $parametr['header'][1][0] = '';
                $parametr['header'][1][1] = '';
                $parametr['header'][1][2] = '';
                $parametr['header'][1][3] = '';
                $parametr['header'][1][4] = 'Новый товар';
                $parametr['header'][1][5] = 'Товар с возврата';
                $parametr['header'][1][6] = 'Отменено с заказа';
                $parametr['header'][1][7] = 'Удалено без возврата с заказа';
                    $style['font']['A1:H1'] = ['font'=>['bold'=>true]];
                    $style['font']['A2:H2'] = ['font'=>['bold'=>true]];
                
                
                
               $i = count($parametr['header']);
                foreach ($orders as $k => $m) {
                    $q = "SELECT SUM(  `red_article_log`.`count` ) AS  `allcount` 
FROM  `red_article_log` 
INNER JOIN  `ws_articles` ON  `red_article_log`.`article_id` =  `ws_articles`.`id` 
WHERE  `red_article_log`.`type_id` = 4
and `ws_articles`.`status` > 2
and `ws_articles`.`ctime` >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
and `ws_articles`.`ctime` <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
AND  `ws_articles`.`active` =  'y'
					";

                    $count_add = wsActiveRecord::useStatic('Shoparticlelog')->findByQuery($q);
					
					
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  '%Прийом товара с возврата%'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
					$hist = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
                                        
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  'Отмена заказа'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
					$cancel = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
					$s = "
					SELECT
							count(order_history.id) as allcount
						FROM
							order_history
						WHERE
							order_history.name LIKE  'Удаление товара без возврата'
							and ctime >= '" . date('Y-m-d 00:00:00', strtotime($m->dat)) . "'
							and ctime <= '" . date('Y-m-d 23:59:59', strtotime($m->dat)) . "'
					";
                    $del = wsActiveRecord::useStatic('OrderHistory')->findByQuery($s);
                    
                    $parametr['data'][$i][0] = $m->dat;
                    $parametr['data'][$i][1] = round($m->suma, 2);
                    $parametr['data'][$i][2] = $m->ctn;
                    $parametr['data'][$i][3] = $m->ctn_ar;
                    
                    $parametr['data'][$i][4] = $count_add->at(0)->getAllcount();
                    $parametr['data'][$i][5] = $hist->at(0)->getAllcount();
                    $parametr['data'][$i][6] = $cancel->at(0)->getAllcount();
                    $parametr['data'][$i][7] = $del->at(0)->getAllcount();
                    $i++;
                   
                }
return ParseExcel::saveToExcel($filename, [0 => $parametr], $style);
              /*  require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');
                exit();*/
    }
    function r3(){
        if (isset($_GET['day'])) {
                $order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
                $to = strtotime($_GET['day']);
                $orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('date_create <="' . date('Y-m-d', $to) . ' 23:59:59" and date_create >= "' . date('Y-m-d', $to) . ' 00:00:00"'), array('date_create' => 'ASC'));
                require_once('PHPExel/PHPExcel.php');
                $name = 'orderexel';
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle('Первый лист');
                $aSheet->getColumnDimension('A')->setWidth(12);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(30);
                $aSheet->getColumnDimension('D')->setWidth(22);
                $aSheet->getColumnDimension('E')->setWidth(15);
                $aSheet->getColumnDimension('F')->setWidth(20);
                $aSheet->getColumnDimension('G')->setWidth(20);
                $aSheet->getColumnDimension('H')->setWidth(50);
                $aSheet->getColumnDimension('I')->setWidth(10);
                $aSheet->getColumnDimension('J')->setWidth(13);
                $aSheet->getColumnDimension('K')->setWidth(15);
                $aSheet->getColumnDimension('L')->setWidth(15);
                $aSheet->getColumnDimension('M')->setWidth(16);
                $aSheet->getColumnDimension('N')->setWidth(16);
                $aSheet->getColumnDimension('O')->setWidth(16);
                $aSheet->getColumnDimension('P')->setWidth(50);
                $aSheet->getColumnDimension('Q')->setWidth(20);
                $aSheet->getColumnDimension('R')->setWidth(20);
                $aSheet->getColumnDimension('S')->setWidth(30);
                $aSheet->setCellValue('A1', 'Номер счета');
                $aSheet->setCellValue('B1', 'ФИО');
                $aSheet->setCellValue('C1', '№ клиента');
                $aSheet->setCellValue('B1', 'Тип');
                $aSheet->setCellValue('E1', 'Статус');
                $aSheet->setCellValue('F1', "Дата создания/\nОтправка письма Новая почта");
                $aSheet->setCellValue('G1', 'Дата отправки товара');
                $aSheet->setCellValue('H1', 'Наименование товара');
                $aSheet->setCellValue('I1', 'Артикул');
                $aSheet->setCellValue('J1', 'Кол-во товара');
                $aSheet->setCellValue('K1', 'Цена');
                $aSheet->setCellValue('L1', 'Старая цена');
                $aSheet->setCellValue('M1', 'Сума без скидки');
                $aSheet->setCellValue('N1', 'Скидка');
                $aSheet->setCellValue('O1', 'Сума(+ доставка)');
                $aSheet->setCellValue('P1', 'Состав');
                $aSheet->setCellValue('Q1', 'Дата оплаты');
                $aSheet->setCellValue('R1', 'Сумма оплаты');
                $aSheet->setCellValue('S1', 'Оплачены заказы');
                $aSheet->setCellValue('T1', 'начальная стоимость');
                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1:T1')->applyFromArray($boldFont);

                $i = 2;
                $assoc = array();
                foreach ($orders as $order) {
                    if ($order->getId() and $order->getName()) {
                        $date = '0000-00-00 00:00:00';
                        if ($order->getOrderGo() and $order->getOrderGo() != '0000-00-00 00:00:00') {
                            $date = $order->getOrderGo();
                        }

                        if ($date and $date != '0000-00-00 00:00:00') {
                            $d = new wsDate($date);
                            $time = $d->getFormattedDateTime();
                        } else {
                            $time = 'не отправлен';
                        }

                        $datenova = '0000-00-00 00:00:00';
                        if ($order->getNowaMail() and $order->getNowaMail() != '0000-00-00 00:00:00') {
                            $datenova = $order->getNowaMail();
                        }
                        $datesozd = $order->getDateCreate();
                        $d = new wsDate($datesozd);
                        $timesozd = $d->getFormattedDateTime();
                        if ($datenova and $datenova != '0000-00-00 00:00:00') {
                            $d = new wsDate($datenova);
                            $timenova = " / \n" . $d->getFormattedDateTime();
                        } else {
                            $timenova = '';
                        }


                        $order_owner = new Customer($order->getCustomerId());
                        $price = 0;
                        $price_skidka = 0;
                        if ($order->getArticlesCount() != 0) {
                           $price = number_format((double)$order->getTotal('a'), 2, ',', ''); 
                           $price_skidka = $order->calculateOrderPrice2(false, false);
                        }
                        $aSheet->setCellValue('T' . $i, $order->getAmount());
                        $aSheet->setCellValue('A' . $i, $order->getId());
                        $aSheet->setCellValue('B' . $i, $order->getName());
                        $aSheet->setCellValue('C' . $i, $order_owner->getId());
                        $aSheet->setCellValue('D' . $i, $order->getDeliveryTypeId()? $order->getDeliveryType()->getName() : " ");
                        $aSheet->setCellValue('E' . $i, isset($order_status[$order->getStatus()])? $order_status[$order->getStatus()] : "");
                        $aSheet->setCellValue('F' . $i, $timesozd . $timenova);
                        $aSheet->setCellValue('G' . $i, $time);
                        $aSheet->setCellValue('H' . $i, '');
                        $aSheet->setCellValue('I' . $i, '');
                        $aSheet->setCellValue('J' . $i, $order->articles->count());
                        $aSheet->setCellValue('K' . $i, '');
                        $aSheet->setCellValue('L' . $i, '');
                        $aSheet->setCellValue('M' . $i, number_format((double)$order->getTotal('a'), 2, ',', ''));
                        $aSheet->setCellValue('N' . $i, $order_owner->getDiscont() . '%');
                        $aSheet->setCellValue('O' . $i, $price_skidka);
                        $aSheet->setCellValue('P' . $i, '');

                        if ($order->getAdminPayId()) {
                            $order_pay = wsActiveRecord::useStatic('OrdersPay')->findAll(array('customer_id' => $order->getCustomerId(), 'ordes=' . $order->getId() . ' OR ordes LIKE "%' . $order->getId() . '%"'));
                            $pay_date = array();
                            $pay_sum = array();
                            $pay_orders = array();

                            if ($order_pay->count()) {
                                foreach ($order_pay as $pay) {
                                    $pay_date[] = date('d-m-Y', strtotime($pay->getCtime()));
                                    $pay_sum[] = $pay->getSum();
                                    $pay_orders[] = $pay->getOrdes();
                                }
                            }
                            $aSheet->setCellValue('Q' . $i, implode(',', $pay_date));
                            $aSheet->setCellValue('R' . $i, implode(',', $pay_sum));
                            $aSheet->setCellValue('S' . $i, implode(',', $pay_orders));


                        }
                        foreach ($order->articles as $art) {
                            $i++;
                            $aSheet->setCellValue('G' . $i, 'Товар');
                            $art_orig = new Shoparticles($art->getArticleId());
                            $size = new Size($art->getSize());
                            $art_name = ($art->getBrand() ? $art->getBrand() . ', '
                                    : '') . $art->getTitle() . ', ' . $size->getSize();
                            $aSheet->setCellValue('H' . $i, $art_name);
                            $aSheet->setCellValue('I' . $i, $art->getCode());
                            $aSheet->setCellValue('J' . $i, $art->getCount());
                            $aSheet->setCellValue('K' . $i, Number::formatFloat($art->getPrice(), 2));
                            $aSheet->setCellValue('L' . $i, Number::formatFloat($art->getOldPrice(), 2));
                            $aSheet->setCellValue('O' . $i, Number::formatFloat(($art->getPrice() * $art->getCount()), 2));
                            $aSheet->setCellValue('P' . $i, $art_orig->getSostav());
                            if ((int)$art->getOldPrice()) {
                                $skidka_t = (1 - ((int)$art->getPrice() / ((int)$art->getOldPrice()
                                                ? (int)$art->getOldPrice() : 1))) * 100;
                            } else{ $skidka_t = 0;}
                            if ($skidka_t > 80) {$skidka_t = 90;}
                            elseif ($skidka_t > 60) {$skidka_t = 70;}
                            elseif ($skidka_t > 40) {$skidka_t = 50;}
                            elseif ($skidka_t > 21) {$skidka_t = 30;}
                            elseif ($skidka_t > 10) {$skidka_t = 20;}

                            if ($skidka_t != 0) {
                                $aSheet->setCellValue('N' . $i, $skidka_t . '%');
                            }

                        }
                        if ((int)$order->getDeliveryCost() > 0) {
                            $i++;
                            $aSheet->setCellValue('G' . $i, 'Услуга');
                            $aSheet->setCellValue('H' . $i, 'Доставка');
                            $aSheet->setCellValue('K' . $i, Shoparticles::showPrice($order->getDeliveryCost()));
                        }
                        $i++;
                        $i++;
                    }
                }
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');
            }
        exit();
    }
    function r44(){
        $ttn = $this->post->ttn;
        $sql = "SELECT 
a.`code`,
SUM( ora.`count` ) AS prodano,
SUM( IF( ora.`option_price` , ora.`option_price` , ora.`price` ) * ora.`count` ) AS prodano_summ,
SUM( a.`min_price` * ora.`count` ) AS ss,
SUM( IF( ora.`option_price` , ora.`option_price` , ora.`price` ) - a.`min_price` ) AS marga_prodano,
SUM( IF( ora.`option_price` , ora.`option_price` , ora.`price` ) - a.`min_price` ) / SUM( ora.`count` ) AS marga_od
FROM `ws_order_articles` AS ora
INNER JOIN `ws_articles` AS a ON a.id = ora.`article_id`
WHERE a.`code` IN ({$ttn})
AND ora.`count` >0
group by a.`code`";
        
        $res = wsActiveRecord::findByQueryArray($sql);
        $mass = [];
        foreach ($res as $tt){
            $mass[$tt->code] = $tt;
            $st ="SELECT SUM( `ws_articles`.`min_price` * size.`count` ) AS sum, SUM( size.`count` ) AS ctn
FROM `ws_articles`
JOIN `ws_articles_sizes` AS size ON size.`id_article` = `ws_articles`.id
WHERE `ws_articles`.`code`
IN (
'82215', '82098'
)
AND size.`count` >0";
            $ost = "SELECT SUM( ws_articles.min_price * ws_articles_sizes.`count` ) AS s_min_ost,
                SUM( ws_articles_sizes.coming ) AS prihod,
                SUM( ws_articles_sizes.`count` ) AS ostatok
FROM ws_articles_sizes
JOIN ws_articles ON ws_articles.id = ws_articles_sizes.id_article
WHERE `ws_articles`.`code` =".$tt->code;
          $ost_res =  wsActiveRecord::findByQueryFirstArray($ost);
          $v = "SELECT SUM( `ws_order_articles_vozrat`.`count` * `ws_articles`.`min_price` ) AS s_min_ost, SUM( `ws_order_articles_vozrat`.`count` ) AS ostatok
FROM `ws_articles`
INNER JOIN `ws_order_articles_vozrat` ON `ws_order_articles_vozrat`.`article_id` = `ws_articles`.`id`
AND `ws_order_articles_vozrat`.`count` >0
WHERE `ws_articles`.`code` =".$tt->code;
          $ost_voz =  wsActiveRecord::findByQueryFirstArray($v);
          
        $ss_prihod = "SELECT SUM( size.`coming` * `ws_articles`.`min_price` ) AS ss, DATEDIFF( NOW( ) , `ws_articles`.`data_new` ) AS
day , SUM( `views` ) AS prosmotr
FROM `ws_articles`
JOIN `ws_articles_sizes` AS size ON size.`id_article` = `ws_articles`.id
WHERE `ws_articles`.`code` = ".$tt->code;
$pr = wsActiveRecord::findByQueryFirstArray($ss_prihod);
          $mass[$tt->code]->prihod = $ost_res['prihod'];
          $mass[$tt->code]->ostatok = $ost_res['ostatok']+$ost_voz['ostatok'];
          $mass[$tt->code]->ss_ostatok = round(($ost_res['s_min_ost']+$ost_voz['s_min_ost']),2);
          $mass[$tt->code]->ss_prihod = round(($pr['ss']),2);
          $mass[$tt->code]->day = $pr['day'];
          $mass[$tt->code]->views = $pr['prosmotr'];
          
        }
        die(json_encode($mass));
    }
    function r45(){
        $d = round((strtotime($this->post->to)-strtotime($this->post->from))/(60*60*24)+1);
                
                  $from = date('Y-m-d',strtotime($this->post->from));
                  $to = date('Y-m-d', strtotime($this->post->to));
                  $mass = [];
      // for($i=0; $i++; $i<=5){
                  //$i=0;
            $sql0 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 0
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";
 $sql1 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 1
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";
 $sql2 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 2
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";
 $sql3 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 3
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";
 $sql4 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 4
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";
 $sql5 = "SELECT `ws_balance_category`.`id_category` AS cat, `ws_categories`.`h1` , ROUND( SUM( `ws_balance_category`.`count` ) /{$d} ) AS ctn, ROUND( SUM( `ws_balance_category`.`summa` ) /$d ) AS summ
FROM `ws_balance`
JOIN `ws_balance_category` ON `ws_balance`.`id` = `ws_balance_category`.`id_balance`
JOIN `red_brands` ON `ws_balance_category`.`id_brand` = `red_brands`.`id`
JOIN `ws_categories` ON `ws_categories`.`id` = `ws_balance_category`.`id_category`
WHERE `ws_balance`.`date`  >= '{$from}' and `ws_balance`.`date` <= '{$to}'
AND `red_brands`.`greyd` = 5
GROUP BY `ws_balance_category`.`id_category`
HAVING ctn >=1";

//$res = 
//l($res);
        $mass[0] = wsActiveRecord::findByQueryArray($sql0);
        $mass[1] = wsActiveRecord::findByQueryArray($sql1);
        $mass[2] = wsActiveRecord::findByQueryArray($sql2);
        $mass[3] = wsActiveRecord::findByQueryArray($sql3);
        $mass[4] = wsActiveRecord::findByQueryArray($sql4);
        $mass[5] = wsActiveRecord::findByQueryArray($sql5);
     //  }
         die(json_encode($mass));
    }
    function r4(){
       //  ini_set('memory_limit', '1024M');

		$q = "SELECT ws_articles_sizes.*, ws_articles.model as model, ws_articles.views, ws_articles.brand, ws_articles.min_price
                FROM ws_articles_sizes
                JOIN ws_articles ON ws_articles.id = ws_articles_sizes.id_article		
                WHERE ws_articles.brand_id = " . $_POST['brend'] . "
                AND ws_articles.data_new >= '" . date('Y-m-d', strtotime($_POST['add_from'])) . "'
                AND ws_articles.data_new <= '" . date('Y-m-d', strtotime($_POST['add_to'])) . "'"
                        . "AND `ws_articles`.`code` IN ( 82215, 82098, 82099, 82097, 82217, 82216, 82100, 82214, 82175, 82113, 83278, 83276)";

                $artucles = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q);
                $mas = [];
                $brand = '';
                foreach ($artucles as $article) {
               
                    $count = $article->count;
                    $views = $article->views;
                    $brand = $article->brand;
                    $coming = $article->coming;
                    $min_sum = $article->min_price;
           
                    $name = $article->getModel();
                    if (isset($mas[$name]) and isset($mas[$name][$article->getIdArticle()])) {
                        $mas[$name][$article->getIdArticle()]['count'] += $count;
                       // $mas[$name][$article->getIdArticle()]['min_ost'] += $views;
                        $mas[$name][$article->getIdArticle()]['coming'] += $coming;
                    } else {
                        $q = "SELECT sum(count) as cnt, sum( if(`option_price` , `option_price`, `price`) * count) as ssum
                            FROM ws_order_articles
			JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
                        where ws_order_articles.article_id = " . $article->getIdArticle()." "
                                . "and ws_orders.date_create >= '" . date('Y-m-d', strtotime($_POST['order_from'])) . " 00:00:00'
                                    AND ws_orders.date_create <= '" . date('Y-m-d', strtotime($_POST['order_to'])) . " 23:59:59'";
                        $now_count = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);//->at(0)->cnt;
                        
			$voz = 	wsActiveRecord::useStatic('ShoporderarticlesVozrat')->findByQuery("SELECT sum(count) as cnt FROM ws_order_articles_vozrat where article_id = " . $article->getIdArticle())->at(0)->cnt;		 
                        $mas[$name][$article->getIdArticle()]['count'] = $count+$voz;
                        $mas[$name][$article->getIdArticle()]['voz'] = $voz;
                        $mas[$name][$article->getIdArticle()]['view'] = $views;
                        $mas[$name][$article->getIdArticle()]['coming'] = $coming;
                        $mas[$name][$article->getIdArticle()]['min'] = $min_sum;
                        // $mas[$name][$article->getIdArticle()]['min_ost'] = $min_sum;
                        
			$mas[$name][$article->getIdArticle()]['ssum'] =  $now_count[0]->ssum?$now_count[0]->ssum:0;
           
                        $mas[$name][$article->getIdArticle()]['now_count'] = $now_count[0]->cnt?$now_count[0]->cnt:0;
                        
			//$mas[$name][$article->getIdArticle()]['ostatok'] =  wsActiveRecord::useStatic('Shoparticlessize')->findByQuery("SELECT sum(count) as cnt FROM ws_articles_sizes where id_article = " . $article->getIdArticle())->at(0)->cnt;
                        $mas[$name][$article->getIdArticle()]['brand'] =  $brand;
			
                    }

                }

                require_once('PHPExel/PHPExcel.php');
                $name = 'brand-'.$_POST['brend'].'-'.$brand;
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle($brand);
                $aSheet->getColumnDimension('A')->setWidth(35);
                $aSheet->getColumnDimension('B')->setWidth(20);
                $aSheet->getColumnDimension('C')->setWidth(20);
                $aSheet->getColumnDimension('D')->setWidth(20);
                $aSheet->getColumnDimension('E')->setWidth(30);
                $aSheet->getColumnDimension('F')->setWidth(30);
                $aSheet->setCellValue('A1', 'Характеристика номенклатуры:');
                $aSheet->setCellValue('B1', 'Приход');
                $aSheet->setCellValue('C1', 'Расход');
                $aSheet->setCellValue('D1', 'Остаток');
                $aSheet->setCellValue('E1', 'Продано на сумму');
                $aSheet->setCellValue('F1', 'Просмотры');
                $aSheet->setCellValue('G1', 'Бренд');
                $aSheet->setCellValue('H1', 'Маржа');
                $aSheet->setCellValue('I1', 'Возвраты');

                $boldFont = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $aSheet->getStyle('A1:I1')->applyFromArray($boldFont);
                $i = 3;
                $count_all = 0;
                $all_nc = 0;
                $all_prich = 0;
                $all_summ = 0;
                $view_all = 0;
                $all_marja = 0;
                $all_voz = 0;
                $all_min = 0;
                foreach ($mas as $kay => $val) {
                    $all = $i;
                    $aSheet->setCellValue('A' . $i, $kay);
                    $i++;
                    $count = 0;
                    $voz = 0;
                    $rs = 0;
                    $nc = 0;
                    $prih = 0;
                    $v = 0;
                    $b = '';
                    $min = 0;
                    $marja = 0;
                    foreach ($val as $tov_kay => $tov_val) {
                       // $aSheet->setCellValue('A' . $i, $tov_kay);
                      //  $aSheet->setCellValue('C' . $i, $tov_val['now_count']);
                      //  $aSheet->setCellValue('E' . $i, $tov_val['ssum']);
                       // $aSheet->setCellValue('D' . $i, $tov_val['count']);
                      //  $aSheet->setCellValue('B' . $i, $tov_val['ostatok'] + $tov_val['count']);//$tov_val['now_count'] + $tov_val['count']);
                        $count += $tov_val['count'];
                        $voz+=$tov_val['voz'];
                        $nc += $tov_val['now_count'];
                        $v+=$tov_val['view'];
			$prih += $tov_val['coming'];
			$rs += $tov_val['ssum'];
                        $b = $tov_val['brand'];
                        $min+= $tov_val['min'];
                        
                       // $min+=$tov_val['min'];
                        $marja +=  round($tov_val['ssum']-($tov_val['min']*$tov_val['now_count']),2);
                       /// $i++;
                    }
                    $aSheet->setCellValue('D' . $all, $count);
                    $aSheet->setCellValue('E' . $all, $rs);
                    $aSheet->setCellValue('F' . $all, $v);
                    $aSheet->setCellValue('G' . $all, $b);
                    $aSheet->setCellValue('C' . $all, $nc);
                    $aSheet->setCellValue('B' . $all, $prih);
                    $aSheet->setCellValue('H' . $all, $marja);
                    $aSheet->setCellValue('I' . $all, $voz);
                     $aSheet->setCellValue('J' . $all, $min);
                    
                    $aSheet->getStyle('A' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('B' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('D' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('E' . $all)->applyFromArray($boldFont);
                     $aSheet->getStyle('F' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('C' . $all)->applyFromArray($boldFont);
                    $aSheet->getStyle('H' . $all)->applyFromArray($boldFont);
                    $count_all+=$count;
                    $all_nc+=$nc;
                    $all_prich+=$prih;
                    $all_summ+=$rs;
                    $view_all+=$v;
                    $all_marja += $marja;
                    $all_voz+=$voz;
                    $all_min+=$min;
                }
                $i++;
                $aSheet->setCellValue('A' . $i, 'ВСЕГО');
                $aSheet->setCellValue('D' . $i, $count_all);
                $aSheet->setCellValue('C' . $i, $all_nc);
                $aSheet->setCellValue('B' . $i, $all_prich);
                $aSheet->setCellValue('E' . $i, $all_summ);
                $aSheet->setCellValue('F' . $i, $view_all);
                $aSheet->setCellValue('H' . $i, $all_marja);
                $aSheet->setCellValue('I' . $i, $all_voz);
                $aSheet->setCellValue('J' . $i, $all_min);

                $aSheet->getStyle('A' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('B' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('D' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('C' . $i)->applyFromArray($boldFont);
                $aSheet->getStyle('H' . $i)->applyFromArray($boldFont);
                
                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header('Content-type: application/ms-excel');
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');

      exit();
    }
    function r55(){
      //  if()
        
     //   die(json_encode(array('start'=>(int)$i, 'end'=>(int)$this->post->end, 'proc'=>(int)$proc, 'exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/'.$path1file)));	


die();
    }
    function r5()
    {
        if($this->post->method == 'list_articles'){
            $sql = "SELECT DISTINCT `ws_articles`.`id`
FROM `ws_articles`
INNER JOIN `ws_articles_sizes` ON `ws_articles`.`id` = `ws_articles_sizes`.`id_article`
WHERE `ws_articles_sizes`.`count` >0
AND `ws_articles`.`status`
IN ( 3, 4 )
AND `ws_articles`.`ucenka` = {$this->post->proc} ORDER BY `brand_id` ASC ";
$res = [];
foreach (wsActiveRecord::findByQueryArray($sql) as $a){
    $res[] = $a->id;
}
die(json_encode($res));

        }else if($this->post->method == 'list_brand'){
$arr = [];
$sql = "SELECT  `ws_articles`.`brand_id` 
FROM  `ws_articles` 
JOIN  `ws_articles_sizes` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
left JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` >0
AND `ws_articles`.`status` in(3,4)
AND  `ws_articles`.`ucenka` = ".$this->post->proc."
GROUP BY  `ws_articles`.`brand_id` 
ORDER BY  `ws_articles`.`brand_id` ASC";
                
 foreach(wsActiveRecord::findByQueryArray($sql) as $a){
 $arr[] = $a->brand_id;
 }
die(json_encode($arr));
}else{
 

$sql = "SELECT  `ws_articles`.`id`,
`ws_articles`.`brand_id`,
`ws_articles`.`brand` ,
`ws_articles`.`model` , 
`ws_articles`.`category_id`,
`ws_categories`.`h1`,
`ws_articles`.`old_price`,
`ws_articles`.`price`,
`ws_articles`.`data_new`,
`ws_articles`.`ucenka`,
if(`ws_articles`.`data_ucenki`,`ws_articles`.`data_ucenki`,'') as `data_ucenki`,

`ws_articles_sizes`.`code` AS  `acode` ,
`ws_sizes`.`size` AS  `sizes` ,
`ws_articles_colors`.`name` AS  `colors` ,
`ws_articles_sizes`.`coming` as `prichod`,

SUM(if(`ws_order_articles`.`count`,`ws_order_articles`.`count`,0)) AS  `rozhod` ,
sum(if(`ws_order_articles`.`count`=0,1,0)) as `vozrat`,
`ws_articles_sizes`.`count` AS  `sklad` 
FROM  `ws_articles_sizes` 
JOIN  `ws_articles` ON  `ws_articles`.`id` = `ws_articles_sizes`.`id_article`
join `ws_categories` ON `ws_articles`.`category_id` = `ws_categories`.`id`
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
left JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` > 0
AND `ws_articles`.`id` = {$this->post->id}
group by `ws_articles_sizes`.`code`"
. "ORDER BY  `ws_articles`.`category_id` ASC ";
//l($sql);
//exit();
$key = ($this->post->key+1);
$mas = wsActiveRecord::findByQueryArray($sql);
die(json_encode(['key' => $key, 'mas' =>$mas]));

  // $proc = (int)$this->post->proc;
	//	$start = (int)$this->post->start;
		//$brand = (int)$this->post->brand;
                

$q = "SELECT  `ws_articles`.`id`,
					`ws_articles`.`old_price`,
					`ws_articles`.`price`,
					`ws_articles`.`category_id`,
					`ws_articles`.`brand_id`,
					`ws_articles`.`ctime`,
                                        `ws_articles_sizes`.`coming` as `prichod`,
					if(`ws_articles`.`data_ucenki`,`ws_articles`.`data_ucenki`,'') as `data_ucenki`,
					`ws_articles_sizes`.`code` AS  `acode` ,
					`ws_articles_sizes`.`count` AS  `sklad` ,
					`ws_articles`.`model` ,  `ws_articles`.`brand` ,
					`ws_sizes`.`size` AS  `sizes` ,  `ws_articles_colors`.`name` AS  `colors` ,
					SUM(`ws_order_articles`.`count`) AS  `sum_order` ,
					sum(if(`ws_order_articles`.`count`=0,1,0)) as `sum_ret`
FROM  `ws_articles` 
JOIN  `ws_articles_sizes` ON  `ws_articles_sizes`.`id_article` =  `ws_articles`.`id` 
JOIN  `ws_sizes` ON  `ws_articles_sizes`.`id_size` =  `ws_sizes`.`id` 
JOIN  `ws_articles_colors` ON  `ws_articles_sizes`.`id_color` =  `ws_articles_colors`.`id` 
left JOIN  `ws_order_articles` ON  `ws_articles`.`id` =  `ws_order_articles`.`article_id` 
AND  `ws_articles_sizes`.`id_size` =  `ws_order_articles`.`size` 
AND  `ws_articles_sizes`.`id_color` =  `ws_order_articles`.`color` 
WHERE  `ws_articles_sizes`.`count` > 0
AND `ws_articles`.`id` = {$this->id}
GROUP BY  `ws_articles_sizes`.`code` 
ORDER BY  `ws_articles`.`category_id` ASC";
$articles = wsActiveRecord::findByQueryArray($q);


		
     $name = 'otchet_ucenka_'.$proc.'_'.date('d-m-Y');
	 
     $filename = $name.'.xls';
	 
	 $path1file = INPATH . "backend/excel/". $filename;
         
	  $boldFont = array('font' => array('bold' => true));
          
			if($start == 0){
                            
                require_once('PHPExel/PHPExcel.php');		
		$pExcel = new PHPExcel();
                $pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
                $aSheet->setTitle((string)$proc);
				
					$aSheet->setCellValue('A1', 'Бренд');
					$aSheet->setCellValue('B1', 'Категория');
                    $aSheet->setCellValue('C1', 'Название');
					
					$aSheet->setCellValue('D1', 'Артикул');
                    $aSheet->setCellValue('E1', 'Цвет');
                    $aSheet->setCellValue('F1', 'Размер');
                                            $aSheet->setCellValue('G1', 'Приход');
                                            $aSheet->setCellValue('H1', 'Расход');
                                            $aSheet->setCellValue('I1', 'Возвраты');
                                            $aSheet->setCellValue('J1', 'Остаток');
                                            
					$aSheet->setCellValue('K1', 'Цена до уценки');
					$aSheet->setCellValue('L1', 'Цена после уценки');
					$aSheet->setCellValue('M1', 'Добавлен');
					$aSheet->setCellValue('N1', 'Уценен');
                                        $aSheet->setCellValue('O1', 'Id');

					$i = 2;
                    $aSheet->getStyle('A1:O1')->applyFromArray($boldFont);
				}else{
                          require_once('PHPExel/PHPExcel/IOFactory.php');           
			$pExcel = PHPExcel_IOFactory::load($path1file);
			$pExcel->setActiveSheetIndex(0);
                $aSheet = $pExcel->getActiveSheet();
				$i = $start;
				}
                                
                    $assoc = [];   
                    foreach ($articles as $article) {
					$cat = $article->category_id;
					$assoc[$article->brand_id][$cat][$article->acode]['category'] =  Shopcategories::CatName($article->category_id);
					$assoc[$article->brand_id][$cat][$article->acode]['model'] =  $article->model;
					$assoc[$article->brand_id][$cat][$article->acode]['color'] =  $article->colors;
					$assoc[$article->brand_id][$cat][$article->acode]['brand'] =  $article->brand;
				
				$assoc[$article->brand_id][$cat][$article->acode]['sizes'] =  $article->sizes;
					
					$q = "SELECT SUM(  `red_article_log`.`count` ) AS ctn
					FROM red_article_log
					WHERE red_article_log.type_id = 1

					AND  `red_article_log`.`code` LIKE  '".$article->acode."'";
					
						//$assoc[$article->brand_id][$cat][$article->acode]['prichod'] =  wsActiveRecord::findByQueryFirstArray($q)['ctn'];
					$assoc[$article->brand_id][$cat][$article->acode]['prichod'] =	$article->prichod;
                                        $assoc[$article->brand_id][$cat][$article->acode]['order'] =  $article->sum_order;
						$assoc[$article->brand_id][$cat][$article->acode]['returns'] =  $article->sum_ret;
					
					$s = "SELECT sum(ws_order_articles_vozrat.`count) as allcount 
					FROM ws_order_articles_vozrat
					WHERE	ws_order_articles_vozrat.status = 0
					and  ws_order_articles_vozrat.`count` > 0
					and ws_order_articles_vozrat.`cod` LIKE  '".$article->acode."' ";
					
					$assoc[$article->brand_id][$cat][$article->acode]['sklad'] =  $article->sklad+wsActiveRecord::findByQueryFirstArray($s)['allcount'];
					$assoc[$article->brand_id][$cat][$article->acode]['old_price'] =  $article->old_price;
					$assoc[$article->brand_id][$cat][$article->acode]['price'] =  $article->price;
					$assoc[$article->brand_id][$cat][$article->acode]['ctime'] =  date("d-m-Y", strtotime($article->ctime));
					$assoc[$article->brand_id][$cat][$article->acode]['ucenka'] =  date("d-m-Y", strtotime($article->data_ucenki));
                                        $assoc[$article->brand_id][$cat][$article->acode]['id'] =  $article->id; 
				
                    }
					

					foreach($assoc as $brand=>$cat){
					$p = 0;
					$r = 0;
					$v = 0;
					$o = 0;
					$o_p = 0;
					$pr = 0;
					$b = '';
					$c = '';
					//$b = $brand['n_brand'];
					//$c = $q['category'];
					foreach($cat as $c => $code){
					foreach($code as $cod=>$q){
					$b=$q['brand'];
					$c = $q['category'];
					 $aSheet->setCellValue('A' . $i, $q['brand']);
					 $aSheet->setCellValue('B' . $i, $q['category']);
					 $aSheet->setCellValue('C' . $i, $q['model']);
					 $aSheet->setCellValue('D' . $i, $cod);
					  $aSheet->setCellValue('E' . $i, $q['color']);
					  
					  $aSheet->setCellValue('F' . $i, $q['sizes']);
                                            $aSheet->setCellValue('G' . $i, $q['prichod']);
                                            $aSheet->setCellValue('H' . $i, $q['order']);
                                            $aSheet->setCellValue('I' . $i, $q['returns']);
                                            $aSheet->setCellValue('J' . $i, $q['sklad']);
					  $aSheet->setCellValue('K' . $i, $q['old_price']);
					  $aSheet->setCellValue('L' . $i, $q['price']);
					  $aSheet->setCellValue('M' . $i, $q['ctime']);
					  $aSheet->setCellValue('N' . $i, $q['ucenka']);
                                          $aSheet->setCellValue('O' . $i, $q['id']);
					  
					$p += (int)$q['prichod']?(int)$q['prichod']:0;
					$r += (int)$q['order']?(int)$q['order']:0;
					$v += (int)$q['returns']?(int)$q['returns']:0;
					$o += (int)$q['sklad']?(int)$q['sklad']:0;
					$o_p += (int)$q['old_price']?(int)$q['old_price']:0;
					$pr += (int)$q['price']?(int)$q['price']:0;
					
					$i++;
					}
					}
					}
require_once("PHPExel/PHPExcel/Writer/Excel5.php");				
$objWriter = new PHPExcel_Writer_Excel5($pExcel);		
				
//$end = $this->post->end - $i;

$objWriter->save($path1file);
die(json_encode(array('start'=>(int)$i, 'end'=>(int)$this->post->end, 'proc'=>(int)$proc, 'exit'=>"Отчёт сформирован!", 'src'=>'https://www.red.ua/'.$path1file)));	

}

die();
    }
    
    function r6(){
        
        ini_set('memory_limit', '1024M');
            $from = strtotime($_POST['order_from']);
            $to = strtotime($_POST['order_to']);


            $q = 'SELECT ws_order_articles.*, ws_orders.date_create, ws_orders.delivery_type_id, ws_orders.status FROM ws_order_articles
                JOIN ws_orders on ws_orders.id = ws_order_articles.order_id
                JOIN ws_articles on ws_articles.id = ws_order_articles.article_id
                WHERE ws_articles.skidka_block = 1
                AND date_create <="' . date('Y-m-d', $to) . ' 23:59:59"
                AND date_create >= "' . date('Y-m-d', $from) . ' 00:00:00"
                ';
            if ($_POST['no_new']) {
                $q .= 'AND status in (1,3,4,6,8,9,11,13,14,15,16)';
            }
            $articles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);

            $mas = array();
            $i = 0;
            foreach ($articles as $article) {
                $i++;
                $count_now = $article->getCountNow();
                if (in_array($article->getDeliveryTypeId(), array(1, 2, 3, 7, 11, 12, 13))) { //Shops
                    $mas[$article->getArticleId()]['types'][1][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(8))) { // Nova
                    $mas[$article->getArticleId()]['types'][2][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(4))) { //Ukr
                    $mas[$article->getArticleId()]['types'][3][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
                if (in_array($article->getDeliveryTypeId(), array(9, 10))) { // Kurer
                    $mas[$article->getArticleId()]['types'][4][] = $article;
                    $mas[$article->getArticleId()]['counts'][$article->getSize() . '_' . $article->getColor()] = $count_now;
                }
            }

            require_once('PHPExel/PHPExcel.php');
            $name = 'otchetexel';
            $kount = 1;
            $filename = $name . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(30);
            $aSheet->getColumnDimension('B')->setWidth(15);
            $aSheet->getColumnDimension('C')->setWidth(15);
            $aSheet->getColumnDimension('D')->setWidth(15);
            $aSheet->getColumnDimension('E')->setWidth(15);
            $aSheet->getColumnDimension('F')->setWidth(15);
            $aSheet->setCellValue('A1', 'Товар');
            $aSheet->setCellValue('B1', 'Загружено');
            $aSheet->setCellValue('C1', 'Цена');
            $aSheet->setCellValue('D1', 'Продано');
            $aSheet->setCellValue('E1', 'Цена');
            $aSheet->setCellValue('F1', 'Остаток');
            $aSheet->setCellValue('G1', 'Цена');
            $aSheet->setCellValue('H1', 'Магазины');
            $aSheet->setCellValue('I1', 'Цена');
            $aSheet->setCellValue('J1', 'Курьером');
            $aSheet->setCellValue('K1', 'Цена');
            $aSheet->setCellValue('L1', 'Новой почтой');
            $aSheet->setCellValue('M1', 'Цена');
            $aSheet->setCellValue('N1', 'Укрпочтой');
            $aSheet->setCellValue('O1', 'Цена');
            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1:O1')->applyFromArray($boldFont);
            $i = 2;
            foreach ($mas as $k => $m) {
                $article_r = new Shoparticles($k);
                $count = 0;
                foreach ($m['counts'] as $v) {
                    $count += $v;
                }

                $price = array();
                $counts = array();
                $price['0'] = 0;
                $price['1'] = 0;
                $price['2'] = 0;
                $price['3'] = 0;
                $price['4'] = 0;
                $counts['0'] = 0;
                $counts['1'] = 0;
                $counts['2'] = 0;
                $counts['3'] = 0;
                $counts['4'] = 0;
                $name = '';
                if (isset($m['types'][1])) {
                    foreach ($m['types'][1] as $art) {
                        $price['1'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['1'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][2])) {
                    foreach ($m['types'][2] as $art) {
                        $price['2'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['2'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][3])) {
                    foreach ($m['types'][3] as $art) {
                        $price['3'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['3'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                if (isset($m['types'][4])) {
                    foreach ($m['types'][4] as $art) {
                        $price['4'] += $art->getPrice() * $art->getCount();
                        $price['0'] += $art->getPrice() * $art->getCount();
                        $counts['4'] += $art->getCount();
                        $counts['0'] += $art->getCount();
                        $name = $art->getTitle();
                    }
                }

                $aSheet->setCellValue('A' . $i, $name);
                $aSheet->setCellValue('B' . $i, $count + $counts['0']);
                $aSheet->setCellValue('C' . $i, ($count + $counts['0']) * $article_r->getPrice());
                $aSheet->setCellValue('D' . $i, $counts['0']);
                $aSheet->setCellValue('E' . $i, $price['0']);
                $aSheet->setCellValue('F' . $i, $count);
                $aSheet->setCellValue('G' . $i, $count * $article_r->getPrice());

                $aSheet->setCellValue('H' . $i, $counts['1']);
                $aSheet->setCellValue('I' . $i, $price['1']);

                $aSheet->setCellValue('J' . $i, $counts['4']);
                $aSheet->setCellValue('K' . $i, $price['4']);

                $aSheet->setCellValue('L' . $i, $counts['2']);
                $aSheet->setCellValue('M' . $i, $price['2']);

                $aSheet->setCellValue('N' . $i, $counts['3']);
                $aSheet->setCellValue('O' . $i, $price['3']);
                $i++;
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            $objWriter->save('php://output');
            return false;
    }
    function r7(){
        ini_set('memory_limit', '1024M');
            $from = strtotime($_POST['order_from']);
            $to = strtotime($_POST['order_to']);


            $q = 'SELECT * FROM ucenka_history
                           WHERE ctime <="' . date('Y-m-d', $to) . ' 23:59:59"
                           AND ctime >= "' . date('Y-m-d', $from) . ' 00:00:00"
                           ';

            $history = wsActiveRecord::useStatic('UcenkaHistory')->findByQuery($q);

            $mas = array();
            $i = 0;
            $prest = 'Y-m';
            if ($this->post->to_day) {
                $prest = 'Y-m-d';
            }
            foreach ($history as $h) {
                $i++;
                $proc = round($h->getProc() / 10) * 10;

                $article = new Shoparticles($h->getArticleId());
                if (!$this->post->to_day) {
                    $count = $article->getCountByDate(date('Y-m-1', strtotime($h->getCtime())), date('Y-m-31', strtotime($h->getCtime())));
                } else {
                    $count = $article->getCountByDate(date('Y-m-d', strtotime($h->getCtime())), date('Y-m-d', strtotime($h->getCtime())));
                }
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['count'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['count'] + 1;
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['count_items'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['count_items'] + $count;
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['old_price'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['old_price'] + ($count * $h->getOldPrice());
                $mas[date($prest, strtotime($h->getCtime()))][$proc]['price'] = @$mas[date($prest, strtotime($h->getCtime()))][$proc]['price'] + ($count * $h->getNewPrice());
            }
            require_once('PHPExel/PHPExcel.php');
            $name = 'otchetexel';
            $kount = 1;
            $filename = $name . '.xls';
            $pExcel = new PHPExcel();
            $pExcel->setActiveSheetIndex(0);
            $aSheet = $pExcel->getActiveSheet();
            $aSheet->setTitle('Первый лист');
            $aSheet->getColumnDimension('A')->setWidth(30);
            $aSheet->getColumnDimension('B')->setWidth(15);
            $aSheet->getColumnDimension('C')->setWidth(15);
            $aSheet->getColumnDimension('D')->setWidth(15);
            $aSheet->getColumnDimension('E')->setWidth(15);
            $aSheet->getColumnDimension('F')->setWidth(15);
            $aSheet->setCellValue('A1', 'Дата');
            $aSheet->setCellValue('B1', 'Процент');
            $aSheet->setCellValue('C1', 'Моделей');
            $aSheet->setCellValue('D1', 'Едениц');
            $aSheet->setCellValue('E1', 'Сумма до');
            $aSheet->setCellValue('F1', 'Сумма после');
            $aSheet->setCellValue('G1', 'Разница');
            $boldFont = array(
                'font' => array(
                    'bold' => true
                )
            );
            $aSheet->getStyle('A1:G1')->applyFromArray($boldFont);
            $i = 2;
            foreach ($mas as $k => $m) {
                $aSheet->setCellValue('A' . $i, $k);
                $i++;
                ksort($m);
                foreach ($m as $k => $v) {
                    $aSheet->setCellValue('B' . $i, $k . '%');
                    $aSheet->setCellValue('C' . $i, $v['count']);
                    $aSheet->setCellValue('D' . $i, $v['count_items']);
                    $aSheet->setCellValue('E' . $i, $v['old_price']);
                    $aSheet->setCellValue('F' . $i, $v['price']);
                    $aSheet->setCellValue('G' . $i, $v['old_price'] - $v['price']);
                    $i++;
                }
            }

            require_once("PHPExel/PHPExcel/Writer/Excel5.php");
            $objWriter = new PHPExcel_Writer_Excel5($pExcel);

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

            $objWriter->save('php://output');
        return false;
    }
    function r8(){
        if(@$_POST['cat']){
$cats = wsActiveRecord::useStatic('Shopcategories')->findFirst(array('id' => (int)$_POST['cat'], 'active' => 1));
$arr = $cats->getKidsIds();
$arr = array_unique($arr);
			$cat = implode(",", $arr);
			}else{
			$cat = '';
			}

		ini_set('memory_limit', '2048M');
		set_time_limit(2400);
					
		require_once('PHPExel/PHPExcel.php');
                $name = 'otchetexel_cat_' . $_POST['cat'];
                $filename = $name . '.xls';
                $pExcel = new PHPExcel();
				

        $pExcel->setActiveSheetIndex(0)->setTitle($_POST['cat']);
		$aSheet = $pExcel->getActiveSheet();		
                $mas = array();
					$q = "SELECT ws_order_articles . * , ws_articles.model, ws_articles.brand, ws_articles.ctime AS dat_add,  `ws_articles`.`data_ucenki` , `ws_articles`.`ucenka` 
FROM ws_order_articles
INNER JOIN ws_articles ON ws_articles.id = ws_order_articles.article_id
INNER JOIN ws_articles_sizes ON ws_order_articles.article_id = ws_articles_sizes.id_article
INNER JOIN ws_orders ON ws_orders.id = ws_order_articles.order_id
WHERE ws_articles.active =  'y'
AND ws_articles_sizes.count >0
AND ws_articles.category_id in(". $cat.") ";

                $artucles = wsActiveRecord::useStatic('Shoporderarticles')->findByQuery($q);
                foreach ($artucles as $article) {
					$brand = $article->getBrand();
                    $title = $article->getModel();
                    $color = $article->getColors()->getName();
                    $size = $article->getSizes()->getSize();
                    $date = date('d.m.Y', strtotime($article->getDatAdd()));
                    $date28 = $article->getUcenka()?date('d.m.Y', strtotime($article->getDataUcenki())):'';

					if(strtotime($date)<strtotime("-1 year")){
					$bolee_god = true;
					}else{
					$bolee_god = false;
					}
					if(strtotime($date)<strtotime("-60 day")){
					$bolee_60 = true;
					}else{
					$bolee_60 = false;
					}
							
					$proc = $article->getUcenka();//procent
                    $count = $article->getCount();
					
					$opder_price = $article->order->getAllAmount();
                    $real_price = $article->getPerc($opder_price);
                    $real_price = $real_price['price'] * (1 - ($article->getEventSkidka() / 100));

					$return = $count==0?1:0; 
					
                    if (isset($mas[$article->getArticleId()])) {
                        $mas[$article->getArticleId()]['count'] += $count;//prodano
                        $mas[$article->getArticleId()]['return'] += $return;
                        $mas[$article->getArticleId()]['price'] += $real_price * $count;
                    } else {
                        $q = 'SELECT sum(`count`) as cnt FROM ws_articles_sizes
							where `count` > 0 and id_article = ' . $article->getArticleId();
                        $now_count = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($q)->at(0)->cnt;
                        $mas[$article->getArticleId()]['title'] = $title;
						$mas[$article->getArticleId()]['brand'] = $brand;
						$mas[$article->getArticleId()]['color'] = $color;
						$mas[$article->getArticleId()]['size'] = $size;
						
						$mas[$article->getArticleId()]['count'] = $count;//prodano
						$mas[$article->getArticleId()]['return'] = $return;//vozvraty
						$mas[$article->getArticleId()]['now_count'] = $now_count;//ostatok
						$mas[$article->getArticleId()]['price'] = $real_price * $count;
						
						
						
                        $mas[$article->getArticleId()]['date'] = $date;//sozdan
                        $mas[$article->getArticleId()]['date28'] = $date28;//ucenks
						$mas[$article->getArticleId()]['proc'] = $proc;//porocent ucenki
						
						
						$mas[$article->getArticleId()]['bolee_god'] = $bolee_god?1:0;//ostatok
						//$mas[$article->getArticleId()]['return_5'] = $return>5?$now_count==1?$now_count:'':'';//ostatok
						$mas[$article->getArticleId()]['uc50_ret_p'] = $proc>=50?1:0;//ostatok
						$mas[$article->getArticleId()]['b60_prod_0'] = $bolee_60?1:0;
                    }

                }
                $aSheet->getColumnDimension('A')->setWidth(18);
                $aSheet->getColumnDimension('B')->setWidth(15);
                $aSheet->getColumnDimension('C')->setWidth(15);
                $aSheet->getColumnDimension('D')->setWidth(10);
                $aSheet->getColumnDimension('E')->setWidth(10);
                $aSheet->getColumnDimension('F')->setWidth(10);
                $aSheet->getColumnDimension('G')->setWidth(10);
                $aSheet->getColumnDimension('H')->setWidth(10);
				$aSheet->getColumnDimension('I')->setWidth(19);
				$aSheet->getColumnDimension('J')->setWidth(16);
				$aSheet->getColumnDimension('K')->setWidth(16);
				$aSheet->getColumnDimension('L')->setWidth(16);
				$aSheet->getColumnDimension('M')->setWidth(16);
				$aSheet->getColumnDimension('N')->setWidth(16);
				$aSheet->getColumnDimension('O')->setWidth(16);
				$aSheet->getColumnDimension('P')->setWidth(16);

                $aSheet->setCellValue('A1', 'Бренд:'); // Наименование
				$aSheet->setCellValue('B1', 'Модель:'); // Наименование
				$aSheet->setCellValue('C1', 'Цвет:'); // Наименование
				$aSheet->setCellValue('D1', 'Размер:'); // Наименование
                $aSheet->setCellValue('E1', 'Приход'); // Цена
                $aSheet->setCellValue('F1', 'Расход'); // к-во всего
				$aSheet->setCellValue('G1', 'Возвраты'); // к-во всего
                $aSheet->setCellValue('H1', 'Остаток'); // к-во прод.
                $aSheet->setCellValue('I1', 'Общая сумма продаж');
                $aSheet->setCellValue('J1', 'Дата добавления');
                $aSheet->setCellValue('K1', 'Дата уценки');
                $aSheet->setCellValue('L1', 'Процент уценки');
				$aSheet->setCellValue('M1', 'Более 1 год на сайте');
				$aSheet->setCellValue('N1', 'Возвратов больше 5 и 1 в остатке');
				$aSheet->setCellValue('O1', 'Уценка больше 50% и возвраты больше чем приход');
				$aSheet->setCellValue('P1', 'Более 60 дней на сайте и 0 продаж');
				

                $boldFont = array('font' => array('bold' => true));
				
                $aSheet->getStyle('A1:P1')->applyFromArray($boldFont);

                $i = 2;
                    foreach ($mas as $tov_kay => $tov_val) {
                        $aSheet->setCellValue('A' . $i, $tov_val['brand']);
						$aSheet->setCellValue('B' . $i, $tov_val['title']);
						$aSheet->setCellValue('C' . $i, $tov_val['color']);
						$aSheet->setCellValue('D' . $i, $tov_val['size']);
						$aSheet->setCellValue('E' . $i, $tov_val['now_count'] + $tov_val['count']);
                        $aSheet->setCellValue('F' . $i, $tov_val['count']);
						$aSheet->setCellValue('G' . $i, $tov_val['return']);
						$aSheet->setCellValue('H' . $i, $tov_val['now_count']);
                        $aSheet->setCellValue('I' . $i, $tov_val['price']);
                        $aSheet->setCellValue('J' . $i, $tov_val['date']);
                        $aSheet->setCellValue('K' . $i, $tov_val['date28']);
                        $aSheet->setCellValue('L' . $i, $tov_val['proc']);
						 $aSheet->setCellValue('M' . $i, $tov_val['bolee_god']);
						//  $aSheet->setCellValue('N' . $i, $tov_val['return_5']);
						  $aSheet->setCellValue('O' . $i, $tov_val['uc50_ret_p']);
						    $aSheet->setCellValue('P' . $i, $tov_val['b60_prod_0']);

                        $i++;
                    }

                require_once("PHPExel/PHPExcel/Writer/Excel5.php");
                $objWriter = new PHPExcel_Writer_Excel5($pExcel);

                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Disposition: attachment; filename=\"" . $filename . "\"");

                $objWriter->save('php://output');
        return false;
    }
    function r9(){
        require_once('PHPExel/PHPExcel.php');
        // ini_set('memory_limit', '1024M');
            $i = 0;
            $parametr = [];

            $q1 = "SELECT t1.id, t1.parent_id, t1.name, SUM( t3.stock ) AS 'ostatok'
				FROM  `ws_categories` t1
				INNER JOIN  `ws_articles` t3 ON t1.id = t3.category_id
                                WHERE t3.shop_id = 1 and t3.status = 3
				GROUP BY t1.id 
				ORDER BY  t1.name ASC";

            $artucles = wsActiveRecord::useStatic('Shopcategories')->findByQuery($q1);
            $style = [];
            $style['width']['A'] = 40;
            $style['width']['B'] = 15;
            $style['font']['A1:B1'] = ['font'=>['bold'=>true], 'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];
            $parametr['header'][$i][0] = 'Категория';
            $parametr['header'][$i][1] = 'Остаток';
            $i++;
            $filename = 'otchet_tov_group_' . date("Y-m-d_H:i:s");
            $mascat = [];
            foreach ($artucles as $cat) {
                $mascat[$cat->getRoutez()] = $cat;
            }
            ksort($mascat);
             
            foreach ($mascat as $val => $article) {
                if($article->getOstatok() > 0){
                    $parametr['data'][$i][0] = $val;
                    $parametr['data'][$i][1] = $article->getOstatok();
                $i++;
                }
            }

            return ParseExcel::saveToExcel($filename, [0 => $parametr], $style);
    }
    function r10(){
        require_once('PHPExel/PHPExcel.php');
        // ini_set('memory_limit', '1024M');
            $i = 0;
            $parametr = [];

            $q1 = "SELECT t1.name, SUM( t3.stock ) AS 'ostatok'
				FROM  `red_brands` t1
				INNER JOIN  `ws_articles` t3 ON t1.id = t3.brand_id
                                WHERE t3.shop_id = 1 and t3.status = 3 and  t3.stock > 0
				GROUP BY t1.id 
				ORDER BY  t1.name ASC"; 

            $artucles = wsActiveRecord::useStatic('Brand')->findByQuery($q1);
            $style = [];
            $style['width']['A'] = 40;
            $style['width']['B'] = 15;
            $style['font']['A1:B1'] = ['font'=>['bold'=>true], 'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]];
            $parametr['header'][$i][0] = 'Бренд';
            $parametr['header'][$i][1] = 'Остаток';
            $i++;
            $filename = 'otchet_tov_brand_' . date("Y-m-d_H:i:s");
            $mascat = [];
            foreach ($artucles as $cat) {
               // $mascat[$cat->getRoutez()] = $cat;
                 $parametr['data'][$i][0] = $cat->name;
                    $parametr['data'][$i][1] = $cat->ostatok;
                $i++;
            }
           // ksort($mascat);
             
          /*  foreach ($mascat as $val => $article) {
                if($article->getOstatok() > 0){
                    $parametr['data'][$i][0] = $val;
                    $parametr['data'][$i][1] = $article->getOstatok();
                $i++;
                }
            }*/

            return ParseExcel::saveToExcel($filename, [0 => $parametr], $style);
    }
    
   
}

