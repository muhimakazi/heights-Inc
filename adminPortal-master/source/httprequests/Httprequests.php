<?php
class Httprequests
{

    /**
     * get Authorization token from header
     * */
    public static function getRequestHeader($headers, $token_name)
    {
        $authorization = "";

        if (!DataValidation::isEmpty($headers)) {
            foreach ($headers as $name => $value) {

                if ($name === $token_name) {
                    $authorization = $headers[$name];
                    return $authorization;
                }
            }
        }

        return $authorization;
    }


    public static function getBearerToken($bearerToken)
    {
        if (substr($bearerToken, 0, strlen("Bearer")) === "Bearer") {
            return substr($bearerToken, 7, strlen($bearerToken));
        }

        return null;
    }

    public static function getBasicToken($basicToken)
    {
        if (substr($basicToken, 0, strlen("Basic")) === "Basic") {
            return substr($basicToken, 6, strlen($basicToken));
        }

        return null;
    }

    public static function getDataKeyPair($token)
    {
        if (DataValidation::isEmpty($token)) {
            return explode(":", $token);
        }

        return null;
    }


    public static function HttpValidationMsg($lang = "ENG")
    {
        switch ($lang) {
            case "FR":
                return "Échec de  validation de la demande";
                break;
            case "ENG":
                return "Request validation failed ";
                break;
        }
    }

    public static function send($url, $method = "POST", $data = array(), $requesttoken = null, $contenttype = "urlform")
    {
        $ch = curl_init();
        switch ($contenttype) {
            case "urlform":
                $data = http_build_query($data);
                $contenttype = 'application/x-www-form-urlencoded';
                break;
            case "json":
                $data = json_encode($data);
                $contenttype = 'application/json';
                break;
            case "xml":
                $xml = new SimpleXMLElement('COMMAND');
                array_walk_recursive($data, array($xml, 'commad'));
                print $xml->asXML();
                $contenttype = 'application/xml';
                break;

            default:
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            default:
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:' . $requesttoken, 'Content-Type:' . $contenttype));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    // Sending Async HTTP requests

    public static function sendAsync($url, $params)
    {
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key . '=' . urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $parts = parse_url($url);
        $fp = fsockopen(
            $parts['host'],
            isset($parts['port']) ? $parts['port'] : 80,
            $errno,
            $errstr,
            30
        );

        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts['host'] . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen($post_string) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out .= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }

    /**
     * Getting the client's IP Address
     * @return string IP Address
     */
    public static function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Getting Client's IP Information
     * @param string $ip
     * @param string $purpose
     * @param bool $deep_detect
     * @return array Client data
     */
    public static function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), "NULL", strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode,
                            "latitude" => @$ipdat->geoplugin_latitude,
                            "longitude" => @$ipdat->geoplugin_longitude,
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                    case "latitude":
                        $output = @$ipdat->geoplugin_latitude;
                        break;
                    case "longitude":
                        $output = @$ipdat->geoplugin_longitude;
                        break;
                }
            }
        }
        return $output;
    }
}