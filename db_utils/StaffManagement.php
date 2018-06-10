<?php

class StaffManagement
{	
	private static $insert_fields = array(
		"firstname",
		"lastname",
		"email",
		"phone1",
		"phone2",
		"comment",
	);

	private static $search_fields = array(
		"id",
		"firstname",
		"lastname",
		"email",
		"phone1",
		"phone2",
	);


	/**
	  * @data - array of 6 values: firstname, lastname, email, phone1, phone2, comment
	  */
	public static function insertRecord($data)
	{
		$vals = "";
		foreach ($data as $val) $vals .= $val . " | ";
		$vals = substr($vals, 0, -3);
		
		if (sizeof($data) != 6) return "ERROR - $vals . Wrong amount of arguments\n";
		
		$insert_data = array();
		$insert_vals = "";
		foreach (self::$insert_fields as $i => $f)
		{
			if ($f === "phone2" || $f === "comment")
				$insert_data[$f] = ($data[$i] === "*" ? "" : $data[$i]);
			else $insert_data[$f] = $data[$i];
			
			$insert_vals .= "'{$insert_data[$f]}', ";
		}
		$insert_vals = substr($insert_vals, 0, -2);
		
		if (!filter_var($insert_data["email"], FILTER_VALIDATE_EMAIL)) 
			return "ERROR - $vals . Invalid email address\n";
		
		if (!preg_match("/^\+?\d+$/", $insert_data["phone1"])) 
			return "ERROR - $vals . Invalid first phone number\n";
		
		if (!preg_match("/^\+?\d+$/", $insert_data["phone2"]) && !empty($insert_data["phone2"]))
			return "ERROR - $vals . Invalid second phone number\n";
		
		global $db;
		$query = "
			INSERT INTO staff (" . implode(", ", self::$insert_fields) . ")
				 VALUES ($insert_vals)";
		if ($db->query($query) === true) return "INSERT - $vals\n";
		else return "ERROR - " . $db->error . "\n";
	}
	
	
	static public function deleteById($id)
	{
		global $db;
		
		if (!ctype_digit($id))
		{
			echo "ID can be only integer-numeric\n";
			return;
		}
		
		$query = "SELECT * FROM staff WHERE id = $id";
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		if ($row['id'])
		{	
			$person = "[ $id ] - {$row['firstname']} {$row['lastname']}";
			$query = "DELETE FROM staff WHERE id = $id";
			if ($db->query($query) === true) echo "DELETE - $person \n";
			else echo "ERROR - $id. " . $db->error . "\n";
		}
		else echo "No record found\n";
	}
	
	
	static public function customDelete($args)
	{
		global $db;
		
		echo "These records will be deleted. Enter YES to proceed:\n$ ";
		$answer = fgets(STDIN);
		if (strtoupper(trim($answer)) === ANSWER_YES)
		{
			$query = "DELETE FROM staff WHERE id IN (
				SELECT * FROM (
					SELECT id FROM staff " . StaffManagement::getRecordsByAllFieldsWhereClause($args) . "
				) AS s
			)";
			if ($db->query($query) === true) echo "CUSTOM DELETE";
			else echo "ERROR. " . $db->error . "\n";
		}
		else echo "CANCELED\n";
	}


	static public function getRecordsByAllFieldsWhereClause($args)
	{
		if (sizeof($args) <= 2) return null;
		
		$query = "WHERE ";
		for ($i = 2; $i < sizeof($args); $i++)
		{
			$where = "(";
			$filter = $args[$i];
			foreach(self::$search_fields as $f) $where .= "$f LIKE '%$filter%' OR ";
			$where = substr($where, 0, -4); // cut last OR
			$where .= ") AND ";
			$query .= $where;
		}
		$query = substr($query, 0, -5);
		
		return $query;
	}


	static public function printStaff($result)
	{
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				echo "[ " . $row["id"] . " ] " .
					$row["firstname"] . " | " .
					$row["lastname"] . " | " .
					$row["email"] . " | " .
					$row["phone1"] . " | " .
					$row["phone2"] . " | " .
					$row["comment"] . "\n";
			}
			return true;
		}
		else echo "No results\n";
		
		return false;
	}	
}