<?php

$PORT = array_key_exists("argv", $_SERVER) && array_search("--port", $_SERVER["argv"]) !== FALSE ? $_SERVER["argv"][array_search("--port", $_SERVER["argv"]) + 1] : 8080;

defined('URL_PRODUCTION') || define('URL_PRODUCTION', "https://kimstella.co.id");
defined('URL_DEVELOPMENT') || define('URL_DEVELOPMENT', "https://kimstella.esoftdream.co.id");
defined('URL_STAGING') || define('URL_STAGING', "http://localhost:{$PORT}");

if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $BASEURL = 'https://' . $_SERVER['HTTP_HOST'];
} else {
    $BASEURL = URL_STAGING;
}

defined('BASEURL') || define('BASEURL', $BASEURL);

if (BASEURL == URL_PRODUCTION) {
    $WA_URL = 'https://api-v2.wa-one.com';
    $WA_KEY = 'NULL'; //live
    $PAYMENT_USER_ID = "639d4b5f9728ad59f99bcb94";
    $PAYMENT_SECRET_KEY = "xnd_production_L25agBI6fkVUwFLWfWvzP77TmF5SNMdGGx7nuAKEndVeo9fJh1WyDPc7zwCgrN"; //secret-key-2
    $PAYMENT_PUBLIC_KEY = "xnd_public_production_e7WoccWILmhb4Vj7jfOR6JeQp9BLOmolufVuiz3uPiF2EEnLZej61Fnhq7hIYG";
    $PAYMENT_CALLBACK_TOKEN = "4PXjQ7h6tILL1HfOOrUQwkxDZnRS95I1v3MqkPxWnjizcUx0";
    // $PAYMENT_SECRET_KEY = "xnd_development_RtQDK3hYHT5K9OAd5qax1WThEAfqbRRNHATgWq6o1mh3rARY6LzVHRJhqvRFUDV";
    // $PAYMENT_PUBLIC_KEY = "xnd_public_development_c9YCotHgMpGZ5Tk8Y0qjYHvTORjKiGFbGffcZzVsYweAeLbTTzOAafyHY0choHp";
    // $PAYMENT_CALLBACK_TOKEN = "6SCROOCdSDJQ6Q4kPObU0ZBZ0rxmuACKh8y5c9OAWf6qpfOS";
} else if (BASEURL == URL_DEVELOPMENT || BASEURL == URL_STAGING) {
    $WA_URL = 'https://api-v2.wa-one.com';
    $WA_KEY = 'NULL'; //dev
    $PAYMENT_USER_ID = "639d4b5f9728ad59f99bcb94";
    $PAYMENT_SECRET_KEY = "xnd_development_b5stoF5JdS3OZgMjQfiWwS0hGVdNAtZACr0QAkxtWfFjx5qBkpf85k77R8udBdW"; //secret-key-2
    $PAYMENT_PUBLIC_KEY = "xnd_public_development_c9YCotHgMpGZ5Tk8Y0qjYHvTORjKiGFbGffcZzVsYweAeLbTTzOAafyHY0choHp";
    $PAYMENT_CALLBACK_TOKEN = "6SCROOCdSDJQ6Q4kPObU0ZBZ0rxmuACKh8y5c9OAWf6qpfOS";
}

defined('URL_IMG_ADMIN') || define('URL_IMG_ADMIN', 'images/admin/');
defined('URL_IMG_MEMBER') || define('URL_IMG_MEMBER', 'images/profile/');
defined('URL_IMG_PRODUCT') || define('URL_IMG_PRODUCT', 'images/product/');
defined('URL_IMG_IDENTITY') || define('URL_IMG_IDENTITY', 'images/identity/');
defined('URL_IMG_TAX') || define('URL_IMG_TAX', 'images/tax/');
defined('URL_IMG_PAYMENT') || define('URL_IMG_PAYMENT', 'images/payment/');
defined('URL_IMG_CONTENT') || define('URL_IMG_CONTENT', 'images/content/');
defined('URL_IMG_BANK') || define('URL_IMG_BANK', 'images/bank/');
defined('URL_IMG_TRANSACTION') || define('URL_IMG_TRANSACTION', 'images/transaction/');
defined('URL_IMG_REWARD') || define('URL_IMG_REWARD', 'images/reward/');
defined('URL_IMG_GALLERY') || define('URL_IMG_GALLERY', 'images/gallery/');
defined('URL_IMG_GALLERY_CATEGORY') || define('URL_IMG_GALLERY_CATEGORY', 'images/gallery/category/');
defined('URL_IMG_BANNER') || define('URL_IMG_BANNER', 'images/banner/');
defined('URL_IMG_COURIER') || define('URL_IMG_COURIER', 'images/courier/');
defined('URL_IMG_BUSINESS') || define('URL_IMG_BUSINESS', 'images/business/');

