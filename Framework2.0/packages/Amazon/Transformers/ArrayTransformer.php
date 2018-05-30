<?php
require_once 'IDataTransformer.php';
class ArrayTransformer implements IDataTransformer {
    public function execute($xmlData) {
        $json = json_encode($xmlData);

        return(json_decode($json));
    }
}

?>
