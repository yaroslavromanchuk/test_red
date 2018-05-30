<?php

//namespace MarcL\Transformers;

require_once 'IDataTransformer.php';

class JsonTransformer implements IDataTransformer {
    public function execute($xmlData) {
        return json_encode($xmlData);
    }
}

?>
