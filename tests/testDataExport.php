<?php
/**
* Test suite for Person
* Created on Feb 9, 2012
*/

//first I include the php file I'm testing
include_once(dirname(__FILE__).'/../domain/DataExport.php');

class testDataExport extends UnitTestCase {
	function testDataExportModule() {

		$new_DataExport = new DataExport("1/1/2012","Jan","Smith","female","volunteer",
										 "","123 Main St","Portland",
										 "ME",04011,"Cumberland",0125557777,1238883333,"jsmith@mail.com",
										 "Big Co.","active",4,"Thu",
										 "December","1/1/2011");
		
		// first assertion: check that getter for gender is working for initialized data
		$this->assertTrue($new_DataExport->get_gender()=="female");

		echo("testDataExport complete");
		
	}
}

?>
