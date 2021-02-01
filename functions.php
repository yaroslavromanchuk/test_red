<?php
//Basic general functions
//_____________________________________

//clever redirect to page
    function redirect($url)
    {
        if (headers_sent()) {
            die("<script>document.location.href='$url';</script>\n");
        } else {
          
            header("Location: $url");
        }
        die();
    }
     function redirect301($url)
    {
        if (headers_sent()) {
            die("<script>document.location.href='$url';</script>\n");
        } else {
            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: $url");
        }
        die();
    }

    function toFixed($number)
    {
        $number = explode(',', $number);
        return $number[0];
    }


    function isValidEmail($email)
    {
        $email_regular_expression = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
        $preg = (function_exists("preg_match") ? "/" . str_replace("/", "\\/", $email_regular_expression) . "/" : "");

        if ($preg) {
            return (preg_match($preg, $email));
        } else {
            return 0;
        }
    }

/**
 * Чтение файла
 * @param type $filename
 * @param type $name
 * @param string $ftype
 */
    function get_file($filename, $name = '', $ftype = '')
    {

        $range = 0;

        if (!$name)
            $name = $filename;
        if (!$ftype)
            $ftype = 'application/octet-stream';

        if (!file_exists($filename)) {
            header("HTTP/1.0 404 Not Found");
            exit;
        } else {
            $fsize = filesize($filename);
            $ftime = date("D, d M Y H:i:s T", filemtime($filename));
            $fd = @fopen($filename, "rb");
            if (!$fd) {
                header("HTTP/1.0 403 Forbidden");
                exit;
            }
            if (isset($HTTP_SERVER_VARS["HTTP_RANGE"])) {
                $range = $HTTP_SERVER_VARS["HTTP_RANGE"];
                $range = str_replace("bytes=", "", $range);
                $range = str_replace("-", "", $range);
                if ($range)
                    fseek($fd, $range);
            }

            if ($range) {
                header("HTTP/1.1 206 Partial Content");
            } else {
                header("HTTP/1.1 200 OK");
            }
            header("Content-Disposition: attachment; filename=\"" . ($name) . "\"");
            header("Last-Modified: $ftime");
            header("Accept-Ranges: bytes");
            header("Content-Length: " . ($fsize - $range));
            header("Content-Range: bytes $range-" . ($fsize - 1) . "/" . $fsize);
            header("Content-type: " . $ftype);

            fpassthru($fd);
            fclose($fd);
            exit;
        }
    }
    /**
     * Запись лога в файл
     * @param type $file - путь к файлу
     * @param type $text - содержимое
     */
    function do_log($file, $text)
    {
        file_put_contents(LOG_FOLDER . $file, $text, FILE_APPEND);
    }

    function fromSite()
    {
        GLOBAL $siteurl;

        if (empty($_SERVER['HTTP_REFERER'])) return false;

        list($srv) = explode('/', str_replace('https://', '', strtolower($_SERVER['HTTP_REFERER'])));
        list($thissrv) = explode('/', str_replace('https://', '', strtolower($siteurl)));

        if ($srv != $thissrv) {
            return false;
        } else {
            return $_SERVER['HTTP_REFERER'];
        }
    }


