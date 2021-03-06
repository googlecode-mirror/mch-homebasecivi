<?php
/*
 * Created February 22, 2012
 * @Author Judy
 */


include_once(dirname(__FILE__).'/../domain/Month.php');
include_once(dirname(__FILE__).'/../database/dbMonths.php');

class testdbMonths extends UnitTestCase {
      function testdbMonthsModule() {

      	
      	//creates an empty dbMonths table
		//$this->assertTrue(create_dbMonths());
      	      	
      	// create a month to add to the table
      	$m1 = new Month("13-10-foodbank", "unpublished");
      	
        // test the insert function
      	$this->assertTrue(insert_dbMonths($m1));
      	
      	// test the retrieve function
      	$this->assertEqual(retrieve_dbMonths($m1->get_id())->get_id(), "13-10-foodbank");
      	$this->assertEqual(retrieve_dbMonths($m1->get_id())->get_status(), "unpublished");
      	$this->assertEqual(retrieve_dbMonths($m1->get_id())->get_group(), "foodbank");
      	$this->assertEqual(retrieve_dbMonths($m1->get_id())->get_end_of_month_timestamp(), mktime(0, 0, 0, 10,31,2013));
      	// testing generation of a new calendar month from the master schedule
      	$this->assertTrue(newMonth("13-10-foodbank"));
      	
      	// test the update function
      	$m1->set_status("published");
      	$this->assertTrue(update_dbMonths($m1));
      	$this->assertEqual(retrieve_dbMonths($m1->get_id())->get_status(), "published");
      	
      	// tests the delete function
    	$this->assertTrue(delete_dbMonths($m1));
      	echo("\ntestdbMonths complete\n");
      }
}

?>