<?php
if (!function_exists('validateMethod')) {
	function validateMethod($request, $method)
	{
        if($request->getMethod() !== strtolower($method)) {
			$response = [
				'message' => 'Method tidak sesuai.',
				'type' => 'process'
			];

			header("HTTP/1.1 400 Bad Request");
    		header('Cache-Control: no-store, no-cache, must-revalidate');
    		header('Pragma: no-cache');
    		header('Keep-Alive: timeout=5, max=100');
    		header('Connection: Keep-Alive');
    		header('Content-Type: application/json; charset=utf-8');

    		exit(json_encode($response));
        }
	}
}

if (!function_exists('getRequestParamsData')) {
	function identityTypeOption($value='')
	{
		$data = [
			"" => "Jenis Identitas",
			"ktp" => "KTP",
			"paspor" => "Paspor",
			"sim" => "SIM",
		];

		return $data;
	}
}

if (!function_exists('getRequestParamsData')) {
	function getRequestParamsData($request, $method)
	{
		if(strtolower($method) === 'get' && !empty($request->getGet())) {
			return (object)$request->getGet();
        } elseif(strtolower($method) === 'post' && !empty($request->getPost())) {
            return (object)$request->getPost();
        } else {
            return (object)$request->getJSON();
        }
	}
}

if (!function_exists('getRequestParamsFile')) {
	function getRequestParamsFile($request, $param)
	{
        if(!empty($request->getFile($param))) {
            return (object)$request->getFile($param);
        } else {
            return (object)[$param => NULL];
        }
	}
}

if (!function_exists('getUserAgent')) {
	function getUserAgent()
	{
        $agent = \Config\Services::request()->getUserAgent();

		if ($agent->isBrowser()) {
			$currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
		} elseif ($agent->isRobot()) {
			$currentAgent = $this->agent->robot();
		} elseif ($agent->isMobile()) {
			$currentAgent = $agent->getMobile();
		} else {
			$currentAgent = 'Unidentified User Agent';
		}

        if ($agent->isMobile()) {
            $device = $agent->getMobile();
        } else {
            $device = '';
        }

        $arr_result = [
            'user_agent' => $currentAgent,
            'platform' => $agent->getPlatform(),
            'device' => $device,
        ];

		return $arr_result;
	}
}

if (!function_exists('phoneNumberFilter')) {
	function phoneNumberFilter($phoneNumber)
	{
		$res = '';
		preg_match("/^(^\+62\s?|^0)(\d{3,4}-?){2}\d{3,4}$/", $phoneNumber, $result);
		if (!empty($result)) {
			$indexCode = $result[1];
			if ($indexCode == '0') {

				$res = substr_replace($phoneNumber, '+62', 0, 1);
			} else {
				$res = $phoneNumber;
			}
		} else {
			//checking country code
			$indexOne = substr($phoneNumber, 0, 1);
			$length = strlen($phoneNumber); // output panjang phone
			if ($indexOne != "+" && $length >= 11) {
				$phoneNumber = '+' . $phoneNumber;
			}
			$res = $phoneNumber;
		}
		$res = str_replace(' ', '', $res);
		return $res;
	}
}

if (!function_exists('convertNullToString')) {
	function convertNullToString($value)
	{
		return (is_null($value)) ? "" : $value;
	}
}

if (!function_exists('generateChar')) {
	function generateChar($length)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars), 0, $length);
	}
}

if (!function_exists('generateNumber')) {
    function generateNumber($length)
    {
        $pin_str = "1234567809";
        for ($i = 0; $i < strlen($pin_str); $i++) {
            $pin_chars[$i] = $pin_str[$i];
        }
        // randomize the chars
        srand((float) microtime() * 1000000);
        shuffle($pin_chars);
        $pin = "";
        for ($i = 0; $i < 20; $i++) {
            $char_num = rand(1, count($pin_chars));
            $pin .= $pin_chars[$char_num - 1];
        }
        $pin = substr($pin, 0, $length);

        return $pin;
    }
}

if (!function_exists('generateLetter')) {
    function generateLetter($length)
    {
        $charset = 'ABCDEFGHKLMNPRSTUVWYZ';
        $code = '';

        for($i = 1, $cslength = strlen($charset); $i <= $length; ++$i) {
            $code .= $charset[rand(0, $cslength - 1)];
        }
        return $code;
    }
}

if (!function_exists('generateCode')) {
    function generateCode($length)
    {
        $charset = 'ABCDEFGHKLMNPRSTUVWYZ23456789';
        $code = '';

        for($i = 1, $cslength = strlen($charset); $i <= $length; ++$i) {
            $code .= $charset[rand(0, $cslength - 1)];
        }
        return $code;
    }
}

