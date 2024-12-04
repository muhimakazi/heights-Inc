<?php
// Secure HTML Input
function escape($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

// Include Scripts From Root Path
function includeScript($path)
{
    return include $_SERVER['DOCUMENT_ROOT'] . "/thefuture/adminPortal/{$path}"; // Local
    // return include $_SERVER['DOCUMENT_ROOT'] . "/{$path}"; // Live
}

// Path To Root Directory
function root($path)
{
    return $_SERVER['DOCUMENT_ROOT'] . "/thefuture/adminPortal/{$path}";
}

// absulot path used in links
function linkto($path)
{
    echo "/thefuture/adminPortal/{$path}"; //Local
    // echo "/{$path}"; //Live
}

//Print Success Message Style
function Success($message)
{
    echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
			<span aria-hidden=\"true\">&times;</span>
			</button>
			{$message}
		</div>";
}

//Print Error Message Style
function Danger($message)
{
    echo "<div class=\"alert alert-danger alert-dismissible show fade in\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
			<span aria-hidden=\"true\">&times;</span>
			</button>
			{$message}
		</div>";
}

function firstUC($character)
{
    return ucfirst($character);
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
        else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function dateDiff($date1, $date2)
{
    $date1_ts = strtotime($date1);
    $date2_ts = strtotime($date2);
    $diff = $date2_ts - $date1_ts;
    return floor($diff / (60 * 60 * 24));
}

function utc_time($time, $timezone)
{
    date_default_timezone_set($timezone);
    $datetime = $time;
    $africa_timestamp = strtotime($datetime);
    // echo date_default_timezone_get()."<br>"; // Africa/Kigali
    date_default_timezone_set('UTC');
    // echo date_default_timezone_get()."<br>"; //UTC
    $utcDateTime = date("H:i A", $africa_timestamp);
    return ($utcDateTime);
}

function country_to_continent($country){
    $continent = '';
    if( $country == 'AF' ) $continent = 'Asia';
    if( $country == 'AX' ) $continent = 'Europe';
    if( $country == 'AL' ) $continent = 'Europe';
    if( $country == 'DZ' ) $continent = 'Africa';
    if( $country == 'AS' ) $continent = 'Oceania';
    if( $country == 'AD' ) $continent = 'Europe';
    if( $country == 'AO' ) $continent = 'Africa';
    if( $country == 'AI' ) $continent = 'North America';
    if( $country == 'AQ' ) $continent = 'Antarctica';
    if( $country == 'AG' ) $continent = 'North America';
    if( $country == 'AR' ) $continent = 'South America';
    if( $country == 'AM' ) $continent = 'Asia';
    if( $country == 'AW' ) $continent = 'North America';
    if( $country == 'AU' ) $continent = 'Oceania';
    if( $country == 'AT' ) $continent = 'Europe';
    if( $country == 'AZ' ) $continent = 'Asia';
    if( $country == 'BS' ) $continent = 'North America';
    if( $country == 'BH' ) $continent = 'Asia';
    if( $country == 'BD' ) $continent = 'Asia';
    if( $country == 'BB' ) $continent = 'North America';
    if( $country == 'BY' ) $continent = 'Europe';
    if( $country == 'BE' ) $continent = 'Europe';
    if( $country == 'BZ' ) $continent = 'North America';
    if( $country == 'BJ' ) $continent = 'Africa';
    if( $country == 'BM' ) $continent = 'North America';
    if( $country == 'BT' ) $continent = 'Asia';
    if( $country == 'BO' ) $continent = 'South America';
    if( $country == 'BA' ) $continent = 'Europe';
    if( $country == 'BW' ) $continent = 'Africa';
    if( $country == 'BV' ) $continent = 'Antarctica';
    if( $country == 'BR' ) $continent = 'South America';
    if( $country == 'IO' ) $continent = 'Asia';
    if( $country == 'VG' ) $continent = 'North America';
    if( $country == 'BN' ) $continent = 'Asia';
    if( $country == 'BG' ) $continent = 'Europe';
    if( $country == 'BF' ) $continent = 'Africa';
    if( $country == 'BI' ) $continent = 'Africa';
    if( $country == 'KH' ) $continent = 'Asia';
    if( $country == 'CM' ) $continent = 'Africa';
    if( $country == 'CA' ) $continent = 'North America';
    if( $country == 'CV' ) $continent = 'Africa';
    if( $country == 'KY' ) $continent = 'North America';
    if( $country == 'CF' ) $continent = 'Africa';
    if( $country == 'TD' ) $continent = 'Africa';
    if( $country == 'CL' ) $continent = 'South America';
    if( $country == 'CN' ) $continent = 'Asia';
    if( $country == 'CX' ) $continent = 'Asia';
    if( $country == 'CC' ) $continent = 'Asia';
    if( $country == 'CO' ) $continent = 'South America';
    if( $country == 'KM' ) $continent = 'Africa';
    if( $country == 'CD' ) $continent = 'Africa';
    if( $country == 'CG' ) $continent = 'Africa';
    if( $country == 'CK' ) $continent = 'Oceania';
    if( $country == 'CR' ) $continent = 'North America';
    if( $country == 'CI' ) $continent = 'Africa';
    if( $country == 'HR' ) $continent = 'Europe';
    if( $country == 'CU' ) $continent = 'North America';
    if( $country == 'CY' ) $continent = 'Asia';
    if( $country == 'CZ' ) $continent = 'Europe';
    if( $country == 'DK' ) $continent = 'Europe';
    if( $country == 'DJ' ) $continent = 'Africa';
    if( $country == 'DM' ) $continent = 'North America';
    if( $country == 'DO' ) $continent = 'North America';
    if( $country == 'EC' ) $continent = 'South America';
    if( $country == 'EG' ) $continent = 'Africa';
    if( $country == 'SV' ) $continent = 'North America';
    if( $country == 'GQ' ) $continent = 'Africa';
    if( $country == 'ER' ) $continent = 'Africa';
    if( $country == 'EE' ) $continent = 'Europe';
    if( $country == 'ET' ) $continent = 'Africa';
    if( $country == 'FO' ) $continent = 'Europe';
    if( $country == 'FK' ) $continent = 'South America';
    if( $country == 'FJ' ) $continent = 'Oceania';
    if( $country == 'FI' ) $continent = 'Europe';
    if( $country == 'FR' ) $continent = 'Europe';
    if( $country == 'GF' ) $continent = 'South America';
    if( $country == 'PF' ) $continent = 'Oceania';
    if( $country == 'TF' ) $continent = 'Antarctica';
    if( $country == 'GA' ) $continent = 'Africa';
    if( $country == 'GM' ) $continent = 'Africa';
    if( $country == 'GE' ) $continent = 'Asia';
    if( $country == 'DE' ) $continent = 'Europe';
    if( $country == 'GH' ) $continent = 'Africa';
    if( $country == 'GI' ) $continent = 'Europe';
    if( $country == 'GR' ) $continent = 'Europe';
    if( $country == 'GL' ) $continent = 'North America';
    if( $country == 'GD' ) $continent = 'North America';
    if( $country == 'GP' ) $continent = 'North America';
    if( $country == 'GU' ) $continent = 'Oceania';
    if( $country == 'GT' ) $continent = 'North America';
    if( $country == 'GG' ) $continent = 'Europe';
    if( $country == 'GN' ) $continent = 'Africa';
    if( $country == 'GW' ) $continent = 'Africa';
    if( $country == 'GY' ) $continent = 'South America';
    if( $country == 'HT' ) $continent = 'North America';
    if( $country == 'HM' ) $continent = 'Antarctica';
    if( $country == 'VA' ) $continent = 'Europe';
    if( $country == 'HN' ) $continent = 'North America';
    if( $country == 'HK' ) $continent = 'Asia';
    if( $country == 'HU' ) $continent = 'Europe';
    if( $country == 'IS' ) $continent = 'Europe';
    if( $country == 'IN' ) $continent = 'Asia';
    if( $country == 'ID' ) $continent = 'Asia';
    if( $country == 'IR' ) $continent = 'Asia';
    if( $country == 'IQ' ) $continent = 'Asia';
    if( $country == 'IE' ) $continent = 'Europe';
    if( $country == 'IM' ) $continent = 'Europe';
    if( $country == 'IL' ) $continent = 'Asia';
    if( $country == 'IT' ) $continent = 'Europe';
    if( $country == 'JM' ) $continent = 'North America';
    if( $country == 'JP' ) $continent = 'Asia';
    if( $country == 'JE' ) $continent = 'Europe';
    if( $country == 'JO' ) $continent = 'Asia';
    if( $country == 'KZ' ) $continent = 'Asia';
    if( $country == 'KE' ) $continent = 'Africa';
    if( $country == 'KI' ) $continent = 'Oceania';
    if( $country == 'KP' ) $continent = 'Asia';
    if( $country == 'KR' ) $continent = 'Asia';
    if( $country == 'KW' ) $continent = 'Asia';
    if( $country == 'KG' ) $continent = 'Asia';
    if( $country == 'LA' ) $continent = 'Asia';
    if( $country == 'LV' ) $continent = 'Europe';
    if( $country == 'LB' ) $continent = 'Asia';
    if( $country == 'LS' ) $continent = 'Africa';
    if( $country == 'LR' ) $continent = 'Africa';
    if( $country == 'LY' ) $continent = 'Africa';
    if( $country == 'LI' ) $continent = 'Europe';
    if( $country == 'LT' ) $continent = 'Europe';
    if( $country == 'LU' ) $continent = 'Europe';
    if( $country == 'MO' ) $continent = 'Asia';
    if( $country == 'MK' ) $continent = 'Europe';
    if( $country == 'MG' ) $continent = 'Africa';
    if( $country == 'MW' ) $continent = 'Africa';
    if( $country == 'MY' ) $continent = 'Asia';
    if( $country == 'MV' ) $continent = 'Asia';
    if( $country == 'ML' ) $continent = 'Africa';
    if( $country == 'MT' ) $continent = 'Europe';
    if( $country == 'MH' ) $continent = 'Oceania';
    if( $country == 'MQ' ) $continent = 'North America';
    if( $country == 'MR' ) $continent = 'Africa';
    if( $country == 'MU' ) $continent = 'Africa';
    if( $country == 'YT' ) $continent = 'Africa';
    if( $country == 'MX' ) $continent = 'North America';
    if( $country == 'FM' ) $continent = 'Oceania';
    if( $country == 'MD' ) $continent = 'Europe';
    if( $country == 'MC' ) $continent = 'Europe';
    if( $country == 'MN' ) $continent = 'Asia';
    if( $country == 'ME' ) $continent = 'Europe';
    if( $country == 'MS' ) $continent = 'North America';
    if( $country == 'MA' ) $continent = 'Africa';
    if( $country == 'MZ' ) $continent = 'Africa';
    if( $country == 'MM' ) $continent = 'Asia';
    if( $country == 'NA' ) $continent = 'Africa';
    if( $country == 'NR' ) $continent = 'Oceania';
    if( $country == 'NP' ) $continent = 'Asia';
    if( $country == 'AN' ) $continent = 'North America';
    if( $country == 'NL' ) $continent = 'Europe';
    if( $country == 'NC' ) $continent = 'Oceania';
    if( $country == 'NZ' ) $continent = 'Oceania';
    if( $country == 'NI' ) $continent = 'North America';
    if( $country == 'NE' ) $continent = 'Africa';
    if( $country == 'NG' ) $continent = 'Africa';
    if( $country == 'NU' ) $continent = 'Oceania';
    if( $country == 'NF' ) $continent = 'Oceania';
    if( $country == 'MP' ) $continent = 'Oceania';
    if( $country == 'NO' ) $continent = 'Europe';
    if( $country == 'OM' ) $continent = 'Asia';
    if( $country == 'PK' ) $continent = 'Asia';
    if( $country == 'PW' ) $continent = 'Oceania';
    if( $country == 'PS' ) $continent = 'Asia';
    if( $country == 'PA' ) $continent = 'North America';
    if( $country == 'PG' ) $continent = 'Oceania';
    if( $country == 'PY' ) $continent = 'South America';
    if( $country == 'PE' ) $continent = 'South America';
    if( $country == 'PH' ) $continent = 'Asia';
    if( $country == 'PN' ) $continent = 'Oceania';
    if( $country == 'PL' ) $continent = 'Europe';
    if( $country == 'PT' ) $continent = 'Europe';
    if( $country == 'PR' ) $continent = 'North America';
    if( $country == 'QA' ) $continent = 'Asia';
    if( $country == 'RE' ) $continent = 'Africa';
    if( $country == 'RO' ) $continent = 'Europe';
    if( $country == 'RU' ) $continent = 'Europe';
    if( $country == 'RW' ) $continent = 'Africa';
    if( $country == 'BL' ) $continent = 'North America';
    if( $country == 'SH' ) $continent = 'Africa';
    if( $country == 'KN' ) $continent = 'North America';
    if( $country == 'LC' ) $continent = 'North America';
    if( $country == 'MF' ) $continent = 'North America';
    if( $country == 'PM' ) $continent = 'North America';
    if( $country == 'VC' ) $continent = 'North America';
    if( $country == 'WS' ) $continent = 'Oceania';
    if( $country == 'SM' ) $continent = 'Europe';
    if( $country == 'ST' ) $continent = 'Africa';
    if( $country == 'SA' ) $continent = 'Asia';
    if( $country == 'SN' ) $continent = 'Africa';
    if( $country == 'RS' ) $continent = 'Europe';
    if( $country == 'SC' ) $continent = 'Africa';
    if( $country == 'SL' ) $continent = 'Africa';
    if( $country == 'SG' ) $continent = 'Asia';
    if( $country == 'SK' ) $continent = 'Europe';
    if( $country == 'SI' ) $continent = 'Europe';
    if( $country == 'SB' ) $continent = 'Oceania';
    if( $country == 'SO' ) $continent = 'Africa';
    if( $country == 'ZA' ) $continent = 'Africa';
    if( $country == 'GS' ) $continent = 'Antarctica';
    if( $country == 'ES' ) $continent = 'Europe';
    if( $country == 'LK' ) $continent = 'Asia';
    if( $country == 'SD' ) $continent = 'Africa';
    if( $country == 'SR' ) $continent = 'South America';
    if( $country == 'SJ' ) $continent = 'Europe';
    if( $country == 'SZ' ) $continent = 'Africa';
    if( $country == 'SE' ) $continent = 'Europe';
    if( $country == 'CH' ) $continent = 'Europe';
    if( $country == 'SY' ) $continent = 'Asia';
    if( $country == 'TW' ) $continent = 'Asia';
    if( $country == 'TJ' ) $continent = 'Asia';
    if( $country == 'TZ' ) $continent = 'Africa';
    if( $country == 'TH' ) $continent = 'Asia';
    if( $country == 'TL' ) $continent = 'Asia';
    if( $country == 'TG' ) $continent = 'Africa';
    if( $country == 'TK' ) $continent = 'Oceania';
    if( $country == 'TO' ) $continent = 'Oceania';
    if( $country == 'TT' ) $continent = 'North America';
    if( $country == 'TN' ) $continent = 'Africa';
    if( $country == 'TR' ) $continent = 'Asia';
    if( $country == 'TM' ) $continent = 'Asia';
    if( $country == 'TC' ) $continent = 'North America';
    if( $country == 'TV' ) $continent = 'Oceania';
    if( $country == 'UG' ) $continent = 'Africa';
    if( $country == 'UA' ) $continent = 'Europe';
    if( $country == 'AE' ) $continent = 'Asia';
    if( $country == 'GB' ) $continent = 'Europe';
    if( $country == 'US' ) $continent = 'North America';
    if( $country == 'UM' ) $continent = 'Oceania';
    if( $country == 'VI' ) $continent = 'North America';
    if( $country == 'UY' ) $continent = 'South America';
    if( $country == 'UZ' ) $continent = 'Asia';
    if( $country == 'VU' ) $continent = 'Oceania';
    if( $country == 'VE' ) $continent = 'South America';
    if( $country == 'VN' ) $continent = 'Asia';
    if( $country == 'WF' ) $continent = 'Oceania';
    if( $country == 'EH' ) $continent = 'Africa';
    if( $country == 'YE' ) $continent = 'Asia';
    if( $country == 'ZM' ) $continent = 'Africa';
    if( $country == 'ZW' ) $continent = 'Africa';
    return $continent;
}

//Get continent from country (full name)
function country_full_to_continent($country){
  $continent = '';
    if( $country == 'Afghanistan' ) $continent = 'Asia';
    if( $country == 'Åland Islands' ) $continent = 'Europe';
    if( $country == 'Albania' ) $continent = 'Europe';
    if( $country == 'Algeria' ) $continent = 'Africa';
    if( $country == 'American Samoa' ) $continent = 'Oceania';
    if( $country == 'Andorra' ) $continent = 'Europe';
    if( $country == 'Angola' ) $continent = 'Africa';
    if( $country == 'Anguilla' ) $continent = 'North America';
    if( $country == 'Antarctica' ) $continent = 'Antarctica';
    if( $country == 'Antigua and Barbuda' ) $continent = 'North America';
    if( $country == 'Argentina' ) $continent = 'South America';
    if( $country == 'Australia' ) $continent = 'Oceania';
    if( $country == 'Austria' ) $continent = 'Europe';
    if( $country == 'Azerbaijan' ) $continent = 'Asia';
    if( $country == 'Bahamas' ) $continent = 'North America';
    if( $country == 'Bahrain' ) $continent = 'Asia';
    if( $country == 'Bangladesh' ) $continent = 'Asia';
    if( $country == 'Barbados' ) $continent = 'North America';
    if( $country == 'Belarus' ) $continent = 'Europe';
    if( $country == 'Belgium' ) $continent = 'Europe';
    if( $country == 'Belize' ) $continent = 'North America';
    if( $country == 'Benin' ) $continent = 'Africa';
    if( $country == 'Bermuda' ) $continent = 'North America';
    if( $country == 'Bhuta' ) $continent = 'Asia';
    if( $country == 'Bolivia' ) $continent = 'South America';
    if( $country == 'Bosnia and Herzegovina' ) $continent = 'Europe';
    if( $country == 'Botswana' ) $continent = 'Africa';
    if( $country == 'Bouvet Island' ) $continent = 'Antarctica';
    if( $country == 'Brazil' ) $continent = 'South America';
    if( $country == 'British Indian Ocean Territory' ) $continent = 'Asia';
    if( $country == 'Virgin Islands, British' ) $continent = 'North America';
    if( $country == 'Brunei Darussalam' ) $continent = 'Asia';
    if( $country == 'Bulgaria' ) $continent = 'Europe';
    if( $country == 'Burkina Faso' ) $continent = 'Africa';
    if( $country == 'Burundi' ) $continent = 'Africa';
    if( $country == 'Cambodia' ) $continent = 'Asia';
    if( $country == 'Cameroon' ) $continent = 'Africa';
    if( $country == 'Canada' ) $continent = 'North America';
    if( $country == 'Cape Verde' ) $continent = 'Africa';
    if( $country == 'Cayman Islands' ) $continent = 'North America';
    if( $country == 'Central African Republic' ) $continent = 'Africa';
    if( $country == 'Chad' ) $continent = 'Africa';
    if( $country == 'Chile' ) $continent = 'South America';
    if( $country == 'China' ) $continent = 'Asia';
    if( $country == 'Christmas Island' ) $continent = 'Asia';
    if( $country == 'Cocos (Keeling) Islands' ) $continent = 'Asia';
    if( $country == 'Colombia' ) $continent = 'South America';
    if( $country == 'Comoros' ) $continent = 'Africa';
    if( $country == 'Zaire' ) $continent = 'Africa';
    if( $country == 'Congo' ) $continent = 'Africa';
    if( $country == 'Cook Islands' ) $continent = 'Oceania';
    if( $country == 'Costa Rica' ) $continent = 'North America';
    if( $country == 'Côte D\'Ivoire' ) $continent = 'Africa';
    if( $country == 'Croatia' ) $continent = 'Europe';
    if( $country == 'Cuba' ) $continent = 'North America';
    if( $country == 'Cyprus' ) $continent = 'Asia';
    if( $country == 'Czech Republic' ) $continent = 'Europe';
    if( $country == 'Denmark' ) $continent = 'Europe';
    if( $country == 'Djibouti' ) $continent = 'Africa';
    if( $country == 'Dominica' ) $continent = 'North America';
    if( $country == 'Dominican Republic' ) $continent = 'North America';
    if( $country == 'Ecuador' ) $continent = 'South America';
    if( $country == 'Egypt' ) $continent = 'Africa';
    if( $country == 'El Salvador' ) $continent = 'North America';
    if( $country == 'Equatorial Guinea' ) $continent = 'Africa';
    if( $country == 'Eritrea' ) $continent = 'Africa';
    if( $country == 'Estonia' ) $continent = 'Europe';
    if( $country == 'Ethiopia' ) $continent = 'Africa';
    if( $country == 'Faroe Islands' ) $continent = 'Europe';
    if( $country == 'Falkland Islands (Malvinas)' ) $continent = 'South America';
    if( $country == 'Fiji' ) $continent = 'Oceania';
    if( $country == 'Finland' ) $continent = 'Europe';
    if( $country == 'France' ) $continent = 'Europe';
    if( $country == 'French Guiana' ) $continent = 'South America';
    if( $country == 'French Polynesia' ) $continent = 'Oceania';
    if( $country == 'French Southern Territories' ) $continent = 'Antarctica';
    if( $country == 'Gabon' ) $continent = 'Africa';
    if( $country == 'Gambia' ) $continent = 'Africa';
    if( $country == 'Georgia' ) $continent = 'Asia';
    if( $country == 'Germany' ) $continent = 'Europe';
    if( $country == 'Ghana' ) $continent = 'Africa';
    if( $country == 'Gibraltar' ) $continent = 'Europe';
    if( $country == 'Greece' ) $continent = 'Europe';
    if( $country == 'Greenland' ) $continent = 'North America';
    if( $country == 'Grenada' ) $continent = 'North America';
    if( $country == 'Guadeloupe' ) $continent = 'North America';
    if( $country == 'Guam' ) $continent = 'Oceania';
    if( $country == 'Guatemala' ) $continent = 'North America';
    if( $country == 'Guernsey' ) $continent = 'Europe';
    if( $country == 'Guinea' ) $continent = 'Africa';
    if( $country == 'Guinea-Bissau' ) $continent = 'Africa';
    if( $country == 'Guyana' ) $continent = 'South America';
    if( $country == 'Haiti' ) $continent = 'North America';
    if( $country == 'Heard Island and Mcdonald Islands' ) $continent = 'Antarctica';
    if( $country == 'Vatican City State' ) $continent = 'Europe';
    if( $country == 'Honduras' ) $continent = 'North America';
    if( $country == 'Hong Kong' ) $continent = 'Asia';
    if( $country == 'Hungary' ) $continent = 'Europe';
    if( $country == 'Iceland' ) $continent = 'Europe';
    if( $country == 'India' ) $continent = 'Asia';
    if( $country == 'Indonesia' ) $continent = 'Asia';
    if( $country == 'Iran' ) $continent = 'Asia';
    if( $country == 'Iraq' ) $continent = 'Asia';
    if( $country == 'Ireland' ) $continent = 'Europe';
    if( $country == 'Isle of Man' ) $continent = 'Europe';
    if( $country == 'Israel' ) $continent = 'Asia';
    if( $country == 'Italy' ) $continent = 'Europe';
    if( $country == 'Jamaica' ) $continent = 'North America';
    if( $country == 'Japan' ) $continent = 'Asia';
    if( $country == 'Jersey' ) $continent = 'Europe';
    if( $country == 'Jordan' ) $continent = 'Asia';
    if( $country == 'Kazakhstan' ) $continent = 'Asia';
    if( $country == 'Kenya' ) $continent = 'Africa';
    if( $country == 'Kiribati' ) $continent = 'Oceania';
    if( $country == 'North Korea' ) $continent = 'Asia';
    if( $country == 'Korea' ) $continent = 'Asia';
    if( $country == 'Kuwait' ) $continent = 'Asia';
    if( $country == 'Kyrgyzstan' ) $continent = 'Asia';
    if( $country == 'Laos' ) $continent = 'Asia';
    if( $country == 'Latvia' ) $continent = 'Europe';
    if( $country == 'Lebanon' ) $continent = 'Asia';
    if( $country == 'Lesotho' ) $continent = 'Africa';
    if( $country == 'Liberia' ) $continent = 'Africa';
    if( $country == 'Libya' ) $continent = 'Africa';
    if( $country == 'Liechtenstein' ) $continent = 'Europe';
    if( $country == 'Lithuania' ) $continent = 'Europe';
    if( $country == 'Luxembourg' ) $continent = 'Europe';
    if( $country == 'Macao' ) $continent = 'Asia';
    if( $country == 'North Macedonia' ) $continent = 'Europe';
    if( $country == 'Madagascar' ) $continent = 'Africa';
    if( $country == 'Malawi' ) $continent = 'Africa';
    if( $country == 'Malaysia' ) $continent = 'Asia';
    if( $country == 'Maldives' ) $continent = 'Asia';
    if( $country == 'Mali' ) $continent = 'Africa';
    if( $country == 'Malta' ) $continent = 'Europe';
    if( $country == 'Marshall Islands' ) $continent = 'Oceania';
    if( $country == 'Martinique' ) $continent = 'North America';
    if( $country == 'Mauritania' ) $continent = 'Africa';
    if( $country == 'Mauritius' ) $continent = 'Africa';
    if( $country == 'Mayotte' ) $continent = 'Africa';
    if( $country == 'Mexico' ) $continent = 'North America';
    if( $country == 'Federated States of Micronesia' ) $continent = 'Oceania';
    if( $country == 'Moldova' ) $continent = 'Europe';
    if( $country == 'Monaco' ) $continent = 'Europe';
    if( $country == 'Mongolia' ) $continent = 'Asia';
    if( $country == 'Montenegro' ) $continent = 'Europe';
    if( $country == 'Montserrat' ) $continent = 'North America';
    if( $country == 'Morocco' ) $continent = 'Africa';
    if( $country == 'Mozambique' ) $continent = 'Africa';
    if( $country == 'Myanmar' ) $continent = 'Asia';
    if( $country == 'Namibia' ) $continent = 'Africa';
    if( $country == 'Nauru' ) $continent = 'Oceania';
    if( $country == 'Nepal' ) $continent = 'Asia';
    if( $country == 'Netherlands Antilles' ) $continent = 'North America';
    if( $country == 'Netherlands' ) $continent = 'Europe';
    if( $country == 'New Caledonia' ) $continent = 'Oceania';
    if( $country == 'New Zealand' ) $continent = 'Oceania';
    if( $country == 'Nicaragua' ) $continent = 'North America';
    if( $country == 'Niger' ) $continent = 'Africa';
    if( $country == 'Nigeria' ) $continent = 'Africa';
    if( $country == 'Niue' ) $continent = 'Oceania';
    if( $country == 'Norfolk Island' ) $continent = 'Oceania';
    if( $country == 'Northern Mariana Islands' ) $continent = 'Oceania';
    if( $country == 'Norway' ) $continent = 'Europe';
    if( $country == 'Oman' ) $continent = 'Asia';
    if( $country == 'Pakistan' ) $continent = 'Asia';
    if( $country == 'Palau' ) $continent = 'Oceania';
    if( $country == 'Palestine' ) $continent = 'Asia';
    if( $country == 'Panama' ) $continent = 'North America';
    if( $country == 'Papua New Guinea' ) $continent = 'Oceania';
    if( $country == 'Paraguay' ) $continent = 'South America';
    if( $country == 'Peru' ) $continent = 'South America';
    if( $country == 'Philippines' ) $continent = 'Asia';
    if( $country == 'Pitcairn' ) $continent = 'Oceania';
    if( $country == 'Poland' ) $continent = 'Europe';
    if( $country == 'Portugal' ) $continent = 'Europe';
    if( $country == 'Puerto Rico' ) $continent = 'North America';
    if( $country == 'Qatar' ) $continent = 'Asia';
    if( $country == 'Réunion' ) $continent = 'Africa';
    if( $country == 'Romania' ) $continent = 'Europe';
    if( $country == 'Russia' ) $continent = 'Europe';
    if( $country == 'Rwanda' ) $continent = 'Africa';
    if( $country == 'Saint Helena' ) $continent = 'Africa';
    if( $country == 'Saint Kitts and Nevis' ) $continent = 'North America';
    if( $country == 'Saint Lucia' ) $continent = 'North America';
    if( $country == 'Saint Pierre and Miquelon' ) $continent = 'North America';
    if( $country == 'Saint Vincent and the Grenadines' ) $continent = 'North America';
    if( $country == 'Samoa' ) $continent = 'Oceania';
    if( $country == 'San Marino' ) $continent = 'Europe';
    if( $country == 'Sao Tome and Principe' ) $continent = 'Africa';
    if( $country == 'Saudi Arabia' ) $continent = 'Asia';
    if( $country == 'Senegal' ) $continent = 'Africa';
    if( $country == 'Serbia' ) $continent = 'Europe';
    if( $country == 'Seychelles' ) $continent = 'Africa';
    if( $country == 'Sierra Leone' ) $continent = 'Africa';
    if( $country == 'Singapore' ) $continent = 'Asia';
    if( $country == 'Slovakia' ) $continent = 'Europe';
    if( $country == 'Slovenia' ) $continent = 'Europe';
    if( $country == 'Solomon Islands' ) $continent = 'Oceania';
    if( $country == 'Somalia' ) $continent = 'Africa';
    if( $country == 'South Africa' ) $continent = 'Africa';
    if( $country == 'South Georgia and the South Sandwich Islands' ) $continent = 'Antarctica';
    if( $country == 'Spain' ) $continent = 'Europe';
    if( $country == 'Sri Lanka' ) $continent = 'Asia';
    if( $country == 'Sudan' ) $continent = 'Africa';
    if( $country == 'Suriname' ) $continent = 'South America';
    if( $country == 'Svalbard and Jan Mayen' ) $continent = 'Europe';
    if( $country == 'Swaziland' ) $continent = 'Africa';
    if( $country == 'Sweden' ) $continent = 'Europe';
    if( $country == 'Switzerland' ) $continent = 'Europe';
    if( $country == 'Syria' ) $continent = 'Asia';
    if( $country == 'Taiwan' ) $continent = 'Asia';
    if( $country == 'Tajikistan' ) $continent = 'Asia';
    if( $country == 'Tanzania' ) $continent = 'Africa';
    if( $country == 'Thailand' ) $continent = 'Asia';
    if( $country == 'Timor-Leste' ) $continent = 'Asia';
    if( $country == 'Togo' ) $continent = 'Africa';
    if( $country == 'Tokelau' ) $continent = 'Oceania';
    if( $country == 'Tonga' ) $continent = 'Oceania';
    if( $country == 'Trinidad and Tobago' ) $continent = 'North America';
    if( $country == 'Tunisia' ) $continent = 'Africa';
    if( $country == 'Turkey' ) $continent = 'Asia';
    if( $country == 'Turkmenistan' ) $continent = 'Asia';
    if( $country == 'Turks and Caicos Islands' ) $continent = 'North America';
    if( $country == 'Tuvalu' ) $continent = 'Oceania';
    if( $country == 'Uganda' ) $continent = 'Africa';
    if( $country == 'Ukraine' ) $continent = 'Europe';
    if( $country == 'United Arab Emirates' ) $continent = 'Asia';
    if( $country == 'United Kingdom' ) $continent = 'Europe';
    if( $country == 'USA' ) $continent = 'North America';
    if( $country == 'United States' ) $continent = 'North America';
    if( $country == 'United States Minor Outlying Islands' ) $continent = 'Oceania';
    if( $country == 'Uruguay' ) $continent = 'South America';
    if( $country == 'Uzbekistan' ) $continent = 'Asia';
    if( $country == 'Vanuatu' ) $continent = 'Oceania';
    if( $country == 'Venezuela' ) $continent = 'South America';
    if( $country == 'Vietnam' ) $continent = 'Asia';
    if( $country == 'British Virgin Islands' ) $continent = 'North America';
    if( $country == 'U.S. Virgin Islands' ) $continent = 'North America';
    if( $country == 'Wallis and Futuna' ) $continent = 'Oceania';
    if( $country == 'Western Sahara' ) $continent = 'Africa';
    if( $country == 'Yemen' ) $continent = 'Asia';
    if( $country == 'Zambia' ) $continent = 'Africa';
    if( $country == 'Zimbabwe' ) $continent = 'Africa';

    return $continent;
}

function dateFormat($date)
{
    $convert = DateTime::createFromFormat('d/m/Y', $date);
    return ($convert->format('Y-m-d'));
}

function countryCodeToCountry($code)
{
    $code = strtoupper($code);
    if ($code == 'AF')
        return 'Afghanistan';
    if ($code == 'AX')
        return 'Aland Islands';
    if ($code == 'AL')
        return 'Albania';
    if ($code == 'DZ')
        return 'Algeria';
    if ($code == 'AS')
        return 'American Samoa';
    if ($code == 'AD')
        return 'Andorra';
    if ($code == 'AO')
        return 'Angola';
    if ($code == 'AI')
        return 'Anguilla';
    if ($code == 'AQ')
        return 'Antarctica';
    if ($code == 'AG')
        return 'Antigua and Barbuda';
    if ($code == 'AR')
        return 'Argentina';
    if ($code == 'AM')
        return 'Armenia';
    if ($code == 'AW')
        return 'Aruba';
    if ($code == 'AU')
        return 'Australia';
    if ($code == 'AT')
        return 'Austria';
    if ($code == 'AZ')
        return 'Azerbaijan';
    if ($code == 'BS')
        return 'Bahamas the';
    if ($code == 'BH')
        return 'Bahrain';
    if ($code == 'BD')
        return 'Bangladesh';
    if ($code == 'BB')
        return 'Barbados';
    if ($code == 'BY')
        return 'Belarus';
    if ($code == 'BE')
        return 'Belgium';
    if ($code == 'BZ')
        return 'Belize';
    if ($code == 'BJ')
        return 'Benin';
    if ($code == 'BM')
        return 'Bermuda';
    if ($code == 'BT')
        return 'Bhutan';
    if ($code == 'BO')
        return 'Bolivia';
    if ($code == 'BA')
        return 'Bosnia and Herzegovina';
    if ($code == 'BW')
        return 'Botswana';
    if ($code == 'BV')
        return 'Bouvet Island (Bouvetoya)';
    if ($code == 'BR')
        return 'Brazil';
    if ($code == 'IO')
        return 'British Indian Ocean Territory (Chagos Archipelago)';
    if ($code == 'VG')
        return 'British Virgin Islands';
    if ($code == 'BN')
        return 'Brunei Darussalam';
    if ($code == 'BG')
        return 'Bulgaria';
    if ($code == 'BF')
        return 'Burkina Faso';
    if ($code == 'BI')
        return 'Burundi';
    if ($code == 'KH')
        return 'Cambodia';
    if ($code == 'CM')
        return 'Cameroon';
    if ($code == 'CA')
        return 'Canada';
    if ($code == 'CV')
        return 'Cape Verde';
    if ($code == 'KY')
        return 'Cayman Islands';
    if ($code == 'CF')
        return 'Central African Republic';
    if ($code == 'TD')
        return 'Chad';
    if ($code == 'CL')
        return 'Chile';
    if ($code == 'CN')
        return 'China';
    if ($code == 'CX')
        return 'Christmas Island';
    if ($code == 'CC')
        return 'Cocos (Keeling) Islands';
    if ($code == 'CO')
        return 'Colombia';
    if ($code == 'KM')
        return 'Comoros the';
    if ($code == 'CD')
        return 'Congo';
    if ($code == 'CG')
        return 'Congo the';
    if ($code == 'CK')
        return 'Cook Islands';
    if ($code == 'CR')
        return 'Costa Rica';
    if ($code == 'CI')
        return 'Cote d\'Ivoire';
    if ($code == 'HR')
        return 'Croatia';
    if ($code == 'CU')
        return 'Cuba';
    if ($code == 'CY')
        return 'Cyprus';
    if ($code == 'CZ')
        return 'Czech Republic';
    if ($code == 'DK')
        return 'Denmark';
    if ($code == 'DJ')
        return 'Djibouti';
    if ($code == 'DM')
        return 'Dominica';
    if ($code == 'DO')
        return 'Dominican Republic';
    if ($code == 'EC')
        return 'Ecuador';
    if ($code == 'EG')
        return 'Egypt';
    if ($code == 'SV')
        return 'El Salvador';
    if ($code == 'GQ')
        return 'Equatorial Guinea';
    if ($code == 'ER')
        return 'Eritrea';
    if ($code == 'EE')
        return 'Estonia';
    if ($code == 'ET')
        return 'Ethiopia';
    if ($code == 'FO')
        return 'Faroe Islands';
    if ($code == 'FK')
        return 'Falkland Islands (Malvinas)';
    if ($code == 'FJ')
        return 'Fiji the Fiji Islands';
    if ($code == 'FI')
        return 'Finland';
    if ($code == 'FR')
        return 'France';
    if ($code == 'GF')
        return 'French Guiana';
    if ($code == 'PF')
        return 'French Polynesia';
    if ($code == 'TF')
        return 'French Southern Territories';
    if ($code == 'GA')
        return 'Gabon';
    if ($code == 'GM')
        return 'Gambia the';
    if ($code == 'GE')
        return 'Georgia';
    if ($code == 'DE')
        return 'Germany';
    if ($code == 'GH')
        return 'Ghana';
    if ($code == 'GI')
        return 'Gibraltar';
    if ($code == 'GR')
        return 'Greece';
    if ($code == 'GL')
        return 'Greenland';
    if ($code == 'GD')
        return 'Grenada';
    if ($code == 'GP')
        return 'Guadeloupe';
    if ($code == 'GU')
        return 'Guam';
    if ($code == 'GT')
        return 'Guatemala';
    if ($code == 'GG')
        return 'Guernsey';
    if ($code == 'GN')
        return 'Guinea';
    if ($code == 'GW')
        return 'Guinea-Bissau';
    if ($code == 'GY')
        return 'Guyana';
    if ($code == 'HT')
        return 'Haiti';
    if ($code == 'HM')
        return 'Heard Island and McDonald Islands';
    if ($code == 'VA')
        return 'Holy See (Vatican City State)';
    if ($code == 'HN')
        return 'Honduras';
    if ($code == 'HK')
        return 'Hong Kong';
    if ($code == 'HU')
        return 'Hungary';
    if ($code == 'IS')
        return 'Iceland';
    if ($code == 'IN')
        return 'India';
    if ($code == 'ID')
        return 'Indonesia';
    if ($code == 'IR')
        return 'Iran';
    if ($code == 'IQ')
        return 'Iraq';
    if ($code == 'IE')
        return 'Ireland';
    if ($code == 'IM')
        return 'Isle of Man';
    if ($code == 'IL')
        return 'Israel';
    if ($code == 'IT')
        return 'Italy';
    if ($code == 'JM')
        return 'Jamaica';
    if ($code == 'JP')
        return 'Japan';
    if ($code == 'JE')
        return 'Jersey';
    if ($code == 'JO')
        return 'Jordan';
    if ($code == 'KZ')
        return 'Kazakhstan';
    if ($code == 'KE')
        return 'Kenya';
    if ($code == 'KI')
        return 'Kiribati';
    if ($code == 'KP')
        return 'Korea';
    if ($code == 'KR')
        return 'Korea';
    if ($code == 'KW')
        return 'Kuwait';
    if ($code == 'KG')
        return 'Kyrgyz Republic';
    if ($code == 'LA')
        return 'Lao';
    if ($code == 'LV')
        return 'Latvia';
    if ($code == 'LB')
        return 'Lebanon';
    if ($code == 'LS')
        return 'Lesotho';
    if ($code == 'LR')
        return 'Liberia';
    if ($code == 'LY')
        return 'Libyan Arab Jamahiriya';
    if ($code == 'LI')
        return 'Liechtenstein';
    if ($code == 'LT')
        return 'Lithuania';
    if ($code == 'LU')
        return 'Luxembourg';
    if ($code == 'MO')
        return 'Macao';
    if ($code == 'MK')
        return 'Macedonia';
    if ($code == 'MG')
        return 'Madagascar';
    if ($code == 'MW')
        return 'Malawi';
    if ($code == 'MY')
        return 'Malaysia';
    if ($code == 'MV')
        return 'Maldives';
    if ($code == 'ML')
        return 'Mali';
    if ($code == 'MT')
        return 'Malta';
    if ($code == 'MH')
        return 'Marshall Islands';
    if ($code == 'MQ')
        return 'Martinique';
    if ($code == 'MR')
        return 'Mauritania';
    if ($code == 'MU')
        return 'Mauritius';
    if ($code == 'YT')
        return 'Mayotte';
    if ($code == 'MX')
        return 'Mexico';
    if ($code == 'FM')
        return 'Micronesia';
    if ($code == 'MD')
        return 'Moldova';
    if ($code == 'MC')
        return 'Monaco';
    if ($code == 'MN')
        return 'Mongolia';
    if ($code == 'ME')
        return 'Montenegro';
    if ($code == 'MS')
        return 'Montserrat';
    if ($code == 'MA')
        return 'Morocco';
    if ($code == 'MZ')
        return 'Mozambique';
    if ($code == 'MM')
        return 'Myanmar';
    if ($code == 'NA')
        return 'Namibia';
    if ($code == 'NR')
        return 'Nauru';
    if ($code == 'NP')
        return 'Nepal';
    if ($code == 'AN')
        return 'Netherlands Antilles';
    if ($code == 'NL')
        return 'Netherlands the';
    if ($code == 'NC')
        return 'New Caledonia';
    if ($code == 'NZ')
        return 'New Zealand';
    if ($code == 'NI')
        return 'Nicaragua';
    if ($code == 'NE')
        return 'Niger';
    if ($code == 'NG')
        return 'Nigeria';
    if ($code == 'NU')
        return 'Niue';
    if ($code == 'NF')
        return 'Norfolk Island';
    if ($code == 'MP')
        return 'Northern Mariana Islands';
    if ($code == 'NO')
        return 'Norway';
    if ($code == 'OM')
        return 'Oman';
    if ($code == 'PK')
        return 'Pakistan';
    if ($code == 'PW')
        return 'Palau';
    if ($code == 'PS')
        return 'Palestinian Territory';
    if ($code == 'PA')
        return 'Panama';
    if ($code == 'PG')
        return 'Papua New Guinea';
    if ($code == 'PY')
        return 'Paraguay';
    if ($code == 'PE')
        return 'Peru';
    if ($code == 'PH')
        return 'Philippines';
    if ($code == 'PN')
        return 'Pitcairn Islands';
    if ($code == 'PL')
        return 'Poland';
    if ($code == 'PT')
        return 'Portugal, Portuguese Republic';
    if ($code == 'PR')
        return 'Puerto Rico';
    if ($code == 'QA')
        return 'Qatar';
    if ($code == 'RE')
        return 'Reunion';
    if ($code == 'RO')
        return 'Romania';
    if ($code == 'RU')
        return 'Russian Federation';
    if ($code == 'RW')
        return 'Rwanda';
    if ($code == 'BL')
        return 'Saint Barthelemy';
    if ($code == 'SH')
        return 'Saint Helena';
    if ($code == 'KN')
        return 'Saint Kitts and Nevis';
    if ($code == 'LC')
        return 'Saint Lucia';
    if ($code == 'MF')
        return 'Saint Martin';
    if ($code == 'PM')
        return 'Saint Pierre and Miquelon';
    if ($code == 'VC')
        return 'Saint Vincent and the Grenadines';
    if ($code == 'WS')
        return 'Samoa';
    if ($code == 'SM')
        return 'San Marino';
    if ($code == 'ST')
        return 'Sao Tome and Principe';
    if ($code == 'SA')
        return 'Saudi Arabia';
    if ($code == 'SN')
        return 'Senegal';
    if ($code == 'RS')
        return 'Serbia';
    if ($code == 'SC')
        return 'Seychelles';
    if ($code == 'SL')
        return 'Sierra Leone';
    if ($code == 'SG')
        return 'Singapore';
    if ($code == 'SK')
        return 'Slovakia (Slovak Republic)';
    if ($code == 'SI')
        return 'Slovenia';
    if ($code == 'SB')
        return 'Solomon Islands';
    if ($code == 'SO')
        return 'Somalia, Somali Republic';
    if ($code == 'ZA')
        return 'South Africa';
    if ($code == 'GS')
        return 'South Georgia and the South Sandwich Islands';
    if ($code == 'ES')
        return 'Spain';
    if ($code == 'LK')
        return 'Sri Lanka';
    if ($code == 'SD')
        return 'Sudan';
    if ($code == 'SS')
        return 'South Sudan';
    if ($code == 'SR')
        return 'Suriname';
    if ($code == 'SJ')
        return 'Svalbard & Jan Mayen Islands';
    if ($code == 'SZ')
        return 'Swaziland';
    if ($code == 'SE')
        return 'Sweden';
    if ($code == 'CH')
        return 'Switzerland, Swiss Confederation';
    if ($code == 'SY')
        return 'Syrian Arab Republic';
    if ($code == 'TW')
        return 'Taiwan';
    if ($code == 'TJ')
        return 'Tajikistan';
    if ($code == 'TZ')
        return 'Tanzania';
    if ($code == 'TH')
        return 'Thailand';
    if ($code == 'TL')
        return 'Timor-Leste';
    if ($code == 'TG')
        return 'Togo';
    if ($code == 'TK')
        return 'Tokelau';
    if ($code == 'TO')
        return 'Tonga';
    if ($code == 'TT')
        return 'Trinidad and Tobago';
    if ($code == 'TN')
        return 'Tunisia';
    if ($code == 'TR')
        return 'Turkey';
    if ($code == 'TM')
        return 'Turkmenistan';
    if ($code == 'TC')
        return 'Turks and Caicos Islands';
    if ($code == 'TV')
        return 'Tuvalu';
    if ($code == 'UG')
        return 'Uganda';
    if ($code == 'UA')
        return 'Ukraine';
    if ($code == 'AE')
        return 'United Arab Emirates';
    if ($code == 'GB')
        return 'United Kingdom';
    if ($code == 'US')
        return 'United States of America';
    if ($code == 'UM')
        return 'United States Minor Outlying Islands';
    if ($code == 'VI')
        return 'United States Virgin Islands';
    if ($code == 'UY')
        return 'Uruguay, Eastern Republic of';
    if ($code == 'UZ')
        return 'Uzbekistan';
    if ($code == 'VU')
        return 'Vanuatu';
    if ($code == 'VE')
        return 'Venezuela';
    if ($code == 'VN')
        return 'Vietnam';
    if ($code == 'WF')
        return 'Wallis and Futuna';
    if ($code == 'EH')
        return 'Western Sahara';
    if ($code == 'YE')
        return 'Yemen';
    if ($code == 'XK')
        return 'Kosovo';
    if ($code == 'ZM')
        return 'Zambia';
    if ($code == 'ZW')
        return 'Zimbabwe';
    return 'Unknown Country';
}

function html_cut($text, $max_length)
{
    $tags = array();
    $result = "";

    $is_open = false;
    $grab_open = false;
    $is_close = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    // while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    // {
    //     $symbol  = $text{$i};
    //     $result .= $symbol;

    //     switch ($symbol)
    //     {
    //        case '<':
    //             $is_open   = true;
    //             $grab_open = true;
    //             break;
    //        case '"':
    //            if ($in_double_quotes)
    //                $in_double_quotes = false;
    //            else
    //                $in_double_quotes = true;
    //             break;

    //         case "'":
    //           if ($in_single_quotes)
    //               $in_single_quotes = false;
    //           else
    //               $in_single_quotes = true;
    //             break;
    //         case '/':
    //             if ($is_open && !$in_double_quotes && !$in_single_quotes)
    //             {
    //                 $is_close  = true;
    //                 $is_open   = false;
    //                 $grab_open = false;
    //             }
    //             break;
    //         case ' ':
    //             if ($is_open)
    //                 $grab_open = false;
    //             else
    //                 $stripped++;
    //             break;
    //         case '>':
    //             if ($is_open)
    //             {
    //                 $is_open   = false;
    //                 $grab_open = false;
    //                 array_push($tags, $tag);
    //                 $tag = "";
    //             }
    //             else if ($is_close)
    //             {
    //                 $is_close = false;
    //                 array_pop($tags);
    //                 $tag = "";
    //             }
    //             break;
    //         default:
    //             if ($grab_open || $is_close)
    //                 $tag .= $symbol;

    //             if (!$is_open && !$is_close)
    //                 $stripped++;
    //     }

    //     $i++;
    // }

    while ($tags)
        $result .= "</" . array_pop($tags) . ">";

    return $result;
}

// https://gist.github.com/josephilipraja/8341837
$countryArray = array(
    'AD' => array('name' => 'ANDORRA', 'code' => '376'),
    'AE' => array('name' => 'UNITED ARAB EMIRATES', 'code' => '971'),
    'AF' => array('name' => 'AFGHANISTAN', 'code' => '93'),
    'AG' => array('name' => 'ANTIGUA AND BARBUDA', 'code' => '1268'),
    'AI' => array('name' => 'ANGUILLA', 'code' => '1264'),
    'AL' => array('name' => 'ALBANIA', 'code' => '355'),
    'AM' => array('name' => 'ARMENIA', 'code' => '374'),
    'AN' => array('name' => 'NETHERLANDS ANTILLES', 'code' => '599'),
    'AO' => array('name' => 'ANGOLA', 'code' => '244'),
    'AQ' => array('name' => 'ANTARCTICA', 'code' => '672'),
    'AR' => array('name' => 'ARGENTINA', 'code' => '54'),
    'AS' => array('name' => 'AMERICAN SAMOA', 'code' => '1684'),
    'AT' => array('name' => 'AUSTRIA', 'code' => '43'),
    'AU' => array('name' => 'AUSTRALIA', 'code' => '61'),
    'AW' => array('name' => 'ARUBA', 'code' => '297'),
    'AZ' => array('name' => 'AZERBAIJAN', 'code' => '994'),
    'BA' => array('name' => 'BOSNIA AND HERZEGOVINA', 'code' => '387'),
    'BB' => array('name' => 'BARBADOS', 'code' => '1246'),
    'BD' => array('name' => 'BANGLADESH', 'code' => '880'),
    'BE' => array('name' => 'BELGIUM', 'code' => '32'),
    'BF' => array('name' => 'BURKINA FASO', 'code' => '226'),
    'BG' => array('name' => 'BULGARIA', 'code' => '359'),
    'BH' => array('name' => 'BAHRAIN', 'code' => '973'),
    'BI' => array('name' => 'BURUNDI', 'code' => '257'),
    'BJ' => array('name' => 'BENIN', 'code' => '229'),
    'BL' => array('name' => 'SAINT BARTHELEMY', 'code' => '590'),
    'BM' => array('name' => 'BERMUDA', 'code' => '1441'),
    'BN' => array('name' => 'BRUNEI DARUSSALAM', 'code' => '673'),
    'BO' => array('name' => 'BOLIVIA', 'code' => '591'),
    'BR' => array('name' => 'BRAZIL', 'code' => '55'),
    'BS' => array('name' => 'BAHAMAS', 'code' => '1242'),
    'BT' => array('name' => 'BHUTAN', 'code' => '975'),
    'BW' => array('name' => 'BOTSWANA', 'code' => '267'),
    'BY' => array('name' => 'BELARUS', 'code' => '375'),
    'BZ' => array('name' => 'BELIZE', 'code' => '501'),
    'CA' => array('name' => 'CANADA', 'code' => '1'),
    'CC' => array('name' => 'COCOS (KEELING) ISLANDS', 'code' => '61'),
    'CD' => array('name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'code' => '243'),
    'CF' => array('name' => 'CENTRAL AFRICAN REPUBLIC', 'code' => '236'),
    'CG' => array('name' => 'CONGO', 'code' => '242'),
    'CH' => array('name' => 'SWITZERLAND', 'code' => '41'),
    'CI' => array('name' => 'COTE D IVOIRE', 'code' => '225'),
    'CK' => array('name' => 'COOK ISLANDS', 'code' => '682'),
    'CL' => array('name' => 'CHILE', 'code' => '56'),
    'CM' => array('name' => 'CAMEROON', 'code' => '237'),
    'CN' => array('name' => 'CHINA', 'code' => '86'),
    'CO' => array('name' => 'COLOMBIA', 'code' => '57'),
    'CR' => array('name' => 'COSTA RICA', 'code' => '506'),
    'CU' => array('name' => 'CUBA', 'code' => '53'),
    'CV' => array('name' => 'CAPE VERDE', 'code' => '238'),
    'CX' => array('name' => 'CHRISTMAS ISLAND', 'code' => '61'),
    'CY' => array('name' => 'CYPRUS', 'code' => '357'),
    'CZ' => array('name' => 'CZECH REPUBLIC', 'code' => '420'),
    'DE' => array('name' => 'GERMANY', 'code' => '49'),
    'DJ' => array('name' => 'DJIBOUTI', 'code' => '253'),
    'DK' => array('name' => 'DENMARK', 'code' => '45'),
    'DM' => array('name' => 'DOMINICA', 'code' => '1767'),
    'DO' => array('name' => 'DOMINICAN REPUBLIC', 'code' => '1809'),
    'DZ' => array('name' => 'ALGERIA', 'code' => '213'),
    'EC' => array('name' => 'ECUADOR', 'code' => '593'),
    'EE' => array('name' => 'ESTONIA', 'code' => '372'),
    'EG' => array('name' => 'EGYPT', 'code' => '20'),
    'ER' => array('name' => 'ERITREA', 'code' => '291'),
    'ES' => array('name' => 'SPAIN', 'code' => '34'),
    'ET' => array('name' => 'ETHIOPIA', 'code' => '251'),
    'FI' => array('name' => 'FINLAND', 'code' => '358'),
    'FJ' => array('name' => 'FIJI', 'code' => '679'),
    'FK' => array('name' => 'FALKLAND ISLANDS (MALVINAS)', 'code' => '500'),
    'FM' => array('name' => 'MICRONESIA, FEDERATED STATES OF', 'code' => '691'),
    'FO' => array('name' => 'FAROE ISLANDS', 'code' => '298'),
    'FR' => array('name' => 'FRANCE', 'code' => '33'),
    'GA' => array('name' => 'GABON', 'code' => '241'),
    'GB' => array('name' => 'UNITED KINGDOM', 'code' => '44'),
    'GD' => array('name' => 'GRENADA', 'code' => '1473'),
    'GE' => array('name' => 'GEORGIA', 'code' => '995'),
    'GH' => array('name' => 'GHANA', 'code' => '233'),
    'GI' => array('name' => 'GIBRALTAR', 'code' => '350'),
    'GL' => array('name' => 'GREENLAND', 'code' => '299'),
    'GM' => array('name' => 'GAMBIA', 'code' => '220'),
    'GN' => array('name' => 'GUINEA', 'code' => '224'),
    'GQ' => array('name' => 'EQUATORIAL GUINEA', 'code' => '240'),
    'GR' => array('name' => 'GREECE', 'code' => '30'),
    'GT' => array('name' => 'GUATEMALA', 'code' => '502'),
    'GU' => array('name' => 'GUAM', 'code' => '1671'),
    'GW' => array('name' => 'GUINEA-BISSAU', 'code' => '245'),
    'GY' => array('name' => 'GUYANA', 'code' => '592'),
    'HK' => array('name' => 'HONG KONG', 'code' => '852'),
    'HN' => array('name' => 'HONDURAS', 'code' => '504'),
    'HR' => array('name' => 'CROATIA', 'code' => '385'),
    'HT' => array('name' => 'HAITI', 'code' => '509'),
    'HU' => array('name' => 'HUNGARY', 'code' => '36'),
    'ID' => array('name' => 'INDONESIA', 'code' => '62'),
    'IE' => array('name' => 'IRELAND', 'code' => '353'),
    'IL' => array('name' => 'ISRAEL', 'code' => '972'),
    'IM' => array('name' => 'ISLE OF MAN', 'code' => '44'),
    'IN' => array('name' => 'INDIA', 'code' => '91'),
    'IQ' => array('name' => 'IRAQ', 'code' => '964'),
    'IR' => array('name' => 'IRAN, ISLAMIC REPUBLIC OF', 'code' => '98'),
    'IS' => array('name' => 'ICELAND', 'code' => '354'),
    'IT' => array('name' => 'ITALY', 'code' => '39'),
    'JM' => array('name' => 'JAMAICA', 'code' => '1876'),
    'JO' => array('name' => 'JORDAN', 'code' => '962'),
    'JP' => array('name' => 'JAPAN', 'code' => '81'),
    'KE' => array('name' => 'KENYA', 'code' => '254'),
    'KG' => array('name' => 'KYRGYZSTAN', 'code' => '996'),
    'KH' => array('name' => 'CAMBODIA', 'code' => '855'),
    'KI' => array('name' => 'KIRIBATI', 'code' => '686'),
    'KM' => array('name' => 'COMOROS', 'code' => '269'),
    'KN' => array('name' => 'SAINT KITTS AND NEVIS', 'code' => '1869'),
    'KP' => array('name' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'code' => '850'),
    'KR' => array('name' => 'KOREA REPUBLIC OF', 'code' => '82'),
    'KW' => array('name' => 'KUWAIT', 'code' => '965'),
    'KY' => array('name' => 'CAYMAN ISLANDS', 'code' => '1345'),
    'KZ' => array('name' => 'KAZAKSTAN', 'code' => '7'),
    'LA' => array('name' => 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'code' => '856'),
    'LB' => array('name' => 'LEBANON', 'code' => '961'),
    'LC' => array('name' => 'SAINT LUCIA', 'code' => '1758'),
    'LI' => array('name' => 'LIECHTENSTEIN', 'code' => '423'),
    'LK' => array('name' => 'SRI LANKA', 'code' => '94'),
    'LR' => array('name' => 'LIBERIA', 'code' => '231'),
    'LS' => array('name' => 'LESOTHO', 'code' => '266'),
    'LT' => array('name' => 'LITHUANIA', 'code' => '370'),
    'LU' => array('name' => 'LUXEMBOURG', 'code' => '352'),
    'LV' => array('name' => 'LATVIA', 'code' => '371'),
    'LY' => array('name' => 'LIBYAN ARAB JAMAHIRIYA', 'code' => '218'),
    'MA' => array('name' => 'MOROCCO', 'code' => '212'),
    'MC' => array('name' => 'MONACO', 'code' => '377'),
    'MD' => array('name' => 'MOLDOVA, REPUBLIC OF', 'code' => '373'),
    'ME' => array('name' => 'MONTENEGRO', 'code' => '382'),
    'MF' => array('name' => 'SAINT MARTIN', 'code' => '1599'),
    'MG' => array('name' => 'MADAGASCAR', 'code' => '261'),
    'MH' => array('name' => 'MARSHALL ISLANDS', 'code' => '692'),
    'MK' => array('name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'code' => '389'),
    'ML' => array('name' => 'MALI', 'code' => '223'),
    'MM' => array('name' => 'MYANMAR', 'code' => '95'),
    'MN' => array('name' => 'MONGOLIA', 'code' => '976'),
    'MO' => array('name' => 'MACAU', 'code' => '853'),
    'MP' => array('name' => 'NORTHERN MARIANA ISLANDS', 'code' => '1670'),
    'MR' => array('name' => 'MAURITANIA', 'code' => '222'),
    'MS' => array('name' => 'MONTSERRAT', 'code' => '1664'),
    'MT' => array('name' => 'MALTA', 'code' => '356'),
    'MU' => array('name' => 'MAURITIUS', 'code' => '230'),
    'MV' => array('name' => 'MALDIVES', 'code' => '960'),
    'MW' => array('name' => 'MALAWI', 'code' => '265'),
    'MX' => array('name' => 'MEXICO', 'code' => '52'),
    'MY' => array('name' => 'MALAYSIA', 'code' => '60'),
    'MZ' => array('name' => 'MOZAMBIQUE', 'code' => '258'),
    'NA' => array('name' => 'NAMIBIA', 'code' => '264'),
    'NC' => array('name' => 'NEW CALEDONIA', 'code' => '687'),
    'NE' => array('name' => 'NIGER', 'code' => '227'),
    'NG' => array('name' => 'NIGERIA', 'code' => '234'),
    'NI' => array('name' => 'NICARAGUA', 'code' => '505'),
    'NL' => array('name' => 'NETHERLANDS', 'code' => '31'),
    'NO' => array('name' => 'NORWAY', 'code' => '47'),
    'NP' => array('name' => 'NEPAL', 'code' => '977'),
    'NR' => array('name' => 'NAURU', 'code' => '674'),
    'NU' => array('name' => 'NIUE', 'code' => '683'),
    'NZ' => array('name' => 'NEW ZEALAND', 'code' => '64'),
    'OM' => array('name' => 'OMAN', 'code' => '968'),
    'PA' => array('name' => 'PANAMA', 'code' => '507'),
    'PE' => array('name' => 'PERU', 'code' => '51'),
    'PF' => array('name' => 'FRENCH POLYNESIA', 'code' => '689'),
    'PG' => array('name' => 'PAPUA NEW GUINEA', 'code' => '675'),
    'PH' => array('name' => 'PHILIPPINES', 'code' => '63'),
    'PK' => array('name' => 'PAKISTAN', 'code' => '92'),
    'PL' => array('name' => 'POLAND', 'code' => '48'),
    'PM' => array('name' => 'SAINT PIERRE AND MIQUELON', 'code' => '508'),
    'PN' => array('name' => 'PITCAIRN', 'code' => '870'),
    'PR' => array('name' => 'PUERTO RICO', 'code' => '1'),
    'PT' => array('name' => 'PORTUGAL', 'code' => '351'),
    'PW' => array('name' => 'PALAU', 'code' => '680'),
    'PY' => array('name' => 'PARAGUAY', 'code' => '595'),
    'QA' => array('name' => 'QATAR', 'code' => '974'),
    'RO' => array('name' => 'ROMANIA', 'code' => '40'),
    'RS' => array('name' => 'SERBIA', 'code' => '381'),
    'RU' => array('name' => 'RUSSIAN FEDERATION', 'code' => '7'),
    'RW' => array('name' => 'RWANDA', 'code' => '250'),
    'SA' => array('name' => 'SAUDI ARABIA', 'code' => '966'),
    'SB' => array('name' => 'SOLOMON ISLANDS', 'code' => '677'),
    'SC' => array('name' => 'SEYCHELLES', 'code' => '248'),
    'SD' => array('name' => 'SUDAN', 'code' => '249'),
    'SE' => array('name' => 'SWEDEN', 'code' => '46'),
    'SG' => array('name' => 'SINGAPORE', 'code' => '65'),
    'SH' => array('name' => 'SAINT HELENA', 'code' => '290'),
    'SI' => array('name' => 'SLOVENIA', 'code' => '386'),
    'SK' => array('name' => 'SLOVAKIA', 'code' => '421'),
    'SL' => array('name' => 'SIERRA LEONE', 'code' => '232'),
    'SM' => array('name' => 'SAN MARINO', 'code' => '378'),
    'SN' => array('name' => 'SENEGAL', 'code' => '221'),
    'SO' => array('name' => 'SOMALIA', 'code' => '252'),
    'SR' => array('name' => 'SURINAME', 'code' => '597'),
    'ST' => array('name' => 'SAO TOME AND PRINCIPE', 'code' => '239'),
    'SV' => array('name' => 'EL SALVADOR', 'code' => '503'),
    'SY' => array('name' => 'SYRIAN ARAB REPUBLIC', 'code' => '963'),
    'SZ' => array('name' => 'SWAZILAND', 'code' => '268'),
    'TC' => array('name' => 'TURKS AND CAICOS ISLANDS', 'code' => '1649'),
    'TD' => array('name' => 'CHAD', 'code' => '235'),
    'TG' => array('name' => 'TOGO', 'code' => '228'),
    'TH' => array('name' => 'THAILAND', 'code' => '66'),
    'TJ' => array('name' => 'TAJIKISTAN', 'code' => '992'),
    'TK' => array('name' => 'TOKELAU', 'code' => '690'),
    'TL' => array('name' => 'TIMOR-LESTE', 'code' => '670'),
    'TM' => array('name' => 'TURKMENISTAN', 'code' => '993'),
    'TN' => array('name' => 'TUNISIA', 'code' => '216'),
    'TO' => array('name' => 'TONGA', 'code' => '676'),
    'TR' => array('name' => 'TURKEY', 'code' => '90'),
    'TT' => array('name' => 'TRINIDAD AND TOBAGO', 'code' => '1868'),
    'TV' => array('name' => 'TUVALU', 'code' => '688'),
    'TW' => array('name' => 'TAIWAN, PROVINCE OF CHINA', 'code' => '886'),
    'TZ' => array('name' => 'TANZANIA, UNITED REPUBLIC OF', 'code' => '255'),
    'UA' => array('name' => 'UKRAINE', 'code' => '380'),
    'UG' => array('name' => 'UGANDA', 'code' => '256'),
    'US' => array('name' => 'UNITED STATES', 'code' => '1'),
    'UY' => array('name' => 'URUGUAY', 'code' => '598'),
    'UZ' => array('name' => 'UZBEKISTAN', 'code' => '998'),
    'VA' => array('name' => 'HOLY SEE (VATICAN CITY STATE)', 'code' => '39'),
    'VC' => array('name' => 'SAINT VINCENT AND THE GRENADINES', 'code' => '1784'),
    'VE' => array('name' => 'VENEZUELA', 'code' => '58'),
    'VG' => array('name' => 'VIRGIN ISLANDS, BRITISH', 'code' => '1284'),
    'VI' => array('name' => 'VIRGIN ISLANDS, U.S.', 'code' => '1340'),
    'VN' => array('name' => 'VIET NAM', 'code' => '84'),
    'VU' => array('name' => 'VANUATU', 'code' => '678'),
    'WF' => array('name' => 'WALLIS AND FUTUNA', 'code' => '681'),
    'WS' => array('name' => 'SAMOA', 'code' => '685'),
    'XK' => array('name' => 'KOSOVO', 'code' => '381'),
    'YE' => array('name' => 'YEMEN', 'code' => '967'),
    'YT' => array('name' => 'MAYOTTE', 'code' => '262'),
    'ZA' => array('name' => 'SOUTH AFRICA', 'code' => '27'),
    'ZM' => array('name' => 'ZAMBIA', 'code' => '260'),
    'ZW' => array('name' => 'ZIMBABWE', 'code' => '263')
);

$INC_DIR = $_SERVER['DOCUMENT_ROOT'] . "/thefuture/adminPortal/includes/"; //Local
// $INC_DIR = $_SERVER['DOCUMENT_ROOT'] . "/includes/"; //Live