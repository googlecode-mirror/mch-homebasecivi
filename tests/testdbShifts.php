<?php
/*
 * Created on Feb 24, 2008
 * @author max
 */
include_once(dirname(__FILE__).'/../database/dbShifts.php');
include_once(dirname(__FILE__).'/../database/dbDates.php');
class testdbShifts extends UnitTestCase {
  function testdbShiftsModule() {
	$s1=new Shift("02-25-08-15-18","Rec", 3, array(), "", "");
	$this->assertTrue(insert_dbShifts($s1));
	$this->assertTrue(delete_dbShifts($s1));
	$s2=new Shift("02-25-08-15-18","Rec",3, array(), "", "");
	$this->assertTrue(insert_dbShifts($s2));
	$s2=new Shift("02-25-08-15-18","Rec",2, array(), "", "");
	$this->assertTrue(update_dbShifts($s2));
	$shifts[] = $s2;
	$this->assertTrue(delete_dbShifts($s2));
	echo ("testdbShifts complete");
  }
}
?>
