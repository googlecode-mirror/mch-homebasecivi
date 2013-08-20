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
		
		$new_MasterScheduleEntry = new MasterScheduleEntry("Wed", "1-3", "Fam", 2, 5, 2,
		"joe2071234567,sue2079876543", "This is a super fun shift.");
		
		//first assertion - check that a getter is working from the superconstructor's initialized data
		$this->assertTrue($new_MasterScheduleEntry->get_day()=="Wed");
		
		$this->assertTrue($new_MasterScheduleEntry->get_time()=="1-3");
		$this->assertTrue($new_MasterScheduleEntry->get_venue()=="Fam");
		$this->assertTrue($new_MasterScheduleEntry->get_weekday_group(), 2);
		$this->assertTrue($new_MasterScheduleEntry->get_weekend_group(), 5);
		$this->assertTrue($new_MasterScheduleEntry->get_slots()==2);
		$this->assertTrue($new_MasterScheduleEntry->get_persons()==array("joe2071234567","sue2079876543"));
		$this->assertTrue($new_MasterScheduleEntry->get_notes()=="This is a super fun shift.");
		$this->assertTrue($new_MasterScheduleEntry->get_id()=="Wed,1-3,Fam,2,5");
		
		echo("testMasterScheduleEntry complete");
	}
}

?>
