 ## Task
 
 CLI based staff register to insert, delete, find and import from CSV files staff members.
 
 ## Technical specs
 
 The project is programmed with PHP 5.6 and uses MySQL database to store data. All functions are executed from staff.php using CLI:
 
    Insert - create new record, type * to leave optional field empty
    -i firstname lastname email phonenumber1 [phonenumber2 comment]
    
    Find - look for staff members whose values match/contain provided arguments
    -f [arg1 arg2 ... argN]
    
    Delete - delete a member by ID
    -d id
    
    Custom delete - delete members whose fields match/contain provided arguments
    -cd [arg1 arg2 ... argN]
    
    Import CSV - import CSV file with the following format:
  	 firstname;lastname;email;phonenumber1;phonenumber2;comment;
    -csv filename

 MySQL settings are defined in db_utils/conn.php file.
    
 ## Project directories

 -	/ - main script, phpunit configuration file and documents.
 -	csv/ - example CSV import file.
 -	db_utils/ - program utilities related with data processing.
 -	sql/ - MySQL script to create database for the project.
 -	tests/ - unit tests.
 -	vendor/ - PHPUnit framework files.
 

 ## How to execute unit tests?

 Launch "/vendor/bin/phpunit" script through a CLI. If configuration file is not detected automatically, then it can be set from an argument:
 `phpunit -c ../../phpunit.xml`
