<?php
require_once 'IDataTransformer.php';
class XmlTransformer implements IDataTransformer {
    public function execute($xmlData) {
        return $xmlData;
    }
}

?>