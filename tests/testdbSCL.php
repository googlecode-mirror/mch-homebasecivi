<?php
/*
 * @author max
 */
include_once(dirname(__FILE__).'/../domain/SCL.php');
include_once(dirname(__FILE__).'/../database/dbSCL.php');
class testdbSCL extends UnitTestCase {
  function testdbSCLModule() {
	$p=array();
    $p[] = array("max123","max","palmer","123","456","1/1/08","LM","true");
    $p[] = array("oliver345","oliver","radwan","123","456","1/1/08","LM","false");
	$s=new SCL("01-01-08-9-12",$p,"open",1,"11223344");
//	print_r($s);
	$this -> assertTrue(insert_dbSCL($s));

	$s2=select_dbSCL("01-01-08-9-12");
	$this -> assertTrue($s2 !== null);
	$p2=$s2->get_persons();
	$p2[]=array("max123","max","palmer","123","456","1/1/08","LM","true");
	$s2->set_persons($p2);
	$this -> assertTrue(update_dbSCL($s2));
//	print_r(select_dbSCL("01-01-08-9-12"));
    $this -> assertTrue(delete_dbSCL($s));

	echo ("testdbSCL complete");
  }
}
?>
