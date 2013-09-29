<?php
/**
* Test suite for MasterScheduleEntry
* Created on Feb 15, 2012
* @author Johnny Coster
*/

//first I include the php file I'm testing
include_once(dirname(__FILE__).'/../domain/MasterScheduleEntry.php');

class testMasterScheduleEntry extends UnitTestCase {
	
	function testMasterScheduleEntryModule() {
		
		$new_MasterScheduleEntry = new MasterScheduleEntry("foodbank","Wed", 1, 2,
		"joe2071234567,sue2079876543", "This is a super fun shift.");
		
		//first assertion - check that a getter is working from the superconstructor's initialized data
		$this->assertTrue($new_MasterScheduleEntry->get_day()=="Wed");
		
		$this->assertTrue($new_MasterScheduleEntry->get_group()=="foodbank");
		$this->assertTrue($new_MasterScheduleEntry->get_week_no(), 1);
		$this->assertEqual($new_MasterScheduleEntry->get_slots(), 2);
		$this->assertTrue($new_MasterScheduleEntry->get_persons()==array("joe2071234567","sue2079876543"));
		$this->assertTrue($new_MasterScheduleEntry->get_notes()=="This is a super fun shift.");
		$this->assertEqual($new_MasterScheduleEntry->get_id(), "foodbankWed1");
		
		echo("testMasterScheduleEntry complete");
	}
}

?>