//create formated plain text from HTML body
    function make_plain($body)
    {

        $patterns[0] = ' />';
        $patterns[1] = '<br>';
        $patterns[2] = '<br/>';

        $patterns[3] = '<p>';
        $patterns[4] = '</p>';

        $patterns[5] = '<tr>';
        $patterns[6] = '</tr>';
        $patterns[7] = '<td>';

        $patterns[8] = '  ';
        $patterns[9] = "\n\n";
        $patterns[10] = "\n ";
        $patterns[11] = "&nbsp;";
        //---------------------
        $replacements[0] = "/>";
        $replacements[1] = "\n";
        $replacements[2] = "\n";

        $replacements[3] = "\n\n";
        $replacements[4] = "\n\n";

        $replacements[5] = "\n";
        $replacements[6] = "\n";
        $replacements[7] = "\t";

        $replacements[8] = " ";
        $replacements[9] = "\n\n";
        $replacements[10] = "\n";
        $replacements[11] = " ";

        $body = str_replace($patterns, $replacements, $body);
        $doit = true;
        while ($doit) {
            $fromsp[0] = '  ';
            $fromsp[1] = "\n\n";
            $fromsp[2] = "\n ";
            $fromsp[3] = "\t\t";

            $tosp[0] = " ";
            $tosp[1] = "\n\n";
            $tosp[2] = "\n";
            $tosp[3] = "\t";

            $newbody = str_replace($fromsp, $tosp, $body);
            if ($newbody == $body) $doit = false;
            $body = $newbody;
        }
        $body = strip_tags($body);
        //$body = preg_replace('!\s+!', ' ', $body);

        return $body;
    }
    /**
     * Вывод на екран дебага
     * @param type $data - содержимое
     * @param type $die - (true - die() - по умолчанию, false - продолжить выполнение)
     */
    function d($data, $die = true)
    {
        if (FORME) {
            Debug::dump($data);
            if ($die) {
                die();
            }
        }
    }
    /**
     * Отображение лога на екран
     * @param type $data
     */
    function l($data){
		echo '<pre>';
		print_r($data);
                echo '</pre>';
	}
        /**
         * Обрезка фото 
         * @param type $folder
         * @param type $file
         * @param type $size
         * @param type $quality
         * @return boolean
         */
    function resizeImage($folder, $file, $size, $quality)
    {
        require_once('upload/class.upload.php');
        $handle = new upload($folder . $file, Registry::get('locale'));
        $handle->image_resize = true;
        $handle->jpeg_quality = $quality;
        $handle->file_overwrite = true;
        $handle->image_x = $size;
        $handle->image_y = $size;
        $handle->image_ratio_no_zoom_in = true;


        if ($handle->uploaded) {
            $handle->process($folder);
            if ($handle->processed) {
            } else {
                return false;
            }
        }else{ return false; }

        return true;
    }

    /**
     * Новая валидация email через filter_var
     * @param type $email
     * @return type
     */
function isValidEmailNew($email)
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
    //  return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
  }
  
function isValidEmailRu($email){
  if(stristr($email, '.ru')) {
    return false;
  }
  return true;
  }
  function isValidEmailRed($email){
      if(strpos($email, 'red.ua')){
          return false;
      }
      return true;
  }
  
  function exception_handler($exception)
{
      bug::add_exception($exception);
  if (Cfg::getInstance()->getValue('is_live')) {
      $exceptionContent = "Uncaught exception '" . get_class($exception)
                        . "' with message '{$exception->getMessage()}'\n"
                        . "File: {$exception->getFile()}, "
                        . "line {$exception->getLine()}\n"
                        . "Trace\n"
                        . preg_replace('/(\#[0-9]+ )/', '\n', $exception->getTraceAsString())
                        . "\n";

   
        wsLog::add($exceptionContent, 'EMERG');
        header("Location: /");
     die();
   }

/*    echo '<fieldset style="font-family:verdana;font-size:11px;line-height:2em">'
         . '<legend>PHP Exception</legend>'
         . "Uncaught exception '" . get_class($exception)
         . "' with message <strong>'{$exception->getMessage()}'</strong><br />\n"
         . "File: <strong>{$exception->getFile()}</strong>, "
         . "line <strong>{$exception->getLine()}</strong><br />\n";

    echo "Trace<br />\n<ol start='0'>"
         . preg_replace('/(\#[0-9]+ )/', '</li><li>', $exception->getTraceAsString())
         . "</li></ol>";
    echo '</fieldset>';*/
 return true;
}

