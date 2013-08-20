<?php
/*
 * Modified March 2012
 * @Author Taylor and Allen
 */
include_once(dirname(__FILE__).'/../database/dbPersons.php');
class testdbPersons extends UnitTestCase {
      function testdbPersonsModule() {
      	//add a manager

//      	setup_dbPersons();
$m = new Person("Gabrielle","Little", "female","14 Way St", "Harpswell", "ME", "04079","",
1112345678, 2071112345,"ted@bowdoin.edu","email", "Mother", 2077758989, "manager","", "programmer", "Steve_2077291234","yes","","", 
"Mon9-12,Tue9-12","","",
"02-19-89", "03-14-08","","");
$this->assertTrue(add_person($m));
//get a person
$p = retrieve_person("Gabrielle1112345678");
$this->assertTrue($p!==false);
$this->assertTrue($p->get_status() == "active");
$this->assertTrue($p->get_occupation() == "programmer");
$this->assertEqual($p->get_refs(), array("Steve_2077291234"));

		//add an applicant
		$p1 = new Person("Johnny","Coster", "male","928 Smith Union", "Brunswick", "ME", "04011","",
			1112345678, 2072654046,"johnny.coster@gmail.com","email", "Mother", 2077758989, "volunteer","", "programmer", "Steve_2077291234","yes","","", 
			"Mon9-12,Tue9-12","","",
			"02-19-89", "03-14-08", "a little note","");
		$this->assertTrue(add_person($p1));

		//add a volunteer
		$p2 = new Person("Judy","Yang", "female","928 Smith Union", "Brunswick", "ME", "04011","",
			1112345678, 2072654046,"judyyang515@gmail.com","email", "Mother", 2077758989, "volunteer","", "programmer", "", "", "", "",
			"Mon9-12,Tue9-12","","",
			"02-19-89", "03-14-08","","" );
		$this->assertTrue(add_person($p2));

//add a sub
$p3 = new Person("Jackson","Moniaga", "male","928 Smith Union", "Brunswick", "ME", "04011","",
1112345678, 2072654046,"pwnage.com@gmail.com","email", "Mother", 2077758989, "volunteer,sub","","","applicant", "programmer", "", "", "", "",
"Mon9-12,Tue9-12","","",
"02-19-89", "03-14-08","","" );
$this->assertTrue(add_person($p3));

$p4 = new Person("Allen","Tucker", "male","928 Smith Union", "Brunswick", "ME", "04011","", 
1112345678, 2072654046,"allen@bowdoin.edu","email", "Mother", 2077758989,"familyroom","", "programmer", "", "", "", "",
"Mon9-12,Tue9-12","","",
"02-19-89", "03-14-08","notes from the manager","" );
$this->assertTrue(add_person($p4));

//add a guest chef
$p5 = new Person("Tom","Bachman", "male","928 Smith Union", "Brunswick", "ME", "04011","", 
1112345678, 2072654046,"tebachman@gmail.com","email", "Mother", 2077758989,"guestchef","", "programmer", "", "", "", "",
"","","",
"02-19-89", "03-14-08","","" );
$this->assertTrue(add_person($p5));

		//try to add a person who is a duplicate
		$this->assertFalse(add_person($p5));

		//try to get a person who is not in the database
		$this->assertFalse(retrieve_person("Tim1112345678"));

// get all volunteers available for Monday from 9-12
$a = getall_available("volunteer", "Mon", "9-12");
$this->assertTrue(count($a) == 1);

//remove all persons
$this->assertTrue(remove_person("Gabrielle1112345678"));
$this->assertTrue(remove_person("Johnny1112345678"));
$this->assertTrue(remove_person("Jackson1112345678"));
$this->assertTrue(remove_person("Judy1112345678"));
$this->assertTrue(remove_person("Allen1112345678"));
$this->assertTrue(remove_person("Tom1112345678"));

//try to remove a person who is not in the db - should not work
$this->assertFalse(remove_person("Brian2074415902"));


echo("testdbPersons complete");

      }
}


?>
