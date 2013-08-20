<?php
// updated by max, 2/25/08
include_once(dirname(__FILE__).'/../domain/RMHdate.php');
class testRMHdate extends UnitTestCase {
      function testRMHdateModule() {
        $my_shifts[] = new Shift("02-28-10-9-13","Rec",1,array(),null,"");
 		$my_date = new RMHdate("02-28-10",$my_shifts,"");
		$my_shifts = $my_date->get_shifts();
        foreach ($my_shifts as $value)
	        $this->assertTrue($value instanceof Shift);
 		$this->assertTrue($my_date->get_id() == "02-28-10");
 		$this->assertTrue($my_date->get_day() == "Sun");
 		$this->assertTrue($my_date->get_day_of_week() == 7);
 		$this->assertTrue($my_date->get_day_of_year() == 59);
 		$this->assertTrue($my_date->get_year() == 2010);

 		echo("testRMHdate complete");
      }
}
?>
