<?php
//    session_start();
//      session_cache_expire(30);
/*
 * Run all the RMH Homebase unit tests
 */
// require_once(dirname(__FILE__).'/simpletest/autorun.php');
class AllTests extends GroupTest {
          function AllTests() {
				$this->addTestFile(dirname(__FILE__).'/testApplicantScreening.php');
       			$this->addTestFile(dirname(__FILE__).'/testDataExport.php');
      			$this->addTestFile(dirname(__FILE__).'/testMasterScheduleEntry.php');
				$this->addTestFile(dirname(__FILE__).'/testMonth.php');
       		    $this->addTestFile(dirname(__FILE__).'/testPerson.php');
       			$this->addTestFile(dirname(__FILE__).'/testCrew.php');
            	$this->addTestFile(dirname(__FILE__).'/testdbPersons.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbCrews.php');
      	 		$this->addTestFile(dirname(__FILE__).'/testdbWeeks.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbMasterSchedule.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbMonths.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbDataExport.php');
       			$this->addTestFile(dirname(__FILE__).'/testdbApplicantScreenings.php'); 
        		echo ("\nAll tests complete\n");
          }
 }
?>