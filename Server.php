#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include ("account.php");

( $connect = mysql_connect ( $dbhostname, $dbusername, $dbpassword ) ) or die ( "Unable to connect to MySQL database" );

mysql_select_db( $dbproject );

//login table is called 'login'//

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
	$s = "select * from login where Username='$username' and Password='$password'";
	( $t = mysql_query ( $s  ) ) or die ( mysql_error() );

	
    return true;
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return "received request";
}

$server = new rabbitMQServer("configurations.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

