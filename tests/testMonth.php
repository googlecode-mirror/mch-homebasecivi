<?php

/*
 * Created on October 20, 2013
 * @author Allen
 */

include_once(dirname(__FILE__).'/../domain/Month.php');
class testMonth extends UnitTestCase {
	function testMonthModule(){	
		$myMonth = new Month("13-10-foodbank", "published");
		$this->assertTrue($myMonth->get_id()=="13-10-foodbank");
		$this->assertEqual($myMonth->get_group(),"foodbank");
		$this->assertTrue($myMonth->get_status()=="published");
		$this->assertTrue($myMonth->get_end_of_month_timestamp()==mktime(0, 0, 0, 10, 31, 2013));
		echo("testMonth complete");
	}
	
}

?>