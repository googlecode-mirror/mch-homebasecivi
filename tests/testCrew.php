<?php
include_once(dirname(__FILE__).'/../domain/Crew.php');
class testCrew extends UnitTestCase {
      function testCrewModule() {
      	$acrew = new Crew("13-10-12","foodbank",3, null, "", "");
      	$this->assertEqual($acrew->get_group(), "foodbank");
      	$this->assertTrue($acrew->get_id() == "13-10-12-foodbank");       
      	$this->assertTrue($acrew->num_vacancies() == 3);
   echo "we are here";      
         $this->assertFalse($acrew->has_sub_call_list());
         $persons = array();
		 $persons[] = "alex1234567890+alex+jones";
         $persons[] = "malcom1234567890+malcom+jones";
         $persons[] = "nat1234567890+nat+jones";
         $acrew->assign_persons($persons);
         $this->assertTrue($acrew->num_vacancies() == 0);
         $acrew->set_notes("Hello 3-5 crew!");
         $this->assertTrue($acrew->get_notes() == "Hello 3-5 crew!");
 		 echo ("testCrew complete");
  	  }
}

?>
