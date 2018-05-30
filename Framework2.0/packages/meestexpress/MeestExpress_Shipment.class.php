<?php
/**
 * Объект "отправки" Meest Express.
 * Класс-массив (обертка)
 *
 * @author    Maxim Miroshnichenko <max@webproduction.ua>
 * @copyright WebProduction
 * @package   MeestExpress 
 */
class MeestExpress_Shipment extends ArrayObject {

    public function __construct(MeestExpress_API $api) {
        $this->_api = $api;
    }

    public function getOrderID() {
        return @$this['orderID'];
    }

    public function setOrderID($orderID) { 
        $this['orderID'] = 'RED'.$orderID;
    }
	
	public function setSender(){
	 $this['sendername'] = 'Интернет магазин РЕД';
	 $this['senderservice'] = 1;
	  $this['senderstreetuid'] = '681D4708-E0D2-11DF-9B37-00215AEE3EBE';
        $this['senderhouse'] = 31;
        $this['senderflat'] = 1;
		$this['senderphone'] = $phone;
	}
	
    public function getSenderName() {
        return @$this['sendername'];
    }

   // public function setSenderName($name) {
       // $this['sendername'] = $name;
   // }

    public function getSenderService() {
        return (int) @$this['senderservice'];
    }

    //public function setSenderService($service) {
     //   $this['senderservice'] = 1;
    //}

    public function getSenderStreetUID() {
        return @$this['senderstreetuid'];
    }

   /* public function setSenderAddress() {
        $countryUID = $this->_getAPI()->getCountryUID($country);
        $cityUID = $this->_getAPI()->getCityUID($city, $countryUID);
        $this['senderstreetuid'] = '681D4708-E0D2-11DF-9B37-00215AEE3EBE';//$this->_getAPI()->getSteetUID($street, $cityUID);

        $this['senderhouse'] = 31;
        $this['senderflat'] = 1;
    }*/

    public function getSenderHouse() {
        return @$this['senderhouse'];
    }

    public function getSenderFlat() {
        return @$this['senderflat'];
    }

    public function getSenderPhone() {
        return @$this['senderphone'];
    }

   /* public function setSenderPhone($phone) {
        $this['senderphone'] = $phone;
    }*/

    public function getReceiverName() {
        return @$this['receivername'];
    }

    public function setReceiverName($name) {
        $this['receivername'] = $name;
    }

    public function getReceiverService() {
        return (int) @$this['receiverservice'];
    }

    public function setReceiverService($service) {
        $this['receiverservice'] = (int) $service;
    }

    public function getReceiverStreetUID() {
        return @$this['receiverstreetuid'];
    }
	 public function getReceiverBranchUID() {
        return @$this['receiverbranchuid'];
    }

  /*  public function setReceiverAddress($country, $city, $street, $house, $flat, $floor = 1) {
        $countryUID = $this->_getAPI()->getCountryUID($country);
        $cityUID = $this->_getAPI()->getCityUID($city, $countryUID);
       
	   $this['receiverstreetuid'] = $this->_getAPI()->getSteetUID($street, $cityUID);

        $this['receiverhouse'] = $house;
        $this['receiverflat'] = $flat;
        $this['receiverfloor'] = $floor;
    }*/
	 public function setReceiverAddress($street, $branch, $house, $flat, $floor = 1) {
		$this['receiverbranchuid'] = (string)$branch;
	    $this['receiverstreetuid'] = (string)$street;
        $this['receiverhouse'] = $house;
        $this['receiverflat'] = $flat;
        $this['receiverfloor'] = $floor;
		$this['paytype'] = 1;
		$this['payreceiver'] =1;
    }

    public function getReceiverHouse() {
        return @$this['receiverhouse'];
    }

    public function getReceiverFlat() {
        return @$this['receiverflat'];
    }

    public function getReceiverFloor() {
        return @$this['receiverfloor'];
    }

    public function getReceiverPhone() {
        return @$this['receiverphone'];
    }

    public function setReceiverPhone($phone) {
        $this['receiverphone'] = $phone;
    }

    public function getNotation() {
        return @$this['notation'];
    }

    public function setNotation($notation) {
        $this['notation'] = $notation;
    }

    public function getPayReceiver() {
        return (int) @$this['payreceiver'];
    }

    /*public function setPayReceiver($service) {
        $this['payreceiver'] = (int) $service;
    }*/

    public function getPayType() {
        return (int) @$this['paytype'];
    }

   /* public function setPayType($type) {
        $this['paytype'] = 1;
    }*/

    public function getSendingFormat() {
        return @$this['sendingformat'];
    }

    public function setSendingFormat($format) {
        $this['sendingformat'] = $format;
    }

    public function getSendingQuantity() {
        $x = (int) @$this['sendingquantity'];
        if ($x <= 1) {
            $x = 1;
        }
        return $x;
    }

    public function setSendingQuantity($count) {
        $this['sendingquantity'] = (int) $count;
    }

    public function getSendingWeight() {
        $x = (float) @$this['sendingweight'];
        if ($x < 0) {
            $x = 0.1;
        }
        return $x;
    }

    public function setSendingWeight($weight) {
        $this['sendingweight'] = (float) $weight;
    }
 public function getCod() {
        return (float) @$this['sendingcod'];
    }
	public function setCod($cod) {
        $this['sendingcod'] = (float) $cod;
    }


    public function getSendingInsurance() {
        return (int) @$this['sendinginsurance'];
    }

    public function setSendingInsurance($price) {
        $this['sendinginsurance'] = (int) $price;
    }

    public function getDeliveryDate() {
        return @$this['deliverydate'];
    }

    public function setDeliveryDate($date) {
        $this['deliverydate'] = $date;
    }
	public function setFormat($from, $to) {
        $this['from'] = $from;
		$this['to'] = $to;
    }
	public function getFrom() {
         return @$this['from'];
    }
	public function getTo() {
		return @$this['to'];
    }
	
	

    /**
     * Получить API родителя
     *
     * @return MeestExpress_API
     */
    private function _getAPI() {
        return $this->_api;
    }

    private $_api;

}