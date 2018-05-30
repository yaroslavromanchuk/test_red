<?php

class EventController extends controllerAbstract
{

    public function indexAction()
    {
        $this->_redirect('/');
    }

    public function activAction()
    {
        if (!$this->ws->getCustomer()->getIsLoggedIn()) {
            $this->_redirect('/account/login/');
            return;
        }

        if (!$this->get->id) {
            $this->_redirect('/event_error/');
            return;
        }

        $mss = explode('_', $this->get->id);
        if (count($mss) != 2) {
            $this->_redirect('/event_error/');
            return;
        }
		
        $event = new Event($mss[1]);
		
        if (!$event->getId()) {
            $this->_redirect('/event_error/');
            return;
        }
       // if (substr(strtotime($event->getCtime()), 3) != $mss[0]) {
           // $this->_redirect('/event_error/');
        //    return;
       // }
	 
        if (!$event->getPublick()) {
            $this->_redirect('/event_error/'); 
            return;
        }
        $find = wsActiveRecord::useStatic('EventCustomer')->findFirst(array('event_id' => $event->getId(), 'customer_id' => $this->ws->getCustomer()->getId()));
		$session = session_id();
        if ($find) {
            echo '<p>Вы уже подписаны на эту акцию.</p>';
        } else {
            $new_cev = new EventCustomer();
            $new_cev->setSessionId($session);
            $new_cev->setCustomerId($this->ws->getCustomer()->getId());
            $new_cev->setEventId($event->getId());
            $new_cev->save();
            echo '<h1 class="violet">Вы успешно подписались на акцию.</h1> <p>Вы получаете дополнительную скидку ' . $event->getDiscont() . '% на период с ' . date('d.m.Y', strtotime($event->getStart())) . ' по ' . date('d.m.Y', strtotime($event->getFinish())) . '</p>';
        }
    }
}