<?php
class Functions
{
	public static function json_enc($str)
	{
		return json_encode($str, true);
	}

	public static function json_dec($str)
	{
		return json_decode($str, true);
	}

	public static function print_time_($dt_retr_date)
	{
		$cur_retr_date = Config::get('time/date_time');
		$cur_plit_date = substr($cur_retr_date, 5, 10);
		$cur_time = substr($cur_retr_date, 16, 11);
		$cur_day = substr($cur_retr_date, 0, 3);

		$dt_plit_date = substr($dt_retr_date, 5, 10);
		$dt_time = substr($dt_retr_date, 16, 11);
		$dt_day = substr($dt_retr_date, 0, 3);

		if ($dt_plit_date === $cur_plit_date) {
			return '<value title="' . $dt_retr_date . '" style="cursor: pointer">' . 'at ' . $dt_time . '</value>';
		}
		if ($dt_plit_date < $cur_plit_date) {
			return '<value title="' . $dt_retr_date . '" style="cursor: pointer">' . 'on ' . $dt_day . ' ' . $dt_plit_date . '</value>';
		}
		return $dt_retr_date;
	}

	public static function time_past($ref_sec)
	{
		$cur_sec = Config::get('time/timestamp');
		$band = ($cur_sec - $ref_sec) / 60;
		$split = explode('.', $band);
		$min = $split[0];
		$sec = $cur_sec - $ref_sec;
		if ($band < 1) {
			return '<value style="cursor: pointer">' . $sec . 'sec ago </value>';
		} elseif ($min < 60) {
			return '<value style="cursor: pointer">' . $min . 'min ago </value>';
		} else {
			$band1 = $min / 60;
			$split1 = explode('.', $band1);
			$hour = $split1[0];
			if ($hour < 24) {
				return '<value style="cursor: pointer">' . $hour . 'h ago </value>';
			} else {
				$band2 = $hour / 24;
				$split2 = explode('.', $band2);
				$day = $split2[0];
				if ($day < 7) {
					return '<value style="cursor: pointer">' . $day . 'd ago </value>';
				} else {
					return '<value style="cursor: pointer">' . $day . 'd ago </value>';
				}
			}
		}
	}

	public static function get_timeago($ptime)
	{
		$temp = Config::get('time/temp');
		$estimate_time = (float)$temp - (float)$ptime;

		if ($estimate_time < 1) {
			return ' < 1 sec ago';
		}

		$condition = array(
			12 * 30 * 24 * 60 * 60  =>  'year',
			30 * 24 * 60 * 60       =>  'month',
			24 * 60 * 60            =>  'day',
			60 * 60                 =>  'hour',
			60                      =>  'minute',
			1                       =>  'second'
		);

		foreach ($condition as $secs => $str) {
			$d = $estimate_time / $secs;

			if ($d >= 1) {
				$r = round($d);
				return $r . ' ' . $str . ($r > 1 ? 's' : '');
			}
		}
	}
	public static function print_F_size($ref_size)
	{
		$size_K = $ref_size / 1000;
		if ($ref_size < 1000) {
			return '<value style="cursor: pointer">' . $ref_size . ' B </value>';
		} elseif ($ref_size < 1000000) {
			return '<value style="cursor: pointer">' . (int)($ref_size / 1000) . 'Kb </value>';
		} elseif ($ref_size < 1000000000) {
			return '<value style="cursor: pointer">' . (int)($ref_size / 1000000) . 'Mb </value>';
		} elseif ($ref_size < 1000000000000) {
			return '<value style="cursor: pointer">' . (int)($ref_size / 1000000) . 'Gb </value>';
		}
	}
	public static function getFileIcon($anyfile)
	{
		if ($anyfile == 1) {
			return 'icon/file_type/img.png';
		} else {
			return 'icon/file_type/file1.png';
		}
	}


	public static function flashMsg()
	{
?>
		<?php
		$session_exist_success = Session::exists('success');
		if ($session_exist_success) {
			$session_success = Session::get('success');
			if (!empty($session_success)) { ?>
				<div class=" alert alert-success" role="alert" onclick="$(this).hide()">
					<div class="row">
						<div class="col-xs-1">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						</div>
						<div class="col-xs-10">
							<?php echo Session::flash('success'); ?>
						</div>
						<div class="col-xs-1">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
		<?php
			}
		} ?>

		<?php
		$session_exist_errors = Session::exists('errors');
		if ($session_exist_errors) {
			$session_errors = Session::get('errors');
			if (!empty($session_errors)) { ?>
				<div class=" alert alert-danger" role="alert" onclick="$(this).hide()">
					<div class="row">
						<div class="col-xs-12 col-sm-1">
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						</div>
						<div class="col-xs-12 col-sm-10">
							<?php echo Session::flash('errors'); ?>
						</div>
						<div class="col-xs-12 col-sm-1">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
		<?php
			}
		} ?>
	<?php
	}



