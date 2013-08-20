<?php

/**
* testdbDataExport class for RMH Homebase
* @author Johnny Coster
* @version February 27, 2012
*/

include_once(dirname(__FILE__).'/../domain/DataExport.php');
include_once(dirname(__FILE__).'/../database/dbDataExport.php');

class testdbDataExport extends UnitTestCase {
	function testdbDataExportModule() {
		// create an empty dbDataExport table 
		//$this->assertTrue(create_dbDataExport());

		// create DataExports to insert into the table
		$data1 = new DataExport("1/1/2012","Jane Smith","female","individual","Has 11 fingers",
							 	"applicant","123 Main St","Citytown","Maine",04015,"Cumberland",
							 	1025557777,1238883333,"jsmith@mail.com","Uber Big Co.","active",
							 	"12/1/2011, 4 hours","Thursday","December","1/1/2011");
		$data2 = new DataExport("5/22/1990","Tim Stuart","male","household","Cannot swim",
								"staff","567 Street Rd","Townville","Maine",04021,"Oakland",
								3428390498,2304872983,"tstuart@mail.com","Happy Land","LOA",
								"7/14/2010, 5 hours","Thursday","August","1/1/2009");
		$data3 = new DataExport("5/22/1990","Tim Stuart","male","household","Cannot swim",
								"staff","567 Street Rd","Townville","Maine",04021,"Oakland",
								3428390498,2304872983,"tstuart@mail.com","Happy Land","LOA",
								"7/14/2010, 5 hours","Thursday","August","1/1/2009");
		$data4 = new DataExport("11/11/2011","Dwight Schrute","male","organization","Beet farmer",
								"guest chef","987 Offie St","Scranton","Pennsylvania",18447,"Lackawanna",
								9436411129,4458390930,"dwight@office.com","Dunder Mifflin","active",
								"1/20/2012, 10 hours","Monday","February","2/12/2012");
		
		// test the insert function
		$this->assertTrue(insert_dbDataExport($data1));
		$this->assertTrue(insert_dbDataExport($data2));
		$this->assertTrue(insert_dbDataExport($data3));
		$this->assertTrue(insert_dbDataExport($data4));
		
		// test the retrieve function
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_export_date(), $data1->get_export_date());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_name(), $data1->get_name());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_gender(), $data1->get_gender());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_type(), $data1->get_type());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_notes(), $data1->get_notes());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_role(), $data1->get_role());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_address(), $data1->get_address());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_city(), $data1->get_city());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_state(), $data1->get_state());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_zip(), $data1->get_zip());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_county(), $data1->get_county());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_phone1(), $data1->get_phone1());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_phone2(), $data1->get_phone2());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_email(), $data1->get_email());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_employer(), $data1->get_employer());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_status(), $data1->get_status());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_hours_worked(), $data1->get_hours_worked());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_day_of_week(), $data1->get_day_of_week());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_month(), $data1->get_month());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_start_date(), $data1->get_start_date());
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_id(), $data1->get_id());
		
		// test the update function
		$data1->set_role("volunteer");
		$this->assertTrue(update_dbDataExport($data1));
		$this->assertEqual(retrieve_dbDataExport($data1->get_id())->get_role(), "volunteer");
		
		$data4->set_email("dschrute@office.com");
		$data4->set_phone1(9997775555);
		$this->assertTrue(update_dbDataExport($data4));
		$this->assertEqual(retrieve_dbDataExport($data4->get_id())->get_email(), "dschrute@office.com");
		$this->assertEqual(retrieve_dbDataExport($data4->get_id())->get_phone1(), 9997775555);

		// test the delete function
		$this->assertTrue(delete_dbDataExport($data1->get_id()));
		$this->assertTrue(delete_dbDataExport($data3->get_id()));
		$this->assertTrue(delete_dbDataExport($data4->get_id()));
		
		echo "testdbDataExport complete";
	}
}

?>