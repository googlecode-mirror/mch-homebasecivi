<?php

/**
* testdbMasterSchedule class for RMH Homebase
* @author Johnny Coster
* @version February 21, 2012
*/

include_once(dirname(__FILE__).'/../domain/MasterScheduleEntry.php');
include_once(dirname(__FILE__).'/../database/dbMasterSchedule.php');

class testdbMasterSchedule extends UnitTestCase {
	function testdbMasterScheduleModule() {
		
		//creates MasterScheduleEntries to insert to database
		$entry1 = new MasterScheduleEntry("foodpantry","Wed", 1, 2,
												  "", "I do not know what Lin means");
		$entry2 = new MasterScheduleEntry("soupkitchen","Tue", 2, 3, 
										  "", "Yay, kitchen shift!");
		$entry3 = new MasterScheduleEntry("foodpantry","Wed", 1, 2,
												  "", "This is a copy of entry 1");
		$entry4 = new MasterScheduleEntry("foodbank","Fri", 3, 4,
										  "", "Best job ever.");
		//tests the insert function
		$this->assertTrue(insert_dbMasterSchedule($entry1));
		$this->assertTrue(insert_dbMasterSchedule($entry2));
		$this->assertTrue(insert_dbMasterSchedule($entry3));
		$this->assertTrue(insert_dbMasterSchedule($entry4));
		
		//tests the retrieve function
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_day(), $entry2->get_day());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_week_no(), $entry2->get_week_no());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_group(), $entry2->get_group());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_slots(), $entry2->get_slots());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_id(), $entry2->get_id());
		
		//tests the update function
		$entry3->set_notes("This is a new note");
		$this->assertTrue(update_dbMasterSchedule($entry3));
		$this->assertEqual(retrieve_dbMasterSchedule($entry3->get_id())->get_notes(), "This is a new note");
		
		//tests the delete function
		$this->assertTrue(delete_dbMasterSchedule($entry1->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry2->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry3->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry4->get_id()));
		
		echo ("testdbMasterSchedule complete");
	}
}

?>