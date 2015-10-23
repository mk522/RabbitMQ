<?php
try
{
    $connection = new MongoClient('mongodb://test:test@ds039504.mongolab.com:31347/your_database');
    $database   = $connection->selectDB('thetop');
    $collection = $database->selectCollection('teams');
}
catch(MongoConnectionException $e)
{
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();
echo "$cursor";

?>