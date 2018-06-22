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
		//get user's input values
		$input = getopt("u:p:h:",array("file:","create_table","dry_run"));
				
		$username = $input['u'];
		$password = $input['p'];
		$host 	  = $input['h'];

		//establish conection to PostgreSQL
		$dbconn = pg_connect("host=$host user=$username password=$password");		
		if(!$dbconn){
			echo "An error occurred when establish database connection.\n";
		}
		

		//read the imported file
		$fileName = $input["file"];
		if(($handle= fopen($fileName,"r"))!== FALSE){
			$row = 1;
			while ($userInfo=fgetcsv($handle,1000,",")){
				
				// get rid of the first line contents i.e.("name","surname","email")
				if($row==1){
					$row++;
					continue;
				}
				
				//strip all white space of user's information
				$userInfo[0]=preg_replace('/\s+/','',$userInfo[0]);
				$userInfo[1]=preg_replace('/\s+/','',$userInfo[1]);
				$userInfo[2]=preg_replace('/\s+/','',$userInfo[2]);

				//validate if user's email are legal
				if (preg_match('/[a-zA-Z\']+/',$userInfo[1])==0||preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,5}$/',$userInfo[2])==0){
					fwrite(STDOUT, "ERROR: $userInfo[0] $userInfo[1]'s email is not legal, no record should be inserted into the table\n");
					continue;
				}
				
				//Capitalise the first character of user's names and strip all special characters or numbers in the names
				$userInfo[0]=ucfirst(strtolower($userInfo[0]));
				$userInfo[0]=preg_replace('/[^a-zA-Z\']+/','',$userInfo[0]);
				$userInfo[1]=ucfirst(strtolower($userInfo[1]));
				$userInfo[1]=preg_replace('/[^a-zA-Z\']+/','',$userInfo[1]);
				
				//if there is '(as a single quote) in any user's name
				//replace it with ''(as two consecutive single quotes) 
				$userInfo[0]=str_replace("'","''",$userInfo[0]);
				$userInfo[1]=str_replace("'","''",$userInfo[1]);
				
				//format email string into lower case
				$userInfo[2]=strtolower($userInfo[2]);

				//format user information into a proper string style, so that the values can fit for sql uses
				$userInfoString = implode("','", $userInfo);
				$userInfoString = "'".$userInfoString."'";
				
				
				$sql = "INSERT INTO users (name,surname,email) VALUES ($userInfoString)";
				$result = pg_query($dbconn,$sql);
			}
			fclose($handle);
		}
		else{
			echo "failed to read the file";
		}



	//Check if user input "--create_tabel" to create or rebuild tabel called "users"
	if(isset($input["create_table"])){
		//Delete any table called "users" if exist
		$sql = "DROP TABLE IF EXISTS users";
		pg_query($dbconn,$sql);

		//Create a new table called "users"
		$sql = "CREATE TABLE IF NOT EXISTS users (name varchar (20) NOT NULL, surname varchar(20) NOT NULL, email varchar(40) NOT NULL UNIQUE)";
		$result = pg_query($dbconn,$sql);
		if(!$result){
			echo "An error occured when create the table.\n";
			exit;
		}
	}
	
	
}