if (!function_exists('setNumberFormat')) {
    function setNumberFormat($number, $is_int = true)
    {
        if(is_numeric($number) && floor($number) != $number && $is_int == false) {
            return number_format($number, 2, ',', '.');
        } else {
            return number_format($number, 0, ',', '.');
        }
    }
}

if (!function_exists('multidimensionalArraySort')) {
    function multidimensionalArraySort(&$array, $key, $sort = 'asc')
    {
        $sorter = array();
        $ret = array();
        reset($array);

        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }

        if ($sort == 'desc') {
            arsort($sorter);
        } else {
            asort($sorter);
        }

        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }

        return $ret;
    }
}

if (!function_exists('slugify')) {
	function slugify($text, string $divider = '-')
	{
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, $divider);

		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
		return 'n-a';
		}

		return $text;
	}
}

// if (!function_exists('cutText')) {
// 	function cutText($text, $length, $mode = 2)
// 	{
// 		if ($mode != 1) {
// 			$char = $text[$length - 1];
// 			switch ($mode) {
// 				case 2:
// 					while ($char != ' ') {
// 						$char = $text[--$length];
// 					}
// 				case 3:
// 					while ($char != ' ') {
// 						$char = $text[++$length];
// 					}
// 			}
// 		}
// 		return substr($text, 0, $length);
// 	}
// }

if (!function_exists('getWeekDateRange')) {
    function getWeekDateRange($date, $weekly_start_day = 0)
    {
        $arr_days = array(
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday'
        );
        $str_date = strtotime($date);
        $str_start_date = (date('w', $str_date) == $weekly_start_day) ? $str_date : strtotime('last ' . $arr_days[$weekly_start_day], $str_date);
        $start_date = date("Y-m-d", $str_start_date);
        $end_date = date("Y-m-d", mktime(0, 0, 0, date("n", strtotime($start_date)), date("j", strtotime($start_date)) + 6, date("Y", strtotime($start_date))));

        return array($start_date, $end_date);
    }
}

if (!function_exists('convertDate')) {
	function convertDate($date, $lang = 'id', $type = 'text', $formatdate = '.')
	{

		if (!empty($date)) {
			if ($type == 'num') {
				$date_converted = str_replace('-', $formatdate, $date);
			} else {
				$year = substr($date, 0, 4);
				$month = substr($date, 5, 2);
				$month = convertMonth($month, $lang);
				$day = substr($date, 8, 2);

				$date_converted = $day . ' ' . $month . ' ' . $year;
			}
		} else {
			$date_converted = '-';
		}
		return $date_converted;
	}
}

if (!function_exists('convertDatetime')) {
	function convertDatetime($date, $lang = 'id', $type = 'text', $formatdate = '.', $formattime = ':')
	{

		if (!empty($date)) {
			if ($type == 'num') {
				$date_converted = str_replace('-', $formatdate, str_replace(':', $formattime, $date));
			} else {
				$year = substr($date, 0, 4);
				$month = substr($date, 5, 2);
				$month = convertMonth($month, $lang);
				$day = substr($date, 8, 2);
				$time = strlen($date) > 10 ? substr($date, 11, 8) : '';
				$time = str_replace(':', $formattime, $time);

				$date_converted = $day . ' ' . $month . ' ' . $year . ' ' . $time;
			}
		} else {
			$date_converted = '-';
		}
		return $date_converted;
	}
}

if (!function_exists('convertMonth')) {
	function convertMonth($month, $lang = 'id')
	{
		$month = (int) $month;
		switch ($lang) {
			case 'id':
				$arr_month = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
				break;

			default:
				$arr_month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
				break;
		}

		if (array_key_exists($month - 1, $arr_month)) {
			$month_converted = $arr_month[$month - 1];
		} else {
			$month_converted = '';
		}

		return $month_converted;
	}
}

if (!function_exists('getFilesize')) {
    function getFilesize($file)
    {
        $bytes = array("B", "KB", "MB", "GB", "TB", "PB");
        $file_with_path = $file;
        $file_with_path;
        // replace (possible) double slashes with a single one
        $file_with_path = str_replace("///", "/", $file_with_path);
        $file_with_path = str_replace("//", "/", $file_with_path);
        $size = @filesize($file_with_path);
        $i = 0;

        //divide the filesize (in bytes) with 1024 to get "bigger" bytes
        while ($size >= 1024) {
            $size = $size / 1024;
            $i++;
        }

        // you can change this number if you like (for more precision)
        if ($i > 1) {
            return round($size, 1) . " " . $bytes[$i];
        } else {
            return round($size, 0) . " " . $bytes[$i];
        }
    }
}

