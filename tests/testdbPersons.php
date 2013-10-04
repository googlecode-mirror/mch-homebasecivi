<?php
/*
 * Modified March 2012
 * @Author Taylor and Allen
 */
include_once(dirname(__FILE__).'/../database/dbPersons.php');
class testdbPersons extends UnitTestCase {
      function testdbPersonsModule() {
      	//add a manager

//setup_dbPersons();
$m = new Person("Taylor","Talmage","male","928 SU","Brunswick","ME",
      2074415902,2072654046,"ttalmage@bowdoin.edu", "email", "volunteer",
      "soupkitchen","C","active", "programmer",
      "Mon:morning,Tue:morning,Wed:earlypm", "09-19-13,09-16-13", "02-19-89", "03-14-08",
      "this is a note","Taylor2074415902");
$this->assertTrue(add_person($m));

//get a person
$p = retrieve_person("Taylor2074415902");
$this->assertTrue($p!==false);
$this->assertTrue($p->get_status() == "active");
$this->assertTrue(remove_person("Taylor2074415902"));

echo("testdbPersons complete");

      }
}


?>
