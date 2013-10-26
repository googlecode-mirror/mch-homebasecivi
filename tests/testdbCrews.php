<?php
/*
 * Created on Feb 24, 2008
 * @author max
 */
include_once(dirname(__FILE__).'/../database/dbCrews.php');
class testdbCrews extends UnitTestCase {
  function testdbCrewsModule() {
	$s1=new Crew("13-10-20","foodpantry", 3, null, "", "");
	$this->assertTrue(insert_dbCrews($s1));
	$this->assertEqual(select_dbCrews($s1->get_id())->get_date(), $s1->get_date());
	$this->assertTrue(delete_dbCrews($s1->get_id()));
	$s2=new Crew("13-10-21","foodbank", 3, null, "", "");
	$this->assertTrue(insert_dbCrews($s2));
	$s2=new Crew("13-10-21","foodbank", 2, null, "", "");
	$this->assertTrue(update_dbCrews($s2));
	$this->assertTrue(delete_dbCrews($s2->get_id()));
	echo ("testdbCrews complete");
  }
}
?>
