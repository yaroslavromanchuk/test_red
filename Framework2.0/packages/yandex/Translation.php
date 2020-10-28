<?php

class Translation
{
    /**
     * API key
     * @var string
     */
    public $key = 'trnsl.1.1.20200428T082013Z.393283503d00f583.b1b28023c09d5a74bf1fdaf1158af549daf9a6ad';
    /**
     * API URL
     */
    const API_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
    /**
     * You can translate text from one language
     * to another language
     * @param string $source Source language
     * @param string $target Target language
     * @param string $text   Source text string
     * @return array
     */
    public function translate($source, $target, $text)
    {
        //$langDirection = explode('-',$source)[0].'-'.explode('-',$target)[0];
        $langDirection = $source.'-'.$target;
        if (strlen($text)>300) {
            return $this->getPostResponse($text, $langDirection);
        } else {
            return $this->getResponse($text, $langDirection);
        }
    }
    /**
     * Forming query parameters
     * @param  string $text   Source text string
     * @param  string $lang   Translation direction ru-en, en-es
     * @return array          Data properties
     */
    protected function getPostResponse($text = '', $lang = 'en-ru')
    {
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(
                    [
                        'key' => $this->key,
                        'lang' => $lang,
                        'text' => $text,
                        'format' => 'html',
                    ]
                )
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents(self::API_URL, false, $context);
        return json_decode($response, true);
    }
    /**
     * Forming query parameters
     * @param  string $text   Source text string
     * @param  string $lang   Translation direction ru-en, en-es
     * @return array          Data properties
     */
    protected function getResponse($text = '', $lang = 'uk-en')
    {
        $request = self::API_URL . '?' . http_build_query(
            [
                'key' => $this->key,
                'lang' => $lang,
                'text' => $text,
                'format' => 'html',
            ]
        );
        $response = file_get_contents($request);
        //print_r($response);
        return json_decode($response, true);
    }
}