Catalyst PHP Script Task


About the script 

This is a command line based php script. It accepts a CSV file which contains user data (name, surname, email), and parses the data then inserts the data into a PostgreSQL database. 


About the functions

- Strip white spaces for each user's information (i.e. name, surname, email) parsed from the CSV file

- Format name and surname with first letter capitalised, and strip all other non-letter characters expect ' (a single quote) 

- Validate email address 
Note: email contains special characters (e.g.`, ', !, #, $, %, ^, (, )) will be treated as invalid. Invalid email will cause an error message to be reported to STDOUT.

- Only insert user's information into users table when user's name, surname and email are all valid. 
Note: for user who has empty value of name or surname, invalid email, or email that already exists in the database, the user's information will not be inserted into the database.

- create_table and dry_run functions are specified as command line directives below


About the command line directives

--help -to view available command options for the script

-u -PostgreSQL username

-p -PostgreSQL password

-h -PostgreSQL host

-d -PostgreSQL database name (in case to specify a particular database)

--file [csv file name] -to parse the csv file

--create_table -to create or rebuild users table in the PostgreSQL database

--dry_run -to be used with the --file directive; run all the functions in the script except inserting data into the database


Author: Gavin LI - shijiali.ga@gmail.com

https://github.com/gavinli11/Catalyst-task