	public static function smartEmail($message, $email, $subject)
	{
		$url    = DN . "/mail_gmail";
		$myvars = 'email=' . $email . '&message=' . $message . '&subject=' . $subject;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
	}

	public static function passPrice($categ, $currency)
	{

		$str = 'Apr 10 2017';
		$format = 'F d Y';
		$dt = DateTime::createFromFormat($format, $str);
		$timestamp = $dt->format('U');
		$cur_sec = Config::get('time/seconds');
		if ($cur_sec > $timestamp) {
			$early_bird = false;
		} else {
			$early_bird = true;
		}
		$amount = 0;
		switch ($categ) {
			case 'Silver':
			case 'Silver-discounted':

			case 'Individual-Silver':
			case 'Group-Silver':

			case 'Individual-Silver-Discounted':
			case 'Group-Silver-Discounted':
			case 'Exhibitor-Silver-Discounted':
			case 'Sponsor-Silver-Discounted':
			case 'Smart-Africa-Member-Silver-Discounted':
			case 'Face-the-Gorillas-Silver-Discounted':
			case 'Ms-Geek-2017-Silver-Discounted':
				if ($currency == "Local") {
					if ($early_bird) {
						$amount = 125000;
						// $amount = 100;
					} else {
						$amount = 170000;
					}
				} else {
					if ($early_bird) {
						$amount = 150;
						// $amount = 1;
					} else {
						$amount = 200;
					}
				}
				break;
			case 'Gold':
			case 'Gold-discounted':

			case 'Individual-Gold':
			case 'Group-Gold':
			case 'Individual-Gold-Discounted':
			case 'Group-Gold-Discounted':
			case 'Exhibitor-Gold-Discounted':
			case 'Sponsor-Gold-Discounted':
			case 'Face-the-Gorillas-Gold-Discounted':
			case 'Smart-Africa-Member-Gold-Discounted':
			case 'Ms-Geek-2017-Gold-Discounted':
				if ($currency == "Local") {
					if ($early_bird) {
						$amount = 290000;
					} else {
						$amount = 425000;
					}
				} else {
					if ($early_bird) {
						$amount = 350;
					} else {
						$amount = 500;
					}
				}
				break;
			case 'Platinum':
			case 'Platinum-discounted':

			case 'Individual-Platinum':
			case 'Group-Platinum':
			case 'Individual-Platinum-Discounted':
			case 'Group-Platinum-Discounted':
			case 'Exhibitor-Platinum-Discounted':
			case 'Sponsor-Platinum-Discounted':
			case 'Smart-Africa-Member-Platinum-Discounted':
			case 'Face-the-Gorillas-Platinum-Discounted':
			case 'Ms-Geek-2017-Platinum-Discounted':
				if ($currency == "Local") {
					$amount = 635000;
				} else {
					$amount = 750;
				}
				break;
			case 'Standard':
				if ($currency == "Local") {
					$amount = 3400000;
				} else {
					$amount = 4000;
				}
				break;
			case 'Premium':
				if ($currency == "Local") {
					$amount = 6375000;
				} else {
					$amount = 7500;
				}
				break;
		}
		return $amount;
	}

	public static function makeCapcha($fieldname)
	{
		$capcha = substr(md5(rand(1000, 9999)), 0, 4);
	?>
		<div style="height:30px; width: 100%; overflow: hidden; position: relative; background: #fff">
			<span style="top: 3px; width: 100%; text-align: center;position: absolute; font-size: 20px; font-weight: 700; font-family: 'Roboto-Bold'; color: #000"><?= $capcha ?></span>
			<img src="<?= DN ?>/img/kcc.jpg" style="opacity: .3; width: 400px; margin-top: -<?= rand(50, 120) ?>px; margin-left: <?= rand(-200, 0) ?>px;">
		</div>
		<input type="hidden" name="<?= $fieldname ?>" value="<?= $capcha ?>" style="width: 0px; height: 0px">
	<?php
	}