function error_handler($errno, $errstr, $errfile, $errline)
{
    bug::add_error($errno, $errstr, $errfile, $errline);
	if ($errno == E_STRICT) {return;}
	if (error_reporting() == 0) {return;}
	global $isAdmin;
	if ($errno!=8){
            $exceptionContent = "FATAL ERROR #" .$errno. ' '
						. " with message: '{$errstr}'\n"
						. "File: {$errfile}, "
						. "line {$errline}\n";
	ob_end_clean();
	if (!$isAdmin) {
		if (Cfg::getInstance()->getValue('is_live')) {
				wsLog::add($exceptionContent, 'EMERG');
				##header("Location: /status/");
				##exit;
				echo $exceptionContent;
		}
		}else{ throw new ErrorException($exceptionContent);}
	}
    return true;
}
function num2strm($num)
        {
		$nul='нуль';
        $ukr = array(
            array( //one_nine
                array('', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
                array('', 'одна', 'дві', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять'),
            ),
            array( //teen
                'десять', 'одинадцять', 'дванадцять', 'тринадцать', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять'
            ),
            array( //tenth
                2 => 'двадцять', 'тридцять', 'сорок', 'п\'ятьдесят', 'шістьдесят', 'сімдесять', 'вісімьдесят', 'дев\'яносто'
            ),
            array( //hundred
                '', 'сто', 'двісти', 'триста', 'чотириста', 'п\'ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев\'ятсот'
            ),
            array( //scales
                array('триліон', 'триліона', 'триліонів', 0),
                array('мільйард', 'мільйарда', 'мільйардів', 0),
                array('мільйон', 'мільйона', 'мільйонів', 0),
                array('тисяча', 'тисячі', 'тисяч', 1),
                array('', '', '', 0)
            ),
            array('Вкажіть число (до 15 цифр)') //number_not_set
        );

        $num = is_numeric(trim($num)) ? (string)$num : 0;

        list($one_nine, $teen, $tenth, $hundred, $scales, $number_not_set) = $ukr;

        // массив будующего числа
        $out = array();

        // обробатываем числа не больше 15 знаков
        if (strlen(trim($num)) <= 15) {
			if (intval($num) > 0) {

				// формируем число с нулями перед ним и длиной 15 сиволов
				$num = sprintf("%015s", trim($num));

				// обробатываем по 3 символа
				foreach (str_split($num, 3) as $k => $v) {

					// пропускаем 000
					if (!intval($v)) continue;

					list($num1, $num2, $num3) = array_map('intval', str_split($v, 1));

					// диапазон 1-999
					$out[] = $hundred[$num1]; // диапазон 100-900
					if ($num2 > 1)
						$out[] = $tenth[$num2] . ' ' . $one_nine[$scales[$k][3]] [$num3]; // диапазон 20-99
					elseif ($num2 > 0)
						$out[] = $teen[$num3]; // диапазон 10-19
					else $out[] = $one_nine[$scales[$k][3]] [$num3]; // диапазон 1-9

					// тысячи, милионы ... и склонения
					$n = $v % 10;
					$n2 = $v % 100;
					if ($n2 > 10 && $n2 < 20) $out[] = $scales[$k][2];
					elseif ($n > 1 && $n < 5) $out[] = $scales[$k][1];
					elseif ($n == 1) $out[] = $scales[$k][0];
					else $out[] = $scales[$k][2];

				}
			}
			elseif (intval($num) == 0) {
				$out[] = $nul;
			}
        } else $out[] = $number_not_set[0];

        return implode(' ', $out);
    }
    
	function morph($n, $k)
                {
		$unit=array(
			array('гривня'  ,'гривні'  ,'гривень'    ,0),
			array('копійка' ,'копійки' ,'копійок',	 1),
		);

		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $unit[$k][2];
		$n = $n % 10;
		if ($n>1 && $n<5) return $unit[$k][1];
		if ($n==1) return $unit[$k][0];
		return $unit[$k][2];
	}
