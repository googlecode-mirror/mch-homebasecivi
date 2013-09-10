<?php
include_once(dirname(__FILE__).'/../domain/Shift.php');
class testShift extends UnitTestCase {
      function testShiftModule() {
         $noonshift = new Shift("03-28-08-12-15","Rec",3, null, "", "");
         $this->assertEqual($noonshift->get_name(), "12-15");
         $this->assertTrue($noonshift->get_id() == "03-28-08-12-15");
         
// Test new function for resetting shift's start/end time
		 $this->assertTrue($noonshift->set_start_end_time(15,17));
		 $this->assertEqual($noonshift->get_id(), "03-28-08-15-17");
		 $this->assertTrue($noonshift->get_name() == "15-17");
		 
// Be sure that invalid times are caught.
		 $this->assertFalse($noonshift->set_start_end_time(13,12));
		 $this->assertTrue($noonshift->get_id() == "03-28-08-15-17");
		 $this->assertTrue($noonshift->get_name() == "15-17");

         $this->assertTrue($noonshift->num_vacancies() == 3);

         $this->assertTrue($noonshift->get_day() == "Fri");
		 $this->assertFalse($noonshift->has_sub_call_list());

         $persons = array();
		 $persons[] = "alex1234567890+alex+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $persons[] = "malcom1234567890+malcom+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $persons[] = "nat1234567890+nat+jones";
         $noonshift->assign_persons($persons);
         $noonshift->ignore_vacancy();
         $this->assertTrue($noonshift->num_vacancies() == 0);
         $noonshift->add_vacancy();
         $this->assertTrue($noonshift->num_slots() == 4);
         $noonshift->ignore_vacancy();
		 $this->assertTrue($noonshift->num_slots() == 3);

         $noonshift->set_notes("Hello 3-5 shift!");
         $this->assertTrue($noonshift->get_notes() == "Hello 3-5 shift!");
 		 echo ("testShift complete");
  	  }
}

?>