if (!function_exists('resizeImage')) {
	function resizeImage($saveToDir, $imagePath, $imageName, $max_x, $max_y)
	{
		preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $imageName, $ext);
		switch (strtolower($ext[2])) {
			case 'jpg':
			case 'jpeg':
				$img = imagecreatefromjpeg($imagePath);
				break;
			case 'gif':
				$img = imagecreatefromgif($imagePath);
				break;
			case 'png':
				$img = imagecreatefrompng($imagePath);
				break;
			default:
				$stop = true;
				break;
		}

		if (!isset($stop)) {
			$x = imagesx($img);
			$y = imagesy($img);

			if (($max_x / $max_y) < ($x / $y)) {
				$save = imagecreatetruecolor($x / ($x / $max_x), $y / ($x / $max_x));
			} else {
				$save = imagecreatetruecolor($x / ($y / $max_y), $y / ($y / $max_y));
			}
			imagecopyresized($save, $img, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);

			switch (strtolower($ext[2])) {
				case 'jpg':
				case 'jpeg':
					unlink($imagePath);
					imagejpeg($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
					break;
				case 'gif':
					unlink($imagePath);
					imagegif($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
					break;
				case 'png':
					unlink($imagePath);
					imagepng($save, "{$saveToDir}{$ext[1]}.{$ext[2]}");
					break;
				default:
					$stop2 = true;
					break;
			}
			imagedestroy($img);
			imagedestroy($save);

			if (!isset($stop2)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

//fungsi untuk mendapatkan jumlah hari dalam 1 tahun
if (!function_exists('getCountDaysInYear')) {
    function getCountDaysInYear($year)
    {
		$days = 0;
    	for($month = 1; $month <= 12; $month++) {
        	$days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
     	}
 		return $days;
    }
}

//fungsi untuk menentukan persentase pajak tahunan pph21
if (!function_exists('getAnnuallyTaxPPh21Percentage')) {
    function getAnnuallyTaxPPh21Percentage($value, $is_npwp)
    {
		if($value <= 60000000) {
			$tax_percentage = 0.05;
		} elseif($value > 60000000 && $value <= 250000000) {
			$tax_percentage = 0.15;
		} elseif($value > 250000000 && $value <= 500000000) {
			$tax_percentage = 0.25;
		} elseif($value > 500000000 && $value <= 5000000000) {
			$tax_percentage = 0.3;
		} elseif($value > 5000000000) {
			$tax_percentage = 0.35;
		}

		if($is_npwp == FALSE) {
			$tax_percentage = 1.2 * $tax_percentage; //jika non npwp dikalikan 120%
		}

		return $tax_percentage;
    }
}

//fungsi untuk menghitung pajak tahunan pph21
if (!function_exists('getCalculateAnnuallyTaxPPh21Value')) {
    function getCalculateAnnuallyTaxPPh21Value($income, $is_npwp)
    {
		if($income <= 60000000) {
			$tax_value = $income * getAnnuallyTaxPPh21Percentage(60000000, $is_npwp);
		} elseif($income > 60000000 && $income <= 250000000) {
			$tax_value = (($income - 60000000) * getAnnuallyTaxPPh21Percentage(250000000, $is_npwp)) + (60000000 * getAnnuallyTaxPPh21Percentage(60000000, $is_npwp));
		} elseif($income > 250000000 && $income <= 500000000) {
			$tax_value = (($income - 250000000) * getAnnuallyTaxPPh21Percentage(500000000, $is_npwp)) + ((250000000 - 60000000) * getAnnuallyTaxPPh21Percentage(250000000, $is_npwp)) + (60000000 * getAnnuallyTaxPPh21Percentage(60000000, $is_npwp));
		} elseif($income > 500000000 && $income <= 5000000000) {
			$tax_value = (($income - 500000000) * getAnnuallyTaxPPh21Percentage(5000000000, $is_npwp)) + ((500000000 - 250000000) * getAnnuallyTaxPPh21Percentage(500000000, $is_npwp)) + ((250000000 - 60000000) * getAnnuallyTaxPPh21Percentage(250000000, $is_npwp)) + (60000000 * getAnnuallyTaxPPh21Percentage(60000000, $is_npwp));
		} elseif($income > 5000000000) {
			$tax_value = (($income - 5000000000) * getAnnuallyTaxPPh21Percentage(5000000001, $is_npwp)) + ((5000000000 - 500000000) * getAnnuallyTaxPPh21Percentage(5000000000, $is_npwp)) + ((500000000 - 250000000) * getAnnuallyTaxPPh21Percentage(500000000, $is_npwp)) + ((250000000 - 60000000) * getAnnuallyTaxPPh21Percentage(250000000, $is_npwp)) + (60000000 * getAnnuallyTaxPPh21Percentage(60000000, $is_npwp));
		}

		return $tax_value;
    }
}

//fungsi untuk menghitung pajak pph21 yang dihitung harian
// if (!function_exists('getCalculateDailyTaxPPh21')) {
//     function getCalculateDailyTaxPPh21($arr_tax_data, $income, $tax_date, $is_npwp)
//     {
// 		$tax_days = date("z", strtotime($tax_date)) + 1;
// 		$arr_tax_data = (array) $arr_tax_data;
//         $tax_percentage = $tax_value = $total_income = $total_tax_value = 0;
// 		$income_pkp_acc = $tax_value_acc = 0;
//         $arr_new_tax_data = [];
//         foreach($arr_tax_data as $key => $value) {
//             if($key == $tax_days) {
//                 $value->date = $tax_date;
//                 $value->income = $income;
//                 $value->income_pkp =  0.5 * $value->income;
//                 $value->income_pkp_acc = $income_pkp_acc + $value->income_pkp;
// 				$value->tax_value = $tax_value = getCalculateAnnuallyTaxPPh21Value($value->income_pkp_acc, $is_npwp) - $tax_value_acc;
// 				$value->tax_value_acc = $tax_value_acc + $value->tax_value;
//             }
//
// 			$arr_new_tax_data[$key] = [
//                 'days' => $key,
//                 'date' => $value->date,
//                 'income' => $value->income,
//                 'income_pkp' => $value->income_pkp,
//                 'income_pkp_acc' => $value->income_pkp_acc,
//                 'tax_value' => $value->tax_value,
//                 'tax_value_acc' => $value->tax_value_acc
//             ];
//
// 			$total_tax_value += $value->tax_value;
//             $total_income += $value->income;
// 			$income_pkp_acc += $value->income_pkp;
// 			$tax_value_acc += $value->tax_value;
//         }
//
// 		$arr_result = [
// 			// 'tax_percentage' => ($tax_percentage * 100),
// 			'tax_value' => $tax_value,
// 			'total_income' => $total_income,
// 			'total_income_pkp' => $income_pkp_acc,
// 			'total_tax_value' => $tax_value_acc,
// 			'arr_tax_data' => $arr_new_tax_data
// 		];
//
// 		return $arr_result;
//     }
// }

//fungsi untuk menentukan persentase pajak pph21 yang dihitung harian (shinebeauty)
// if (!function_exists('getDailyTaxPPh21Percentage')) {
//     function getDailyTaxPPh21Percentage($annual_income, $is_npwp)
//     {
//         if($annual_income < 60000000) {
// 			return ($is_npwp) ? (2.5 / 100) : (3 / 100);
// 		} elseif($annual_income > 60000000 && $annual_income <= 250000000) {
// 			return ($is_npwp) ? (6.07 / 100) : (7.29 / 100);
// 		} elseif($annual_income > 250000000 && $annual_income <= 500000000) {
// 			return ($is_npwp) ? (8.37 / 100) : (10.04 / 100);
// 		} elseif($annual_income > 500000000) {
// 			return ($is_npwp) ? (11.24 / 100) : (13.49 / 100);
// 		} else {
// 			return 0;
// 		}
//     }
// }

//fungsi untuk menghitung pajak pph21 yang dihitung harian (shinebeauty)
// if (!function_exists('getCalculateDailyTaxPPh21')) {
//     function getCalculateDailyTaxPPh21($arr_tax_data, $income, $tax_date, $is_npwp)
//     {
// 		$tax_days = date("z", strtotime($tax_date)) + 1;
// 		$days_count = getCountDaysInYear(date("Y", strtotime($tax_date)));
// 		$arr_tax_data = (array) $arr_tax_data;
//         $tax_percentage = $tax_value = $total_income = $total_tax_value = 0;
//         $arr_new_tax_data = [];
//         foreach($arr_tax_data as $key => $value) {
//             if($key == $tax_days) {
//                 $value->date = $tax_date;
//                 $value->income = $income;
//                 $value->income_acc = $total_income + $income;
//                 $value->income_avg = intval(($value->income_acc) / $key);
//                 $value->income_annual = intval($value->income_avg * $days_count);
//                 $value->tax_percentage = $tax_percentage = getDailyTaxPPh21Percentage($value->income_annual, $is_npwp);
//                 $value->tax_value = $tax_value = intval($value->income * $value->tax_percentage);
//             }
//
//             $total_tax_value += $value->tax_value;
//             $total_income += $value->income;
//             $arr_new_tax_data[$key] = [
//                 'days' => $key,
//                 'date' => $value->date,
//                 'income' => $value->income,
//                 'income_acc' => $value->income_acc,
//                 'income_avg' => $value->income_avg,
//                 'income_annual' => $value->income_annual,
//                 'tax_percentage' => $value->tax_percentage,
//                 'tax_value' => $value->tax_value
//             ];
//         }
//
// 		$arr_result = [
// 			'tax_percentage' => ($tax_percentage * 100),
// 			'tax_value' => $tax_value,
// 			'total_income' => $total_income,
// 			'total_tax_value' => $total_tax_value,
// 			'arr_tax_data' => $arr_new_tax_data
// 		];
//
// 		return $arr_result;
//     }
// }
