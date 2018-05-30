<?php
header("Content-type: text/xml; charset: UTF-8");
class ArticleList extends DOMDocument {
	private $price;
	
	public function __construct($title, $url, $company) {
		parent::__construct('1.0', 'utf-8');
		
		$this->formatOutput = true;
		
		
		//$root = $this->appendChild($this->createElement('price'));
		$price = $this->appendChild($this->createElement('price'));
		$price->setAttribute("date", date('Y-m-d H:m:s'));
		$price->setAttribute('version', '2.0');
		$price->appendChild($this->createElement('title', $title));
		$price->appendChild($this->createElement('url', $url));
		$price->appendChild($this->createElement('company', $company));
		$of = $price->appendChild($this->createElement('offers'));
		//$o = $of->appendChild($this->createElement('offer', 'fhdfg'));
		//$this->price = $price;
		$this->offers = $of;
		
	}
	public function addOffer($vendor, $reference, $categoryId, $title, $description, $picture, $url, $price, DateTime $aOptPubDate) {
		$item = $this->createElement('offer');
		$item->appendChild($this->createElement('vendor', $vendor));
		$item->appendChild($this->createElement('reference', $reference));
		$item->appendChild($this->createElement('categoryId', $categoryId));
		$item->appendChild($this->createElement('title', $title));
		$item->appendChild($this->createElement('description', $description));
		$item->appendChild($this->createElement('picture', $picture));
		$item->appendChild($this->createElement('url', $url));
		$item->appendChild($this->createElement('price', $price));

		if (!is_null($aOptPubDate) && ($aOptPubDate instanceof \DateTime)) {
			$item->appendChild($this->createElement('pubDate', $aOptPubDate->format(\DateTime::RFC2822)));
		}
		$this->offers->appendChild($item);
		return $this;
	}
}
?>