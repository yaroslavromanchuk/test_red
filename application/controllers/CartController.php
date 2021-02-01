<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartController
 *
 * @author PHP
 */
class CartController extends controllerAbstract
{
    public function init()
    {

        parent::init();


    }

    //put your code here
    public function indexAction()
    {
        $this->view->setArticlesTop(wsActiveRecord::useStatic('Shoparticlestop')->findAll());
        echo $this->render('shop/index.tpl.php');

    }

    public function ajaxAction()
    {
        $res = [];

        if ($this->post->term && $this->post->method == "street_trekko_load") {
            $lang = Registry::get('lang');
            if ($lang == 'uk') {
                $lang = 'ua';
            }
            require_once('meestexpress/include.php');
            $api = new MeestExpress_API('red_ua', '3#snlMO0sP', '35a4863e-13bb-11e7-80fc-1c98ec135263', $lang);

            $street = $api->getStreet($this->post->term, '5CB61671-749B-11DF-B112-00215AEE3EBE');
            $i = 0;
            if (!empty($street) && is_object($street)) {
                foreach ($street as $c) {
                    $res[$i]['text'] = ($lang == 'ru') ? (string)$c->StreetTypeRU . ' ' . $c->DescriptionRU : (string)$c->StreetTypeUA . ' ' . $c->DescriptionUA;
                    $res[$i]['id'] = ($lang == 'ru') ? (string)$c->DescriptionRU : (string)$c->DescriptionUA;//(string)$c->uuid;
                    $i++;
                }
            }
            die(json_encode($res));
        } elseif ($this->post->term && $this->post->method == "city_np_load") {
            require_once('np/NovaPoshta.php');
            $lang = Registry::get('lang');
            if ($lang == 'uk') {
                $lang = 'ua';
            }
            $np = new NovaPoshta($lang);
            $cities = $np->getCities(0, $this->post->term);

            $i = 0;
            if ($cities['success']) {
                foreach ($cities['data'] as $c) {
                    $res[$i]['text'] = ($lang == 'ru') ? $c['DescriptionRu'] : $c['Description'];
                    $res[$i]['id'] = $c['Ref'];
                    $i++;
                }
            }
            die(json_encode($res));
        } elseif ($this->post->term && $this->post->method == "warehouses_np_load") {
            require_once('np/NovaPoshta.php');
            $lang = Registry::get('lang');
            $s = 'Отд';
            if ($lang == 'uk') {
                $s = 'Від';
                $lang = 'ua';


            }
            $np = new NovaPoshta($lang);
            $wh = $np->getWarehouses($this->post->term, 0);
            //l($wh);
            $i = 0;
            $type = ["841339c7-591a-42e2-8233-7a0a00f0ed6f", "9a68df70-0267-42a8-bb5c-37f427e36ee4"];
            foreach ($wh['data'] as $c) {
                if (in_array($c['TypeOfWarehouse'], $type)) {
                    $res[$i]['text'] = ($lang == 'ru') ? $c['DescriptionRu'] : $c['Description'];
                    $res[$i]['id'] = $c['Ref'];
                    $res[$i]['val'] = $c['TypeOfWarehouse'];
                    $i++;
                }
            }

            die(json_encode($res));
        } elseif ($this->post->delivery_type_id and $this->post->shop) {
            $data = [];
            $block = [];
            $block[] = 8;
            $block[] = 9;
            $pay = '';
            $info = '';
            if ($this->ws->getCustomer()->getIsLoggedIn()) {
                switch ($this->post->delivery_type_id) {
                    case '3':
                        if ($this->ws->getCustomer()->isBlockM()) {
                            $block[] = 1;
                        }
                        break;
                    case '4':
                        break;
                    case '8':
                        if ($this->ws->getCustomer()->isBlockNpN()) {
                            $block[] = 3;
                        }
                        break;
                    case '9':
                        if ($this->ws->getCustomer()->isBlockCur()) {
                            $block[] = 1;
                        }
                        break;
                    case '18':
                        if ($this->ws->getCustomer()->isBlockJustin()) {
                            $block[] = 1;
                        }
                        break;
                }
                if ($this->ws->getCustomer()->isBlockOnline() and Config::findByCode('pay_online')->getValue()) {
                    $block[] = 4;
                    $block[] = 6;
                }
            }
            if (count($block)) {

                $data[] = 'payment_id not in(' . implode(",", $block) . ')';
            }
            $data['active'] = 1;
            $data[] = 'max_sum > ' . $this->post->amount;
            $data['delivery_id'] = $this->post->delivery_type_id;
            // l($data);
            $pays = wsActiveRecord::useStatic('DeliveryPayment')->findAll($data);
            if ($pays) {
                $pay .= '<div class="form-group form-group-sm"><label for="payment_' . $this->post->shop . '">Способ оплаты <span class="red">*</span></label>';
                $pay .= '<div id="payment_method_id' . $this->post->shop . '">
<select name="dostavka[' . $this->post->shop . '][payment_method_id]" id="payment_method_id' . $this->post->shop . '" data-placeholder="Выбрать"  class="form-control select2" data-parsley-class-handler="#payment_method_id' . $this->post->shop . '" data-parsley-errors-container="#p_sl_' . $this->post->shop . '_ErrorContainer" required>
<option label="Выбрать"></option>';
                foreach ($pays as $p) {
                    $pay .= '<option value="' . $p->payment_id . '">' . $p->payment->name . '</option>';
                }
                $pay .= '</select>
    <div id="d_sl_' . $this->post->shop . '_ErrorContainer"></div>
</div>
</div>
<div class="col-sm-12 col-md-6 dop_pay_' . $this->post->shop . '">
    
</div>';
//$dat = date('Y-m-d');
//$flag = wsActiveRecord::useStatic('Shoparticlesoption')->count(["status"=>1, "`start` <= '$dat'", " '$dat' <= `end`", "type"=>"final"]); and !$flag

                $deposit = false;
                $coin = false;
//$deposit = $this->ws->getCustomer()->getDeposit();
                if ($this->ws->getCustomer()->getIsLoggedIn() && !DeliveryType::getIsShop($this->post->delivery_type_id)) {

                    $deposit = $this->ws->getCustomer()->getDeposit();
                }
                if ($this->post->shop == 1 && $this->ws->getCustomer()->getIsLoggedIn()) {
                    $coin = $this->ws->getCustomer()->getSummCoin('active');
                }

                if ($coin and $deposit) {
                    $pay .= '<div class="form-group form-group-sm"><label for="coin_' . $this->post->shop . '">Можно использовать</label>';
                    $pay .= '<div class=" mg-b-0">
  <label class="ckbox">
    <input type="checkbox" name="dostavka[' . $this->post->shop . '][bonus]" id="bonus_' . $this->post->shop . '" onchange="if($(this).prop(\'checked\')) { $(\'#deposit_' . $this->post->shop . '\').attr(\'checked\', false); }" value="1">'
                        . '<span>' . $this->trans->get("Бонус") . ' - ' . $coin . ' грн.</span>
  </label>
  <label class="ckbox">
    <input type="checkbox" name="dostavka[' . $this->post->shop . '][deposit]" id="deposit_' . $this->post->shop . '" onchange="if($(this).prop(\'checked\')) { $(\'#bonus_' . $this->post->shop . '\').attr(\'checked\', false); }" value="1">'
                        . '<span>' . $this->trans->get("Ваш депозит") . ' - ' . $deposit . ' грн.</span>
  </label>
  </div>';
                    $pay .= '</div>';

                } elseif ($coin) {
                    $pay .= '<div class="form-group form-group-sm"><label for="coin_' . $this->post->shop . '">Можно использовать</label>';
                    $pay .= '<div class=" mg-b-0">
  <label class="ckbox">
    <input type="checkbox" name="dostavka[' . $this->post->shop . '][bonus]" id="bonus_' . $this->post->shop . '"  value="1">'
                        . '<span>' . $this->trans->get("Бонус") . ' - ' . $coin . ' грн.</span>
  </label>
  </div>';
                    $pay .= '</div>';
                } elseif ($deposit) {
                    $pay .= '<div class="form-group form-group-sm"><label for="coin_' . $this->post->shop . '">Можно использовать</label>';
                    $pay .= '<div class=" mg-b-0">
  <label class="ckbox">
    <input type="checkbox" name="dostavka[' . $this->post->shop . '][deposit]" id="deposit_' . $this->post->shop . '" value="1">'
                        . '<span>' . $this->trans->get("Ваш депозит") . ' - ' . $deposit . ' грн.</span>
  </label>
  </div>';
                    $pay .= '</div>';
                }
            }


            $pay .= '<div class="alert alert-info alert-dismissible fade show" role="alert">
                   <h5 class="alert-heading">Доставка:</h5><hr>
                   <p>' . $pays[0]->delivery->getTime() . '</p>
                   <p> Стоимость доставки: ' . $pays[0]->delivery->prices . '</p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
            $pay .= '<div class="alert alert-warning  alert-dismissible fade show" role="alert">
                   <h5 class="alert-heading">Варианты возврата:</h5><hr>
                   <p><b>' . $pays[0]->delivery->getReturn() . '</b></p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';


            switch ($this->post->delivery_type_id) {
                case '3':

                    $info .= '<div class="col-sm-12 col-md-12"><div class="alert alert-danger" role="alert">
                                        <h4 class="alert-heading">Обратите внимание!</h4>
                                        <p>Ожидайте смс о том что Ваш заказ доставлен в пункт видачи и только тогда приходите за ним.<br>
                                        <b>Режим работы: с 10:00 до 21:00</b>
                                        </p>
                                        <hr>
<p>Для получения заказа в пункте самовывоза, его нужно оплатить в полном размере, после чего Вы сможете посмотреть и примерить товар. <br>Если товар не подошел, Вы сможете оформить возврат не выходя с пункта самовывоза или в течении 14 дней, и получить деньги обратно.</p>
                                    </div></div>';
                    $info .= '<div class="col-sm-12 col-md-12"><div class="alert alert-dark" role="alert">' . $this->trans->get('<p>г. Киев</p>
							<p>проспект Победы, 98/2</p>
							<p>между метро "Нивки" и "Святошино"</p>
							<p>пн-вс: 10:00-21:00</p>
							<p>(093) 854 23 53</p>
							<p>(067) 406 90 80</p>') . '</div></div>';
                    break;
                case '4':
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="index_' . $this->post->shop . '">' . $this->trans->get("Индекс") . '<span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][index]" type="text" id="index_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->index . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="obl_' . $this->post->shop . '">' . $this->trans->get("Область") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][obl]" type="text" id="obl_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->obl . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="city_' . $this->post->shop . '">' . $this->trans->get("Город") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][city]" type="text" id="city_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->city . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="rayon_' . $this->post->shop . '">' . $this->trans->get("Район") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][rayon]" type="text" id="rayon_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->rayon . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="street_' . $this->post->shop . '">' . $this->trans->get("Улица") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][street]" type="text" id="street_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->street . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="house_' . $this->post->shop . '">' . $this->trans->get("Дом") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][house]" type="text" id="house_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->house . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="flat_' . $this->post->shop . '">' . $this->trans->get("Квартира") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][flat]" type="text" id="flat_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->flat . '">';
                    $info .= '</div></div>';
                    break;
                case '8':
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="city_np_' . $this->post->shop . '">' . $this->trans->get("Город") . ' <span class="red">*</span></label>';
                    $info .= '<select class="form-control city-select2-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][city]" id="city_' . $this->post->shop . '" required ></select>';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="warehouses_np_' . $this->post->shop . '">' . $this->trans->get("Склад") . ' <span class="red">*</span></label>';
                    $info .= '<select class=" form-control warehouses-select2-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][warehouses]" id="warehouses_' . $this->post->shop . '" required ></select>';
                    $info .= '</div></div>';
                    break;
                case '9':
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="street_' . $this->post->shop . '">' . $this->trans->get("Улица") . ' <span class="red">*</span></label>';
                    $info .= '<select class="form-control street-select-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][street]"  id="street_' . $this->post->shop . '" required ></select>';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="house_' . $this->post->shop . '">' . $this->trans->get("Дом") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][house]" type="text" id="house_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->house . '">';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="flat_' . $this->post->shop . '">' . $this->trans->get("Квартира") . ' <span class="red">*</span></label>';
                    $info .= '<input class="form-control" name="dostavka[' . $this->post->shop . '][flat]" type="text" id="flat_' . $this->post->shop . '" required value="' . $this->ws->getCustomer()->flat . '">';
                    $info .= '</div></div>';
                    break;
                case '18':
                    $dep = wsActiveRecord::useStatic('JustinCities')->findAll(['active' => 1]);

                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="city_np_' . $this->post->shop . '">' . $this->trans->get("Город") . ' <span class="red">*</span></label>';
                    $info .= '<select class="form-control city-select2-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][city]" id="city_' . $this->post->shop . '" required data-placeholder="Виберите со списка" >';

