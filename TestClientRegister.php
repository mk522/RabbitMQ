#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$request = array();
$request['type'] = "Register";
$request['username'] = "test";
$request['password'] = "test";
$request['email'] = "test@njit.edu";
$request['message'] = $argv[1];
$response = $client->publish($request);

echo "response: ".PHP_EOL;
var_dump($response);

echo $argv[0]." END".PHP_EOL;

