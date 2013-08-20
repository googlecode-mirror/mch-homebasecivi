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
		//creates an empty dbMasterSchedule table
		//$this->assertTrue(create_dbMasterSchedule());
		
		//creates MasterScheduleEntries to insert to database
		$entry1 = new MasterScheduleEntry("Wed", "10-12", "Lin", 2, 5, 2,
												  "jim2072931023, sarah3219404839", "I do not know what Lin means");
		$entry2 = new MasterScheduleEntry("Tue", "10-12", "Kit", 2, 2, 3, 
										  "alex2071234567, jane1112345567", "Yay, kitchen shift!");
		$entry3 = new MasterScheduleEntry("Wed", "10-12", "Lin", 2, 5, 2,
												  "jim2072931023, sarah3219404839", "This is a copy of entry 1");
		$entry4 = new MasterScheduleEntry("Fri", "4-7", "Rec", 1, 1, 4,
										  "jim2072931023, rachel0238449323", "Best job ever.");
		
		//tests the insert function
		$this->assertTrue(insert_dbMasterSchedule($entry1));
		$this->assertTrue(insert_dbMasterSchedule($entry2));
		$this->assertTrue(insert_dbMasterSchedule($entry3));
		$this->assertTrue(insert_dbMasterSchedule($entry4));
		
		//tests the retrieve function
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_day(), $entry2->get_day());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_time(), $entry2->get_time());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_venue(), $entry2->get_venue());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_weekday_group(), $entry2->get_weekday_group());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_weekend_group(), $entry2->get_weekend_group());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_slots(), $entry2->get_slots());
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_id(), $entry2->get_id());
		
		/**** SETTER TESTS *******/
		/*//tests the update function
		$entry3->set_notes("This is a new note");
		$this->assertTrue(update_dbMasterSchedule($entry3));
		$this->assertEqual(retrieve_dbMasterSchedule($entry3->get_id())->get_notes(), "This is a new note");
		
		$entry2->set_venue("Off");
		$entry2->set_time("1-3");
		$this->assertTrue(update_dbMasterSchedule($entry2));
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_venue(), "Off");
		$this->assertEqual(retrieve_dbMasterSchedule($entry2->get_id())->get_time(), "1-3");*/
		
		//tests the delete function
		$this->assertTrue(delete_dbMasterSchedule($entry2->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry3->get_id()));
		$this->assertTrue(delete_dbMasterSchedule($entry4->get_id()));
		
		//uncomment below 3 lines if you want to drop the table after running the tests
		/*
		connect();
		mysql_query("DROP TABLE IF EXISTS dbMasterSchedule");
		mysql_close();
		*/
		
		echo ("testdbMasterSchedule complete");
	}
}

?>