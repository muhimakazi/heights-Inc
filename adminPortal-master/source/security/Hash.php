<?php

/**
 * Class for encrypting password
 */

class Hash
{

	public static function make($string, $algorithm = 'sha256', $salt = '')
	{
		return hash($algorithm, $string . $salt);
	}


	public static function getKey()
	{
		return self::make(self::serialnumber(32, false, "kap"));
	}

	public static function setEditKey($text = null, $var = 'ML')
	{
		$text = str_replace($var, '', $text);
		return $text = (int) $text * 99343;
	}

	public static function getEditKey($text = null, $var = 'ML', $size)
	{
		$text = $text / 99343;
		//$count=$size-strlen($text)
		while (strlen($text) < $size) {
			$text = '0' . $text;
		}
		return $text = $var . $text;
	}

	public static function RandNumber($min = 1000000000000000, $max = 9999999999999999)
	{
		return rand($min, $max);
	}

	public  static function serialnumber($length = 32, $add_dashes = false, $available_sets = 'ka')
	{
		$sets = array();
		if (strpos($available_sets, 'o') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if (strpos($available_sets, 'k') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if (strpos($available_sets, 'a') !== false)
			$sets[] = '23456789';
		if (strpos($available_sets, 'p') !== false)
			$sets[] = '!@#$%&*?';
		$all = '';
		$password = '';
		foreach ($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for ($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		if (!$add_dashes)
			return $password;
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while (strlen($password) > $dash_len) {
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	}


	private static function cryptAES($event, $data)
	{
		// DEFINE our cipher
		$AES_256_CBC = 'aes-256-cbc';
		$encrypted = $decrypted = '';
		// Generate a 256-bit encryption key
		// This should be stored somewhere instead of recreating it each time
		$encryption_key = base64_decode('Cu+/ve+/vU8fxa7vv73vv73vv71gQBUf77+977+9Uu+/vUoSIO+/vV7vv73vv71F77+977+9Pe+/vTHvv73vv712BETvv73vv73vv71L77+977+9He+/ve+/vTMU77+9f2ZPLO+/ve+/vVLvv71W77+977+9UA7vv73vv73vv73vv70I77+977+977+9H0lV77+9F++/vR/vv73loI5ccy1U77+977+977+9Oe+/vWLvv71h662k77+9Fe+/vSzvv73vv71g77+9Nu+/ve+/ve+/vU3vv71/YH8o77+977+9bO+/vXpWJm1sZ0Yf77+9HkLvv73vv73vv71LG1Lvv71Xwo44B1YlKhjvv73vv70c77+977+977+9ZHQZ77+9Fe+/vSDvv73vv71VfnZkQBnvv73vv73vv70877+9HzZuAWY277+977+9Ne+/ve+/vX4I77+977+9TCzvv73vv70FMALvv73vv73vv70Q77+977+9ee+/ve+/vU9NYyHvv71k77+9PCbvv70+77+977+977+9KBM977+977+977+977+977+977+977+977+9aO+/vR3vv73vv73vv70I77+9bO+/vWhW77+977+977+977+977+977+9W++/ve+/ve+/ve+/vXc0R1R0Pg==');
		// Generate an initialization vector
		// This *MUST* be available for decryption as well
		// $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($AES_256_CBC));
		$iv = substr(base64_decode('77+9Gxjvv70YO92+77+977+977+977+977+977+9Ge+/vQ=='), 0, 16);
		if ($event == 'base64_encode') :
			// Encrypt $data using aes-256-cbc cipher with the given encryption key and
			// our initialization vector. The 0 gives us the default options, but can
			// be changed to OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
			$encrypted = openssl_encrypt($data, $AES_256_CBC, $encryption_key, 0, $iv);
			return $encrypted;
		elseif ($event == 'base64_decode') : $encrypted = $data;
			// If we lose the $iv variable, we can't decrypt this, so:
			// - $encrypted is already base64-encoded from openssl_encrypt
			// - Append a separator that we know won't exist in base64, ":"
			// - And then append a base64-encoded $iv
			$encrypted = $encrypted . '\\' . base64_encode($iv);
			// To decrypt, separate the encrypted data from the initialization vector ($iv).
			$parts = explode('\\', $encrypted);
			// $parts[0] = encrypted data
			// $parts[1] = base-64 encoded initialization vector

			// Don't forget to base64-decode the $iv before feeding it back to
			//openssl_decrypt
			$decrypted = openssl_decrypt($parts[0], $AES_256_CBC, $encryption_key, 0, base64_decode($parts[1]));
			// echo "<br>Decrypted: $encrypted\n";
			//return result
			return $decrypted;
		endif;
	}

	public function encryptAES($plain_txt)
	{
		return str_replace(['==', '='], '', $this->cryptAES('base64_encode', $plain_txt));
	}

	public function decryptAES($cipher_text)
	{
		return $this->cryptAES('base64_decode', $cipher_text);
	}

	public static function encryptSecToken($ctx)
	{
		$Hash   = new \Hash();
		$result = $Hash->encryptAES($ctx);
		return bin2hex($result);
	}

	public static function decryptSecToken($ctx)
	{
		$Hash   = new \Hash();
		$result = $ctx;
		$result = $Hash->decryptAES(hex2bin($ctx));
		return $result;
	}

	/**
	 * Encrypting numbers using the AES. Works best with integers. To be used very carefuly with decimals
	 * @param num $plain
	 * @param string $prefix
	 * @return string AES cypher
	 *  */
	public static function encryptAuthToken($ctx, $prefix)
	{
		// Removing the prfix before encryption
		$ctx = str_replace($prefix, '', $ctx);
		$ctx = (int) $ctx;
		// Encrypting
		$Hash   = new \Hash();
		$result = $Hash->encryptAES(Hash::encryptToken($ctx));
		return bin2hex($result);
	}
	/**
	 * Decrypting numbers encrypted with the AES. Works best with integers. To be used very carefuly with decimals
	 * @param string $aes_cipher
	 * @param string $prefix
	 * @return num $plain
	 *  */
	public static function decryptAuthToken($ctx, $prefix = '')
	{
		$Hash   = new \Hash();
		$result = $ctx;
		$result = Hash::decryptToken($Hash->decryptAES(hex2bin($ctx)));
		// Adding the prefix back to the code
		$result = $prefix . $result;
		return $result;
	}

	public static function encryptToken($ctx)
	{
		$result = (397957353 * $ctx) + 424264868;
		return bin2hex($result);
	}

	public static function decryptToken($ctx)
	{
		$result = (hex2bin($ctx) - 424264868) / 397957353;
		return $result;
	}

	public static function decryptTokenArray($ctxArray)
	{
		$ArrayEncryptedIDS = explode(',', $ctxArray);
		$ArrayIDS          = array();
		foreach ($ArrayEncryptedIDS as $key => $encryptedID) :
			$ArrayIDS[] = Hash::decryptToken($encryptedID);
		endforeach;
		$result      = implode(',', $ArrayIDS);
		return $result;
	}

	public static function rpassword($length)
	{
		$length   = ($length < 8) ? 8 : $length;
		$length   = ($length > 16) ? 16 : $length;
		$keyspace = '1234567890[{!@#$%;&*-+?}]ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz1234567890[{!@#$%;&*-+?}]';
		return substr(str_shuffle($keyspace), 0, $length);
	}

	/**
	 * Encrypting using the AES
	 * @param string $plain
	 * @return string $aes_cipher
	 *  */
	public static function encryptStringAES($plain)
	{
		$Hash   = new \Hash();
		$result = $Hash->encryptAES($plain);
		return bin2hex($result);
	}

	/**
	 * Decrypting an AES cipher
	 * @param string $aes_cipher
	 * @return string plain text
	 *  */
	public static function decryptStringAES($encrypted)
	{
		$Hash   = new \Hash();
		$result = $encrypted;
		$result = $Hash->decryptAES(hex2bin($encrypted));
		return $result;
	}
}