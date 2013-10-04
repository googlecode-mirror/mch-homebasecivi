<?php
/**
 * Test suite for Person
 * Created on Feb 27, 2008
 * @author Taylor Talmage
 */

/**
 * updated 10/04/13
 * to reflect structuree of new Persons class
 * Brian, Oliver
**/

  //first I include the php file I'm testing
 include_once(dirname(__FILE__).'/../domain/Person.php');
 class testPerson extends UnitTestCase {
      function testPersonModule() {

 $myPerson = new Person("Taylor","Talmage","928 SU","Brunswick","ME",
      2074415902,2072654046,"ttalmage@bowdoin.edu", "volunteer",
      "soupkitchen","C","active",
      "Mon:morning,Tue:morning,Wed:earlypm", "09-19-13,09-16-13", "02-19-89", "03-14-08",
      "this is a note","Taylor2074415902");

 //first assertion - check that a getter is working from the superconstructor's initialized data
 $this->assertTrue($myPerson->get_first_name()=="Taylor");
 $this->assertTrue($myPerson->get_type()==array("volunteer"));
 $this->assertTrue($myPerson->get_status()=="active");
 $this->assertEqual($myPerson->get_availability(),array("Mon:morning","Tue:morning","Wed:earlypm"));
 $this->assertTrue($myPerson->get_last_name() !== "notMyLastName");
 echo("testPerson complete");
      }
 }

?>