                    $info .= '<option label="Виберите со списка"></option>';
                    foreach ($dep as $d) {
                        $info .= '<option value="' . $d->uuid . '">' . $d->getName() . '</option>';
                    }
                    $info .= '</select>';
                    $info .= '</div></div>';
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="warehouses_np_' . $this->post->shop . '">' . $this->trans->get("Склад") . ' <span class="red">*</span></label>';
                    $info .= '<select class=" form-control warehouses-select2-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][warehouses]" id="warehouses_' . $this->post->shop . '" required data-placeholder="Виберите со списка" ></select>';
                    $info .= '</div></div>';
                    break;
                case '20' :
                    $dep = wsActiveRecord::useStatic('DeliveryType')->findAll(['active' => 1, 'shop' => 1]);
                    $info .= '<div class="col-sm-12 col-md-6"><div class="form-group form-group-sm"><label for="shop_' . $this->post->shop . '">' . $this->trans->get("Адресс") . ' <span class="red">*</span></label>';
                    $info .= '<select class="form-control city-select2-' . $this->post->shop . '" name="dostavka[' . $this->post->shop . '][delivery_shop_id]" id="shop_' . $this->post->shop . '" required data-placeholder="Виберите со списка" >';

                    $info .= '<option label="Виберите со списка"></option>';
                    foreach ($dep as $d) {
                        $info .= '<option value="' . $d->id . '">' . $d->getName() . '</option>';
                    }
                    $info .= '</select>';
                    break;
                default :
                    $info .= 'Error';
                    break;
            }

            $res = ['pay' => $pay, 'info' => $info];
        }

        die(json_encode($res));
    }

    /**
     * Корзина
     */
    public function cartAction()
    {
        if ($this->get->dellkupon) {
            unset($_SESSION['kupon']);
            $this->_redir('basket');
        } elseif ('delete' == $this->cur_menu->getParameter()) {
            $basket = $_SESSION['basket'];
            if ($basket) {
                $_SESSION['basket'] = [];
                foreach ($basket as $key => $value) {
                    if ($key != (int)$this->get->getPoint()) {
                        $_SESSION['basket'][] = $value;
                    }
                }
                $this->view->basket = $this->basket = &$_SESSION['basket'];
                if ($this->ws->getCustomer()->getIsLoggedIn()) {
                    $this->ws->getCustomer()->getCart()->updateCart();
                }
                $this->_redir('basket');
            }
        } elseif ('change' == $this->cur_menu->getParameter() && $this->get->getCount()) {
            if (isset($_SESSION['basket'][(int)$this->get->getPoint()]['count'])) {
                $count = $this->get->getCount();
                $_SESSION['basket'][(int)$this->get->getPoint()]['count'] = $count;
            }
            $this->_redir('basket');
        } elseif ('clear' == $this->cur_menu->getParameter()) {

            $_SESSION['basket'] = $this->basket = [];
            $c = $this->ws->getCustomer()->getCart();
            if ($c->id) {
                $c->clearCart();
            }
            $this->_redir('basket');
        } elseif ($this->get->l) {
            $refer = false;
            if ($this->get->referral) {
                $refer = $this->get->referral;
            }
            if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                $e = new EncodeDecode();
                $hash = $e->decode($this->get->l);
                // $customer = wsActiveRecord::useStatic('Customer')->findFirst(['hash_id' => (string)$hash]);
                // if($customer->id){
                $res = $this->ws->getCustomer()->loginByHash($hash);
                if ($res) {
                    $this->website->updateHashes();
                    // $this->ws->getCustomer()->updateCartUserReturn();
                    //l($_SESSION['basket']);
                    if ($refer) {
                        $this->_redirect($refer);
                    }
                    // $this->_redir('basket');
                }
                //}
            }
            if ($refer) {
                $this->_redirect($refer);
            }
            $this->_redir('basket');
        }
        $error = [];

        if (count($_POST)) {
            if (isset($_POST['tostep2'])) {
                foreach ($_POST as &$value) {
                    $value = stripslashes(trim($value));
                }

                unset($_SESSION['cart']);

                $_SESSION['cart'] = $_POST;
                if (!isset($_SESSION['basket_contacts'])) {
                    $_SESSION['basket_contacts'] = [];
                }

                $this->_redirect('/shop-checkout-step2/');
            } elseif (isset($_POST['orders_create_submit'])) {
                $errors = [];
                // $dostavka = [];
                // foreach ($_POST as &$value){ $value = stripslashes(trim($value)); }
                if (isset($_SESSION['orders'])) {
                    $tel = substr(preg_replace('/[^0-9]/', '', $_POST['telephone']), -10);
                    $_POST['telephone'] = '38' . $tel;
                    if (!$this->ws->getCustomer()->getIsLoggedIn()) {
                        $customer = wsActiveRecord::useStatic('Customer')->findFirst(['email' => $_POST['email'], 'phone1 LIKE  "%' . $tel . '" ']);
                        if ($customer->id) {
                            $res = $this->ws->getCustomer()->loginAdminByUsername($customer->username);
                            // $res = $cus->loginByUsername($customer->getEmail(), $newPass);
                            if ($res) {
                                $this->website->updateHashes();
                            }
                            // $this->ws->upadteHashes();
                            $_POST['customer_id'] = $customer->id;
                            $_POST['skidka'] = $customer->real_skidka;
                            $count_order_magaz = $customer->count_order_magaz;
                        } else {
                            if (wsActiveRecord::useStatic('Customer')->findByEmail($_POST['email'])->count() != 0) {
                                $errors['email'] = $this->trans->get('Такой email уже используется.<br /> Поменяйте email или зайдите как зарегистрированный пользователь') . '.';
                            } elseif (wsActiveRecord::useStatic('Customer')->findFirst([" phone1 LIKE  '%" . $tel . "' "])) {
                                $errors['telephone'] = $this->trans->get('Пользователь с таким номером телефона уже зарегистрирован в системе.<br /> Поменяйте телефон или зайдите как зарегистрированный пользователь') . ".";
                            } else {
                                $customer = $this->create_customer($_POST);
                                if ($customer->id) {

                                    $_POST['customer_id'] = $customer->id;
                                    $_POST['skidka'] = $customer->real_skidka;
                                    $count_order_magaz = $customer->count_order_magaz;
                                } else {
                                    $errors['telephone'] = "Ошибка при регистрации нового пользователя, измените Email или Телефон и попробуйте еще раз.";
                                }
                            }

                        }

                    } else {

                        $customer = $this->ws->getCustomer();
                        $count_order_magaz = $customer->count_order_magaz;
                    }
                    if ($this->ws->getCustomer()->getId() != 8005 && $this->ws->getCustomer()->isBloskOrder()) {
                        $this->_redir('block_order');
                    }

                    if (count($errors) < 1) {
                        $curdate = Registry::get('curdate');
                        $_POST['status'] = 100;
                        $_POST['lang_id'] = Registry::get('lang_id');
                        $_POST['date_create'] = $curdate->getFormattedMySQLDateTime();
                        $_POST['liqpay_status_id'] = 1;
                        $_POST['quick'] = 0;


                        $create = [];
                        foreach ($_SESSION['orders'] as $k => $r) {
                            $mas_adres = [];
                            if (!$_POST['dostavka'][$k]['delivery_type_id']) {
                                $errors[] = $this->trans->get('Способ доставки');
                            }
                            if (!$_POST['dostavka'][$k]['payment_method_id']) {
                                $errors[] = $this->trans->get('Способ оплаты');
                            }
                            switch ($_POST['dostavka'][$k]['delivery_type_id']) {
                                case '3':
                                    $mas_adres[] = 'г. Киев';
                                    break;
                                case '4':
                                    $mas_adres[] = $_POST['dostavka'][$k]['index'];
                                    $mas_adres[] = $_POST['dostavka'][$k]['obl'];
                                    $mas_adres[] = $_POST['dostavka'][$k]['rayon'];
                                    $mas_adres[] = 'г. ' . $_POST['dostavka'][$k]['city'];
                                    $mas_adres[] = 'ул.' . $_POST['dostavka'][$k]['street'];
                                    $mas_adres[] = 'д.' . $_POST['dostavka'][$k]['house'];
                                    $mas_adres[] = 'кв.' . $_POST['dostavka'][$k]['flat'];
                                    break;
                                case '8':
                                    $_POST['dostavka'][$k]['city_np'] = $_POST['dostavka'][$k]['city'];
                                    require_once('np/NovaPoshta.php');
                                    $lang = Registry::get('lang');
                                    if ($lang == 'uk') {
                                        $lang = 'ua';
                                    }
                                    $np = new NovaPoshta($lang);

                                    $_POST['dostavka'][$k]['city'] = $np->getCitiesName($_POST['dostavka'][$k]['city']);
                                    $_POST['dostavka'][$k]['sklad'] = $np->getWarehousesName($_POST['dostavka'][$k]['city_np'], $_POST['dostavka'][$k]['warehouses']);

                                    $mas_adres[] = 'г. ' . $_POST['dostavka'][$k]['city'];
                                    $mas_adres[] = 'НП: ' . $_POST['dostavka'][$k]['sklad'];
                                    break;
                                case '9':
                                    $mas_adres[] = 'г. Киев';
                                    $mas_adres[] = 'ул.' . $_POST['dostavka'][$k]['street'];
                                    $mas_adres[] = 'д.' . $_POST['dostavka'][$k]['house'];
                                    $mas_adres[] = 'кв.' . $_POST['dostavka'][$k]['flat'];
                                    break;
                                case '18':
                                    $_POST['dostavka'][$k]['city_justin'] = $_POST['dostavka'][$k]['city'];
                                    $_POST['dostavka'][$k]['city'] = JustinCities::getCityName($_POST['dostavka'][$k]['city']);
                                    $_POST['dostavka'][$k]['sklad'] = JustinDepartments::getDepartment($_POST['dostavka'][$k]['warehouses']);
                                    $mas_adres[] = 'г. ' . $_POST['dostavka'][$k]['city'];
                                    $mas_adres[] = 'Justin: ' . $_POST['dostavka'][$k]['sklad'];
                                    break;
                                case '20':
                                    $_POST['dostavka'][$k]['city'] = 'г. Киев';
                                    // $_POST['dostavka'][$k]['sklad'] = DeliverySopType::geAdress($_POST['dostavka'][$k]['delivery_shop_id']);
                                    $mas_adres[] = 'г. Киев';
                                    $mas_adres[] = DeliveryType::Adress($_POST['dostavka'][$k]['delivery_shop_id']);
                                    $_POST['dostavka'][$k]['delivery_type_id'] = $_POST['dostavka'][$k]['delivery_shop_id'];
                                    unset($_POST['dostavka'][$k]['delivery_shop_id']);
                                    break;
                                default :
                                    break;
                            }
                            $_POST['dostavka'][$k]['address'] = implode(', ', $mas_adres);
                            $_POST['dostavka'][$k]['delivery_cost'] = DeliveryPayment::getPriceDelivery($_POST['dostavka'][$k]['delivery_type_id'], $_POST['dostavka'][$k]['payment_method_id']);
                            $_POST['dostavka'][$k]['fop_id'] = DeliveryPayment::getFop($_POST['dostavka'][$k]['delivery_type_id'], $_POST['dostavka'][$k]['payment_method_id']);

                            $create[$k]['order'] = $_POST['dostavka'][$k];
                        }

                        if (!$errors) {
                            unset($_POST['dostavka']);
                            foreach ($_SESSION['orders'] as $k => $r) {
                                foreach ($_POST as $pk => $p) {
                                    if (is_array($p)) {
                                        foreach ($p as $pkp => $pz) {
                                            $create[$k]['order'][$pz] = 1;
                                        }
                                    } else {
                                        $create[$k]['order'][$pk] = $p;
                                    }
                                    $create[$k]['order']['shop_id'] = $k;
                                    //  if($this->ws->getCustomer()->isAdmin()){
                                    $create[$k]['order']['magaz'] = DeliveryType::getIsShop($create[$k]['order']['delivery_type_id']);
                                    // l($create[$k]['order']);
                                    // exit();
                                    //  }

                                }
                                //  if($this->ws->getCustomer()->isAdmin()){
                                //     $create[$k]['order']['is_admin'] = 1;
                                // }
                                $create[$k]['articles'] = $r;
                            }

                            $res = [];
                            foreach ($create as $c) {
                                $res[] = $this->create_order($c['order'], $c['articles'])->id;
                            }
                            $this->basket = $this->view->basket = $_SESSION['basket'] = [];
                            $this->basket_articles = $this->view->basket_articles = $_SESSION['basket_articles'] = [];

                            if ($this->ws->getCustomer()->getCart()) {
                                $this->ws->getCustomer()->getCart()->clearCart();
                            }

                            $_SESSION['orders'] = $res;
                            $this->_redirect(SITE_URL . '/ordersucces/');
                        } else {
                            $this->view->errors = $errors;
                        }
                    } else {
                        $this->view->errors = $errors;
                    }// net errors
                }

            }
        }
        $card = [];
        $card_article = [];
        $total_price = 0;
        // $param = [];
        $total_price_minus = 0;
        // $customer = $this->ws->getCustomer();
        // $boll = $customer->getIsLoggedIn();
        $sum_order = Cart::getSumCard($this->ws->getCustomer()->getIsLoggedIn() ? $this->ws->getCustomer()->getId() : false);
        $_SESSION['or_sum'] = $param['sum_order'] = $sum_order;
        // unset($_SESSION['kupon']);
        /*   if($this->get->kupon){
             // echo $this->get->kupon;
             $k =   Other::findActiveCode($this->get->kupon);
             if($k['flag']){
                 $_SESSION['kupon'] = $k['cod'];
                 $param['kupon'] = $k['cod'];
                  $this->view->kupon_a = $k;
                  $this->view->kupon = $k;
                 //$this->view->mes_kupon = $k['message'];
             }
             $this->view->kupon_a = $k;
            // print($k['message']);
           }elseif(isset($_SESSION['kupon'])){
               $k =   Other::findActiveCode($_SESSION['kupon']);
               if($k['flag']){
                 $param['kupon'] = $k['cod'];
                 $this->view->kupon = $k;
             }else{
                 unset($_SESSION['kupon']);
             }
           }
           if ($boll) {
               $param['customer_id'] = $customer->id;
               $param['skidka'] = $customer->getDiscont(false, 0, true);
               $param['event_skidka'] = EventCustomer::getEventsDiscont($customer->getId());
               $param['now_orders'] = $customer->getSumOrderNoNew();
               }
         * */
        // unset($_SESSION['promo']);
        $promo = [];
        if (isset($_SESSION['promo']) && !empty($_SESSION['promo'])) {
            $k = (object)Other::findActiveCode($_SESSION['promo']);
            if ($k->flag) {
                //  $promo = ['cod'=> $k->cod, 'value' => $k->skidka];
                //  $this->view->promo =  (object)$promo;

                if (Other::flagCodeMinSumActive($k->cod, Cart::sumCartSession())) {
                    $promo = ['cod' => $k->cod, 'value' => $k->skidka];
                    $this->view->promo = (object)$promo;
                }
            } else {
                unset($_SESSION['promo']);
            }
        }
        //  l($promo);
        // echo $sum_order;
        $id_in_cart = [];
        foreach ($this->basket as $key => $item) {
            if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) {
                $id_in_cart[] = $item['article_id'];
                $_SESSION['basket'][$key]['option_price'] = 0;
                $_SESSION['basket'][$key]['option_id'] = 0;
                $_SESSION['basket'][$key]['skidka_block'] = 0;

                $card_article[$key] = Cart::getArticles($article, $item, $promo);

                if ($_SESSION['basket'][$key]['price'] != $card_article[$key]['price']) {
                    $_SESSION['basket'][$key]['price'] = $card_article[$key]['price'];
                }

                $_SESSION['basket'][$key]['skidka'] = $card_article[$key]['skidka'];

                if ($card_article[$key]['option_id']) {
                    $_SESSION['basket'][$key]['option_id'] = $card_article[$key]['option_id'];
                }
                if ($card_article[$key]['option_price']) {
                    $_SESSION['basket'][$key]['option_price'] = $card_article[$key]['option_price'];
                }
                if ($card_article[$key]['skidka_block']) {
                    $_SESSION['basket'][$key]['skidka_block'] = $card_article[$key]['skidka_block'];
                }

                $total_price += ($card_article[$key]['price'] * $card_article[$key]['count']);
                $total_price_minus += $card_article[$key]['minus'] * $card_article[$key]['count'];
            }

        }

        $card['articles'] = [];
        foreach ($card_article as $key => $a) {
            $card['articles'][$a['shop_id']][$key] = $a;
        }

        // $card['article'] = $card_article;
        $card['total_price'] = $_SESSION['total_price'] = $total_price;
        $card['total_price_minus'] = $total_price_minus;

        $c = $this->ws->getCustomer()->getCart();
        if ($c->id) {
            $c->updateCart();
        } elseif ($this->ws->getCustomer()->getIsLoggedIn() and count($card_article)) {
            $this->ws->getCustomer()->newCart();
        }
        if (isset($error)) {
            $this->view->error = $error;
        }
        $css = [
            '/js/select2/css/select2.min.css',
            '/js/slider-fhd/slick.css',
            '/css/article/article.css',
        ];
        $scripts = [
            '/js/np/np_api.js',
            '/js/slider-fhd/slick.min.js',
            '/js/select2/js/select2.min.js',
            '/js/parsleyjs/parsley.js',
            '/js/jquery.maskedinput.min_1.js',
            '/js/basket/basket.js'
        ];
        $this->view->css = $css;
        $this->view->scripts = $scripts;
        $this->view->card = $card;

        $this->view->history = $this->history(implode(',', $id_in_cart));

        // if(count($card['articles']) > 1 or ($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isAdmin())){
        echo $this->render('cart/cart_shop.tpl.php');
        //  }else{
        //  echo $this->render('cart/cart.tpl.php');
        //  }


    }

    /**
     * История просмотренного товьара
     *
     * @param type $id - список товаров в корзине
     * @return boolean
     */
    function history($id = '')
    {
        if ($this->ws->getCustomer()->getId()) {
            $sql = "SELECT  `ws_articles`. * 
FROM  `ws_articles` 
INNER JOIN  `ws_articles_history` ON  `ws_articles`.`id` =  `ws_articles_history`.`article_id` 
WHERE  `ws_articles_history`.`customer_id` =" . $this->ws->getCustomer()->getId() . "
AND ws_articles.`stock`  not like '0' and `ws_articles`.`status` = 3 ";
            if (!empty($id)) {
                $sql .= " and ws_articles.id not in('{$id}')";
            }

            $sql .= " GROUP BY  `ws_articles`.`id` 
ORDER BY  `ws_articles_history`.`id` DESC 
LIMIT 6";
            return wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
        } elseif (count($_SESSION['hist']) > 0) {
            $id_ses = implode(',', array_unique($_SESSION['hist']));
            $sql = "SELECT  `ws_articles`. * 
FROM  `ws_articles` 
INNER JOIN  `ws_articles_history` ON  `ws_articles`.`id` =  `ws_articles_history`.`article_id` 
WHERE   ws_articles.`stock`  not like '0' and `ws_articles`.`status` = 3 ";
            if (!empty($id)) {
                $sql .= " and ws_articles.id not in('{$id}')";
            }

            $sql .= "
    and ws_articles.id in(" . $id_ses . ")
GROUP BY  `ws_articles`.`id` 
ORDER BY  `ws_articles_history`.`id` DESC 
LIMIT 6";
            return wsActiveRecord::useStatic('Shoparticles')->findByQuery($sql);
        } else {
            return false;
        }
    }


    public function _redir($param)
    {
        if ($param == 'index') {
            $this->_redirect(SITE_URL . '/');

        } else {
            $this->_redirect(SITE_URL . '/' . $param . '/');

        }
    }

    /**
     * создание заказа
     * @param type $orders - массив данных по заказу
     * @param type $articles - массив товаров в заказе
     * @return \Shoporders - обьект заказа
     */
    public function create_order($orders = null, $articles = [])
    {
        $curdate = Registry::get('curdate');
        $bonus = 0;
        $deposit = 0;
        if (isset($orders['bonus'])) {
            $bonus = 1;
            unset($orders['bonus']);
        }
        if (isset($orders['deposit'])) {
            $deposit = 1;
            unset($orders['deposit']);
        }
        $order = new Shoporders();
        $order->import($orders);
        $order->save();

        if (!$order->getCustomerId()) {
            $usr = Customer::findByUsername($order->getEmail());
            if ($usr->getId()) {

                $order->setCustomerId($usr->getId());
                $order->save();
            }
        }

        $customer = new Customer($order->customer_id);
        if ($customer->isAdmin()) {
            //$order->setIsAdmin(1);
        }

        foreach ($articles as $article) {
            $item = new Shoparticles($article['id']);
            $itemcs = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(
                [
                    'id_article' => $article['id'],
                    'id_size' => $article['id_size'],
                    'id_color' => $article['id_color']
                ]);
            $data = [];
            if ($itemcs->getCount() > 0) {
                $item->setStock($item->getStock() - $article['count']);
                $item->save();

                $itemcs->setCount($itemcs->getCount() - $article['count']);
                $itemcs->save();

                $data = $article;
                unset($data['id']);
                unset($data['size']);
                unset($data['color']);
                $data['order_id'] = $order->getId();
                $data['article_id'] = $item->id;
                $data['price'] = $item->getRealPrice();
                $data['old_price'] = $item->getOldPrice();
                $data['artikul'] = $itemcs->code;
                $data['ucenka'] = $item->ucenka;
                $data['size'] = $data['id_size'];
                $data['color'] = $data['id_color'];
                // $data['event_skidka'] = $event_skidka_klient;
                // $data['event_id'] = $event_skidka_klient_id;
            } else {
                $article['count'] = 0;
                $article['title'] = $article['title'] . ' (нет на складе)';
                $data = $article;
                $data['article_id'] = $data['id'];
                unset($data['id']);
                unset($data['id']);
                unset($data['size']);
                unset($data['color']);
                $data['order_id'] = $order->getId();
                $data['size'] = $data['id_size'];
                $data['color'] = $data['id_color'];
            }
            $a = new Shoporderarticles();
            $a->import($data);
            $a->save();
        }


        $order->reCalculate();

        if (($order->getDeliveryTypeId() == 8) && $order->getPaymentMethodId() == 3) {
            $order->setDeliveryTypeId(16);
            $order->save();
        }

        $s_or_sum = $order->getOrderAmountCoin();
        $sum_coin_user = $customer->getSummCoin('active');
        if ($bonus and $s_or_sum > 0 and $sum_coin_user) {
            $coin = $customer->getAllCoin('active');
            $total_price = $s_or_sum;
            $scoin = 0;
            foreach ($coin as $m) {
                if ($m->coin <= $total_price) {
                    $total_price -= $m->coin;
                    $scoin += $m->coin;
                    BonusHistory::add($order->customer_id, 'Использован', $m->coin, $order->id);
                    $m->setCoinOn($m->coin_on + $m->coin);
                    $m->setCoin(0);
                    $m->setStatus(3);
                    $m->save();

                } else {
                    $m->setCoin($m->coin - $total_price);
                    $m->setCoinOn($m->coin_on + $total_price);
                    $scoin += $total_price;
                    BonusHistory::add($order->customer_id, 'Использован', $total_price, $order->id);
                    $total_price = 0;
                    $m->save();
                }
            }
            if ($scoin >= $order->amount) {
                $scoin--;
            }
            $this->view->coin = $scoin;
            $order->setBonus($scoin);
            $amount = $order->amount - $scoin;
            $order->setAmount($amount);
            $order->save();
            OrderHistory::newHistory($order->customer_id, $order->id, ' Клиент использовал бонус (' . $scoin . ') redcoin. ', '');

            //perevod v novu pochtu esly polnosty oplachen depositom


            if ($order->getDeliveryTypeId() == 16 and $order->getAmount() == 0) {
                $order->setDeliveryTypeId(8);
                $order->setPaymentMethodId(9);
                $order->setFopId(DeliveryPayment::getFop(8, 9));
            } elseif ($order->getDeliveryTypeId() == 4 and $order->getAmount() == 0) {
                $order->setPaymentMethodId(9);
                $order->setFopId(DeliveryPayment::getFop(4, 9));
            }
            //perevod v novu pochtu esly polnosty oplachen depositom
        } elseif ($deposit and $order->getAmount() > 0 and $customer->getDeposit()) {

            $total_price = $order->getAmount();

            $dep = $this->ws->getCustomer()->getDeposit();

            if (($total_price - $dep) < 0) {
                $dep -= $total_price;
                $deposit = $total_price;
                $total_price = 0;
            } else {
                $total_price -= $dep;
                $deposit = $dep;
                $dep = 0;
            }
            $order->setDeposit($deposit);
            $order->setAmount($total_price);
            $order->save();

            //perevod v novu pochtu esly polnosty oplachen depositom
            if ($order->getDeliveryTypeId() == 16 and $order->getAmount() == 0) {
                $order->setDeliveryTypeId(8);
                $order->setPaymentMethodId(8);
                $order->setFopId(DeliveryPayment::getFop(8, 8));
            } elseif ($order->getDeliveryTypeId() == 4 and $order->getAmount() == 0) {
                $order->setPaymentMethodId(8);
                $order->setFopId(DeliveryPayment::getFop(4, 8));
            }
            //perevod v novu pochtu esly polnosty oplachen depositom

            //$customer = new Customer($order->customer_id);
            $customer->setDeposit($dep);
            $customer->save();
            OrderHistory::newHistory($customer->getId(), $order->getId(), ' Клиент использовал депозит (' . $order->getDeposit() . ') грн. ', 'Осталось на депозите "' . $customer->getDeposit() . '"');

            $no = '-';
            DepositHistory::newDepositHistory($customer->getId(), $customer->getId(), $no, $order->getDeposit(), $order->getId());

            $this->view->deposit = $deposit;
        }
        $order->getDeliveryPriceReload();// обновить стоимость доставки

        if ($order->getDeliveryTypeId() == 8 or $order->getDeliveryTypeId() == 16) {
            $new_np = Shopordersmeestexpres::newOrderNp($order->getId(), $orders['city_np'], $orders['warehouses']);
            $order->setMeestId($new_np);
            $order->save();
        } elseif ($order->getDeliveryTypeId() == 18) {
            $new_np = JustinDepartmentToOrder::newOrderJastin($order->getId(), $orders['city_justin'], $orders['warehouses']);
            // $order->setMeestId($new_np);
            //  $order->save();
        }

        $this->view->order = $order;
        // otpravka email
        if (!$customer->isBlockEmail()) {

            $msg = $this->render('email/basket.tpl.php');
            $subject = Config::findByCode('email_order_subject')->getValue();

            EmailLog::add($subject, $msg, 'new_order', $customer->getId(), $order->getId()); //сохранение письма отправленного пользователю

            SendMail::getInstance()->sendEmail($order->getEmail(), $order->getName(), $subject, $msg);
        }

        OrderHistory::newOrder($customer->getId(), $order->getId(), ($order->getAmount() + $order->getDeposit() + $order->getBonus()), $order->getArticlesCount());

        if ($customer->isAdmin() and $customer->getTelegram()) {
            $message = 'Ваш заказ № ' . $order->getId() . ' оформлен. Сумма к оплате ' . $order->calculateOrderPrice2() . ' грн. Телефон (044) 224-40-00';
        } else {
            $phone = Number::clearPhone($order->getTelephone());

            require_once('alphasms/smsclient.class.php');
            $sms = new SMSClient(Config::findByCode('sms_login')->getValue(), Config::findByCode('sms_pass')->getValue(), Config::findByCode('sms_key')->getValue());
            $id = $sms->sendSMS(Config::findByCode('sms_alphaname')->getValue(), $phone, $this->trans->get('Vash zakaz') . ' № ' . $order->getId() . ' ' . $this->trans->get('Summa') . ' ' . $order->calculateOrderPrice2() . ' grn. tel. (044) 224-40-00');

            if ($sms->hasErrors()) {
                $res = $sms->getErrors();
            } else {
                $res = $sms->receiveSMS($id);
            }
            wsLog::add('Order:' . $order->id . ' to SMS: ' . $phone . ' - ' . $res, 'SMS_' . $res);

        }
        if ($order->getDeliveryTypeId() == 16) {
            $find_count_orders_by_user = wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId(), 'delivery_type_id' => 16));

            if ($find_count_orders_by_user == 1) {
                $remark = new Shoporderremarks();
                $com = [
                    'order_id' => $order->getId(),
                    'date_create' => $curdate->getFormattedMySQLDateTime(),
                    'remark' => "Первый заказ наложкой!!! Не отправлять до уточнения деталей"
                ];

                $remark->import($com);
                $remark->save();
            } elseif (wsActiveRecord::useStatic('Shoporders')->count(array('customer_id' => $order->getCustomerId(), 'delivery_type_id' => 16, 'status' => 6)) > 3) {
                $remark = new Shoporderremarks();
                $com = [
                    'order_id' => $order->getId(),
                    'date_create' => $curdate->getFormattedMySQLDateTime(),
                    'remark' => "Не отправлять пока не заберет заказы!"
                ];
                $remark->import($com);
                $remark->save();
            }
        }

        return $order;
    }

    private function create_customer($order = null)
    {
        if (!$this->ws->getCustomer()->getIsLoggedIn()) {
            $allowedChars = 'abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789';
            $newPass = '';
            $allowedCharsLength = strlen($allowedChars);

            while (strlen($newPass) < 8) {
                $newPass .= $allowedChars[rand(0, $allowedCharsLength - 1)];
            }

            $customer = new Customer();
            $customer->setUsername($order['email']);
            $customer->setPassword(md5($newPass));
            $customer->setCustomerTypeId(1);
            $customer->setFirstName($order['name']);
            $customer->setMiddleName($order['middle_name']);
            $customer->setEmail($order['email']);
            $customer->setPhone1(Number::clearPhone($order['telephone']));
            $customer->setCity($order['city']);
            $customer->setObl($order['obl']);
            //$customer->setAdress($order->getAddress());
            $customer->setRayon($order['rayon']);
            $customer->setIndex($order['index']);
            $customer->setStreet($order['street']);
            $customer->setHouse($order['house']);
            $customer->setFlat($order['flat']);
            $res = $customer->save();
            // Telegram::sendMessageTelegram(404070580, $res);

            $coin = new RedCoin();
            $coin->import(
                [
                    'coin' => 50,
                    'customer_id' => $customer->getId(),
                    'status' => 2,
                    'order_id_add' => 0,
                    'date_add' => date("Y-m-d"),
                    'date_active' => date("Y-m-d"),
                    'date_off' => date("Y-m-d", strtotime("now +50 days"))
                ]
            );
            $coin->save();
            BonusHistory::add($customer->getId(), 'Зачислено', 50, 0);

            $subscriber = new Subscriber();
            $subscriber->setSegmentId(1);
            $subscriber->setCustomerId($customer->getId());
            $subscriber->setName($customer->name);
            $subscriber->setEmail($customer->getEmail());
            $subscriber->setConfirmed(date('Y-m-d H:i:s'));
            $subscriber->setActive(1);
            $subscriber->save();

            $this->view->login = $customer->getEmail();
            $this->view->pass = $newPass;
            $subject = 'Создан акаунт';
            $msg = $this->render('email/new-customer.tpl.php');
            EmailLog::add($subject, $msg, 'new_customer', $res);
            SendMail::getInstance()->sendEmail($customer->getEmail(), $customer->getFirstName(), $subject, $msg);

            $cus = $this->ws->getCustomer();
            $res = $cus->loginByUsername($customer->getEmail(), $newPass);
            if ($res) {
                $this->website->updateHashes();
            }
            return $customer;
        } else {
            return $this->ws->getCustomer();
        }

        return;
    }
}