defined('PROJECT_NAME') || define('PROJECT_NAME', 'KIMSTELLA NETWORK SEJAHTERA');
defined('PROJECT_DESCRIPTION') || define('PROJECT_DESCRIPTION', 'PT. KIMSTELLA NETWORK SEJAHTERA.');
defined('COMPANY_NAME') || define('COMPANY_NAME', 'PT. KIMSTELLA NETWORK SEJAHTERA');
defined('COMPANY_DESCRIPTION') || define('COMPANY_DESCRIPTION', 'PT KIMSTELLA NETWORK SEJAHTERA.');
defined('COMPANY_ADDRESS') || define('COMPANY_ADDRESS', 'Citraland BSB City Cluster Ivy Park Blok A1.27 Kec. Mijen, Kota Semarang, Jawa Tengah');
defined('COMPANY_SUBDISTRICT') || define('COMPANY_SUBDISTRICT', 5504);
defined('COMPANY_CITY') || define('COMPANY_CITY', 399);
defined('COMPANY_PROVINCE') || define('COMPANY_PROVINCE', 10);

defined('DELIVERY_WAREHOUSE_ADDRESS') || define('DELIVERY_WAREHOUSE_ADDRESS', "Citraland BSB City Cluster Ivy Park Blok A1.27 Kec. Mijen, Kota Semarang, Jawa Tengah");
defined('DELIVERY_WAREHOUSE_MAPS') || define('DELIVERY_WAREHOUSE_MAPS', "NULL");
defined('DELIVERY_WAREHOUSE_SUBDISTRICT') || define('DELIVERY_WAREHOUSE_SUBDISTRICT', 5504);
defined('DELIVERY_WAREHOUSE_CITY') || define('DELIVERY_WAREHOUSE_CITY', 399);
defined('DELIVERY_WAREHOUSE_PROVINCE') || define('DELIVERY_WAREHOUSE_PROVINCE', 10);

defined('BASIC_USERNAME') || define('BASIC_USERNAME', 'kimstella2022');
defined('BASIC_PASSWORD') || define('BASIC_PASSWORD', 'k1m5t3ll44ju');

defined('JWT_KEY') || define('JWT_KEY', BASIC_USERNAME . BASIC_PASSWORD);
defined('JWT_LIFE_TIME') || define('JWT_LIFE_TIME', 18000); //second

defined('NETWORK_CODE_PREFIX') || define('NETWORK_CODE_PREFIX', 'KNS'); //prefiks network_code
defined('NETWORK_CODE_LENGTH') || define('NETWORK_CODE_LENGTH', 10); //jumlah karakter network_code
defined('NETWORK_CODE_STOCK_MIN') || define('NETWORK_CODE_STOCK_MIN', 10000); //minimal stok network_code
defined('SERIAL_STOCK_MIN') || define('SERIAL_STOCK_MIN', 10000); //minimal stok serial masing-masing tipe
defined('SERIAL_RO_STOCK_MIN') || define('SERIAL_RO_STOCK_MIN', 10000); //minimal stok serial ro masing-masing tipe

defined('TRANSACTION_STOCKIST_MASTER_DISCOUNT_TYPE') || define('TRANSACTION_STOCKIST_MASTER_DISCOUNT_TYPE', "percent");
defined('TRANSACTION_STOCKIST_MASTER_DISCOUNT_PERCENT') || define('TRANSACTION_STOCKIST_MASTER_DISCOUNT_PERCENT', 5);
defined('TRANSACTION_STOCKIST_MASTER_DISCOUNT_VALUE') || define('TRANSACTION_STOCKIST_MASTER_DISCOUNT_VALUE', 0);
defined('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_TYPE') || define('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_TYPE', "value");
defined('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_PERCENT') || define('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_PERCENT', 0);
defined('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_VALUE') || define('TRANSACTION_STOCKIST_MASTER_EXTRA_DISCOUNT_VALUE', 0);

