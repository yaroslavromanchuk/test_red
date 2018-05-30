<?php

class MeestexpresController extends controllerAbstract
{

  public function getmistcityAction()
    { 
	require_once('meestexpress/include.php');
	$api = new MeestExpress_API('red_ua','3#snlMO0sP','35a4863e-13bb-11e7-80fc-1c98ec135263');
	$strana = 'C35B6195-4EA3-11DE-8591-001D600938F8';
		if($this->get->what == 'city'){
		$city = $api->getCity($this->get->term, $strana);
		$mas = array();
		$i = 0;
		foreach ($city as $c) {	
		$mas[$i]['label'] = (string)$c->DescriptionRU.' ( '.$c->RegionDescriptionRU.' обл. '.$c->DistrictDescriptionRU.' р-н )';
		$mas[$i]['value'] = (string)$c->DescriptionRU.' ( '.$c->RegionDescriptionRU.' обл. '.$c->DistrictDescriptionRU.' р-н )';
		$mas[$i]['id'] = (string)$c->uuid; 
		$i++;
			}
			echo json_encode($mas);
		
		}
		
		if ($this->get->what == 'branch') {
		$branch = $api->getBranch($this->get->term);
		
	$text = '';
    foreach ($branch as $b) {
	$text.='<option dat-value="'.$b->DescriptionRU.'" value="'.$b->UUID.'">'.$b->DescriptionRU.'</option>';
    }	
		 die($text);
	}
		
		
        die();
    }
}
?>