<?php
/**
 * Define constant common
 */


// Regex constant
define("REGEX_ALPHA_NUMERIC_UNDERSCORE", "/^[a-zA-Z0-9_]+$/");
// Datetime constant
define("DATETIME_FIRST_DAY_MONTH", "Y/m/01");
define("DATETIME_LAST_DAY_MONTH", "Y/m/31");
define("DATETIME_YEAR_AND_MONTH_DASH", "Y-m");
define("DATETIME_LAST_MONTH_DAY", "12/01");
define("WORKINGTIMELIST_IS_DONT_SHOW_OT_NOT_FULL_APPROVAl", true);
define("TIME_OUT_DOWNLOAD_FILE", 5);
define("PATH_TO_FILE_IMAGE_LOGO", 'https://place-hold.it/200x200');
define("ARRAY_TIME_OUT",[
   0 =>'5',
   1 =>'10',
   2 =>'20',
   3 =>'30',
   4 =>'45',
   5 =>'60',
]);
define('LEAVE_TYPE_DEFAULT', 'annual_leave');
define('LEAVE_TYPE_WOMAN', 'woman_leave');
define('LEAVE_TYPE_INSURANCE', 'insurance_leave');
define('LEAVE_TYPE_SPECIAL', 'special_leave');
define('LEAVE_TYPE_COMPENSATORY', 'compensatory_leave');
define('LEAVE_TYPE_UNPAID', 'unpaid_leave');
define('LEAVE_TYPE_INSURANCE_ID', -2);
define('LEAVE_TYPE_UNPAID_ID', -1);
define('JOB_TYPE_EXCHANGE', 'exchange_type');
define('ACTIVE_STATUS', 1);
define('DEACTIVE_STATUS', 0);
define('DEFAULT_MAX_EXECUTION_TIME', 120);
define('DEFAULT_DATETIME_FORMAT', 'Y-m-d H:i:s');
define('CACHE_EXPIRES', 5);
define('SA_USER_NAME', 'SA');
define('DEFAULT_DATE_FORMAT', 'Y-m-d');
define('HOURS_DIFF', 4*60*60);
define('DEFAULT_SECOND_IN_HOUR', 3600);
define('DEFAULT_TIME_FORMAT', 'H:i:s');
define('MAX_LEVEL_APPROVAL', 5);
define('MAX_LEVEL_REST_TIME', 5);
define('DEFAULT_ROUND_NUMBER', 2);
define('ONE_MINITES', 60);
define('LEVEL_EMPLOYEE', 0);
define('HOUR_REST_TIME', 1*60*60);
define('YEAR_MONTH_FORMAT', 'Y-m');
define('OPTIMIZE_FREE_MB', 0);
define('MONTH_OF_YEAR', 12);
define('ALLOW_FILE_EXTENSION', [
    'doc',
    'docx',
    'pdf'
]);
define('HALF_DAY_HOURS', 4);
define('ONE_DAY_HOURS', 8);
define('DEFAULT_UPLOAD_MAX_FILESIZE', '10M');
define('SEX_PROP', [
    'MALE' => 'male',
    'FEMALE' => 'female'
]);
define('ROUND_NUMBER', [
    'NONE' => 0,
    'ONE_NUMBER' => 1,
    'DEFAULT_NUMBER' => 2
]);

