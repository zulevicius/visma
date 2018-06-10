<?php

class StaffUtilsTest extends \PHPUnit_Framework_TestCase
{
	
	public function test_saveRecord()
	{
		ob_start();
		require '/../db_utils/StaffManagement.php';
		ob_end_clean();
		
		$GLOBALS['db'] = new MockDB;
		
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "fl@m.c", "98", "65")),
			"ERROR - fn | ln | fl@m.c | 98 | 65 . Wrong amount of arguments\n");
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "flm.c", "98", "65", "c")),
			"ERROR - fn | ln | flm.c | 98 | 65 | c . Invalid email address\n");
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "fl@mc", "98", "65", "c")),
			"ERROR - fn | ln | fl@mc | 98 | 65 | c . Invalid email address\n");
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "fl@m.c", "98+", "65", "c")),
			"ERROR - fn | ln | fl@m.c | 98+ | 65 | c . Invalid first phone number\n");
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "fl@m.c", "98", "6.5", "c")),
			"ERROR - fn | ln | fl@m.c | 98 | 6.5 | c . Invalid second phone number\n");
		$this->assertEquals(
			StaffManagement::saveRecord(array("fn", "ln", "fl@m.c", "98", "65", "c")),
			"SAVE - fn | ln | fl@m.c | 98 | 65 | c\n");
	}

	
	public function test_getRecordsByAllFieldsWhereClause()
	{
		$args = array(
			"staff.php",
			"-f",
		);
		$this->assertNull(StaffManagement::getRecordsByAllFieldsWhereClause($args));
		
		
		$args = array(
			"staff.php",
			"-f",
			"fn",
		);
		$this->assertEquals(StaffManagement::getRecordsByAllFieldsWhereClause($args),
			"WHERE (id LIKE '%fn%' OR firstname LIKE '%fn%' OR lastname LIKE '%fn%' OR email LIKE '%fn%' OR phone1 LIKE '%fn%' OR phone2 LIKE '%fn%')");
		
		
		$args = array(
			"staff.php",
			"-f",
			"fn",
			"7",
		);
		$this->assertEquals(StaffManagement::getRecordsByAllFieldsWhereClause($args),
			"WHERE (id LIKE '%fn%' OR firstname LIKE '%fn%' OR lastname LIKE '%fn%' OR email LIKE '%fn%' OR phone1 LIKE '%fn%' OR phone2 LIKE '%fn%') AND (id LIKE '%7%' OR firstname LIKE '%7%' OR lastname LIKE '%7%' OR email LIKE '%7%' OR phone1 LIKE '%7%' OR phone2 LIKE '%7%')");
	}
}


class MockDB
{
	static public function query($arg)
	{
		return true;
	}
}