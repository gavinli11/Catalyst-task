<?php

$val = getopt("u:p:h:");
$username = $val['u'];
$password = $val['p'];
$host 	  = $val['h'];

$dbconn = pg_connect("host=$host user=$username password=$password") or die("Could not connect");
$stat =pg_connection_status($dbconn);
if ($stat===PGSQL_CONNECTION_OK){

	echo "Connection status ok\n";
}else{

	echo "Connection status bad\n";
}

//var_dump($username);