defined('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_TYPE') || define('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_TYPE', "percent");
defined('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_PERCENT') || define('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_PERCENT', 3);
defined('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_VALUE') || define('TRANSACTION_STOCKIST_MOBILE_DISCOUNT_VALUE', 0);
defined('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_TYPE') || define('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_TYPE', "value");
defined('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_PERCENT') || define('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_PERCENT', 0);
defined('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_VALUE') || define('TRANSACTION_STOCKIST_MOBILE_EXTRA_DISCOUNT_VALUE', 0);

defined('TRANSACTION_MEMBER_DISCOUNT_TYPE') || define('TRANSACTION_MEMBER_DISCOUNT_TYPE', "value");
defined('TRANSACTION_MEMBER_DISCOUNT_PERCENT') || define('TRANSACTION_MEMBER_DISCOUNT_PERCENT', 0);
defined('TRANSACTION_MEMBER_DISCOUNT_VALUE') || define('TRANSACTION_MEMBER_DISCOUNT_VALUE', 0);
defined('TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE') || define('TRANSACTION_MEMBER_EXTRA_DISCOUNT_TYPE', "value");
defined('TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT') || define('TRANSACTION_MEMBER_EXTRA_DISCOUNT_PERCENT', 0);
defined('TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE') || define('TRANSACTION_MEMBER_EXTRA_DISCOUNT_VALUE', 0);

defined('CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE') || define('CONFIG_WITHDRAWAL_ADM_CHARGE_TYPE', "value");
defined('CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT') || define('CONFIG_WITHDRAWAL_ADM_CHARGE_PERCENT', 0);
defined('CONFIG_WITHDRAWAL_ADM_CHARGE_VALUE') || define('CONFIG_WITHDRAWAL_ADM_CHARGE_VALUE', 10000);
defined('CONFIG_WITHDRAWAL_MIN_VALUE') || define('CONFIG_WITHDRAWAL_MIN_VALUE', 60000);

defined('CONFIG_BONUS_LIMIT') || define('CONFIG_BONUS_LIMIT', 5000000);
defined('CONFIG_BONUS_LIMIT_2') || define('CONFIG_BONUS_LIMIT_2', 8000000);

defined('CONFIG_BONUS_SPONSOR_VALUE') || define('CONFIG_BONUS_SPONSOR_VALUE', 50000);
defined('CONFIG_BONUS_SPONSOR_PERCENT') || define('CONFIG_BONUS_SPONSOR_PERCENT', 12.5 / 100);
defined('CONFIG_BONUS_SPONSOR_POINT') || define('CONFIG_BONUS_SPONSOR_POINT', 0);
defined('ARR_CONFIG_BONUS_GEN_NODE_VALUE') || define('ARR_CONFIG_BONUS_GEN_NODE_VALUE', [
    1 => 10000,
    2 => 7000,
    3 => 7000,
    4 => 7000,
    5 => 7000,
    6 => 7000,
    7 => 7000,
    8 => 7000,
    9 => 7000,
]);
defined('ARR_CONFIG_BONUS_GEN_NODE_PERCENT') || define('ARR_CONFIG_BONUS_GEN_NODE_PERCENT', [
    1 => 2.5 / 100,
    2 => 1.75 / 100,
    3 => 1.75 / 100,
    4 => 1.75 / 100,
    5 => 1.75 / 100,
    6 => 1.75 / 100,
    7 => 1.75 / 100,
    8 => 1.75 / 100,
    9 => 1.75 / 100,
]);
defined('CONFIG_BONUS_GEN_NODE_POINT') || define('CONFIG_BONUS_GEN_NODE_POINT', 1);
defined('CONFIG_BONUS_POWER_LEG_VALUE') || define('CONFIG_BONUS_POWER_LEG_VALUE', 40000);
defined('CONFIG_BONUS_POWER_LEG_PERCENT') || define('CONFIG_BONUS_POWER_LEG_PERCENT', 10 / 100);
defined('CONFIG_BONUS_POWER_LEG_POINT') || define('CONFIG_BONUS_POWER_LEG_POINT', 2);
defined('CONFIG_BONUS_MATCHING_LEG_VALUE') || define('CONFIG_BONUS_MATCHING_LEG_VALUE', 20000);
defined('CONFIG_BONUS_MATCHING_LEG_PERCENT') || define('CONFIG_BONUS_MATCHING_LEG_PERCENT', 5 / 100);
defined('CONFIG_BONUS_MATCHING_LEG_POINT') || define('CONFIG_BONUS_MATCHING_LEG_POINT', 1);

