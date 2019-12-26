<?php
header("Content-type: text/xml; charset: UTF-8");
class ReviewsXML extends DOMDocument {
	private $list;
	
	public function __construct($title, $url, $company) {
		parent::__construct('1.0', 'utf-8');
		
		$this->formatOutput = true;
		
		
		//$root = $this->appendChild($this->createElement('list'));
		$list = $this->appendChild($this->createElement('list'));
		$list->setAttribute("date", date('Y-m-d H:m:s'));
		$list->setAttribute('version', '2.0');
		$list->appendChild($this->createElement('title', $title));
		$list->appendChild($this->createElement('url', $url));
		$list->appendChild($this->createElement('company', $company));
		$of = $list->appendChild($this->createElement('offers'));
		$this->offers = $of;
	}
	public function addOffer($name, $review, $aOptPubDate) {
		$item = $this->createElement('offer');
		$item->appendChild($this->createElement('name', $name));
		$item->appendChild($this->createElement('review', $review));
		$item->appendChild($this->createElement('pubDate', $aOptPubDate));
		$this->offers->appendChild($item);
		return $this;
	}
	
	
	}
