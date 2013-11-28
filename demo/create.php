<?php

try
{
    $m = new Mongo("mongodb://social:password123!!!@paulo.mongohq.com:10032"); // connect
    $db = $m->selectDB("socialhubster");
    print_r($db);
}
catch ( MongoConnectionException $e )
{
    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
    exit();
}

$connection_url = getenv("mongodb://social:password123!!!@paulo.mongohq.com:10032/socialhubster");
// create the mongo connection object
$m = new Mongo($connection_url);
// extract the DB name from the connection path
$url = parse_url($connection_url);
$db_name = preg_replace('/\/(.*)/', '$1', $url['path']);
// use the database we connected to
$db = $m->selectDB($db_name); 

$doc = array(
    "name" => "MongoDB",
    "type" => "database",
    "count" => 1,
    "info" => (object)array( "x" => 203, "y" => 102),
    "versions" => array("0.9.7", "0.9.8", "0.9.9")
);
$connection = new MongoClient("mongodb://social:password123!!!@paulo.mongohq.com:10032");
$collection = $db->service;

$collection->insert( $doc );