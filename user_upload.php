<?php

//Check if user input "--help" command to see available command line directives for this script
$checkHelp= getopt("",array("help"));
if(isset($checkHelp["help"])){
	echo "
		  --file [csvfile name] - this is the name of the CSV to be parsed\n
		  --create_table - this will cause the PostgreSQL users table to be built in the 
		  database (and no further action will be taken\n
		  --dry_run - this will be used with the --file directive in the instance that we want
		  to run the script but not insert into the DB. All other functions will be executed,
		  but the database won't be altered.\n
		  -u - PostgreSQL username\n
		  -p - PostgreSQL password\n
		  -h - PostgreSQL host\n";
	 
}

else{
	
	$input = getopt("u:p:h:",array("create_table","dry_run"));
	$username = $input['u'];
	$password = $input['p'];
	$host 	  = $input['h'];

	//establish conection to PostgreSQL
	$dbconn = pg_connect("host=$host user=$username password=$password dbname=sample_db") or die("Could not connect");
	
	//output PostgreSQL connection status
	$stat =pg_connection_status($dbconn);
	if ($stat===PGSQL_CONNECTION_OK){
		echo "Connection status ok\n";
	}else{
		echo "Connection status bad\n";
	}
	
	
	//var_dump($input);
	if(isset($input["create_table"])){
		//Delete any table called "users" if exist
		$sql = "DROP TABLE IF EXISTS users";
		pg_query($dbconn,$sql);

		//Create a new table called "users"
		$sql = "CREATE TABLE IF NOT EXISTS users (name varchar (20) NOT NULL, surname varchar(20) NOT NULL, email varchar(40) NOT NULL UNIQUE)";
		$result = pg_query($dbconn,$sql);
		if(!$result){
			echo "An error occured.\n";
			exit;
		}
	}

	//$sql= "INSERT INTO users (name,surname,email) VALUES ('abc','abc','tsts@gmail.com')";
//$result = pg_query($dbconn,$sql);
}


//getopt(array("help",

//

