#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include ("account.php");

//$myFile = "log.txt";
//$fh = fopen($myFile, 'a') or die("Can't open file");
( $connect = mysql_connect ( $dbhostname, $dbusername, $dbpassword ) ) or die ( "Unable to connect to MySQL database" );
mysql_select_db( $dbproject );

function cleaner($data)
{
	$data = mysql_real_escape_string($data);
	$data = htmlspecialchars($data);
	return $data;
}

function doLogin($username,$password)
{
    $s = "select * from login where Username='$username' and Password='$password'";
    ( $t = mysql_query ( $s  ) ) or die ( mysql_error() );
    if ( mysql_num_rows ($t) > 0 )
        return true;
    else
        return false;
}
function doRegister($username,$password,$email)
{
    $s = "select * from login where Username='$username' or Email='$email'";
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
  $info = "received request".PHP_EOL;
  echo $info;
  $logger = implode(" | ",$request);
  $myFile = "log.txt";
  $fh = fopen($myFile, 'a') or die("Can't open file");
  fwrite($fh, $info);
  //fwrite($fh, ":");
  fwrite($fh, $logger);
  fwrite($fh, "\n\n");
  fclose($fh);
  //print( $logger);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }

	$salt = "hdf62GD&32$$%1dDJSUCKS6283mf%$#@";
	$password = $request['password'];
	$SaltyPassword = md5($salt . $password);


  switch ($request['type'])
  {
    case "login":
        $authentication = doLogin(cleaner($request['username']),cleaner($SaltyPassword));
        if ($authentication == true)
		return array("returnCode" => '0', 'message' => "Login Successful.");
	else
		return array("returnCode" => '1', 'message' => "Login Unsuccessful.");
    case "register":
        $registerUser = doRegister(cleaner($request['username']),cleaner($SaltyPassword),cleaner($request['email']));
	if ($registerUser == true)
		return array("returnCode" => '2', 'message' => "Register Successful.");
	else
		return array("returnCode" => '3', 'message' => "Register Unsuccessful.");
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  //return array("returnCode" => '0', 'message'=>"Server received request and processed");
}
$server = new rabbitMQServer("RabbitMQ.ini","testServer");
$server->process_requests('requestProcessor');
exit();
?>
