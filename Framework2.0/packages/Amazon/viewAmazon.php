<?php 
require_once 'simple_html_dom.php';
class viewAmazon{

public function curl_get($url, $width = 100)
{
$proxy = '127.0.0.1:8888';
$start_url = 'https://www.amazon.de/dp/';
$referer = 'https://www.amazon.de/';
$url = $start_url.$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
$html_base = new simple_html_dom();
// загружаем HTML из строки
$html_base->load($data);
// возьмем из таблицы все ссылки
$mas_img = array();
$mas_desc = array();
foreach($html_base->find('span.a-button-text img') as $a) {
     if (preg_match("/_.jpg/i", $a->src)) {
  $a->src = str_replace('&amp;','&',$a->src);
  $a->src = substr_replace($a->src, '._SL'.$width.'_.jpg', strpos($a->src, '._'));
 // $a->src = str_replace('US40','SL'.$width, $a->src);
  $mas_img[] = $a->src;
}
}
$mas_desc[] = $html_base->find('div #title_feature_div')[0]->plaintext;
$mas_desc[] = $html_base->find('div #feature-bullets')[0]->outertext;
$html_base->clear(); 
unset($html_base);

return array('img'=>$mas_img, 'desc'=>$mas_desc);
}
}
?>