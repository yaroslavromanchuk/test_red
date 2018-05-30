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

    function d($data, $die = true)
    {
        if (FORME) {
            Debug::dump($data);
            if ($die) {
                die();
            }
        }
    }

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
        } else
            return false;

        return true;
    }

function isValidEmailNew($email)
  {
      return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
  }
  function isValidEmailRu($email){
  if(stristr($email, '.ru')) {
    return false;
  }
  else{
  return true;
  }
  }
/*
$countries=array(
            '--' => 'None',
            'AF' => 'Afganistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegowina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, the Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d\'Ivoire',
            'HR' => 'Croatia (Hrvatska)',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'TP' => 'East Timor',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'FX' => 'France, Metropolitan',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard and Mc Donald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran (Islamic Republic of)',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea, Democratic People\'s Republic of',
            'KR' => 'Korea, Republic of',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macau',
            'MK' => 'Macedonia, The Former Yugoslav Republic of',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of',
            'MD' => 'Moldova, Republic of',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint LUCIA',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia (Slovak Republic)',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SH' => 'St. Helena',
            'PM' => 'St. Pierre and Miquelon',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan, Province of China',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania, United Republic of',
            'TH' => 'Thailand',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands (British)',
            'VI' => 'Virgin Islands (U.S.)',
            'WF' => 'Wallis and Futuna Islands',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'YU' => 'Yugoslavia',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
            );
*/