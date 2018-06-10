<?php
 
 /**
  * @author	Zygimantas Ulevicius <z.ulevicius@gmail.com>
  * @link	http://github.com/zulevicius
  */

error_reporting(E_ERROR | E_WARNING | E_PARSE);

define("ANSWER_YES", "YES");

require_once('db_utils/conn.php');
require_once('db_utils/StaffManagement.php');


if (sizeof($argv) <= 1)
{
	help();
	exit;
}

$com = $argv[1];
switch ($com)
{
	case "-i":
		insert();
		break;
	case "-f":
		find();
		break;
	case "-d":
		single_delete();
		break;
	case "-cd":
		custom_delete();
		break;
	case "-csv":
		import_csv();
		break;
	case "-h":
		help();
		break;
	default:
		echo "Unrecognized command\n";
}
close_db();


function insert()
{
	global $argv;
	$member_data = array_slice($argv, 2);
	echo StaffManagement::insertRecord($member_data);
}


function find()
{
	global $argv, $db;
	$query = "SELECT * FROM staff " . StaffManagement::getRecordsByAllFieldsWhereClause($argv);
	$result = $db->query($query);
	
	return StaffManagement::printStaff($result);
}


function custom_delete()
{
	global $argv, $db;
	$records_found = find();
	
	if ($records_found) echo StaffManagement::customDelete($argv);
}


function single_delete()
{
	global $argv;
	
	if (sizeof($argv) < 3)
	{
		echo "No ID provided\n";
		return;
	}
	
	$id = $argv[2];
	StaffManagement::deleteById($id);
}

function import_csv()
{
	global $argv;
	
	if (sizeof($argv) <= 2) 
	{
		echo "No file provided\n";
		return;
	}
	
	$filename = $argv[2];
	
	if (!file_exists($filename))
	{
		echo "ERROR - file does not exist\n";
		return;
	}
	
	$row = 1;
	if (($handle = fopen($filename, "r")) !== false)
	{
		while (($data = fgetcsv($handle, 0, ";")) !== false)
		{
			$ret = StaffManagement::insertRecord(array_slice($data, 0, -1));
			echo $ret;
			$row++;
		}
		fclose($handle);
	}
}


function help()
{
	echo "
-- STAFF REGISTRY MANAGER --

Use the commands below to manage staff database:
 Insert - create new record, type * to leave optional field empty
 -i firstname lastname email phonenumber1 [phonenumber2 comment]
 
 Find - look for staff members whose values match/contain provided arguments
 -f [arg1 arg2 ... argN]
 
 Delete - delete a member by ID
 -d id
 
 Custom delete - delete members whose fields match provided arguments
 -cd [arg1 arg2 ... argN]
 
 Import CSV - import CSV file with the following format:
	firstname;lastname;email;phonenumber1;phonenumber2;comment;
 -csv filename
 ";
}
