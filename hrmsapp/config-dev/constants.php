<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

define('TODAY_DATE',date('Y-m-d'));
define('DEFAULT_SALARY_START_DATE','2016-04-19');//2017-07-20 give -1 date 2016-02-29   2016-04-19
define('DEFAULT_SALARY_YEAR_MONTH','2016-05');//2017-08 2016-03   2016-05
define('OT_ELIGIBLE_HOURS','01:00');
define('JOINEE_SICK_LEAVE_NOT_ALLOW','+9 months');
define('MATERNITY_LEAVE_ALLOW','+12 months');
define('ANNUAL_LEAVE_ALLOW_EOS','+6 months');
define('ANNUAL_LEAVE_ALLOW','+6 months');
define('ANNUAL_LEAVE_ALLOW_FULL','+12 months');
define('MATERNITYLEAVE_PAID','45');
define('SICKLEAVE_FULLPAID','15');
define('SICKLEAVE_HALFPAID','45');
define('SICKLEAVE_NOPAID','90');
define('AD_ROLE','Administrator');
define('AD_VIEW_ROLE','Administrator View');
define('R_M_ROLE','Reporting Manager');
define("HR_L_ROLE", serialize (array ("HR Employee")));
define('E_M_ROLE','Employee');
define('SYSTEM_LIVE_DATE','2018-01-01');
define('DEFAULT_IN_TIME','09:00');
define('DEFAULT_OUT_TIME','18:00');
define('DEFAULT_WEEK_OFF','Friday');
define('WEEKOFF_TWODAYS_STARTDATE','2018-02-03');
define('ANNUAL_LEAVE_POLICY_STARTDATE','2018-02-15');
define('BUS_LATE_ADJUSTMENTS',5);
define('MAIL_OUTER_CSS','background: #f7eaea;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;margin: 0 auto;padding:20px;max-width: 65em;border: 2px solid #D40732;');
define('MAX_PHONE_DIGITS',15);
define('REQUIRED_FIELD','<span class="text-danger ml-5">*</span>');
/*Only For Testing Purpose*/
define('TESTING_MAIL',TRUE);
define('FROM_MAIL','monitor@awok.com');
define('TO_MAIL','siddiq.awok@gmail.com');
define('CC_MAIL','siddiq.930@awok.ae');
/*Only For Testing Purpose*/			
define('LOGISTICS_SERVERNAME','35.195.18.195');
define('LOGISTICS_USERNAME','fivetran');
define('LOGISTICS_PASSWORD','1YsLnTNcKuuh%0jkv9I1g');
define('LOGISTICS_DBNAME','awok_logistics');
/*SMS Package*/
define("SMS_EXCEPTION", serialize (array ("0000000952","0000000036")));
define("SMS_ENABLE_LOC", serialize (array ("DIP 2")));
define('SMS_USERNAME','hrms');
define('SMS_PASSWORD','qW8(e52ju(s!');
define('SMS_SENDERID','AWOK');
define('SMS_URL','http://94.56.94.242/api/api_http.php?');
/*SMS Package*/				
			
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
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
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
