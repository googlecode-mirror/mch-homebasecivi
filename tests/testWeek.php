<?php
include_once(dirname(__FILE__).'/../domain/Week.php');
class testWeek extends UnitTestCase {
      function testWeekModule() {
      	 $days = array();
      	 for($i=6;$i<13;$i++) {
      	 	$days[] = new RMHDate(date('m-d-y',mktime(0,0,0,2,$i,2012)),array(),"");
      	 }
         $aweek = new Week($days,"Rec", 2, 5,"archived");
         $this->assertEqual($aweek->get_name(), "February 6, 2012 to February 12, 2012");
		 $this->assertEqual($aweek->get_id(), "02-06-12Rec");
		 $this->assertTrue(sizeof($aweek->get_dates()) == 7);
		 $this->assertEqual($aweek->get_status(), "archived");
		 $this->assertEqual($aweek->get_end(), mktime(23,59,59,2,12,2012));

 		 echo ("testWeek complete");
  	  }
}

?>
