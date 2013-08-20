<?php
include_once(dirname(__FILE__).'/../domain/SCL.php');
class testSCL extends UnitTestCase {
      function testSCLModule() {
         $p=array();
         $p[] = array("max123","max","palmer","123","456","1/1/08","LM","true");
         $p[] = array("oliver345","oliver","radwan","123","456","1/1/08","LM","false");
	     $s=new SCL("01-01-08-9-12",$p,"open",1,"11223344");
//	     print_r($s);
		 $this->assertTrue($s->get_id() == "01-01-08-9-12");
	     $p2=$s->get_persons();
	     $p2[]=array("max123","max","palmer","123","456","1/1/08","LM","true");
	     $s->set_persons($p2);
//	     echo count($s->get_persons());
         $this->assertTrue(count($s->get_persons())==3);
		 echo ("testSCL complete");
  	  }
}

?>
