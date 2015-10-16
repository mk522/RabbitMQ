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


	// authenticate
	if ( mysql_num_rows ($t) > 0 )
		return true;
	else
    		return false;
    //return false if not valid
}

function doRegister($username,$password,$email)
{
	$s = "select * from login where Username='$username' and Password='$password'";
	( $t = mysql_query ( $s  ) ) or die ( mysql_error() );

	if ( mysql_num_rows ($t) < 1 )
	{
		$x = "insert into login values ('$username','$password','$email')";
		( $y = mysql_query ( $x  ) ) or die ( mysql_error() );	
		return true;
	}
	else
		return false;	
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);

  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
    case "Register":
      return doRegister($request['username'],$request['password'],$request['email']);
  }
  return "Server received request and processed";
}

$server = new rabbitMQServer("configurations.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