	public static function getJobTitles($errorstate, $value = '', $categ)
	{
	?>

		<option value=""> [--Select--] </option>

		<option value="Chief Executive Officer (CEO)" <?php if ($errorstate && $value == 'Chief Executive Officer (CEO)') {
															echo 'selected="selected"';
														} ?>>Chief Executive Officer (CEO)</option>

		<option value="Chief Marketing Officer (CMO)" <?php if ($errorstate && $value == 'Chief Marketing Officer (CMO)') {
															echo 'selected="selected"';
														} ?>>Chief Marketing Officer (CMO)</option>

		<option value="Chief Operating Officer (COO)" <?php if ($errorstate && $value == 'Chief Operating Officer (COO)') {
															echo 'selected="selected"';
														} ?>>Chief Operating Officer (COO)</option>
		<option value="Chief Technology Officer" <?php if ($errorstate && $value == 'Chief Technology Officer') {
														echo 'selected="selected"';
													} ?>>Chief Technology Officer</option>
		<option value="Co-Founder" <?php if ($errorstate && $value == 'Co-Founder') {
										echo 'selected="selected"';
									} ?>>Co-Founder</option>
		<option value="Consultant / Freelancer" <?php if ($errorstate && $value == 'Consultant / Freelancer') {
													echo 'selected="selected"';
												} ?>>Consultant / Freelancer</option>
		<option value="Creative Director" <?php if ($errorstate && $value == 'Creative Director') {
												echo 'selected="selected"';
											} ?>>Creative Director</option>
		<option value="Deputy CEO" <?php if ($errorstate && $value == 'Deputy CEO') {
										echo 'selected="selected"';
									} ?>>Deputy CEO</option>
		<option value="Deputy Director" <?php if ($errorstate && $value == 'Deputy Director') {
											echo 'selected="selected"';
										} ?>>Deputy Director</option>
		<option value="Director of Operations" <?php if ($errorstate && $value == 'Director of Operations') {
													echo 'selected="selected"';
												} ?>>Director of Operations</option>
		<option value="Engineer" <?php if ($errorstate && $value == 'Engineer') {
										echo 'selected="selected"';
									} ?>>Engineer</option>
		<option value="Entrepreneur" <?php if ($errorstate && $value == 'Entrepreneur') {
											echo 'selected="selected"';
										} ?>>Entrepreneur</option>
		<option value="Executive Director" <?php if ($errorstate && $value == 'Executive Director') {
												echo 'selected="selected"';
											} ?>>Executive Director</option>
		<option value="Executive President" <?php if ($errorstate && $value == 'Executive President') {
												echo 'selected="selected"';
											} ?>>Executive President</option>
		<option value="Founder" <?php if ($errorstate && $value == 'Founder') {
									echo 'selected="selected"';
								} ?>>Founder</option>
		<option value="General Manager" <?php if ($errorstate && $value == 'General Manager') {
											echo 'selected="selected"';
										} ?>>General Manager</option>
		<option value="Web Developer" <?php if ($errorstate && $value == 'Web Developer') {
											echo 'selected="selected"';
										} ?>>Web Developer</option>


		<option value="Other" <?php if ($errorstate && $value == 'Other') {
									echo 'selected="selected"';
								} ?>>Other </option>
<?php
	}

	public static function errorPage($number)
	{
		if ($number == 404) {
			include 'views/errors/404' . P;
		} else {
			include 'views/errors/404' . P;
		}
		echo '<div class="clearfix"></div>';
	}

	public static function getStateCol($state)
	{
		switch ($state) {
			case 'Activate':
			case 'Activated':
				return '#4093D0';
				break;
			case 'Attend':
			case 'Attended':
				return '#00a65a';
				break;
			case 'Pending':
				return 'orange';
				break;
			case 'Deactivate':
			case 'Deactivated':
				return '#999';
				break;
		}
	}


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
		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
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

