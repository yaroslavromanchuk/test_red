<?php

require_once 'ArrayTransformer.php';
require_once 'JsonTransformer.php';
require_once 'SimpleArrayTransformer.php';
require_once 'XmlTransformer.php';

class DataTransformerFactory {
	static public function create($outputType) {
		switch($outputType) {
			case 'array':
				return new ArrayTransformer();
				break;
			case 'json':
				return new JsonTransformer();
				break;
			case 'simple':
				return new SimpleArrayTransformer();
				break;
			case 'xml':
			default:
				return new XmlTransformer();
				break;
		}
	}
}

?>