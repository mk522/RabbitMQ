#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$request = array();
$request['type'] = "Login";
$request['username'] = "martin";
$request['password'] = "password";
$request['message'] = $argv[1];
$response = $client->publish($request);

echo "response: ".PHP_EOL;
var_dump($response);

echo $argv[0]." END".PHP_EOL;