	public static function generateStrongPassword($length = 6, $add_dashes = false, $available_sets = 'luds')
	{
		$sets = array();
		if (strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if (strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if (strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
		if (strpos($available_sets, 's') !== false)
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

	public static function generateQRString($qr_code)
	{
		$qr_string = strtoupper(hash_hmac('SHA256', $qr_code, pack('H*', 3061995)));
		//$qr_string = $qr_code;
		return $qr_string;
	}

	public static function discount($amount_gross, $discount)
	{
		return $amount_gross - $amount_gross * $discount / 100;
	}

	public static function uploadFile($path, $code, $fileName)
	{
		$name = $fileName['name'];
		$nameSplit = explode('.', $name);
		$ext = strrchr($name, '.');
		$tmp_name = $fileName['tmp_name'];
		$file_name = $code . "." . $nameSplit[1];
		$dir_file = $path . $file_name;

		//$valables = array('.docx','.doc','.xls','.xlsx','.pdf','.PDF');
		//if(in_array($ext, $valables)):
		if (move_uploaded_file($tmp_name, $dir_file)) :
			return $file_name;
		else :
			return false;
		endif;
		// else:
		// 	return false;
		// endif;
	}

	public static function fileUpload($path, $fileName)
	{
		$ext    	 = strrchr($fileName['name'], '.');
		$name		 = sha1($fileName['name'] . date('YmdHis')) . $ext;
		$tmp_name 	 = $fileName['tmp_name'];
		$dir_picture = $path . $name;
		$valables 	 = array('.docx', '.doc', '.xls', '.xlsx', '.pdf', '.PDF', '.png', '.PNG', '.jpg', '.jpeg');
		if (in_array(strtolower($ext), $valables)) :
			if (move_uploaded_file($tmp_name, $dir_picture)) :
				return $name;
			else :
				return false;
			endif;
		else :
			return false;
		endif;
	}

	public static function genaratecode($period_table_row_number, $operation_code, $limit_registration_period)
	{
		if ((int)$period_table_row_number < $limit_registration_period) {
			$period_table_row_number += 1;
			$result = $period_table_row_number / $limit_registration_period;
			$result = str_replace(".", "", $result);
			while (strlen($result) < strlen($limit_registration_period)) {
				$result .= 0;
			}
		}
		return $operation_code . Time::transform(Time::getdate('Y-m-d')) . $result;
	}

	public static function saveBase64ImagePng($base64Image, $imageDir, $name)
	{
		//set name of the image file

		$fileName =  $name . '.png';

		$base64Image = trim($base64Image);
		$base64Image = str_replace('data:image/png;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
		$base64Image = str_replace(' ', '+', $base64Image);

		$imageData = base64_decode($base64Image);
		//Set image whole path here
		$filePath = $imageDir . $fileName;
		file_put_contents($filePath, $imageData);
	}

	public static function saveBase64Imag($base64Image, $imageDir, $name)
	{
		//set name of the image file

		$fileName =  $name . '.jpg';

		$base64Image = trim($base64Image);
		$base64Image = str_replace('data:image/png;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
		$base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
		$base64Image = str_replace(' ', '+', $base64Image);

		$imageData = base64_decode($base64Image);
		//Set image whole path here
		$filePath = $imageDir . $fileName;
		file_put_contents($filePath, $imageData);
	}


	public static function Languages($languages)

	{
		$languages = str_replace("KI", "KINYARWANDA", $languages);
		$languages = str_replace("EN", "ENGLISH", $languages);
		$languages = str_replace("FR", "FRENCH", $languages);

		return $languages;
	}


	public static function getPaymentType($payment_type)
	{
		switch ($payment_type) {

			case "PAYWITHCARD":
				return "Payment with Cards";
				break;

			case "ALL":
				return "All Payment types";
				break;

			default:
				return "NOT DEFINED ";
				break;
		}
	}



	public static function getType($type)
	{
		switch ($type) {
			case "OPEN":
				return "All Customers";
				break;
			case "RESTRICTED":
				return "Registered Customers ";
				break;


			default:
				return "NOT DEFINED ";
				break;
		}
	}


	public static function getStatus($status)
	{
		switch ($status):
			case "PENDING":
				return "Pending";
				break;
			case "DENIED":
				return "Denied";
				break;
			case "ACTIVE":
				return "Active";
				break;
			case "APPROVED":
				return "Approved";
				break;

			default:
				return $status;
				break;
		endswitch;
	}



	public static function getEventCategory($category)
	{
		switch ($category) {
			case "INPERSON":
				return "In-person";
				break;
			case "VIRTUAL":
				return "Virtual";
				break;
			default:
				return $category;
				break;
		}
	}



	public static function getCompanyCodeByTelephone($telephone)
	{
		if (substr($telephone, 0, -7) == "25073" || substr($telephone, 0, -7) == "25072")
			return "AIRTELRW";
		else if (substr($telephone, 0, -7) == "25078")
			return "MTNRW";
		else
			return false;
		// return substr($telephone,0,-7);
	}

	public static function array_sort($array, $on, $order = SORT_ASC)
	{
		$new_array      = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}

	public static function cleanDateFormat($str_date)
	{
		list($day, $month, $year) = explode('/', $str_date);
		$foramted_date = "$day-$month-$year";
		return (string)$foramted_date;
	}
	public static function getLanguageName($lang_code = 'eng-lang')
	{
		switch ($lang_code):
			case 'eng-lang':
				return 'English';
				break;
			case 'fr-lang':
				return 'French';
				break;
			case 'ar-lang':
				return 'Arabic';
				break;
			case 'pt-lang':
				return 'Portuguese';
				break;
			default:
				return 'English';
				break;
		endswitch;
	}

	//UPDATE JSON
	public static function json_overwrite($json_original, $json_overwrite)
	{
	    $original = json_decode($json_original, true);
	    $overwrite = json_decode($json_overwrite, true);

	    $original = self::array_overwrite($original, $overwrite);

	    return json_encode($original);
	}

	public static function array_overwrite(&$original, $overwrite)
	{
	    // Not included in function signature so we can return silently if not an array
	    if (!is_array($overwrite)) {
	        return;
	    }
	    if (!is_array($original)) {
	        $original = $overwrite;
	    }
	    foreach($overwrite as $key => $value) {
	        // if (array_key_exists($key, $original) && is_array($value)) {
	        //     self::array_overwrite($original[$key], $overwrite[$key]);
	        // } else {
	        //     $original[$key] = $value;
	        // }

	        $original[$key] = $value;
	    }

	    return $original;
	}
}
?>