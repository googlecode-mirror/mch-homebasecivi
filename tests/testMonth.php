<?php

/*
 * Created on Feb 15, 2012
 * @author Judy
 */

include_once(dirname(__FILE__).'/../domain/Month.php');
class testMonth extends UnitTestCase {
	function testMonthModule(){
		
		$myMonth = new Month("02-01-12", "One", "published");
		
		
		$this->assertTrue($myMonth->get_id()=="02-01-12");
		$this->assertTrue($myMonth->get_group()=="One");
		$this->assertTrue($myMonth->get_status()=="published");
		$this->assertTrue($myMonth->get_end_of_month_timestamp()==1330491600);
		echo("testMonth complete");
	}
	
	
	
}

?>