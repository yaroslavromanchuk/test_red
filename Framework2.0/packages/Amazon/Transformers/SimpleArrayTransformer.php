<?php
require_once 'IDataTransformer.php';

class SimpleArrayTransformer implements IDataTransformer {
    public function execute($xmlData) {
		$items = array();
		if (empty($xmlData)) {
			throw new \Exception('No XML response');
		}

		if ($xmlData->Items->Request->IsValid != 'True') {
			$errorCode = $xmlData->Items->Request->Errors->Error->Code;
			$errorMessage = $xmlData->Items->Request->Errors->Error->Message;
			$error = "API error ($errorCode) : $errorMessage";
			throw new \Exception($error);
		}

		if (empty($xmlData->Items->Item)) {
			return($items);
		}

		foreach($xmlData->Items->Item as $responseItem) {
			$item = array();
			$item['asin'] = (string) $responseItem->ASIN;
			$item['url'] = (string) $responseItem->DetailPageURL;
			$item['rrp'] = ((float) $responseItem->ItemAttributes->ListPrice->Amount) / 100.0;
			$item['title'] = (string) $responseItem->ItemAttributes->Title;
			$item['binding'] = (string) $responseItem->ItemAttributes->Binding;
			$item['brand'] = (string) $responseItem->ItemAttributes->Brand;
			$item['color'] = (string) $responseItem->ItemAttributes->Color;
			$item['size'] = (string) $responseItem->ItemAttributes->Size;
			
			foreach($responseItem->ItemAttributes->Feature as $f){
			$item['feature'][] = (string) $f;
			}
			//$item['feature'] =  $responseItem->ItemAttributes->Feature;

			if ($responseItem->OfferSummary) {
				$item['lowestPrice'] = ((float) $responseItem->OfferSummary->LowestNewPrice->Amount) / 100.0;
			}
			else {
				$item['lowestPrice'] = 0.0;
			}

			$item['largeImage'] = (string) $responseItem->LargeImage->URL;

			//$item['mediumImage'] = (string) $responseItem->MediumImage->URL;
			$item['smallImage'] = (string) $responseItem->SmallImage->URL;

			array_push($items, $item);
		}

		return($items);
	}
}

?>
