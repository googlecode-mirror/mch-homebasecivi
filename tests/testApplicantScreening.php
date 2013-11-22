<?php
/**
 * Test suite for ApplicantScreening
 * Created on Feb 15, 2012
 * @author Jackson Moniaga
 */

//first I include the php file I'm testing
include_once(dirname(__FILE__).'/../domain/ApplicantScreening.php');
class testApplicantScreening extends UnitTestCase {
	function testApplicantScreeningModule() {

 		$myApplicantScreening = new ApplicantScreening("volunteer", "Gabrielle1111234567", "Background_Check,Interview","unpublished");

		 //first assertion - check that a getter is working from the superconstructor's initialized data
 		$this->assertTrue($myApplicantScreening->get_type()== "volunteer");
	 	$this->assertTrue($myApplicantScreening->get_creator()=="Gabrielle1111234567");
 		$this->assertEqual($myApplicantScreening->get_steps(), array("Background_Check","Interview"));
 		$this->assertTrue($myApplicantScreening->get_status()=="unpublished");
	 	echo("testApplicantScreening complete");
 	}
}
?>