defined('ARR_CONFIG_FEE_IT_PERCENTAGE') || define(
    'ARR_CONFIG_FEE_IT_PERCENTAGE',
    [
        1 => (2 / 100),
        2 => (1 / 100),
        3 => (0.5 / 100),
        4 => (0.5 / 100),
        5 => (0.5 / 100),
        'repeatorder' => (0.5 / 100)
    ]
);

defined('DEFAULT_LIMIT') || define('DEFAULT_LIMIT', 10);
defined('DEFAULT_PAGE') || define('DEFAULT_PAGE', 1);
defined('DEFAULT_SORT') || define('DEFAULT_SORT', 'id');
defined('DEFAULT_DIRECTION') || define('DEFAULT_DIRECTION', 'ASC');

define(
    'TRANSACTION_STATUS',
    [
        'pending' => 'Menunggu Pembayaran',
        'approved' => 'Pembayaran Diterima',
        'paid' => 'Dibayar',
        'delivered' => 'Dikirim',
        'complete' => 'Selesai',
        'void' => 'Ditolak',
        'expired' => 'Kedaluarsa'
    ]
);

defined('UPLOAD_PATH') || define('UPLOAD_PATH', FCPATH . 'upload/');
defined('UPLOAD_URL') || define('UPLOAD_URL', BASEURL . '/upload/');

defined('MAIL_IS_ACTIVE') || define('MAIL_IS_ACTIVE', 0);
defined('MAIL_VENDOR') || define('MAIL_VENDOR', 'phpmailer');

defined('WA_NOTIFICATION_IS_ACTIVE') || define('WA_NOTIFICATION_IS_ACTIVE', 1);
defined('WA_VENDOR') || define('WA_VENDOR', 'wa-one');
defined('WA_URL') || define('WA_URL', $WA_URL);
defined('WA_KEY') || define('WA_KEY', $WA_KEY);
defined('WA_CS_NUMBER') || define('WA_CS_NUMBER', '+6282322823755');

defined('PAYMENT_VENDOR') || define('PAYMENT_VENDOR', 'xendit');
defined('PAYMENT_SECRET_KEY') || define('PAYMENT_SECRET_KEY', $PAYMENT_SECRET_KEY);
defined('PAYMENT_PUBLIC_KEY') || define('PAYMENT_PUBLIC_KEY', $PAYMENT_PUBLIC_KEY);
defined('PAYMENT_CALLBACK_TOKEN') || define('PAYMENT_CALLBACK_TOKEN', $PAYMENT_CALLBACK_TOKEN);
defined('PAYMENT_URL') || define('PAYMENT_URL', '');
defined('PAYMENT_NOTE') || define('PAYMENT_NOTE', 'KOMISI KIMSTELLA NETWORK');

defined('DELIVERY_VENDOR') || define('DELIVERY_VENDOR', 'raja-ongkir');
defined('DELIVERY_KEY') || define('DELIVERY_KEY', '989c322f1a27f2f9dea4a9f21e53dd58');
defined('DELIVERY_URL') || define('DELIVERY_URL', 'https://pro.rajaongkir.com/api/');

if (BASEURL != URL_STAGING) {
    defined('TM_SENDER_TOKEN') || define('TM_SENDER_TOKEN', '1605703625:AAHlsI2KxgD4RwXKeO_jDBGU-G5xC5Tka_M');
    defined('TM_BUGS_CENTER') || define('TM_BUGS_CENTER', '-1001370345923');
}

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);
